<?php
/**
 * Block template for CB Service Nav.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="service-nav">
	<div class="service-nav-wrapper">
		<div class="service-nav-heading">
			<div class="container">
				<h2 class="service-nav-heading-text d-flex flex-column lh-tightest word-slide-effect">
					<span class="text-start">OUR</span>
					<span class="text-end">SERVICES</span>
				</h2>
				<span class="service-nav-heading-arrow">
					<svg width="150" height="148" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path class="cls-1" d="M100.89,4.24L8.49,96.63l92.4,92.4M8.49,96.63h189" stroke="currentcolor" stroke-width="12" stroke-miterlimit="10"/>
					</svg>
				</span>
			</div>
		</div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 fs-600 fw-medium lh-tight">
                   Strategy, creativity, and flawless execution-all under one roof.Our services span the entire event lifecycle, from initial brainstorming sessions to post-event analysis andeverything in between. 
                </div>
            </div>
        </div>
    </div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
gsap.registerPlugin(ScrollTrigger);

function initServiceNavHeading() {
	const wrapper = document.querySelector(".service-nav-wrapper");
	const heading = wrapper?.querySelector(".service-nav-heading");
	const headingText = heading?.querySelector(".service-nav-heading-text.word-slide-effect");

	if (!heading || !headingText) return;

	// Word animation setup
	let wordInners = [];
	if (!headingText.dataset.wordsSplit) {
		const spans = headingText.querySelectorAll('span');
		spans.forEach((span) => {
			const text = span.textContent.trim();
			const words = text.split(/\s+/);
			span.innerHTML = '';
			
			words.forEach((word, wordIdx) => {
				const wordMask = document.createElement('span');
				wordMask.className = 'word-mask';
				
				const wordInner = document.createElement('span');
				wordInner.className = 'word-inner';
				wordInner.textContent = word;
				
				wordMask.appendChild(wordInner);
				span.appendChild(wordMask);
				wordInners.push(wordInner);
				
				if (wordIdx < words.length - 1) {
					span.appendChild(document.createTextNode(' '));
				}
			});
		});
		headingText.dataset.wordsSplit = 'true';
	} else {
		wordInners = Array.from(headingText.querySelectorAll('.word-inner'));
	}

	if (wordInners.length) {
		gsap.set(wordInners, { y: '100%' });
		
		ScrollTrigger.create({
			trigger: wrapper,
			start: 'top center',
			onEnter: () => {
				wordInners.forEach((wordInner, idx) => {
					gsap.to(wordInner, { y: 0, duration: 0.6, ease: 'power2.out', delay: idx * 0.15 });
				});
			},
			once: true
		});
	}

	// Arrow rotation animation
	const arrow = heading.querySelector('.service-nav-heading-arrow');
	if (arrow) {
		ScrollTrigger.create({
			trigger: wrapper,
			start: 'top 80%',
			onUpdate: (self) => {
				const clamped = gsap.utils.clamp(0, 0.2, self.progress);
				gsap.set(arrow, { rotation: gsap.utils.mapRange(0, 0.2, 0, -90, clamped) });
			}
		});
	}
}

window.addEventListener("load", initServiceNavHeading);
</script>
		<?php
	},
	9999
);