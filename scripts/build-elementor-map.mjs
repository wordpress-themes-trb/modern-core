/**
 * Build inc/Elementor/controls.json from the blocks' editor sidebars.
 *
 * Reads every blocks/*\/edit.jsx and records, for each sidebar control,
 * which block attribute it edits, its label, panel, options and range. The
 * Elementor widgets (inc/Elementor/BlockWidget.php) use this map, so a
 * block's Elementor panel matches its block-editor sidebar without being
 * written twice.
 *
 * Usage: node scripts/build-elementor-map.mjs   (runs as part of npm run build)
 */
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { parse } from '@babel/parser';
import traverseModule from '@babel/traverse';

const traverse = traverseModule.default || traverseModule;
const ROOT = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const BLOCKS = path.join(ROOT, 'blocks');
const OUT = path.join(ROOT, 'inc', 'Elementor', 'controls.json');

const KINDS = {
	SelectControl: 'select',
	ToggleControl: 'switch',
	CheckboxControl: 'switch',
	RangeControl: 'number',
	TextControl: 'text',
	TextareaControl: 'textarea',
	ColorPalette: 'color',
	ColorPicker: 'color',
	MediaUpload: 'media',
};

/** Plain string from a literal, __( 'x' ), a static template or a JSX container. */
function str(node) {
	if (!node) return null;
	switch (node.type) {
		case 'StringLiteral':
			return node.value;
		case 'NumericLiteral':
		case 'BooleanLiteral':
			return node.value;
		case 'TemplateLiteral':
			return node.expressions.length ? null : node.quasis.map((q) => q.value.cooked).join('');
		case 'JSXExpressionContainer':
			return str(node.expression);
		case 'CallExpression':
			if (node.callee.type === 'Identifier' && ['__', '_x', 'esc_html__'].includes(node.callee.name)) {
				return str(node.arguments[0]);
			}
			return null;
		case 'UnaryExpression':
			return node.operator === '-' && node.argument.type === 'NumericLiteral' ? -node.argument.value : null;
		default:
			return null;
	}
}

function prop(el, name) {
	const a = el.openingElement.attributes.find((x) => x.type === 'JSXAttribute' && x.name.name === name);
	return a ? a.value : undefined;
}

function propValue(el, name) {
	const v = prop(el, name);
	if (v === undefined) return undefined;
	if (v === null) return true; // <Foo disabled />
	return str(v);
}

/** Visible text of a JSX element (for labels written as <p><strong>Label</strong></p>). */
function jsxText(node) {
	if (!node) return '';
	if (node.type === 'JSXText') return node.value;
	if (node.type === 'JSXExpressionContainer') {
		const s = str(node.expression);
		return typeof s === 'string' ? s : '';
	}
	if (node.type === 'JSXElement' || node.type === 'JSXFragment') return node.children.map(jsxText).join('');
	return '';
}

/** Find setAttributes({...}) keys and repeater item assignments inside a handler. */
function analyseHandler(fnNode, scope, helpers, arrayAttrs) {
	const result = { keys: [], itemField: null, itemArray: null, mediaMap: {} };
	if (!fnNode) return result;
	const visit = (node) => {
		if (!node || typeof node.type !== 'string') return;
		if (node.type === 'CallExpression' && node.callee.type === 'Identifier') {
			const name = node.callee.name;
			if (name === 'setAttributes' && node.arguments[0] && node.arguments[0].type === 'ObjectExpression') {
				for (const p of node.arguments[0].properties) {
					if (p.type !== 'ObjectProperty' || p.computed) continue;
					const key = p.key.name || p.key.value;
					result.keys.push(key);
					// media.url / media.id style values (MediaUpload onSelect).
					if (p.value.type === 'MemberExpression' && !p.value.computed) {
						result.mediaMap[key] = p.value.property.name;
					} else if (p.value.type === 'OptionalMemberExpression' && !p.value.computed) {
						result.mediaMap[key] = p.value.property.name;
					} else if (p.value.type === 'LogicalExpression' && p.value.left.type.endsWith('MemberExpression')) {
						result.mediaMap[key] = p.value.left.property.name;
					}
				}
			} else if (helpers[name]) {
				// updateItem( index, 'field', value ) style helpers.
				const field = node.arguments.map(str).find((s) => typeof s === 'string');
				if (field) {
					result.itemArray = helpers[name];
					result.itemField = field;
				}
			}
		}
		// const next = [ ...items ]; next[ i ].field = value;
		if (node.type === 'AssignmentExpression' && node.left.type === 'MemberExpression') {
			const left = node.left;
			if (left.object.type === 'MemberExpression' && left.object.computed) {
				const field = left.computed ? str(left.property) : left.property.name;
				if (typeof field === 'string') result.itemField = result.itemField || field;
			}
		}
		for (const key of Object.keys(node)) {
			if (key === 'loc' || key === 'start' || key === 'end') continue;
			const child = node[key];
			if (Array.isArray(child)) child.forEach(visit);
			else if (child && typeof child.type === 'string') visit(child);
		}
	};
	visit(fnNode);
	if (!result.itemArray && result.itemField) {
		const arr = result.keys.find((k) => arrayAttrs.has(k));
		if (arr) result.itemArray = arr;
	}
	return result;
}

