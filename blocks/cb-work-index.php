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
            
            if ( $casestudy_query->have_posts() ) {
                $index = 0;
                while ( $casestudy_query->have_posts() ) {
                    $casestudy_query->the_post();
                    $thumb = get_field( 'thumbnail_video', get_the_ID() );
                    $title = get_field( 'short_title', get_the_ID() ) ? get_field( 'short_title', get_the_ID() ) : get_the_title();
                    $year = get_field( 'year', get_the_ID() );
                    $text = get_field( 'card_text', get_the_ID() );
                    $col_class = ( $index === 0 ) ? 'col-12' : 'col-12 col-md-6';
                    ?>
                    <div class="<?php echo esc_attr( $col_class ); ?>">
                        <a class="our-work-card" href="<?= esc_url( get_permalink() ); ?>">
                            <div class="our-work-card__header">
                                <div class="our-work-card__header-title">
                                    <div class="our-work-card__header-arrow">
                                        <svg width="26" height="21" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                    <?= esc_html( $text ); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                    $index++;
                }
                wp_reset_postdata();
            }
            ?>
        </div>
    </div>
</section>