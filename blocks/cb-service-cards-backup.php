<?php
/**
 * Block template for CB Service Cards.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="service-cards">
	<div class="services-wrapper">
		<div class="services-heading">
			<div class="container">
				<h2 class="services-heading-text d-flex flex-column lh-tightest word-slide-effect">
					<span class="text-start">OUR</span>
					<span class="text-end">DNA</span>
				</h2>
				<span class="services-heading-arrow">
					<svg width="150" height="148" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path class="cls-1" d="M100.89,4.24L8.49,96.63l92.4,92.4M8.49,96.63h189" stroke="currentcolor" stroke-width="12" stroke-miterlimit="10"/>
					</svg>
				</span>
			</div>
		</div>
		<?php
		while ( have_rows( 'cards' ) ) {
			the_row();
			$card_title  = get_sub_field( 'title' );
			$description = get_sub_field( 'content' );
			$image       = get_sub_field( 'image' );
			?>
		<div class="service-card-wrapper">
			<div class="container">
				<div class="card service-card shadow-lg rounded-4">
					<div class="card-body p-4">
						<div class="row">
							<div class="col-md-6">
								<h3 class="card-title h2 mb-3"><?= esc_html( $card_title ); ?></h3>
								<p class="card-text"><?= wp_kses_post( $description ); ?></p>
							</div>
							<div class="col-md-6">
								<div class="card-image">
									<img src="<?= esc_url( $image['url'] ); ?>" alt="<?= esc_attr( $image['alt'] ); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			<?php
		}
		?>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
gsap.registerPlugin(ScrollTrigger);

// Integrate Lenis with GSAP ScrollTrigger
if (window.lenis) {
	window.lenis.on('scroll', ScrollTrigger.update);
	
	gsap.ticker.add((time) => {
		window.lenis.raf(time * 1000);
	});
	
	gsap.ticker.lagSmoothing(0);
}

function initServicesPanels() {
	// Kill existing triggers to prevent conflicts on re-init
	ScrollTrigger.getAll().forEach(trigger => {
		if (trigger.vars.trigger?.closest?.('.services-wrapper') || 
		    trigger.vars.trigger?.classList?.contains('service-card-wrapper')) {
			trigger.kill();
		}
	});

	const wrapper = document.querySelector(".services-wrapper");
	const heading = wrapper?.querySelector(".services-heading");
	const headingText = heading?.querySelector(".services-heading-text.word-slide-effect");
	const cards = gsap.utils.toArray(".service-card-wrapper", wrapper);

	if (!cards.length || !wrapper || !heading) return;

	// Word animation setup
	let wordInners = [];
	if (headingText && !headingText.dataset.wordsSplit) {
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
	} else if (headingText) {
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

	// Arrow rotation and heading fade animations
	const arrow = heading?.querySelector('.services-heading-arrow');
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
	
	const firstCard = cards[0];
	if (firstCard && heading) {
		ScrollTrigger.create({
			trigger: firstCard,
			start: 'top 150px',
			end: 'top 80px',
			onUpdate: (self) => {
				const progress = gsap.utils.clamp(0, 1, self.progress);
				gsap.set(heading, { opacity: gsap.utils.mapRange(0, 1, 1, 0, progress) });
			}
		});
	}

	// Each card has its own pin animation
	cards.forEach((card, i) => {
		const cardElement = card.querySelector('.service-card');
		if (!cardElement) return;
		
		// Set initial state
		gsap.set(cardElement, { scale: 1, opacity: 1, force3D: true });
		
		// Fixed duration for consistent behavior
		const pinDuration = 800;
		
		const tl = gsap.timeline({
			paused: true,
			smoothChildTiming: true
		});
		
		tl.to(cardElement, { duration: 0.5 }, 0)
			.to(cardElement, { 
				scale: 0.85, 
				opacity: 0, 
				duration: 0.5,
				ease: "none",
				force3D: true
		  	},
			0.5);
		
		ScrollTrigger.create({
			trigger: card,
			start: "top 80px",
			end: () => `+=${pinDuration}`,
			pin: card,
			pinSpacing: false,
			anticipatePin: 1,
			scrub: 0.5,
			animation: tl,
			refreshPriority: -i, // Critical: ensures proper stacking order
			onRefreshInit: (self) => {
				if (self.pin) {
					gsap.set(self.pin, { clearProps: "transform" });
				}
			},
			invalidateOnRefresh: true
		});
	});

	// Refresh ScrollTrigger to ensure all calculations are correct
	ScrollTrigger.refresh();
}

// Initialize after DOM is fully ready
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initServicesPanels);
} else {
	initServicesPanels();
}

// Reinitialize on window resize to handle responsive changes
let resizeTimeout;
window.addEventListener('resize', () => {
	clearTimeout(resizeTimeout);
	resizeTimeout = setTimeout(() => {
		initServicesPanels();
	}, 250);
});
</script>
		<?php
	},
	9999
);