// Add your custom JS here.

// Initialize Lenis (autoRaf disabled - controlled by GSAP ticker instead)
const lenis = new Lenis({
  autoRaf: false,
  lerp: 0.1,
  smoothWheel: true,
});

// Make lenis available globally for GSAP integration
window.lenis = lenis;
