<?php
/**
 * Block template for CB Challenge.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$id = $block['anchor'] ?? $block['id'];

?>
<section class="challenge py-5" id="<?php echo esc_attr( $id ); ?>" style="--_heading-width-ch: <?= esc_attr( $width ); ?>ch;">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2 class="challenge-text fw-medium d-flex flex-column lh-tightest heading-word-slide">
                    <span class="text-start">The challenge</span>
                </h2>
                <div class="fs-500"><?= wp_kses_post( get_field( 'content' ) ); ?></div>
            </div>
            <div class="col-md-4 text-end">
                <span class="challenge-arrow">
                    <svg width="150" height="148" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="cls-1" d="M100.89,4.24L8.49,96.63l92.4,92.4M8.49,96.63h189" stroke="currentcolor" stroke-width="12" stroke-miterlimit="10"/>
                    </svg>
                </span>
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
	
	const challengeSections = document.querySelectorAll('section.challenge');
	
	challengeSections.forEach((challengeSection) => {
		const challengeArrow = challengeSection.querySelector('.challenge-arrow');
		
		if (challengeArrow) {
			// Set display property to allow rotation
			gsap.set(challengeArrow, { display: 'inline-block' });
			
			ScrollTrigger.create({
				trigger: challengeSection,
				start: 'top 80%',
				end: 'bottom top',
				onUpdate: (self) => {
					const clamped = gsap.utils.clamp(0, 0.2, self.progress);
					gsap.set(challengeArrow, { rotation: gsap.utils.mapRange(0, 0.2, 0, -90, clamped) });
				}
			});
		}
	});
});
</script>
		<?php
	},
	9999
);
