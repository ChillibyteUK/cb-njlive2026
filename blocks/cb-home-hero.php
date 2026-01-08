<?php
/**
 * Template for CB Home Hero.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center home-hero">
	<div class="underlay"></div>
	<div class="content py-5">
		<div class="container h-100 d-flex flex-column align-items-center">
			<div class="row m-auto justify-content-center align-items-center">
				<div class="col-lg-8 mb-5">
					<h1 class="type-writer"><?= esc_html( get_field( 'title' ) ); ?></h1>
				</div>
				<div class="col-lg-4 d-flex flex-column justify-content-start align-items-center gap-2">
					spinny thingy
				</div>
			</div>
		</div>
	</div>
</section>
<a id="content" class="anchor"></a>
<?php

/**
 * Inline typing animation script for hero h1.
 */
add_action(
	'wp_footer',
	function () {
		?>
		<script>
			(function() {
				function gsapWordSlideUp(element, opts = {}) {
					if (!element || !window.gsap) return;
					const text = element.textContent.trim();
					if (!text) return;
					const delay = opts.delay || 0.3;
					const stagger = opts.stagger || 0.15;
					const duration = opts.duration || 0.6;
					element.innerHTML = '';
					const words = text.split(/\s+/);
					const wordInners = [];
					
					words.forEach((word, wordIdx) => {
						const wordMask = document.createElement('span');
						wordMask.className = 'word-mask';
						
						const wordInner = document.createElement('span');
						wordInner.className = 'word-inner';
						wordInner.textContent = word;
						wordInner.style.transform = 'translateY(100%)';
						
						wordMask.appendChild(wordInner);
						element.appendChild(wordMask);
						wordInners.push(wordInner);
						
						if (wordIdx < words.length - 1) {
							element.appendChild(document.createTextNode(' '));
						}
					});
					
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
					
					return timeline;
				}
				
				const typeWriter = document.querySelector('#hero h1.type-writer');
				if (typeWriter) {
					gsapWordSlideUp(typeWriter, { delay: 0.3, stagger: 0.15, duration: 0.6 });
					// Refresh ScrollTrigger after DOM manipulation
					if (window.ScrollTrigger) {
						setTimeout(() => window.ScrollTrigger.refresh(), 100);
					}
				}
			})();
		</script>
		<?php
	},
	99
);