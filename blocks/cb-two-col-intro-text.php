<?php
/**
 * Block template for CB Two Col Intro Text.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="two_col_intro_text">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3 class="word-slide-effect" data-delay="0.3" data-stagger="0.15" data-duration="0.8"><?= wp_kses_post( get_field( 'left_text' ) ); ?></h3>
            </div>
            <div class="col-md-6">
                <?= wp_kses_post( get_field( 'right_text' ) ); ?>
            </div>
        </div>
    </div>
</section>