/** Elementor-style condition from `attr === "x" && <Control/>` wrappers. */
function conditionFor(pathRef, attrNames) {
	const cond = {};
	let p = pathRef;
	while (p && p.parentPath) {
		const parent = p.parentPath.node;
		if (parent.type === 'LogicalExpression' && parent.operator === '&&' && parent.right === p.node) {
			collectTest(parent.left, cond, attrNames);
		}
		if (parent.type === 'ConditionalExpression' && (parent.consequent === p.node || parent.alternate === p.node)) {
			const sub = {};
			collectTest(parent.test, sub, attrNames);
			if (parent.consequent === p.node) Object.assign(cond, sub);
		}
		if (parent.type === 'ArrowFunctionExpression' || parent.type === 'FunctionDeclaration' || parent.type === 'Program') break;
		p = p.parentPath;
	}
	return Object.keys(cond).length ? cond : null;
}

function collectTest(test, cond, attrNames) {
	if (test.type === 'LogicalExpression' && test.operator === '&&') {
		collectTest(test.left, cond, attrNames);
		collectTest(test.right, cond, attrNames);
		return;
	}
	if (test.type === 'Identifier' && attrNames.has(test.name)) {
		cond[test.name] = true;
	} else if (test.type === 'UnaryExpression' && test.operator === '!' && test.argument.type === 'Identifier' && attrNames.has(test.argument.name)) {
		cond[test.argument.name] = false;
	} else if (test.type === 'BinaryExpression' && test.left.type === 'Identifier' && attrNames.has(test.left.name)) {
		const v = str(test.right);
		if (v === null) return;
		if (test.operator === '===' || test.operator === '==') cond[test.left.name] = v;
		if (test.operator === '!==' || test.operator === '!=') cond[test.left.name + '!'] = v;
	}
}

