#!/usr/bin/env python3
"""Check every block comment in templates/, parts/ and patterns/ against block.json.

Reports unknown blocks, unknown attributes, wrong value types, unbalanced
open/close comments and template parts that don't exist.

Usage: python3 scripts/validate-templates.py   (exit code 1 on problems)
"""
import json
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TOKEN = re.compile(
    r'<!--\s+(?P<closer>/)?wp:(?P<ns>[a-z][a-z0-9_-]*/)?(?P<name>[a-z][a-z0-9_-]*)\s+'
    r'(?P<attrs>{(?:(?:[^}]+|}+(?=})|(?!}\s+/?-->).)*+)?}\s+)?(?P<void>/)?-->',
    re.S,
)
# Core blocks used in the templates, with the attributes we rely on.
CORE_OK = {
    'core/template-part', 'core/group', 'core/heading', 'core/paragraph', 'core/buttons', 'core/button',
    'core/post-title', 'core/post-content', 'core/query', 'core/post-template', 'core/post-featured-image',
    'core/post-excerpt', 'core/post-date', 'core/query-pagination', 'core/query-pagination-next',
    'core/query-pagination-previous', 'core/query-pagination-numbers', 'core/query-no-results',
    'core/query-title', 'core/search', 'core/spacer', 'core/separator', 'core/image', 'core/list',
    'core/list-item', 'core/columns', 'core/column', 'core/post-terms', 'core/term-description',
    'core/post-author-name', 'core/comments', 'core/comments-title', 'core/comment-template',
    'core/comment-author-name', 'core/comment-date', 'core/comment-content', 'core/comments-pagination',
    'core/post-comments-form', 'core/html', 'core/post-navigation-link', 'core/read-more',
    'core/site-title', 'core/navigation', 'core/navigation-link', 'core/navigation-submenu', 'core/pattern', 'core/cover', 'core/quote',
    'woocommerce/store-notices', 'woocommerce/legacy-template',
}
TYPES = {
    'string': (str,), 'number': (int, float), 'integer': (int,), 'boolean': (bool,),
    'array': (list,), 'object': (dict,),
}


def load_schemas():
    schemas = {}
    blocks = os.path.join(ROOT, 'blocks')
    for d in sorted(os.listdir(blocks)):
        f = os.path.join(blocks, d, 'block.json')
        if os.path.isfile(f):
            meta = json.load(open(f))
            attrs = dict(meta.get('attributes', {}))
            # Attributes WordPress adds through "supports".
            for extra in ('className', 'align', 'anchor', 'style', 'lock', 'metadata'):
                attrs.setdefault(extra, {})
            schemas[meta['name']] = {'attrs': attrs, 'parent': meta.get('parent')}
    return schemas


def check_value(spec, value):
    t = spec.get('type')
    if not t:
        return True
    types = t if isinstance(t, list) else [t]
    for one in types:
        py = TYPES.get(one, ())
        if one in ('number', 'integer') and isinstance(value, bool):
            continue
        if isinstance(value, py):
            if 'enum' in spec and value not in spec['enum']:
                return False
            return True
    return False


def main():
    schemas = load_schemas()
    parts = {os.path.splitext(f)[0] for f in os.listdir(os.path.join(ROOT, 'parts'))}
    problems = 0
    for folder in ('templates', 'parts', 'patterns'):
        for fn in sorted(os.listdir(os.path.join(ROOT, folder))):
            if not fn.endswith('.php' if folder == 'patterns' else '.html'):
                continue
            path = os.path.join(folder, fn)
            text = open(os.path.join(ROOT, path)).read()
            if folder == 'patterns':  # Inline PHP (dates, URLs) runs before WordPress parses the blocks.
                text = re.sub(r'<\?php.*?\?>', lambda m: 'PHP' + '\n' * m.group(0).count('\n'), text, flags=re.S)
            stack = []
            for m in TOKEN.finditer(text):
                name = (m.group('ns') or 'core/') + m.group('name')
                line = text.count('\n', 0, m.start()) + 1
                where = f'{path}:{line}'
                if m.group('closer'):
                    if not stack or stack[-1] != name:
                        print(f'{where}: closing {name} does not match {stack[-1] if stack else "nothing"}')
                        problems += 1
                    else:
                        stack.pop()
                    continue
                try:
                    attrs = json.loads(m.group('attrs')) if m.group('attrs') else {}
                except json.JSONDecodeError as e:
                    print(f'{where}: {name} has invalid JSON ({e})')
                    problems += 1
                    attrs = {}
                if name in schemas:
                    schema = schemas[name]
                    for key, value in attrs.items():
                        if key not in schema['attrs']:
                            print(f'{where}: {name} has unknown attribute "{key}"')
                            problems += 1
                        elif not check_value(schema['attrs'][key], value):
                            print(f'{where}: {name}.{key} = {json.dumps(value)[:60]} does not match {schema["attrs"][key]}')
                            problems += 1
                    if schema['parent'] and (not stack or stack[-1] not in schema['parent']):
                        print(f'{where}: {name} must be inside {schema["parent"]}')
                        problems += 1
                elif name not in CORE_OK and not name.startswith('woocommerce/'):
                    print(f'{where}: unknown block {name}')
                    problems += 1
                if name == 'core/template-part' and attrs.get('slug') not in parts:
                    print(f'{where}: template part "{attrs.get("slug")}" does not exist in parts/')
                    problems += 1
                if not m.group('void'):
                    stack.append(name)
            if stack:
                print(f'{path}: unclosed {stack}')
                problems += 1
    print(f'{problems} problem(s)')
    return 1 if problems else 0


if __name__ == '__main__':
    sys.exit(main())
