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

//Global Animation option --Block level

import AOS from "aos";
import "aos/dist/aos.css";

// Use 'load' instead of 'DOMContentLoaded' so images and grids have their true height
window.addEventListener("load", () => {
  AOS.init({
    duration: 800,
    easing: "ease-out-cubic",
    once: true,
    offset: 50, // Triggers when element is 50px past the bottom of the screen
  });

  // Failsafe: Force AOS to recalculate layout height 500ms after load
  // in case WordPress lazy-loads images and pushes content further down.
  setTimeout(() => {
    AOS.refresh();
  }, 500);
});