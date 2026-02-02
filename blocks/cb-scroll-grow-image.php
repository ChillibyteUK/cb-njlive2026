<?php
/**
 * Block template for CB Scroll Grow Image.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$image_id = get_field( 'image' );
$block_id = 'grow-image-' . uniqid();

/**
 * Enqueue block-specific script
 */
if ( ! function_exists( 'cb_scroll_grow_image_enqueue' ) ) {
	function cb_scroll_grow_image_enqueue() {
		static $enqueued = false;
		if ( ! $enqueued ) {
			wp_add_inline_script( 'gsap-scrolltrigger', '
			document.addEventListener("DOMContentLoaded", function() {
				gsap.registerPlugin(ScrollTrigger);
				
				const growImages = document.querySelectorAll(".grow-image");
				
				growImages.forEach((section) => {
					const img = section.querySelector("img");
					if (!img) return;
					
					gsap.fromTo(img, 
						{
							scale: 0.7,
						},
						{
							scale: 1,
							ease: "none",
							scrollTrigger: {
								trigger: section,
								start: "top bottom",
								end: "center center",
								scrub: 1,
							}
						}
					);
				});
			});
			' );
			$enqueued = true;
		}
	}
	add_action( 'wp_footer', 'cb_scroll_grow_image_enqueue' );
}

?>
<section class="grow-image" id="<?php echo esc_attr( $block_id ); ?>">
    <div class="container">
        <?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
    </div>
</section>