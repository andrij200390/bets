<?php
/**
 * The template for displaying all single posts.
 *
 * @package consultpresslite-pt
 */

get_header();

?>

	<div id="primary" class="content-area  container">
		<div class="row">
			<main id="main" class="site-main  col-xs-12  col-lg-9">
				<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="article__content  e-content">
								<div class="article__meta">
									<!-- Categories -->
									<?php if ( has_category() ) : ?>
										<span class="article__categories"><?php the_category( ' ' ); ?></span>
									<?php endif; ?>
									<!-- Author -->
									<span class="article__author"><i class="fa fa-user" aria-hidden="true"></i><span class="p-author"><?php the_author(); ?></span></span>
									<!-- Date -->
									<a class="article__date" href="<?php the_permalink(); ?>"><time class="dt-published" datetime="<?php the_time( 'c' ); ?>"><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo get_the_date(); ?></time></a>
								</div>
								<!-- Content -->
								<?php the_title( sprintf( '<h2 class="article__title  p-name">', esc_url( get_permalink() ) ), '</h2>' ); ?>
								<!-- Featured Image -->
								<?php if ( has_post_thumbnail() ) : ?>
								<div class="article__featured-image-link bets-img">
									<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid  article__featured-image  u-photo' ) ); ?>
								</div>
								<?php endif; ?>

								
								<div class="bets-info">
												
														<div id="alxtabs-4" class="tabs"> 
														 
														<ul class="tabs__caption alx-tabs-nav group tab-count-6">
															<li class="alx-tab tab-desc-bets active"><a href="#tab-desc-bets" title="Основное описание "><i class="fa fa-bookmark" aria-hidden="true"></i> Основное</a></li>
															
															<li class="alx-tab tab-plus-bets"><a href="#tab-plus-bets" title="Плюсы и минусы"><i class="fa fa-plus-circle" aria-hidden="true"></i> Плюсы и минусы</a></li>
															
															<li class="alx-tab tab-link-bets"><a href="#tab-link-bets" title="Полезные ссылки"><i class="fa fa-link" aria-hidden="true"></i> Ссылки</a></li>
															
														</ul> 
														 <div class="alx-tabs-container">   
														  <div id="tab-desc-bets" class="alx-tab tabs__content active" >
															<div class="col1">
																 <div class="rating">
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
																<!--<div class="minrate">
																	<i class="fa" aria-hidden="true"></i> Минимальная ставка: <?php// echo get_post_meta( get_the_ID(), 'minrate', true ) ; ?>
																</div> -->
																<!--<div class="mindep">
																  <i class="fa" aria-hidden="true"></i>  Минимальный депозит: <?php// echo get_post_meta( get_the_ID(), 'mindep', true ) ; ?>
																</div>-->
																
																<div class="currency">
																	<i class="fa fa-usd" aria-hidden="true"></i>Валюта: <?php echo get_post_meta( get_the_ID(), 'сurrency', true ) ; ?>
																</div>
																													   
																
																<div class="desc">
																 <i class="fa fa-file-text" aria-hidden="true"></i> Дополнительно: <?php echo get_post_meta( get_the_ID(), 'desc', true ) ; ?>
																</div>
																<div class="recomend">
																<?php 
																if(get_post_meta( get_the_ID(), 'recomend', true ) == 'Рекомендуем')
																{
																$class = 'green';
																}
																elseif(get_post_meta( get_the_ID(), 'recomend', true ) == 'Не рекомендуем')
																{
																$class = 'red';
																}

																echo '<span class="'.$class.'">МЫ '. get_post_meta( get_the_ID(), 'recomend', true ).'</span>'; ?>

																</div>
															</div>
														</div>
														<div id="tab-plus-bets" class="alx-tab tabs__content" >
														
															<div class="pos">
																<div class="poshead">		
															   <i class="fa fa-plus-square" aria-hidden="true"></i> Плюсы: <br />
																</div>
																<div class="positive">
																	<?php echo get_post_meta( get_the_ID(), 'positive', true ) ; ?>
																</div>
															</div>
															<div class="neg">
																<div class="neghead">	
																 <i class="fa fa-minus-square" aria-hidden="true"></i> Минусы: <br />
																</div>
																 <div class="negative">
																	<?php echo get_post_meta( get_the_ID(), 'negative', true ) ; ?>
																</div>
															</div>
															
														</div>
														<div id="tab-link-bets" class="alx-tab tabs__content"  > 
														   
															<span class="link-home"><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo get_post_meta( get_the_ID(), 'betsite', true ) ; ?>">Официальный сайт</a></span>
															<span class="link-reviews"><i class="fa fa-comments-o" aria-hidden="true"></i><a href="<?php echo get_post_meta( get_the_ID(), 'bet', true ) ; ?>">Отзывы</a></span>   
														</div>
														
														
													</div>
														
														
												</div>
												
								</div>
								<div class="clear"></div>
								<?php the_content(); ?>
								<!-- Multi Page in One Post -->
								<?php
									$consultpresslite_args = array(
										'before'      => '<div class="multi-page  clearfix">' . /* translators: after that comes pagination like 1, 2, 3 ... 10 */ esc_html__( 'Pages:', 'consultpress-lite' ) . ' &nbsp; ',
										'after'       => '</div>',
										'link_before' => '<span class="btn  btn-primary">',
										'link_after'  => '</span>',
										'echo'        => 1,
									);
									wp_link_pages( $consultpresslite_args );
								?>
								<!-- Tags -->
								<?php if ( has_tag() ) : ?>
									<div class="article__tags"><span class="article__tags-text"><?php esc_html_e( 'Tags:' , 'consultpress-lite' ); ?></span> <?php the_tags( '', '' ); ?></div>
								<?php endif; ?>
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
						<?php dynamic_sidebar( apply_filters( 'consultpresslite_blog_sidebar', 'blog-sidebar', get_the_ID() ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div><!-- #primary -->


   
<script>
    (function($) {
$(function() {

  $('ul.tabs__caption').on('click', 'li:not(.active)', function() {
    $(this)
      .addClass('active').siblings().removeClass('active')
      .closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
  });

});
})(jQuery);
</script>
<?php get_footer(); ?>
      
      
   
        
    