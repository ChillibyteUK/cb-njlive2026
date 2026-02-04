<?php
/**
 * Block template for CB Arrow Heading.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$width = get_field( 'width_ch' );

$id = $block['anchor'] ?? $block['id'];

?>
<section class="arrow-heading" id="<?php echo esc_attr( $id ); ?>" style="--_heading-width-ch: <?= esc_attr( $width ); ?>ch;">
    <div class="container">
        <h2 class="arrow-heading-text d-flex flex-column lh-tightest heading-word-slide">
            <span class="text-start"><?= esc_html( get_field( 'word_one' ) ); ?></span>
            <span class="text-end"><?= esc_html( get_field( 'word_two' ) ); ?></span>
        </h2>
        <span class="arrow-heading-arrow">
            <svg width="150" height="148" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="cls-1" d="M100.89,4.24L8.49,96.63l92.4,92.4M8.49,96.63h189" stroke="currentcolor" stroke-width="12" stroke-miterlimit="10"/>
            </svg>
        </span>
    </div>
</section>