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
				<h2 class="work-heading-text d-flex flex-column lh-tightest heading-word-slide">
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
			<?php
			$showreel = get_field( 'showreel' );
			if ( $showreel ) {
				?>
			<div class="ratio ratio-16x9 w-100 mb-5 grow-showreel" style="position: relative; background: #000;">
				<video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;">
					<source src="<?= esc_url( wp_get_attachment_url( $showreel ) ); ?>" type="video/webm">
				</video>
				<button class="showreel-sound-toggle" style="position: absolute; bottom: 20px; right: 20px; padding: 12px 24px; background: rgba(0, 0, 0, 0.8); color: white; cursor: pointer; opacity: 0; transition: opacity 0.3s ease; z-index: 10;" aria-label="Toggle sound">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
						<path class="sound-off" d="M11 5L6 9H2v6h4l5 4V5z"></path>
						<line class="sound-off" x1="23" y1="9" x2="17" y2="15"></line>
						<line class="sound-off" x1="17" y1="9" x2="23" y2="15"></line>
						<path class="sound-on" d="M11 5L6 9H2v6h4l5 4V5z" style="display: none;"></path>
						<path class="sound-on" d="M19.07 4.93a10 10 0 0 1 0 14.14" style="display: none;"></path>
						<path class="sound-on" d="M15.54 8.46a5 5 0 0 1 0 7.07" style="display: none;"></path>
					</svg>
				</button>
			</div>
				<?php
			}
			?>
			<div class="row g-5">
				<?php
				$q = new WP_Query(
					array(
						'post_type'      => 'casestudy',
						'posts_per_page' => 2,
						'orderby'        => 'date',
						'order'          => 'DESC',
					)
				);
				if ( $q->have_posts() ) {
					while ( $q->have_posts() ) {
						$q->the_post();
						$thumb = get_field( 'thumbnail_video', get_the_ID() );
						$title = get_field( 'short_title', get_the_ID() ) ? get_field( 'short_title', get_the_ID() ) : get_the_title();
						$year = get_field( 'year', get_the_ID() );
						$text = get_field( 'card_text', get_the_ID() );
						?>
				<div class="col-md-6">
					<a class="our-work-card" href="<?= esc_url( get_permalink() ); ?>">
						<div class="our-work-card__header">
							<div class="our-work-card__header-title">
								<div class="our-work-card__header-arrow">
									<svg width="26" height="21" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path class="cls-1" d="M97.11,4.24L189.51,96.63 97.11,189.03M189.51,96.63H0.51" stroke="currentcolor" stroke-width="12" stroke-miterlimit="10"/>
									</svg>
								</div>
								<?= esc_html( $title ); ?>
							</div>
							<div class="our-work-card__header-year">
								<?= esc_html( $year ); ?>
							</div>
						</div>
						<div class="our-work-card__body">
							<div class="our-work-card__body-front">
								<?php
								if ( $thumb ) {
									?>
									<video autoplay loop muted playsinline style="width: 100%; height: auto; display: block;">
										<source src="<?= esc_url( wp_get_attachment_url( $thumb ) ); ?>" type="video/webm">
									</video>
									<?php
								}
								?>
							</div>
							<div class="our-work-card__body-back">
								<?= esc_html( $text ); ?>
							</div>
						</div>
					</a>
				</div>
						<?php
					}
					wp_reset_postdata();
				}
				?>
			</div>
		</div>
		<div class="pt-5 text-center">
			<a href="/work/" target="" class="fancy-button">
				<span class="fancy-button__icon fancy-button__icon--left">
					<svg viewBox="0 0 70 70" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M35.103 27 43 34.897l-7.897 7.896M43 35.071H26" vector-effect="non-scaling-stroke"></path></svg>
				</span>
				<span class="fancy-button__label" style="">
					View all work
				</span>
				<span class="fancy-button__icon fancy-button__icon--right">
					<svg viewBox="0 0 70 70" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M35.103 27 43 34.897l-7.897 7.896M43 35.071H26" vector-effect="non-scaling-stroke"></path></svg>
				</span>
			</a>
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
	const headingText = heading?.querySelector(".work-heading-text.heading-word-slide");

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

	// Showreel grow animation
	const showreelContainer = wrapper?.querySelector('.grow-showreel');
	if (showreelContainer) {
		gsap.fromTo(showreelContainer, 
			{
				scale: 0.7,
			},
			{
				scale: 1,
				ease: "none",
				scrollTrigger: {
					trigger: showreelContainer,
					start: "top bottom",
					end: "center center",
					scrub: 1,
				}
			}
		);
	}

	// Showreel sound toggle
	if (showreelContainer) {
		const video = showreelContainer.querySelector('video');
		const soundToggle = showreelContainer.querySelector('.showreel-sound-toggle');
		
		if (video && soundToggle) {
			showreelContainer.addEventListener('mouseenter', () => {
				soundToggle.style.opacity = '1';
			});
			
			showreelContainer.addEventListener('mouseleave', () => {
				soundToggle.style.opacity = '0';
			});
			
			soundToggle.addEventListener('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				video.muted = !video.muted;
				const soundOff = soundToggle.querySelectorAll('.sound-off');
				const soundOn = soundToggle.querySelectorAll('.sound-on');
				
				if (video.muted) {
					soundOff.forEach(el => el.style.display = '');
					soundOn.forEach(el => el.style.display = 'none');
				} else {
					soundOff.forEach(el => el.style.display = 'none');
					soundOn.forEach(el => el.style.display = '');
				}
			});
		}
	}

	// Work cards grow animation
	const workCards = wrapper?.querySelectorAll('.our-work-card');
	if (workCards && workCards.length) {
		workCards.forEach((card) => {
			gsap.fromTo(card, 
				{
					scale: 0.7,
				},
				{
					scale: 1,
					ease: "none",
					scrollTrigger: {
						trigger: card,
						start: "top bottom",
						end: "center center",
						scrub: 1,
					}
				}
			);
		});
	}
}

window.addEventListener("load", initWorkHeading);
</script>
		<?php
	},
	9999
);