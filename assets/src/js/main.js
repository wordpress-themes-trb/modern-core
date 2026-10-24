/**
 * Modern Core global front-end script.
 *
 * Block-specific behaviour lives in each block's render.php. This file only
 * handles behaviour shared across the site.
 */

import AOS from 'aos';
import 'aos/dist/aos.css';

// ---------------------------------------------------------------------------
// Entrance animations (the "Entrance Animation" panel on every block).
// The server outputs data-mc-aos-* so nothing is hidden if this script never
// runs; here they become the data-aos-* attributes AOS reads.
// ---------------------------------------------------------------------------
const initAnimations = () => {
  const blocks = document.querySelectorAll('[data-mc-aos]');
  if (!blocks.length && !document.querySelector('[data-aos]')) return;
  blocks.forEach((el) => {
    ['', '-duration', '-delay', '-offset'].forEach((suffix) => {
      const value = el.getAttribute(`data-mc-aos${suffix}`);
      if (value !== null) el.setAttribute(`data-aos${suffix}`, value);
    });
  });
  AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true,
    offset: 50,
    // Respect "reduce motion": show everything without animating.
    disable: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches,
  });
  // Images and lazy content change the page height after load.
  window.addEventListener('load', () => setTimeout(() => AOS.refresh(), 300));
};

const initHeaderSearch = () => {
  document.querySelectorAll('.modern-core-search-trigger[aria-controls]').forEach((trigger) => {
    const form = document.getElementById(trigger.getAttribute('aria-controls'));
    if (!form) return;
    const input = form.querySelector('input[type="search"]');

    const setOpen = (open) => {
      form.hidden = !open;
      trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open && input) input.focus();
    };

    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      setOpen(form.hidden);
    });
    form.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        setOpen(false);
        trigger.focus();
      }
    });
    document.addEventListener('click', (event) => {
      if (!form.hidden && !form.contains(event.target) && !trigger.contains(event.target)) {
        setOpen(false);
      }
    });
  });
};

// Submit the WooCommerce "Sort by" dropdown as soon as it changes.
const initCatalogOrdering = () => {
  document.querySelectorAll('form.woocommerce-ordering select.orderby').forEach((select) => {
    select.addEventListener('change', () => select.form.submit());
  });
};

// ---------------------------------------------------------------------------
// Wishlist and compare lists (stored in the visitor's browser).
// Buttons: <button data-mc-wishlist="123"> / <button data-mc-compare="123">.
// The compare list is mirrored to a cookie so the Compare Table block can
// render it on the server.
// ---------------------------------------------------------------------------
const LISTS = {
  wishlist: { key: 'mc_wishlist', max: 100 },
  compare: { key: 'mc_compare', max: 4 },
};

const readList = (name) => {
  try {
    const raw = JSON.parse(localStorage.getItem(LISTS[name].key) || '[]');
    return Array.isArray(raw) ? raw.map((v) => parseInt(v, 10)).filter((v) => v > 0) : [];
  } catch (e) {
    return [];
  }
};

const writeList = (name, ids) => {
  try {
    localStorage.setItem(LISTS[name].key, JSON.stringify(ids));
  } catch (e) {
    /* Storage blocked: the list just won't persist. */
  }
  if (name === 'compare') {
    document.cookie = `mc_compare=${ids.join(',')};path=/;max-age=${60 * 60 * 24 * 30};SameSite=Lax`;
  }
  document.dispatchEvent(new CustomEvent('mc:list:changed', { detail: { name, ids } }));
};

let toastTimer;
const toast = (message) => {
  let el = document.getElementById('mc-toast');
  if (!el) {
    el = document.createElement('div');
    el.id = 'mc-toast';
    el.className = 'mc-toast';
    el.setAttribute('role', 'status');
    el.setAttribute('aria-live', 'polite');
    document.body.appendChild(el);
  }
  el.textContent = message;
  el.classList.add('is-visible');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove('is-visible'), 2600);
};

const syncButtons = () => {
  Object.keys(LISTS).forEach((name) => {
    const ids = readList(name);
    document.querySelectorAll(`[data-mc-${name}]`).forEach((btn) => {
      const on = ids.includes(parseInt(btn.getAttribute(`data-mc-${name}`), 10));
      btn.setAttribute('aria-pressed', on ? 'true' : 'false');
      btn.classList.toggle('is-active', on);
    });
    document.querySelectorAll(`[data-mc-${name}-count]`).forEach((badge) => {
      badge.textContent = String(ids.length);
      badge.setAttribute(`data-mc-${name}-count`, String(ids.length));
      badge.hidden = ids.length === 0; // No "0" bubble on an empty list.
    });
  });
};

const initSaveButtons = () => {
  document.addEventListener('click', (event) => {
    const btn = event.target.closest('[data-mc-wishlist], [data-mc-compare]');
    if (!btn) return;
    event.preventDefault();
    event.stopPropagation();
    const name = btn.hasAttribute('data-mc-wishlist') ? 'wishlist' : 'compare';
    const id = parseInt(btn.getAttribute(`data-mc-${name}`), 10);
    let ids = readList(name);
    const i18n = (window.mc_ajax && window.mc_ajax.i18n) || {};
    if (ids.includes(id)) {
      ids = ids.filter((v) => v !== id);
      toast(name === 'wishlist' ? i18n.wishRemoved || 'Removed from your wishlist' : i18n.compareRemoved || 'Removed from compare');
    } else {
      if (ids.length >= LISTS[name].max) {
        toast(name === 'compare' ? i18n.compareFull || 'You can compare up to 4 products' : i18n.wishFull || 'Your wishlist is full');
        return;
      }
      ids.push(id);
      toast(name === 'wishlist' ? i18n.wishAdded || 'Saved to your wishlist' : i18n.compareAdded || 'Added to compare');
    }
    writeList(name, ids);
    syncButtons();
    // Buttons on a server-rendered list (e.g. the Compare Table's remove "x") refresh the page.
    if (btn.hasAttribute('data-mc-reload')) window.location.reload();
  });
  document.addEventListener('mc:list:changed', syncButtons);
  // Lists changed in another tab.
  window.addEventListener('storage', syncButtons);
  syncButtons();
};

window.mcLists = { read: readList, write: writeList, sync: syncButtons };

const init = () => {
  document.body.classList.add('modern-core-loaded');
  initHeaderSearch();
  initCatalogOrdering();
  initSaveButtons();
  initAnimations();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
