<?php 
require_once(dirname(__FILE__).'/../../../wp-load.php');
$bets = new WP_Query( array( 'post_type' => 'bets', 'posts_per_page' => 5,  'meta_key' => 'rating', 'orderby' => 'meta_value_num', 'order' => 'DESC' ) );
function new_excerpt_length($length) {
	return 8;
}
add_filter('excerpt_length', 'new_excerpt_length');
?>
<?php while ( $bets->have_posts() ) : $bets->the_post(); ?>


    <div class="sidebar-bets">
          <?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?> 
         <div class="img-bets">
			 <a class="post_thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" alt="">
				 <?php the_post_thumbnail(); ?>
			 </a>
			  <?php endif; ?>
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
			<div class="rating">
					
					<?php 
					$nb_stars = intval( get_post_meta( get_the_ID(), 'rating', true ) );
						for ( $star_counter = 1; $star_counter <= 5; $star_counter++ ) {
						if ( $star_counter <= $nb_stars ) {
							echo '<img src="' . plugins_url( 'bets/images/iconmini.png' ) . '" />';
							 } else {
								echo '<img src="' . plugins_url( 'bets/images/greymini.png' ). '" />';
							}
						}
					?>
			</div>
		</div>
		<div class="desc-bets">
		 <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		 <span><?php the_excerpt(); ?></span>
		</div>
    </div><!-- bets -->

<?php endwhile; ?>
<?php wp_reset_postdata(); 

?> 

