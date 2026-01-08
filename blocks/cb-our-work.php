<?php
/**
 * Block template for CB Our Work.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="our-work">
	<div class="work-wrapper">
		<div class="work-heading">
			<div class="container">
				<h2 class="work-heading-text d-flex flex-column lh-tightest word-slide-effect">
					<span class="text-start">OUR</span>
					<span class="text-end">WORK</span>
				</h2>
				<span class="work-heading-arrow">
					<svg width="150" height="148" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path class="cls-1" d="M100.89,4.24L8.49,96.63l92.4,92.4M8.49,96.63h189" stroke="currentcolor" stroke-width="12" stroke-miterlimit="10"/>
					</svg>
				</span>
			</div>
		</div>
		<div class="container">
			<div class="ratio ratio-16x9 lite-vimeo w-100 mb-5" style="position: relative;">
				<div style="position: absolute; inset: 0; background: #000; z-index: -1;"></div>
	            <iframe src="https://player.vimeo.com/video/1108035268?autoplay=1&amp;loop=1&amp;muted=1&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&amp;dnt=1&amp;color=000000" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Timber Rooms" spellcheck="false" style="background: #000; position: relative; z-index: 1;"></iframe>
			</div>
			<div class="row g-5">
				<div class="col-md-6">
					<div class="our-work-card">
						<div class="our-work-card__header">
							
						</div>
					</div>
					CASE STUDY HERE
				</div>
				<div class="col-md-6">
					CASE STUDY HERE
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

function initWorkHeading() {
	const wrapper = document.querySelector(".work-wrapper");
	const heading = wrapper?.querySelector(".work-heading");
	const headingText = heading?.querySelector(".work-heading-text.word-slide-effect");

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
	const arrow = heading.querySelector('.work-heading-arrow');
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

window.addEventListener("load", initWorkHeading);
</script>
		<?php
	},
	9999
);