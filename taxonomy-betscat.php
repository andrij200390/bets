<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package consultpresslite-pt
 */

get_header();

?>

	<div id="primary" class="content-area  container">
		<div class="row">
			<main id="main" class="site-main  col-xs-12  col-lg-9">
				<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'h-entry', 'clearfix' ) ); ?>>
					<!-- Featured Image and Date -->
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="article__featured-image-link" href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid  article__featured-image  u-photo' ) ); ?>
						</a>
					<?php endif; ?>

					<!-- Content Box -->
					<div class="article__content">
						
						<span class="article__author"><a href="<?php echo get_permalink();?>">Обзор</a></span> <span class="article__author"><a href="<?php echo get_post_meta( get_the_ID(), 'betsite', true ) ; ?>">Сайт</a></span>
							
						<div class="rating" style="display:inline-block;">
					
                                <?php 
                                 $nb_stars = intval( get_post_meta( get_the_ID(), 'rating', true ) );
                                for ( $star_counter = 1; $star_counter <= 5; $star_counter++ ) {
                                    if ( $star_counter <= $nb_stars ) {
                                        echo '<img src="' . plugins_url( 'bets/images/icon.png' ) . '" />';
                                         } else {
                                            echo '<img src="' . plugins_url( 'bets/images/grey.png' ). '" />';
                                        }
                                    }
                                ?>
                            </div>
						<!-- Content -->
						<?php the_title( sprintf( '<h2 class="article__title  p-name"><a class="article__title-link  u-url" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php
						$consultpresslite_is_excerpt = ( 1 === (int) get_option( 'rss_use_excerpt', 0 ) );
						if ( $consultpresslite_is_excerpt ) : ?>
							<p class="e-content">
								<?php echo wp_kses_post( get_the_excerpt() ); ?>
							</p>
							<p>
								<a href="<?php echo esc_url( get_permalink() ); ?>" class="more-link"><?php printf( esc_html__( 'Read more %s', 'consultpress-lite' ), the_title( '<span class="screen-reader-text">', '</span>', false ) ); ?></a>
							</p>
						<?php else :
							/* translators: %s: Name of current post */
							the_content( sprintf(
								esc_html__( 'Read more %s', 'consultpress-lite' ),
								the_title( '<span class="screen-reader-text">"', '"</span>', false )
							) );
						endif;
						?>
					</div><!-- .article__content -->
				</article><!-- .article -->

					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>

				<?php endwhile; // End of the loop. ?>
			</main>

			<div class="col-xs-12  col-lg-3">
				<div class="sidebar  js-sidebar">
					<div class="sidebar__shift">
						<!-- Header widget area -->
						<?php get_template_part( 'template-parts/header-widget-area' ); ?>
						<!-- Featured Button -->
						<?php get_template_part( 'template-parts/featured-button' ); ?>
						<!-- Main Navigation -->
						<?php get_template_part( 'template-parts/main-navigation' ); ?>
						<!-- Sidebar -->
						<?php dynamic_sidebar( apply_filters( 'consultpresslite_regular_page_sidebar', 'regular-page-sidebar', get_the_ID() ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
                           