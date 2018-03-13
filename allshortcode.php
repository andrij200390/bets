<?php 
require_once(dirname(__FILE__).'/../../../wp-load.php');
$bets = new WP_Query( array( 'post_type' => 'bets', 'posts_per_page' => 10
                            //,  'meta_key' => 'rating', 'orderby' => 'meta_value_num', 'order' => 'DESC' 
));
?>
<div class="type-bets carousel shadow"> 
<div class="carousel-button-left"><a href="#"></a></div> 
<div class="carousel-button-right"><a href="#"></a></div> 
<div id="top-bets" class = "carousel-wrapper">
 <div class="carousel-items"> 
	<?php while ( $bets->have_posts() ) : $bets->the_post(); ?>

	<div class="one-bets carousel-block">
            <div class="td-module-image">
                <div class="td-module-thumb"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
                    
                <?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?> 
        
                 <?php the_post_thumbnail(array(100,52)); ?>
				 <?php endif;?>
                 </a>
                </div>                
               <div class="links-bets"><span class="article__categories"><a href="<?php echo get_post_meta( get_the_ID(), 'betsite', true ) ; ?>">На сайт</a></span><span class="article__categories"><a class="article__categories" href="<?php echo get_permalink();?>">Обзор</a></span></div>
            </div>
			
            <div class="rating">
					<?php /*
					 $nb_stars = intval( get_post_meta( get_the_ID(), 'rating', true ) );
					for( $star_counter = 1; $star_counter <= 5; $star_counter++ ){
						if ( $star_counter <= $nb_stars ) {
							echo '<img src="' . plugins_url( 'bets/images/iconmini.png' ) . '" />';
							 } else {
								echo '<img src="' . plugins_url( 'bets/images/greymini.png' ). '" />';
							}
						}*/
					?>
					 <a href="<?php the_permalink(); ?>" class="td-post-category"><?php the_title(); ?></a> 
			</div>
            
 
	</div> <!-- ./td-block-span4 -->
	
    <?php endwhile; ?>
    <?php wp_reset_postdata(); 
    ?>
</div>
</div>
</div>
<script>

</script>

       
 