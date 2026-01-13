// Add your custom JS here.

// Initialize Lenis (autoRaf disabled - controlled by GSAP ticker instead)
const lenis = new Lenis({
  autoRaf: false,
  lerp: 0.1,
  smoothWheel: true,
});

// Make lenis available globally for GSAP integration
window.lenis = lenis;

// Arrow heading animation - works with multiple instances
function initArrowHeadings() {
	gsap.registerPlugin(ScrollTrigger);
	const sections = document.querySelectorAll('section.arrow-heading');
	sections.forEach((section) => {
		const arrow = section.querySelector('.arrow-heading-arrow');
		if (arrow) {
			ScrollTrigger.create({
				trigger: section,
				start: 'top 80%',
				onUpdate: (self) => {
					const clamped = gsap.utils.clamp(0, 0.2, self.progress);
					gsap.set(arrow, { rotation: gsap.utils.mapRange(0, 0.2, 0, -90, clamped) });
				}
			});
		}
	});
}

window.addEventListener('load', initArrowHeadings);

// GSAP word slide up animation - apply to elements with .word-slide-effect class
function initWordSlideAnimations() {
	gsap.registerPlugin(ScrollTrigger);
	const elements = document.querySelectorAll('.word-slide-effect');
	elements.forEach((element) => {
		// Skip if already animated (has word-inner children or data-wordsSplit flag)
		if (element.querySelector('.word-inner') || element.dataset.wordsSplit) {
			return;
		}
		
		const text = element.textContent.trim();
		if (!text) return;
		
		// Get options from data attributes or use defaults
		const delay = parseFloat(element.getAttribute('data-delay')) || 0.3;
		const stagger = parseFloat(element.getAttribute('data-stagger')) || 0.15;
		const duration = parseFloat(element.getAttribute('data-duration')) || 0.6;
		
		// Preserve original text-align and flex properties
		const originalAlign = element.style.textAlign || window.getComputedStyle(element).textAlign;
		const originalDisplay = element.style.display || window.getComputedStyle(element).display;
		
		element.innerHTML = '';
		const words = text.split(/\s+/);
		const wordInners = [];
		
		words.forEach((word, wordIdx) => {
			const wordMask = document.createElement('span');
			wordMask.className = 'word-mask';
			wordMask.style.display = 'inline-block';
			wordMask.style.overflow = 'hidden';
			
			const wordInner = document.createElement('span');
			wordInner.className = 'word-inner';
			wordInner.textContent = word;
			wordInner.style.transform = 'translateY(100%)';
			wordInner.style.display = 'inline-block';
			
			wordMask.appendChild(wordInner);
			element.appendChild(wordMask);
			wordInners.push(wordInner);
			
			if (wordIdx < words.length - 1) {
				element.appendChild(document.createTextNode(' '));
			}
		});
		
		// Restore text alignment
		element.style.textAlign = originalAlign;
		element.style.display = originalDisplay;
		
		// Mark element as split to prevent reprocessing
		element.dataset.wordsSplit = 'true';
		
		// Create scroll trigger to start animation when element enters viewport
		ScrollTrigger.create({
			trigger: element,
			start: 'top 80%',
			onEnter: () => {
				const timeline = gsap.timeline({ delay });
				wordInners.forEach((wordInner, idx) => {
					timeline.to(
						wordInner,
						{
							y: 0,
							duration: duration,
							ease: 'power2.out'
						},
						idx * stagger
					);
				});
			},
			once: true
		});
		
		// Refresh ScrollTrigger after DOM manipulation
		if (window.ScrollTrigger) {
			setTimeout(() => window.ScrollTrigger.refresh(), 100);
		}
	});
}

window.addEventListener('load', initWordSlideAnimations);

// Heading word animations - for headings with alignment (arrow-heading, work-heading, etc)
function initHeadingWordAnimations() {
	const headings = document.querySelectorAll('.heading-word-slide');
	headings.forEach((heading) => {
		// Skip if already animated
		if (heading.dataset.wordsSplit) {
			return;
		}
		
		const spans = Array.from(heading.children).filter(el => el.tagName === 'SPAN');
		let wordInners = [];
		
		spans.forEach((span) => {
			const text = span.textContent.trim();
			const words = text.split(/\s+/);
			span.innerHTML = '';
			
			words.forEach((word, wordIdx) => {
				const wordMask = document.createElement('span');
				wordMask.className = 'word-mask';
				wordMask.style.display = 'inline-block';
				wordMask.style.overflow = 'hidden';
				
				const wordInner = document.createElement('span');
				wordInner.className = 'word-inner';
				wordInner.textContent = word;
				wordInner.style.transform = 'translateY(100%)';
				wordInner.style.display = 'inline-block';
				
				wordMask.appendChild(wordInner);
				span.appendChild(wordMask);
				wordInners.push(wordInner);
				
				if (wordIdx < words.length - 1) {
					span.appendChild(document.createTextNode(' '));
				}
			});
		});
		
		heading.dataset.wordsSplit = 'true';
		
		if (wordInners.length) {
			gsap.set(wordInners, { y: '100%' });
			
			ScrollTrigger.create({
				trigger: heading.closest('section') || heading.parentElement,
				start: 'top center',
				onEnter: () => {
					wordInners.forEach((wordInner, idx) => {
						gsap.to(wordInner, { y: 0, duration: 0.6, ease: 'power2.out', delay: idx * 0.15 });
					});
				},
				once: true
			});
		}
	});
}

window.addEventListener('load', initHeadingWordAnimations);
