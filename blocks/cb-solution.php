<?php
/**
 * Block template for CB Solution.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="solution py-5 has-light-grey-background-color">
    <div class="container">
        <div class="row">
            <div class="col-md-8 grow-solution">
                <?= wp_get_attachment_image( get_field( 'image' ), 'full' ); ?>
            </div>
            <div class="col-md-4">
                <h2 class="fw-medium">The Solution</h2>
                <div class="fs-500"><?= wp_kses_post( get_field( 'content' ) ); ?></div>
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
window.addEventListener("load", function() {
	if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
	
	gsap.registerPlugin(ScrollTrigger);
	
	const solutionImage = document.querySelector('.solution .grow-solution');
	
	if (solutionImage) {
		gsap.fromTo(solutionImage, 
			{
				scale: 0.7,
			},
			{
				scale: 1,
				ease: "none",
				scrollTrigger: {
					trigger: solutionImage,
					start: "top bottom",
					end: "center center",
					scrub: 1,
				}
			}
		);
	}
});
</script>
		<?php
	},
	9999
);