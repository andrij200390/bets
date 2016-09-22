<?php

locate_template('includes/wp_booster/td_single_template_vars.php', true);

get_header();

global $loop_module_id, $loop_sidebar_position, $post, $td_sidebar_position;

$td_mod_single = new td_module_single($post);

?>
<div class="td-main-content-wrap">

    <div class="td-container td-post-template-default <?php echo $td_sidebar_position; ?>">
        <div class="td-crumb-container"><?php echo td_page_generator::get_single_breadcrumbs($td_mod_single->title); ?></div>

        <div class="td-pb-row">
            <?php

            //the default template
            switch ($loop_sidebar_position) {
                default: //sidebar right
					?>
                        <div class="td-pb-span8 td-main-content" role="main">
                            <div class="td-ss-main-content">
								<?
                                    if (have_posts()) {
                                    the_post();

                                    $td_mod_single = new td_module_single($post);
                                    ?>

                                    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>
                                        <div class="td-post-header">

                                            <?php echo $td_mod_single->get_category(); ?>

                                            <header class="td-post-title">
                                                <?php echo $td_mod_single->get_title();?>


                                                <?php if (!empty($td_mod_single->td_post_theme_settings['td_subtitle'])) { ?>
                                                    <p class="td-post-sub-title"><?php echo $td_mod_single->td_post_theme_settings['td_subtitle'];?></p>
                                                <?php } ?>


                                                <div class="td-module-meta-info">
                                                    <?php echo $td_mod_single->get_author();?>
                                                    <?php echo $td_mod_single->get_date(false);?>
                                                    <?php echo $td_mod_single->get_comments();?>
                                                    <?php echo $td_mod_single->get_views();?>
                                                </div>

                                            </header>

                                        </div>

                                        <?php echo $td_mod_single->get_social_sharing_top();?>


                                        <div class="td-post-content">
                                        <div class="td-bets-content">
                                            <div class="bets-img">
                                                <?php
                                                // override the default featured image by the templates (single.php and home.php/index.php - blog loop)
                                                if (!empty(td_global::$load_featured_img_from_template)) {
                                                    echo $td_mod_single->get_image(td_global::$load_featured_img_from_template);
                                                } else {
                                                    echo $td_mod_single->get_image('td_696x0');
                                                }
                                                ?>
                                            </div>
                                            <div class="bets-info">
                                                <div class="currency">
                                                    Валюта: <?php echo get_post_meta( get_the_ID(), 'сurrency', true ) ; ?>
                                                </div>
                                                <div class="minrate">
                                                    Минимальная ставка: <?php echo get_post_meta( get_the_ID(), 'minrate', true ) ; ?>
                                                </div> 
                                                <div class="mindep">
                                                    Минимальный депозит: <?php echo get_post_meta( get_the_ID(), 'mindep', true ) ; ?>
                                                </div>
                                              
                                                <div class="desc">
                                                   Дополнительное описание: <?php echo get_post_meta( get_the_ID(), 'desc', true ) ; ?>
                                                </div>
                                                <div class="rating">
                                                   Рейтинг букмекера:<br />
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
                                        
                                                    echo '<span class="'.$class.'">'. get_post_meta( get_the_ID(), 'recomend', true ).'</span>'; ?>
                                                    <div class="bets-desc-lnk">
                                                        <a href="<?php echo get_post_meta( get_the_ID(), 'bet', true ) ; ?>">Ссылка на отзывы</a>
                                                        <a href="<?php echo get_post_meta( get_the_ID(), 'betsite', true ) ; ?>">Официальный сайт </a>
                                                       
                                                    </div>
                                                </div>
                                                <div class="posneg">
                                                    Обзор конторы<br />
                                                    Положительно: <br />
                                                    <div class="positive">
                                                        <?php echo get_post_meta( get_the_ID(), 'positive', true ) ; ?>
                                                    </div>
                                                    Отрицательно: <br />
                                                     <div class="negative">
                                                        <?php echo get_post_meta( get_the_ID(), 'negative', true ) ; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        </div>
                                       

                                        <?php echo $td_mod_single->get_content();?>
                                        </div>


                                        <footer>
                                            <?php echo $td_mod_single->get_post_pagination();?>
                                            <?php echo $td_mod_single->get_review();?>

                                            <div class="td-post-source-tags">
                                                <?php echo $td_mod_single->get_source_and_via();?>
                                                <?php echo $td_mod_single->get_the_tags();?>
                                            </div>

                                            <?php echo $td_mod_single->get_social_sharing_bottom();?>
                                            <?php echo $td_mod_single->get_next_prev_posts();?>
                                            <?php echo $td_mod_single->get_author_box();?>
                                            <?php echo $td_mod_single->get_item_scope_meta();?>
                                        </footer>

                                    </article> <!-- /.post -->

                                    <?php echo $td_mod_single->related_posts();?>

                                <?php
                                } else {
                                    //no posts
                                    echo td_page_generator::no_posts();
                                }
                                ?>	
                                <?php
                                
                                comments_template('', true);
                                ?>
                            </div>
                        </div>
                        <div class="td-pb-span4 td-main-sidebar" role="complementary">
                            <div class="td-ss-main-sidebar">
                                <?php get_sidebar(); ?>
                            </div>
                        </div>
                    <?php
                    break;

                case 'sidebar_left':
                    ?>
                        <div class="td-pb-span8 td-main-content <?php echo $td_sidebar_position; ?>-content" role="main">
                           <div class="td-ss-main-content">
								<?
                                    if (have_posts()) {
                                    the_post();

                                    $td_mod_single = new td_module_single($post);
                                    ?>

                                    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>
                                        <div class="td-post-header">

                                            <?php echo $td_mod_single->get_category(); ?>

                                            <header class="td-post-title">
                                                <?php echo $td_mod_single->get_title();?>


                                                <?php if (!empty($td_mod_single->td_post_theme_settings['td_subtitle'])) { ?>
                                                    <p class="td-post-sub-title"><?php echo $td_mod_single->td_post_theme_settings['td_subtitle'];?></p>
                                                <?php } ?>


                                                <div class="td-module-meta-info">
                                                    <?php echo $td_mod_single->get_author();?>
                                                    <?php echo $td_mod_single->get_date(false);?>
                                                    <?php echo $td_mod_single->get_comments();?>
                                                    <?php echo $td_mod_single->get_views();?>
                                                </div>

                                            </header>

                                        </div>

                                        <?php echo $td_mod_single->get_social_sharing_top();?>


                                        <div class="td-post-content">
                                        <div class="td-bets-content">
                                            <div class="bets-img">
                                                <?php
                                                // override the default featured image by the templates (single.php and home.php/index.php - blog loop)
                                                if (!empty(td_global::$load_featured_img_from_template)) {
                                                    echo $td_mod_single->get_image(td_global::$load_featured_img_from_template);
                                                } else {
                                                    echo $td_mod_single->get_image('td_696x0');
                                                }
                                                ?>
                                            </div>
                                            <div class="bets-info">
                                                <div class="currency">
                                                    Валюта: <?php echo get_post_meta( get_the_ID(), 'сurrency', true ) ; ?>
                                                </div>
                                                <div class="minrate">
                                                    Минимальная ставка: <?php echo get_post_meta( get_the_ID(), 'minrate', true ) ; ?>
                                                </div> 
                                                <div class="mindep">
                                                    Минимальный депозит: <?php echo get_post_meta( get_the_ID(), 'mindep', true ) ; ?>
                                                </div>
                                              
                                                <div class="desc">
                                                   Дополнительное описание: <?php echo get_post_meta( get_the_ID(), 'desc', true ) ; ?>
                                                </div>
                                                <div class="rating">
                                                   Рейтинг букмекера:<br />
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
                                        
                                                    echo '<span class="'.$class.'">'. get_post_meta( get_the_ID(), 'recomend', true ).'</span>'; ?>
                                                    <div class="bets-desc-lnk">
                                                        <a href="<?php echo get_post_meta( get_the_ID(), 'bet', true ) ; ?>">Сделать ставку</a>
                                                       
                                                    </div>
                                                </div>
                                                <div class="posneg">
                                                    Обзор конторы<br />
                                                    Положительно: <br />
                                                    <div class="positive">
                                                        <?php echo get_post_meta( get_the_ID(), 'positive', true ) ; ?>
                                                    </div>
                                                    Отрицательно: <br />
                                                     <div class="negative">
                                                        <?php echo get_post_meta( get_the_ID(), 'negative', true ) ; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        </div>
                                       

                                        <?php echo $td_mod_single->get_content();?>
                                        </div>


                                        <footer>
                                            <?php echo $td_mod_single->get_post_pagination();?>
                                            <?php echo $td_mod_single->get_review();?>

                                            <div class="td-post-source-tags">
                                                <?php echo $td_mod_single->get_source_and_via();?>
                                                <?php echo $td_mod_single->get_the_tags();?>
                                            </div>

                                            <?php echo $td_mod_single->get_social_sharing_bottom();?>
                                            <?php echo $td_mod_single->get_next_prev_posts();?>
                                            <?php echo $td_mod_single->get_author_box();?>
                                            <?php echo $td_mod_single->get_item_scope_meta();?>
                                        </footer>

                                    </article> <!-- /.post -->

                                    <?php echo $td_mod_single->related_posts();?>

                                <?php
                                } else {
                                    //no posts
                                    echo td_page_generator::no_posts();
                                }
                                ?>	
                                <?php
                                
                                comments_template('', true);
                                ?>
                            </div>
                        </div>
		                <div class="td-pb-span4 td-main-sidebar" role="complementary">
			                <div class="td-ss-main-sidebar">
				                <?php get_sidebar(); ?>
			                </div>
		                </div>
                    <?php
                    break;

                case 'no_sidebar':
                    td_global::$load_featured_img_from_template = 'td_1068x0';
                    ?>
                        <div class="td-pb-span12 td-main-content" role="main">
                            <div class="td-ss-main-content">
								<?
                                    if (have_posts()) {
                                    the_post();

                                    $td_mod_single = new td_module_single($post);
                                    ?>

                                    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>
                                        <div class="td-post-header">

                                            <?php echo $td_mod_single->get_category(); ?>

                                            <header class="td-post-title">
                                                <?php echo $td_mod_single->get_title();?>


                                                <?php if (!empty($td_mod_single->td_post_theme_settings['td_subtitle'])) { ?>
                                                    <p class="td-post-sub-title"><?php echo $td_mod_single->td_post_theme_settings['td_subtitle'];?></p>
                                                <?php } ?>


                                                <div class="td-module-meta-info">
                                                    <?php echo $td_mod_single->get_author();?>
                                                    <?php echo $td_mod_single->get_date(false);?>
                                                    <?php echo $td_mod_single->get_comments();?>
                                                    <?php echo $td_mod_single->get_views();?>
                                                </div>

                                            </header>

                                        </div>

                                        <?php echo $td_mod_single->get_social_sharing_top();?>


                                        <div class="td-post-content">
                                        <div class="td-bets-content">
                                            <div class="bets-img">
                                                <?php
                                                // override the default featured image by the templates (single.php and home.php/index.php - blog loop)
                                                if (!empty(td_global::$load_featured_img_from_template)) {
                                                    echo $td_mod_single->get_image(td_global::$load_featured_img_from_template);
                                                } else {
                                                    echo $td_mod_single->get_image('td_696x0');
                                                }
                                                ?>
                                            </div>
                                            <div class="bets-info">
                                                <div class="currency">
                                                    Валюта: <?php echo get_post_meta( get_the_ID(), 'сurrency', true ) ; ?>
                                                </div>
                                                <div class="minrate">
                                                    Минимальная ставка: <?php echo get_post_meta( get_the_ID(), 'minrate', true ) ; ?>
                                                </div> 
                                                <div class="mindep">
                                                    Минимальный депозит: <?php echo get_post_meta( get_the_ID(), 'mindep', true ) ; ?>
                                                </div>
                                              
                                                <div class="desc">
                                                   Дополнительное описание: <?php echo get_post_meta( get_the_ID(), 'desc', true ) ; ?>
                                                </div>
                                                <div class="rating">
                                                   Рейтинг букмекера:<br />
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
                                        
                                                    echo '<span class="'.$class.'">'. get_post_meta( get_the_ID(), 'recomend', true ).'</span>'; ?>
                                                    <div class="bets-desc-lnk">
                                                        <a href="<?php echo get_post_meta( get_the_ID(), 'bet', true ) ; ?>">Сделать ставку</a>
                                                       
                                                    </div>
                                                </div>
                                                <div class="posneg">
                                                    Обзор конторы<br />
                                                    Положительно: <br />
                                                    <div class="positive">
                                                        <?php echo get_post_meta( get_the_ID(), 'positive', true ) ; ?>
                                                    </div>
                                                    Отрицательно: <br />
                                                     <div class="negative">
                                                        <?php echo get_post_meta( get_the_ID(), 'negative', true ) ; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        </div>
                                       

                                        <?php echo $td_mod_single->get_content();?>
                                        </div>


                                        <footer>
                                            <?php echo $td_mod_single->get_post_pagination();?>
                                            <?php echo $td_mod_single->get_review();?>

                                            <div class="td-post-source-tags">
                                                <?php echo $td_mod_single->get_source_and_via();?>
                                                <?php echo $td_mod_single->get_the_tags();?>
                                            </div>

                                            <?php echo $td_mod_single->get_social_sharing_bottom();?>
                                            <?php echo $td_mod_single->get_next_prev_posts();?>
                                            <?php echo $td_mod_single->get_author_box();?>
                                            <?php echo $td_mod_single->get_item_scope_meta();?>
                                        </footer>

                                    </article> <!-- /.post -->

                                    <?php echo $td_mod_single->related_posts();?>

                                <?php
                                } else {
                                    //no posts
                                    echo td_page_generator::no_posts();
                                }
                                ?>	
                                <?php
                                
                                comments_template('', true);
                                ?>
                            </div>
                        </div>
                    <?php
                    break;

            }
            ?>
        </div> <!-- /.td-pb-row -->
    </div> <!-- /.td-container -->
</div> <!-- /.td-main-content-wrap -->

<?php

get_footer();