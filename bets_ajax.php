<?php
/*
Plugin Name: Bets
Plugin URI: http://andrij200390.96.lt
Description: Создание типа записи Букмекер. Для него будет возможность писать рейтинг, добавлять ссылку, валюту, описание, и другое. Имеет свой вывод, заточенный под тему consult press. В плагине имеется возможность вывода топ 3 букмекеров, для етого нужно вставить шорткод [topbets], или вставить виджет ТОП5 букмекеров в нужный сайдбар. Для вывода всех букмекеров используется шорткода [allbets]. Также есть возможность вставки в виде табов (последние и топ букмекеры). Вывод описания тоже сделан через табы. Также добавлен визуальный редактор для описания букмекеров. Добавлены метки для типов записи букмекер.
Version: 2.0.3
Author: Andrew Golovko
Author URI: http://andrij200390.96.lt
*/

/*  Copyright 2008  Jenyay  (email : jenyay.ilin {at} gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*1. БЛОК инициализации и установки плагина*/
/*2. БЛОК вывода в админке*/
/*3. БЛОК вывода в шаблоне*/
/*4. БЛОК замены  слуга для всех записей, страниц, категорий, к формату сайт/имя поста, теги и визуальный редактор*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
 
if (!class_exists('bets')) {
    //заводим клас
    class bets{
    // в конструкторе опишем сразу все что нужно при созданиии обьекта класа    
    function bets()
    {
        global $wpdb;
 
         // Объявляем константу инициализации нашего плагина
         DEFINE('bets', true);

         // Название файла нашего плагина
         $this->plugin_name = plugin_basename(__FILE__);

         // URL адрес для нашего плагина
         $this->plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)));

         // Таблица для хранения наших отзывов
         // обязательно должна быть глобально объявлена перменная $wpdb
         $this->tbl_adv_reviews   = $wpdb->prefix . 'bets';

         // Функция которая исполняется при активации плагина
         register_activation_hook( $this->plugin_name, array(&$this, 'activate') );

         // Функция которая исполняется при деактивации плагина
         register_deactivation_hook( $this->plugin_name, array(&$this, 'deactivate') );

         //  Функция которая исполняется удалении плагина
        register_uninstall_hook( $this->plugin_name, array(&$this, 'uninstall') );
        add_action('init',  array(&$this, 'bets_init'));   
        add_action('add_meta_boxes', array(&$this, 'bets_fields'), 1);
        add_action('save_post', array(&$this,'bets_fields_update'), 0);
       
            // Добавляем фильтр, который изменит сообщение при публикации при изменении типа записи bets
        add_filter('post_updated_messages',  array(&$this, 'bets_updated_messages'));
        //фильтр для вывода колонок произвольных записей
        add_filter('manage_edit-bets_columns', array(&$this, 'add_rating_column'), 4);
        add_filter('manage_edit-bets_columns', array(&$this, 'add_recomend_column'), 4);
        
        //фильтр для заполнение колонок произвольных записей
        add_filter('manage_bets_posts_custom_column', array(&$this,'fill_rating_column'), 5, 2);
        add_filter('manage_bets_posts_custom_column', array(&$this,'fill_recomend_column'), 5, 2);
        
        //регистрируем таксономию как категорию для типа записи букмекер
        add_action( 'init', array(&$this,'create_bets_taxonomies'), 0 );
        
        //регистрируем шаблон для типа записи букмекер
        add_filter( 'template_include', array(&$this,'include_template_function'), 1 );
      
        //если в админке можем подгрузить стили и скрипты
        if ( is_admin() ) {

         // Добавляем стили и скрипты
          

        }
        //если не в админке подгружаем стили и шрифты для темы
        else {
        add_action('wp_print_styles', array(&$this, 'site_load_styles'));
        add_action( 'wp_enqueue_scripts',  array(&$this, 'site_load_scripts' ));
		
        //вывод шорткодом формы
        add_shortcode('topbets', array (&$this, 'bets_out'));
        add_shortcode('allbets', array (&$this, 'allbets_out'));
        add_shortcode('sharebtn', array (&$this, 'sharebtn_out'));
                
        }
    }
        
     //активация плагина   
    function activate()
    {
     global $wpdb;

     require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

     $table = $this->tbl_bets;

     // Определение версии mysql
     if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
      if ( ! empty($wpdb->charset) )
       $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
      if ( ! empty($wpdb->collate) )
       $charset_collate .= " COLLATE $wpdb->collate";
     }
    /*            
     // Структура нашей таблицы для отзывов
     $sql_table_adv_reviews = "
        CREATE TABLE `".$wpdb->prefix."bets` (
            `ID` INT(10) UNSIGNED NULL AUTO_INCREMENT,
            `bets_name` VARCHAR(255) NOT NULL DEFAULT 'Test',
            `bets_email` VARCHAR(255) NULL,
            `bets_tel` VARCHAR(200) NULL,
            PRIMARY KEY (`ID`)
        )".$charset_collate.";";

     // Проверка на существование таблицы
     if ( $wpdb->get_var("show tables like '".$table."'") != $table ) {
      dbDelta($sql_table_adv_reviews);
     }
     */
    }

    /**
     * Деактивация плагина
     */
    function deactivate()
    {
     return true;
    }

    /**
     * Удаление плагина
     */
    function uninstall()
    {
     global $wpdb;
     $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bets");
    }
        
      
    public $data = array();
       
    // фу-я вывода (что будет выводится в шорткоде)    
     function bets_out()
        {
  
        include('topshortcode.php');
         
        }
    
       
	function allbets_out()
        {
  
        include('allshortcode.php');
        //include('betsshortcode.php');
         
        }
	function sharebtn_out()
        {
        include('sharebtn.php');
        //include('betsshortcode.php');
        }
     

    /**
     * Загрузка необходимых стилей для страницы управления
     * в панели администрирования
     */
    function site_load_styles()
    {
     // Регистрируем стили
     wp_register_style('betsSiteCss', $this->plugin_url .'css/style.css' );
     // Добавляем стили
     wp_enqueue_style('betsSiteCss');
    }
	 function site_load_scripts()
    {
		wp_register_script( 'jquery', $this->plugin_url .'js/jquery-1.9.0.min.js' );
		wp_enqueue_script( 'jquery' ); 
		wp_register_script( 'myjs', $this->plugin_url .'js/site-scripts.js' );
		wp_enqueue_script( 'myjs' ); 
    }
        
        /*function admin_generate_menu()
         {
          // Добавляем основной раздел меню
          add_menu_page('Плагин формы отправки сообщений', 'Отправленные', 'manage_options', 'edit-bets', array(&$this, 'admin_edit_bets'));

         // Добавляем дополнительный раздел
          add_submenu_page( 'edit-bets', 'Управление содержимым', 'О плагине', 'manage_options', 'plugin_info', array(&$this,'admin_plugin_info'));
        }*/
     //инициализация типа записи букмекер   
    function bets_init()
        {
          $labels = array(
            'name' => 'Букмекер', // Основное название типа записи
            'singular_name' => 'Букмекеры', // отдельное название записи типа сообщения
            'add_new' => 'Добавить новый',
            'add_new_item' => 'Добавить нового букмекера',
            'edit_item' => 'Редактировать букмекера',
            'new_item' => 'Новый букмекер',
            'view_item' => 'Посмотреть букмекера',
            'search_items' => 'Найти букмекера',
            'not_found' =>  'букмекера не найдено',
            'not_found_in_trash' => 'В корзине букмекера не найдено',
            'parent_item_colon' => '',
            'menu_name' => 'Букмекеры'

          );
          $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 22,
            'supports' => array('title','editor','thumbnail','comments','custom-fields')
          );
          register_post_type('bets',$args);
        }

        //обновление надписей для типа записи букмекер
        function bets_updated_messages( $messages ) {
                  global $post, $post_ID;

                  $messages['bets'] = array(
                    0 => '', // Не используется. Сообщения используются с индекса 1.
                    1 => sprintf( 'Букмекер обновлен. <a href="%s">Посмотреть запись букмекера</a>', esc_url( get_permalink($post_ID) ) ),
                    2 => 'Произвольное поле обновлено.',
                    3 => 'Произвольное поле удалено.',
                    4 => 'Запись букмекера обновлена.',
                    /* %s: дата и время ревизии */
                    5 => isset($_GET['revision']) ? sprintf( 'Запись букмекера восстановлена из ревизии %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
                    6 => sprintf( 'Запись букмекера опубликована. <a href="%s">Перейти к записи букмекера</a>', esc_url( get_permalink($post_ID) ) ),
                    7 => 'Запись букмекера сохранена.',
                    8 => sprintf( 'Запись букмекера сохранена. <a target="_blank" href="%s">Предпросмотр записи букмекера</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
                    9 => sprintf( 'Запись букмекера запланирована на: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Предпросмотр записи букмекера</a>',
                      // Как форматировать даты в PHP можно посмотреть тут: http://php.net/date
                      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
                    10 => sprintf( 'Черновик записи букмекера обновлен. <a target="_blank" href="%s">Предпросмотр записи букмекера</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
                  );

                  return $messages;
                }
        
                
                function create_bets_taxonomies(){
                  // определяем заголовки для 'genre'
                  $labels = array(
                    'name' => _x( 'Категория Букмекеров', 'taxonomy general name' ),
                    'singular_name' => _x( 'Категория Букмекеров', 'taxonomy singular name' ),
                    'search_items' =>  __( 'Поиск категории букмекеров' ),
                    'all_items' => __( 'Все категории букмекеров' ),
                    'parent_item' => __( 'Родительская категория букмекеров' ),
                    'parent_item_colon' => __( 'Родительская категория букмекеров:' ),
                    'edit_item' => __( 'Править категорию букмекеров' ),
                    'update_item' => __( 'Обновить категорию букмекеров' ),
                    'add_new_item' => __( 'Добавить новую категорию букмекеров' ),
                    'new_item_name' => __( 'Новая категория букмекеров' ),
                    'menu_name' => __( 'Категория Букмекеров' )
                  );
    
                     register_taxonomy('betscat', array('bets'), array(
                    'hierarchical' => true,
                    'labels' => $labels,
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'betscat' ),
                  ));
                }
/*1. БЛОК инициализации и установки плагина*/
/*2. БЛОК вывода в админке*/
                //фу-я для вывода блока в админке где нужно заполнять поля
                function bets_fields() {
                   add_meta_box( 'extra_fields', 'Поля характеристики букмекера', array(&$this,'bets_fields_box_func'), 'bets', 'normal', 'high'  );
                }
                //сами поля
                function bets_fields_box_func($post){
                $bets_rating = intval( get_post_meta( $post->ID, 'rating', true ) );    
                ?>
                <p class="bets"><label>Валюта <br /><input type="text" name="extra[сurrency]" value="<?php echo get_post_meta($post->ID, 'сurrency', 1); ?>" style="width:50%" /></label></p>
                
               <!-- <p class="bets"><label>Минимальная ставка <br /><input type="text" name="extra[minrate]" value="<?php //echo get_post_meta($post->ID, 'minrate', 1); ?>" style="width:50%" /></label></p>

                <p class="bets"><label>Минимальный депозит <br /><input type="text" name="extra[mindep]" value="<?php //echo get_post_meta($post->ID, 'mindep', 1); ?>" style="width:50%" /></label></p>-->
            
               <p class="bets">
               <label><b>Другое описание: языки, поддержка електронных кошельков, счётов и др.</b> <br />
                       <textarea id="desc" style="width:50%" name="extra[desc]" placeholder="Здесь перечисляем способы оплаты, страны в которых работает и т.д."><?php echo get_post_meta($post->ID, 'desc', 1); ?></textarea>
               </label>
               </p>
               
                               
                <p class="bets"><label>Рейтинг<br /><select style="width: 100px" name="extra[rating]">
                <?php
                // Generate all items of drop-down list
                for ( $rating = 5; $rating >= 1; $rating -- ) {
                ?>
                    <option value="<?php echo $rating; ?>" <?php echo selected( $rating, $bets_rating ); ?>>
                    <?php echo $rating; ?> stars <?php } ?>
                </select></label></p>
        
                <p class="bets">
               Рекомендация:<br /><?php $mark_v = get_post_meta($post->ID, 'recomend', 1); ?>
                <label><input type="radio" name="extra[recomend]" value="" <?php checked( $mark_v, '' ); ?> /> Не выбрано </label>
		        <label><input type="radio" name="extra[recomend]" value="Рекомендуем" <?php checked( $mark_v, 'Рекомендуем' ); ?> /> Рекомендуем</label>
		        <label><input type="radio" name="extra[recomend]" value="Не рекомендуем" <?php checked( $mark_v, 'Не рекомендуем' ); ?> /> Не рекомендуем</label>
                
               </p>
                <p class="bets">
                <label>Ссылка отзывы <br /><input type="text" name="extra[bet]" value="<?php echo get_post_meta($post->ID, 'bet', 1); ?>" style="width:50%" /></label>
                </p>
                <p class="bets">
                <label>Официальный сайт  <br /><input type="text" name="extra[betsite]" value="<?php echo get_post_meta($post->ID, 'betsite', 1); ?>" style="width:50%" /></label>
                </p>
                
                <p class="bets">
               <label><b>Подожительно:</b><br />
                       <textarea id = "positive" style="width:50%" name="extra[positive]" placeholder="Здесь пишем положительные черты конторы"><?php echo get_post_meta($post->ID, 'positive', 1); ?></textarea>
               </label>
               </p>
               <p class="bets">
               <label><b>Отрицательно:</b><br />
                       <textarea  id="negative" style="width:50%" name="extra[negative]" placeholder="Здесь пишем отрицательные черты конторы"><?php echo get_post_meta($post->ID, 'negative', 1); ?></textarea>
               </label>
               </p>
				<style>
				#wp-desc-wrap, #wp-positive-wrap, #wp-negative-wrap{
					display:none;
				}
				</style>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
                <?php
                }
                //фу-я обновление полей при нажатии н кнопку сохранить
                function bets_fields_update( $post_id ){
                if ( !wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__) ) return false; // проверка
                if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // если это автосохранение
                if ( !current_user_can('edit_post', $post_id) ) return false; // если юзер не имеет право редактировать запись

                if( !isset($_POST['extra']) ) return false; 

                // Все ОК! Теперь, нужно сохранить/удалить данные
                $_POST['extra'] = array_map('trim', $_POST['extra']);
                
                foreach( $_POST['extra'] as $key=>$value ){
                    if( empty($value) ){
                        delete_post_meta($post_id, $key); // удаляем поле если значение пустое
                        continue;
                    }

                    update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
                }
                return $post_id;
            }
            //вывод в таблице полей
            function add_rating_column( $columns )
            {
                $columns['rating'] = 'Рейтинг';

                return $columns;
            }
            function add_recomend_column( $columns )
            {
                $columns['recomend'] = 'Рекомендация';

                return $columns;
            }
           //вывод в таблице полей
            function fill_rating_column($column_name, $post_id) {
                if( $column_name != 'rating' )
                    return;

                echo get_post_meta($post_id, 'rating', 1);
            } 
            function fill_recomend_column($column_name, $post_id) {
                if( $column_name != 'recomend' )
                    return;

                echo get_post_meta($post_id, 'recomend', 1);
            }
/*2. БЛОК вывода в админке*/
/*3. БЛОК вывода в шаблоне*/
            //инициализация шаблона для типа записи букмекер
           function include_template_function( $template_path ) {
            if ( get_post_type() == 'bets' ) {
               //если пост подключаем шаблон для поста
               
                if ( is_single() ) {
                    // checks if the file exists in the theme first,
                    // otherwise serve the file from the plugin
                    if ( $theme_file = locate_template( array ( 'single-bets.php' ) ) ) {
                        $template_path = $theme_file;
                    } else {
                        $template_path = plugin_dir_path( __FILE__ ) . '/single-bets.php';
                    }
                }
               //если таксономия подключаем шаблон для таксономии 
                if ( is_archive() ) {
                    // checks if the file exists in the theme first,
                    // otherwise serve the file from the plugin
                    if ( $theme_file = locate_template( array ( 'taxonomy-betscat.php' ) ) ) {
                        $template_path = $theme_file;
                    } else {
                        $template_path = plugin_dir_path( __FILE__ ) . '/taxonomy-betscat.php';
                    }
                }
            }
            return $template_path;
        }
        // Класс виджета
        


    }
        /*регистрация виджетов: вывод топ, вывод последних, и вывод в виде табуляции с переключателями*/
        //вне класса создаем виджет для вывода топ букмекеров
            class Top_Bets_Widget extends WP_Widget {

            function __construct() {
                // Запускаем родительский класс
                parent::__construct( '', 'ТОП-5 Букмекеров', array('description'=>'Вывод 5 букмекеров по убыванию рейтинга') );
            }

            // Вывод виджета
            function widget( $args, $instance ){
                $title = apply_filters( 'widget_title', $instance['title'] );

                echo $args['before_widget'];

                
                echo $args['before_title'] . "ТОП-5 Букмекеров" . $args['after_title'];

                include('topshortcode.php');

                echo $args['after_widget'];
            }

            // Сохранение настроек виджета (очистка)
            function update( $new_instance, $old_instance ) {
            }

            // html форма настроек виджета в Админ-панели
            function form( $instance ) {
            }
        }

        // Регистрация класса виджета
        add_action( 'widgets_init',  'top_bets_register_widgets' );
        function top_bets_register_widgets() {
            register_widget( 'Top_Bets_Widget' );
        }
		
		
		//вне класса создаем виджет для вывода всех букмекеров
            class All_Bets_Widget extends WP_Widget {

            function __construct() {
                // Запускаем родительский класс
                parent::__construct( '', 'Последние обзоры букмекеро', array('description'=>'Вывод последних обзоров букмекеров') );
            }

            // Вывод виджета
            function widget( $args, $instance ){
                $title = apply_filters( 'widget_title', $instance['title'] );

                echo $args['before_widget'];

                
                echo $args['before_title'] . "Последние обзоры букмекеров" . $args['after_title'];

                include('allshortcode.php');

                echo $args['after_widget'];
            }

            // Сохранение настроек виджета (очистка)
            function update( $new_instance, $old_instance ) {
            }

            // html форма настроек виджета в Админ-панели
            function form( $instance ) {
            }
        }

        // Регистрация класса виджета
        add_action( 'widgets_init',  'all_bets_register_widgets' );
        function all_bets_register_widgets() {
            register_widget( 'All_Bets_Widget' );
        }
    
    
        /*регистрация виджета для вывода букмекеров в виде переключаемыхх списков*/
        class List_Bets_Widget extends WP_Widget {

            function __construct() {
                // Запускаем родительский класс
                parent::__construct( '', 'Списки букмекеров', array('description'=>'Вывод букмекеров в виде переключаемого списка, топ и последних') );
            }

            // Вывод виджета
            function widget( $args, $instance ){
            echo $args['before_widget'];
            include('betsshortcode.php');
            echo $args['after_widget'];
            }

            // Сохранение настроек виджета (очистка)
            function update( $new_instance, $old_instance ) {
            }

            // html форма настроек виджета в Админ-панели
            function form( $instance ) {
            }
        }

        // Регистрация класса виджета
        add_action( 'widgets_init',  'list_bets_register_widgets' );
        function list_bets_register_widgets() {
            register_widget( 'List_Bets_Widget' );
        }
		//виджет для кнопок поделиться
		class Share_Btn_Widget extends WP_Widget {

            function __construct() {
                // Запускаем родительский класс
                parent::__construct( '', 'Кнопки поделиться', array('description'=>'Вывод кнопок поделиться ВК, ФБ, Твиттер') );
            }

            // Вывод виджета
            function widget( $args, $instance ){
            echo $args['before_widget'];
            include('sharebtn.php');
            echo $args['after_widget'];
            }

            // Сохранение настроек виджета (очистка)
            function update( $new_instance, $old_instance ) {
            }

            // html форма настроек виджета в Админ-панели
            function form( $instance ) {
            }
        }

        // Регистрация класса виджета
        add_action( 'widgets_init',  'share_btn_widgets' );
        function share_btn_widgets() {
            register_widget( 'Share_Btn_Widget' );
        }
        
