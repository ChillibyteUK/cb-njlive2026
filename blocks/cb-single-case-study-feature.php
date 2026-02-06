<?php
/**
 * Block template for CB Single Case Study Feature.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$cs = get_field( 'case_study' );


if ( ! $cs[0] ) {
    return;
}

$cs = $cs[0];

?>
<section class="single-case-study pb-5">
    <div class="container">
        <?php
        $thumb = get_field( 'thumbnail_video', $cs );
        $title = get_field( 'short_title', $cs ) ? get_field( 'short_title', $cs ) : get_the_title( $cs );
        $year = get_field( 'year', $cs );
        $text = get_field( 'card_text', $cs );
        ?>
        <a class="our-work-card" href="<?= esc_url( get_permalink( $cs ) ); ?>">
            <div class="our-work-card__header">
                <div class="our-work-card__header-title">
                    <div class="our-work-card__header-arrow">
                        <svg width="48" height="39" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                    <span><?= esc_html( $text ); ?></span>
                </div>
            </div>
        </a>
    </div>
    <div class="pt-5 text-center">
        <a href="/work/" target="" class="fancy-button fancy-button--dark">
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
</section>
<?php

wp_reset_postdata();

add_action(
	'wp_footer',
	function () {
		?>
<script>
window.addEventListener("load", function() {
	if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
	
	gsap.registerPlugin(ScrollTrigger);
	
	const card = document.querySelector('.single-case-study .our-work-card');
	
	if (card) {
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
	}
});
</script>
		<?php
	},
	9999
);
