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
		'suppress_filters' => true,
	)
);

if ( ! $team_members->have_posts() ) {
	return;
}

$block_id = 'team-carousel-' . wp_rand( 1000, 9999 );

// Build array of team members
$members = array();
while ( $team_members->have_posts() ) {
	$team_members->the_post();
	$post_id = get_the_ID();
	$image_html = '';
	if ( has_post_thumbnail() ) {
		$image_url = get_the_post_thumbnail_url( $post_id, 'full' );
		$image_alt = get_post_meta( get_post_thumbnail_id( $post_id ), '_wp_attachment_image_alt', true );
		$image_html = sprintf(
			'<img src="%s" alt="%s" loading="lazy">',
			esc_url( $image_url ),
			esc_attr( $image_alt )
		);
	}
	
	$members[] = array(
		'id'             => $post_id,
		'title'          => get_the_title(),
		'job_title'      => get_field( 'role', $post_id ) ?? '',
		'content'        => get_the_content(),
		'image'          => $image_html,
		'secondary_image_id' => get_field( 'secondary_image', $post_id ),
	);
}
wp_reset_postdata();
?>
<a class="anchor" id="team"></a>
<div class="cb-team-carousel" id="<?php echo esc_attr( $block_id ); ?>">
	<!-- Left Swiper (inactive slides) -->
	<div class="team-carousel__left">
		<div class="swiper" id="<?php echo esc_attr( $block_id ); ?>-left">
			<div class="swiper-wrapper">
				<?php foreach ( $members as $member ) : ?>
					<div class="swiper-slide">
						<?php echo $member['image']; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<!-- Center Swiper (active slide with details) -->
	<div class="team-carousel__center">
		<div class="swiper" id="<?php echo esc_attr( $block_id ); ?>-center">
			<div class="swiper-wrapper">
				<?php foreach ( $members as $member ) : ?>
					<div class="swiper-slide" data-post-id="<?php echo esc_attr( $member['id'] ); ?>">
						<div class="team-member__image-container">
							<?php echo $member['image']; ?>
							<div class="team-member__details">
								<span class="team-member__name"><?php echo esc_html( $member['title'] ); ?></span>
								<?php if ( $member['job_title'] ) : ?>
									<span class="team-member__title"><?php echo esc_html( $member['job_title'] ); ?></span>
								<?php endif; ?>
							</div>
						</div>
						<div class="team-member__modal-data" style="display: none;">
							<div class="modal-title"><?php echo esc_html( $member['title'] ); ?></div>
							<div class="modal-image"><?php echo $member['image']; ?></div>
							<div class="modal-secondary-image">
								<?php 
								if ( $member['secondary_image_id'] ) {
									echo wp_get_attachment_image( $member['secondary_image_id'], 'large' );
								}
								?>
							</div>
							<div class="modal-content"><?php echo wp_kses_post( $member['content'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<!-- Right Swiper (inactive slides) -->
	<div class="team-carousel__right">
		<div class="swiper" id="<?php echo esc_attr( $block_id ); ?>-right">
			<div class="swiper-wrapper">
				<?php foreach ( $members as $member ) : ?>
					<div class="swiper-slide">
						<?php echo $member['image']; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<!-- Navigation Arrows -->
	<div class="team-carousel__arrows">
		<button class="team-carousel__arrow team-carousel__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'cb-njlive2026' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 78.53 81.57">
				<path fill="#fff" d="M5.66 36.63H78.53V44.63H5.66z"></path>
				<path fill="#fff" d="M40.92 81.57L0 40.65 40.65 0 46.31 5.66 11.31 40.65 46.57 75.91 40.92 81.57z"></path>
			</svg>
		</button>
		<button class="team-carousel__arrow team-carousel__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'cb-njlive2026' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 78.53 81.57">
				<path fill="#fff" d="M0 36.94h72.87v8H0z"></path>
				<path fill="#fff" d="M37.88 81.57l-5.65-5.66 34.99-34.99L31.96 5.66 37.62 0l40.91 40.92-40.65 40.65z"></path>
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
			const carouselId = '<?php echo esc_js( $block_id ); ?>';
			const carouselEl = document.getElementById(carouselId);
			
			if (!carouselEl || typeof Swiper === 'undefined') {
				console.error('[Team Carousel] Missing element or Swiper not loaded');
				return;
			}

			// Get the total count for proper looping
			const slideCount = carouselEl.querySelectorAll('#' + carouselId + '-center .swiper-slide').length;
			
			console.log('Slide count:', slideCount);

			// Initialize all three swipers identically
			const swiperLeft = new Swiper('#' + carouselId + '-left', {
				slidesPerView: 'auto',
				spaceBetween: 10,
				loop: true,
				loopAdditionalSlides: slideCount,
				speed: 600,
			});

			const swiperCenter = new Swiper('#' + carouselId + '-center', {
				slidesPerView: 'auto',
				loop: true,
				loopAdditionalSlides: slideCount,
				speed: 600,
			});

			const swiperRight = new Swiper('#' + carouselId + '-right', {
				slidesPerView: 'auto',
				spaceBetween: 10,
				loop: true,
				loopAdditionalSlides: slideCount,
				speed: 600,
			});

			console.log('Swipers initialized:', {
				left: swiperLeft.slides.length,
				center: swiperCenter.slides.length,
				right: swiperRight.slides.length
			});

			// Set initial positions
		// Left shows 3 slides before center, so start at slideCount - 3
		swiperLeft.slideToLoop(slideCount - 3, 0);
		swiperCenter.slideToLoop(0, 0);
		swiperRight.slideToLoop(1, 0);

		// Navigation with arrow buttons
		const prevBtn = carouselEl.querySelector('.team-carousel__arrow--prev');
		const nextBtn = carouselEl.querySelector('.team-carousel__arrow--next');

			if (prevBtn && nextBtn) {
				prevBtn.addEventListener('click', () => {
					console.log('Prev clicked');
					swiperCenter.slidePrev();
					swiperLeft.slidePrev();
					swiperRight.slidePrev();
				});

				nextBtn.addEventListener('click', () => {
					console.log('Next clicked');
					swiperCenter.slideNext();
					swiperLeft.slideNext();
					swiperRight.slideNext();
				});
			} else {
				console.error('Buttons not found');
			}

			// Modal functionality
			const modal = document.getElementById('team-modal-<?php echo esc_js( $block_id ); ?>');
			const modalBody = modal?.querySelector('.team-modal__body');
			const modalOverlay = modal?.querySelector('.team-modal__overlay');

			if (!modal || !modalBody || !modalOverlay) {
				console.error('[Team Carousel] Modal elements missing');
				return;
			}

			// Click handler for center slides
			swiperCenter.slides.forEach((slide, index) => {
				slide.addEventListener('click', function() {
					if (!slide.classList.contains('swiper-slide-active')) return;
					
					const modalData = slide.querySelector('.team-member__modal-data');
					if (!modalData) return;
					
					const titleEl = modalData.querySelector('.modal-title');
					const imageEl = modalData.querySelector('.modal-image');
					const secondaryImageEl = modalData.querySelector('.modal-secondary-image');
					const contentEl = modalData.querySelector('.modal-content');
					
					if (!titleEl || !imageEl || !contentEl) return;
					
					modalBody.innerHTML = `
						<div class="team-modal__image">${imageEl.innerHTML}</div>
						${secondaryImageEl?.innerHTML ? `<div class="team-modal__secondary-image">${secondaryImageEl.innerHTML}</div>` : ''}
						<div class="team-modal__text">
							<div class="content">${contentEl.innerHTML}</div>
							<button class="team-modal__close" aria-label="Close modal">&times;</button>
						</div>
					`;
					
					modal.style.display = 'flex';
					document.body.style.overflow = 'hidden';
				});
			});

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
		})();
		</script>
		<?php
	},
    9999
);
