<?php
/**
 * Template for displaying single posts.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="case-study-single">
	<section class="breadcrumbs fs-ui mb-4">
		<div class="container pt-4">
		<?php
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<div id="breadcrumbs" class="my-2">', '</div>' );
		}
		?>
		</div>
	</section>
	<article>
		<?php
		the_content();
		?>
	</article>
</main>
<?php
get_footer();
?>