<?
//подключили фу-и wp
require_once(dirname(__FILE__).'/../../../wp-load.php');
//require(dirname(__FILE__) . '/wp-load.php');
if($_POST['name']){ // заносим в массив значение полей, их может быть больше
  $data = array(
    1 => $_POST['name'],
    2 => $_POST['email'],
    3 => $_POST['phone']
  );
  mail("vkworms@yandex.ru", "контакты".$_SERVER['HTTP_REFERER'], $data[3]." ". $data[2]." ".$data[1]); // письмо на свой электронный ящик
}
// перенаправление на страницу
Header("Refresh: 2; URL=".$_SERVER['HTTP_REFERER']); 

// подключаем (если надо будет обращение к базе)
global $wpdb;
// копируем в переменнуюю (не обязательно)
$name = $data[1];
$email = $data[2];
$phone = $data[3];
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

<!DOCTYPE html>
<html lang="ru-RU" class="no-js">
<head>
	<meta charset="UTF-8">
</head>

<body>
    <div style="background:#999; color:#fff; font-size: 20px; text-align:center">Страница отправка формы, подождите секунду! Вы автоматически будете перенаправлены</div>
</body>
</html>
