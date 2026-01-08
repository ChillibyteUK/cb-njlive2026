<?php
/**
 * Template for displaying single posts.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="blog">
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
		<div class="container">
			<h1><?= esc_html( get_the_title() ); ?></h1>
			<div class="post_hero">
				<?php
				if ( has_post_thumbnail() ) {
					echo get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'blog_hero__image mb-4' ) );
				}
				?>
			</div>
		</div>
		<?php
		the_content();
		?>
	</article>
</main>
<?php
get_footer();
?>