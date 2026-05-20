/**
 * Main Theme JavaScript Entry Point
 *
 * This file handles global interactivity that falls outside the scope of
 * individual Gutenberg blocks or the Interactivity API.
 */

document.addEventListener("DOMContentLoaded", () => {
  // Global script initialization will occur here.
  // Example: Initializing a custom event bus or sitewide accessibility toggles.

  const initModernCore = () => {
    document.body.classList.add("modern-core-loaded");
  };

  initModernCore();
});