function buildBlock(folder) {
	const file = path.join(BLOCKS, folder, 'edit.jsx');
	const meta = JSON.parse(fs.readFileSync(path.join(BLOCKS, folder, 'block.json'), 'utf8'));
	if (!fs.existsSync(file)) return null;
	const attrs = meta.attributes || {};
	const attrNames = new Set(Object.keys(attrs));
	const arrayAttrs = new Set(Object.keys(attrs).filter((k) => attrs[k].type === 'array'));
	const ast = parse(fs.readFileSync(file, 'utf8'), { sourceType: 'module', plugins: ['jsx'] });

	// Helpers such as updateFaq( index, key, value ) and the array they write to.
	const helpers = {};
	traverse(ast, {
		VariableDeclarator(p) {
			const init = p.node.init;
			if (p.node.id.type !== 'Identifier' || !init || !/Function/.test(init.type)) return;
			const res = analyseHandler(init.body, null, {}, arrayAttrs);
			const arr = res.keys.find((k) => arrayAttrs.has(k));
			if (arr && init.params.length >= 2) helpers[p.node.id.name] = arr;
		},
		FunctionDeclaration(p) {
			const res = analyseHandler(p.node.body, null, {}, arrayAttrs);
			const arr = res.keys.find((k) => arrayAttrs.has(k));
			if (arr && p.node.params.length >= 2) helpers[p.node.id.name] = arr;
		},
	});

	const sections = [];
	const repeaters = {};
	const seen = new Set();
	const sectionFor = (title) => {
		let s = sections.find((x) => x.title === title);
		if (!s) {
			s = { title, controls: [] };
			sections.push(s);
		}
		return s;
	};

	traverse(ast, {
		JSXElement(p) {
			const name = p.node.openingElement.name.name;
			const kind = KINDS[name];
			if (!kind) return;

			// Panel title from the nearest <PanelBody title="…">.
			let panel = 'Settings';
			const panelPath = p.findParent((x) => x.isJSXElement() && x.node.openingElement.name.name === 'PanelBody');
			if (panelPath) panel = propValue(panelPath.node, 'title') || panel;

			let label = propValue(p.node, 'label');
			if (typeof label !== 'string' || !label) {
				// Label written as a sibling <p>/<label> just before the control.
				const siblings = p.parentPath.node.children || [];
				const idx = siblings.indexOf(p.node);
				for (let i = idx - 1; i >= 0; i--) {
					const t = jsxText(siblings[i]).trim();
					if (t) {
						label = t;
						break;
					}
					if (siblings[i].type === 'JSXElement') break;
				}
			}

			const handlerProp = prop(p.node, kind === 'media' ? 'onSelect' : 'onChange');
			const handler = handlerProp && handlerProp.type === 'JSXExpressionContainer' ? handlerProp.expression : null;
			const res = analyseHandler(handler, null, helpers, arrayAttrs);

			const control = { kind, label: typeof label === 'string' ? label.trim() : '' };
			if (kind === 'select') {
				const opts = prop(p.node, 'options');
				if (opts && opts.type === 'JSXExpressionContainer' && opts.expression.type === 'ArrayExpression') {
					control.options = opts.expression.elements
						.filter((e) => e && e.type === 'ObjectExpression')
						.map((e) => {
							const o = {};
							for (const pr of e.properties) {
								if (pr.type === 'ObjectProperty') o[pr.key.name || pr.key.value] = str(pr.value);
							}
							return o;
						})
						.filter((o) => o.value !== null && o.value !== undefined);
				}
			}
			if (kind === 'number') {
				for (const k of ['min', 'max', 'step']) {
					const v = propValue(p.node, k);
					if (typeof v === 'number') control[k] = v;
				}
			}
			if (kind === 'textarea') control.rows = propValue(p.node, 'rows') || 4;

			if (res.itemArray && res.itemField) {
				const rep = (repeaters[res.itemArray] = repeaters[res.itemArray] || { fields: [] });
				if (!rep.fields.find((f) => f.key === res.itemField)) {
					rep.fields.push({ key: res.itemField, ...control });
					const s = sectionFor(panel);
					if (!s.controls.find((c) => c.repeater === res.itemArray)) s.controls.push({ repeater: res.itemArray });
				}
				return;
			}

			if (kind === 'media') {
				const urlKey = Object.keys(res.mediaMap).find((k) => res.mediaMap[k] === 'url') || res.keys[0];
				const idKey = Object.keys(res.mediaMap).find((k) => res.mediaMap[k] === 'id');
				if (!urlKey || !attrNames.has(urlKey) || seen.has(urlKey)) return;
				seen.add(urlKey);
				const c = { attr: urlKey, ...control };
				if (idKey && attrNames.has(idKey)) c.idAttr = idKey;
				const cond = conditionFor(p, attrNames);
				if (cond) c.condition = cond;
				sectionFor(panel).controls.push(c);
				return;
			}

			const key = res.keys.find((k) => attrNames.has(k));
			if (!key || seen.has(key)) return;
			seen.add(key);
			const c = { attr: key, ...control };
			const cond = conditionFor(p, attrNames);
			if (cond) c.condition = cond;
			sectionFor(panel).controls.push(c);
		},
	});

	return { sections: sections.filter((s) => s.controls.length), repeaters };
}

const map = {};
for (const folder of fs.readdirSync(BLOCKS).sort()) {
	if (!fs.existsSync(path.join(BLOCKS, folder, 'block.json'))) continue;
	try {
		const entry = buildBlock(folder);
		if (entry) map[folder] = entry;
	} catch (e) {
		console.warn(`elementor map: skipped ${folder}: ${e.message}`);
	}
}
fs.mkdirSync(path.dirname(OUT), { recursive: true });
fs.writeFileSync(OUT, JSON.stringify(map, null, '\t') + '\n');
const count = Object.values(map).reduce((n, b) => n + b.sections.reduce((m, s) => m + s.controls.length, 0), 0);
console.log(`elementor map: ${Object.keys(map).length} blocks, ${count} controls -> ${path.relative(ROOT, OUT)}`);
