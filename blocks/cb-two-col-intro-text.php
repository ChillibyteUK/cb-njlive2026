<?php
/**
 * Block template for CB Two Col Intro Text.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$fsl = 'fs-' . get_field( 'left_size' );
$fsr = 'fs-' . get_field( 'right_size' );

$fwl = 'fw-' . get_field( 'left_weight' );
$fwr = 'fw-' . get_field( 'right_weight' );

?>
<section class="two-col-intro-text">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-md-6">
                <div class="<?= esc_attr( implode( ' ', array( $fsl, $fwl ) ) ); ?> lh-snug word-slide-effect" data-delay="0.3" data-stagger="0.15" data-duration="0.8"><?= wp_kses_post( get_field( 'left_text' ) ); ?></div>
            </div>
            <div class="<?= esc_attr( implode( ' ', array( $fsr, $fwr ) ) ); ?> lh-snug col-md-5 two-col-intro-text__right">
                <?= wp_kses_post( get_field( 'right_text' ) ); ?>
            </div>
        </div>
    </div>
</section>