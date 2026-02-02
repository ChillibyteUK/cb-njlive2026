<?php
/**
 * Block template for CB Full Image Grow.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="full-image-grow">
    <?= wp_get_attachment_image( get_field( 'image' ), 'full' ); ?>
</section>