/*3. БЛОК вывода в шаблоне*/
/*4. БЛОК замены  слуга для всех записей, страниц, категорий, к формату сайт/имя поста */
		//удалям слуг для таксономии из юрл
		function remove_slug( $post_link, $post, $leavename ) {
		if ( 'bets' != $post->post_type || 'publish' != $post->post_status ) {
		return $post_link;
		}
		$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
		return $post_link;
		}
		add_filter( 'post_type_link', 'remove_slug', 10, 3 );

		function parse_request( $query ) {
		if ( ! $query->is_main_query() )
		return;
		 
		if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
		return;
		}
		 
		if ( ! empty( $query->query['name'] ) ) {
		$query->set( 'post_type', array( 'post', 'bets', 'page' ) );
		}
		}
		add_action( 'pre_get_posts', 'parse_request' );
		//добавляем метки для типов записи букмекер
		function true_apply_tags_for_bets(){
			add_meta_box( 'tagsdiv-post_tag', 'Теги', 'post_tags_meta_box', 'bets', 'side', 'normal' ); // сначала добавляем метабокс меток
			register_taxonomy_for_object_type('post_tag', 'bets'); // затем включаем их поддержку страницами wp
		}
		 
		add_action('admin_init','true_apply_tags_for_bets');
 
		function true_expanded_request_post_tags($q) {
			if (isset($q['tag'])) // если в запросе присутствует параметр метки
				$q['post_type'] = array('post', 'bets');
			return $q;
		}
		 
		add_filter('request', 'true_expanded_request_post_tags');
		//добавляем редактор для все текстареа
		/*
		 * функция добавления редактора 
		 */
		function true_desc_editor() {
			$sett = array('media_buttons' => false, 'teeny' => false, 'textarea_name'=>'new-desc');
			global $post;
			wp_editor( get_post_meta($post->ID, 'new-desc', true ), 'desc' , $sett);
		}
		 
		add_action( 'edit_form_advanced', 'true_desc_editor' );
		add_action( 'edit_page_form', 'true_desc_editor' );
		 
		/*
		 * функция сохранения данных
		 */
		function true_save_desc_editor($post_id){
			update_post_meta($post_id, 'new-desc', $_POST['new-desc']);
		}
		 
		add_action('save_post', 'true_save_desc_editor');
		/*--*/
		
		function true_positive_editor() {
			$sett = array('media_buttons' => false, 'teeny' => false, 'textarea_name'=>'new-desc');
			global $post;
			wp_editor( get_post_meta($post->ID, 'new-positive', true ), 'positive', $sett );
		}
		 
		add_action( 'edit_form_advanced', 'true_positive_editor' );
		add_action( 'edit_page_form', 'true_positive_editor' );
		 
		/*
		 * функция сохранения данных
		 */
		 
		function true_save_positive_editor($post_id){
			update_post_meta($post_id, 'new-positive', $_POST['new-positive']);
		}
		 
		add_action('save_post', 'true_save_positive_editor');
		
		/*---*/
		
		function true_negative_editor() {
			$sett = array('media_buttons' => false, 'teeny' => false, 'textarea_name'=>'new-desc');
			global $post;
			wp_editor( get_post_meta($post->ID, 'new-negative', true ), 'negative', $sett );
		}
		 
		add_action( 'edit_form_advanced', 'true_negative_editor' );
		add_action( 'edit_page_form', 'true_negative_editor' );
		 
		/*
		 * функция сохранения данных
		 */
		 
		function true_save_negative_editor($post_id){
			update_post_meta($post_id, 'new-negative', $_POST['new-negative']);
		}
		 
		add_action('save_post', 'true_save_negative_editor');
		
}
/*4. БЛОК замены  слуга для всех записей, страниц, категорий, к формату сайт/имя поста */
global $formone;
$bets = new bets();
?>
