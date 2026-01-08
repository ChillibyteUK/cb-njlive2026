// Dynamically load the ESM build of Three.js inside a module script,
// expose it on window, and dispatch a readiness event.
(function() {
	var mod = document.createElement('script');
	mod.type = 'module';
	mod.textContent = `
		import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.182.0/build/three.module.min.js';
		window.THREE = THREE;
		window.dispatchEvent(new Event('threejs:ready'));
	`;
	document.head.appendChild(mod);
})();
