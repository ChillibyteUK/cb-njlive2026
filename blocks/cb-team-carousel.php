<?php
/**
 * Block template for CB Team Carousel.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$team_members = new WP_Query(
	array(
		'post_type'      => 'person',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);

if ( ! $team_members->have_posts() ) {
	return;
}

$block_id = 'team-carousel-' . wp_rand( 1000, 9999 );
?>

<div class="cb-team-carousel" id="team">
	<div class="carousel-container">
		<div class="carousel-track">
			<?php
			while ( $team_members->have_posts() ) {
				$team_members->the_post();
				$post_id = get_the_ID();
				$title   = get_the_title();
				$job_title = get_field( 'role', $post_id ) ?? '';
				$content = get_the_content();
				$secondary_image_id = get_field( 'secondary_image', $post_id );
				$secondary_image_url = $secondary_image_id ? wp_get_attachment_image_url( $secondary_image_id, 'large' ) : '';
				?>
				<div class="carousel-slide" data-post-id="<?php echo esc_attr( $post_id ); ?>">
					<div class="team-member">
						<?php
						if ( has_post_thumbnail() ) {
							echo '<div class="team-member__image">';
							the_post_thumbnail( 'large' );
							echo '</div>';
						}
						?>
						<div class="team-member__content">
							<div class="team-member__content-overlay"></div>
							<h3 class="team-member__name"><?php echo esc_html( $title ); ?></h3>
							<?php if ( $job_title ) : ?>
								<p class="team-member__title"><?php echo esc_html( $job_title ); ?></p>
                            <?php endif; ?>
						</div>
						<div class="team-member__modal-data" style="display: none;">
							<div class="modal-title"><?php echo esc_html( $title ); ?></div>
							<div class="modal-image"><?php echo has_post_thumbnail() ? get_the_post_thumbnail( $post_id, 'large' ) : ''; ?></div>
							<div class="modal-secondary-image"><?php echo $secondary_image_url ? '<img src="' . esc_url( $secondary_image_url ) . '" alt="' . esc_attr( $title ) . '">' : ''; ?></div>
							<div class="modal-content"><?php echo wp_kses_post( $content ); ?></div>
						</div>
					</div>
				</div>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	</div>

	<div class="carousel-controls">
		<button class="carousel-btn carousel-btn--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'cb-njlive2026' ); ?>">
			<svg viewBox="0 0 24 24" width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</button>
		<button class="carousel-btn carousel-btn--next" aria-label="<?php esc_attr_e( 'Next slide', 'cb-njlive2026' ); ?>">
			<svg viewBox="0 0 24 24" width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</button>
	</div>
	
	<!-- Modal -->
	<div class="team-modal" id="team-modal-<?php echo esc_attr( $block_id ); ?>" style="display: none;">
		<div class="team-modal__overlay"></div>
		<div class="team-modal__content">
			<div class="team-modal__body"></div>
		</div>
	</div>
</div>

<?php
add_action(
	'wp_footer',
	function() use ( $block_id ) {
		?>
		<script>
		(function() {
			const carouselEl = document.getElementById('<?php echo esc_js( $block_id ); ?>');
			
			if (!carouselEl || typeof gsap === 'undefined') {
				console.error('[Carousel] Missing element or GSAP not loaded');
				return;
			}

			const track = carouselEl.querySelector('.carousel-track');
			const origSlides = Array.from(carouselEl.querySelectorAll('.carousel-slide'));
			const prevBtn = carouselEl.querySelector('.carousel-btn--prev');
			const nextBtn = carouselEl.querySelector('.carousel-btn--next');

			if (!track || origSlides.length === 0 || !prevBtn || !nextBtn) {
				console.error('[Carousel] Missing required elements');
				return;
			}

			const slideCount = origSlides.length;
			const bufferThreshold = Math.max(10, slideCount * 2);
			
			// Store original index on each original slide
			origSlides.forEach((slide, idx) => {
				slide.dataset.originalIdx = idx;
			});
			
			// Add initial buffer slides - 3 sets on each side
			for (let i = 0; i < slideCount * 3; i++) {
				const slideToClone = origSlides[i % slideCount];
				const clone = slideToClone.cloneNode(true);
				clone.dataset.originalIdx = i % slideCount;
				track.insertBefore(clone, track.firstChild);
			}
			for (let i = 0; i < slideCount * 3; i++) {
				const slideToClone = origSlides[i % slideCount];
				const clone = slideToClone.cloneNode(true);
				clone.dataset.originalIdx = i % slideCount;
				track.appendChild(clone);
			}

			let allSlides = track.querySelectorAll('.carousel-slide');
			let currentDOMIndex = slideCount * 3 + 3;
			let slidePixelWidth = 0;
			
			const calculateSlideWidth = () => {
				if (allSlides.length > 0) {
					slidePixelWidth = allSlides[0].offsetWidth;
				}
			};
			
			const updateCarousel = (animate = true, skipScaling = false) => {
				if (slidePixelWidth === 0) {
					calculateSlideWidth();
				}
				
				const newX = -((currentDOMIndex - 3) * slidePixelWidth);
				
				if (animate) {
					gsap.to(track, {
						x: newX + 'px',
						duration: 0.6,
						ease: 'power2.inOut'
					});
				} else {
					gsap.set(track, { x: newX + 'px' });
				}

				if (!skipScaling) {
					allSlides.forEach((slide, idx) => {
						const isActive = idx === currentDOMIndex;
						const teamMember = slide.querySelector('.team-member');
						
						if (isActive) {
							slide.classList.add('active');
						} else {
							slide.classList.remove('active');
						}
						
						if (teamMember) {
							teamMember.style.cursor = isActive ? 'pointer' : 'default';
						}
					});
				}
			};

			const moveNext = () => {
				if (currentDOMIndex >= allSlides.length - bufferThreshold) {
					for (let i = 0; i < slideCount; i++) {
						const slideToClone = origSlides[i];
						const clone = slideToClone.cloneNode(true);
						clone.dataset.originalIdx = i;
						track.appendChild(clone);
					}
					allSlides = track.querySelectorAll('.carousel-slide');
					attachModalHandlers(allSlides, allSlides.length - slideCount);
				}
				
				currentDOMIndex++;
				updateCarousel();
			};

			const movePrev = () => {
				if (currentDOMIndex <= bufferThreshold) {
					for (let i = slideCount - 1; i >= 0; i--) {
						const slideToClone = origSlides[i];
						const clone = slideToClone.cloneNode(true);
						clone.dataset.originalIdx = i;
						track.insertBefore(clone, track.firstChild);
					}
					currentDOMIndex += slideCount;
					allSlides = track.querySelectorAll('.carousel-slide');
					attachModalHandlers(allSlides, 0, slideCount);
					
					calculateSlideWidth();
					const newX = -((currentDOMIndex - 3) * slidePixelWidth);
					gsap.set(track, { x: newX + 'px' });
				}
				
				currentDOMIndex--;
				updateCarousel();
			};

			prevBtn.addEventListener('click', movePrev);
			nextBtn.addEventListener('click', moveNext);

			// Store modal data from original slides
			const modalDataArray = origSlides.map(slide => {
				const teamMember = slide.querySelector('.team-member');
				const modalData = teamMember?.querySelector('.team-member__modal-data');
				
				if (!modalData) return null;
				
				const titleEl = modalData.querySelector('.modal-title');
				const imageEl = modalData.querySelector('.modal-image');
				const secondaryImageEl = modalData.querySelector('.modal-secondary-image');
				const contentEl = modalData.querySelector('.modal-content');
				
				if (!titleEl || !imageEl || !contentEl) return null;
				
				return {
					title: titleEl.textContent.trim(),
					image: imageEl.innerHTML,
					secondaryImage: secondaryImageEl?.innerHTML || '',
					content: contentEl.innerHTML
				};
			});

			// Modal functionality
			const modal = document.getElementById('team-modal-<?php echo esc_js( $block_id ); ?>');
			const modalBody = modal?.querySelector('.team-modal__body');
		const modalOverlay = modal?.querySelector('.team-modal__overlay');

		if (!modal || !modalBody || !modalOverlay) {
				console.error('[Carousel] Modal elements missing');
				return;
			}

			// Function to attach modal handlers to slides
			function attachModalHandlers(slides, startIdx = 0, endIdx = null) {
				const end = endIdx ?? slides.length;
				
				for (let i = startIdx; i < end; i++) {
					const slide = slides[i];
					const teamMember = slide.querySelector('.team-member');
					
					if (teamMember && !teamMember.dataset.hasHandler) {
						teamMember.dataset.hasHandler = 'true';
						teamMember.addEventListener('click', function() {
							const currentSlides = track.querySelectorAll('.carousel-slide');
							const slideIndex = Array.from(currentSlides).indexOf(this.closest('.carousel-slide'));
							
							if (slideIndex !== currentDOMIndex) return;
							
							const originalIdx = parseInt(this.closest('.carousel-slide').dataset.originalIdx, 10);
							const data = modalDataArray[originalIdx];
							
							if (!data) return;
							
							modalBody.innerHTML = `
								<div class="team-modal__image">${data.image}</div>
								${data.secondaryImage ? `<div class="team-modal__secondary-image">${data.secondaryImage}</div>` : ''}
								<div class="team-modal__text">
									<div class="content">${data.content}</div>								<button class="team-modal__close" aria-label="Close modal">&times;</button>								</div>
							`;
							
							modal.style.display = 'flex';
							document.body.style.overflow = 'hidden';
						});
					}
				}
			}

			// Attach handlers to initial slides
			attachModalHandlers(allSlides);

			function closeModal() {
				modal.style.display = 'none';
				document.body.style.overflow = '';
			}

modal.addEventListener('click', function(e) {
			if (e.target.classList.contains('team-modal__close')) {
				closeModal();
			}
		});
			modalOverlay.addEventListener('click', closeModal);
			
			// Handle window resize
			let resizeTimeout;
			window.addEventListener('resize', () => {
				clearTimeout(resizeTimeout);
				resizeTimeout = setTimeout(() => {
					slidePixelWidth = 0;
					updateCarousel(false);
				}, 250);
			});

			updateCarousel(false);
		})();
		</script>
		<?php
	},
    9999
);
