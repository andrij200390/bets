<?php
//подключили фу-и wp
require_once(dirname(__FILE__).'/../../../wp-load.php');
if (isset($_POST["name"]) && isset($_POST["phone"]) && isset ($_POST["email"]) ) { 

	// Формируем массив для JSON ответа
    $result = array(
    	'name' => $_POST["name"],
    	'phone' => $_POST["phone"],
    	'email' => $_POST["email"]
    ); 
	 mail("vkworms@yandex.ru", "контакты".$_SERVER['HTTP_REFERER'], $result['name']." ". $result['phone']." ".$result['email']); // письмо на свой электронный ящик
    // Переводим массив в JSON
    echo json_encode($result); 
}

// подключаем (если надо будет обращение к базе)
global $wpdb;
// копируем в переменнуюю (не обязательно)
$name = $result['name'];
$email = $result['email'];
$phone = $result['phone'];
//strval($name);
//strval($email);
//strval($phone);
// запишем в заголовок например дату и время
$today = date("F j, Y, g:i a");
//масив переменных и параметров
$my_post = array(
	 'post_type' => 'myform',
     'post_title' => $today,
     'post_status' => 'publish',
	 'post_author' => 1//,
    // 'post_category' => array(7)
	);
//втсавляем в бд пост 
$post_id = wp_insert_post( $my_post, $wp_error = true );
//добавляем значение полей с формы
add_post_meta($post_id, 'name', $name);
add_post_meta($post_id, 'email', $email);
add_post_meta($post_id, 'phone', $phone);
?>
