<?php
/**
 * Block template for CB Solution.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="solution py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?= wp_get_attachment_image( get_field( 'image' ), 'full' ); ?>
            </div>
            <div class="col-md-4">
                <h2 class="h3">The Solution</h2>
                <?= wp_kses_post( get_field( 'content' ) ); ?>
            </div>
        </div>
    </div>
</section>