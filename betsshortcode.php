<?php require_once(dirname(__FILE__).'/../../../wp-load.php');?>
<div id="text-3" class="widget widget_text">			
<div class="textwidget">
<div id="list_bets_widget-4" class="widget widget_list_bets_widget">
    <div id="alxtabs-3" class="widget tabs2 widget_bets">
    <ul class="alx-tabs-nav group tabs__caption2">
    <li class="alx-tab tab-recent-bets active"><a href="#tab-recent-bets" title="Последние обзоры ">Последние</a></li>
    <li class="alx-tab tab-popular-bets"><a href="#tab-popular-bets" title="Популярные букмекеры">Популярные</a></li>
    </ul>
        <div class="alx-tabs-container">

                <ul id="tab-recent-bets" class="alx-tab tabs__content2 active">
                    <?php 
                     $allbets = new WP_Query( array( 'post_type' => 'bets', 'posts_per_page' => 5

                    ) );
                    ?>

                    <?php while ( $allbets->have_posts() ) : $allbets->the_post(); ?>
                    <li>

                            <div class="img-bets">
                                <div class="td-module-thumb"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">

                                <?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?> 

                                 <?php the_post_thumbnail(array(218,150)); ?>
                                 <?php endif;?>
                                 </a>
                                </div>                

                            </div>
                            <div class="desc-bets"><a href="<?php the_permalink(); ?>" class="td-post-category"><?php the_title(); ?></a>
                            <a class="link-betsite" href="<?php echo get_post_meta( get_the_ID(), 'betsite', true ) ; ?>">На сайт</a>
                            </div>


                    </li>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); 
                    ?>

                </ul><!--/.alx-tab-->

                <ul id="tab-popular-bets" class="alx-tab tabs__content2">
                        <?php 
                         $topbets = new WP_Query( array( 'post_type' => 'bets', 'posts_per_page' => 5,  'meta_key' => 'rating', 'orderby' => 'meta_value_num', 'order' => 'DESC' ) );
                        ?>

                            <?php while ( $topbets->have_posts() ) : $topbets->the_post(); ?>
                                    <li>
                                    <?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?> 
                                        <div class="desc-bets">
                                            <a href="<?php the_permalink() ?>"><h3><?php the_title(); ?></h3></a>
                                             <?php 
                                                if(get_post_meta( get_the_ID(), 'recomend', true ) == 'Рекомендуем')
                                                            {
                                                            $class = 'green';
                                                            }
                                                elseif(get_post_meta( get_the_ID(), 'recomend', true ) == 'Не рекомендуем')
                                                            {
                                                            $class = 'red';
                                                            }

                                                echo '<span class="'.$class.'">'. get_post_meta( get_the_ID(), 'recomend', true ).'</span>'; ?>
                                        </div>
                                        <div class="img-bets">
                                         <a class="post_thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" alt="">
                                             <?php the_post_thumbnail(); ?>
                                         </a>
                                          <?php endif; ?>

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


                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); 

                        ?> 				
                </ul><!--/.alx-tab-->
            <script>
                (function($) {
                    $(function() {

                      $('ul.tabs__caption2').on('click', 'li:not(.active)', function() {
                        $(this)
                          .addClass('active').siblings().removeClass('active')
                          .closest('div.tabs2').find('ul.tabs__content2').removeClass('active').eq($(this).index()).addClass('active');
                      });

                    });
                })(jQuery);
            </script>
        </div>

    </div>
    </div>
    </div>
</div>
