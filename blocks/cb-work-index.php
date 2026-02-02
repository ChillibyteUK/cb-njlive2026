<?php
/**
 * Block template for CB Work Index.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;




?>
<section class="work-index py-5">
    <div class="container">
        <div class="row g-4">
            <?php
            $args = array(
                'post_type'      => 'casestudy',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            
            $casestudy_query = new WP_Query( $args );
            
            if ( $casestudy_query->have_posts() ) :
                $index = 0;
                while ( $casestudy_query->have_posts() ) : $casestudy_query->the_post();
                    $col_class = ( $index === 0 ) ? 'col-12' : 'col-12 col-md-6';
                    ?>
                    <article class="<?php echo esc_attr( $col_class ); ?>">
                        <a href="<?php the_permalink(); ?>" class="work-card">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'large', array( 'class' => 'work-card-image' ) ); ?>
                            <?php endif; ?>
                            <h3 class="work-card-title"><?php the_title(); ?></h3>
                        </a>
                    </article>
                    <?php
                    $index++;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>