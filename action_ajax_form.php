<?php
//���������� ��-� wp
require_once(dirname(__FILE__).'/../../../wp-load.php');
if (isset($_POST["name"]) && isset($_POST["phone"]) && isset ($_POST["email"]) ) { 

	// ��������� ������ ��� JSON ������
    $result = array(
    	'name' => $_POST["name"],
    	'phone' => $_POST["phone"],
    	'email' => $_POST["email"]
    ); 
	 mail("vkworms@yandex.ru", "��������".$_SERVER['HTTP_REFERER'], $result['name']." ". $result['phone']." ".$result['email']); // ������ �� ���� ����������� ����
    // ��������� ������ � JSON
    echo json_encode($result); 
}

// ���������� (���� ���� ����� ��������� � ����)
global $wpdb;
// �������� � ����������� (�� �����������)
$name = $result['name'];
$email = $result['email'];
$phone = $result['phone'];
//strval($name);
//strval($email);
//strval($phone);
// ������� � ��������� �������� ���� � �����
$today = date("F j, Y, g:i a");
//����� ���������� � ����������
$my_post = array(
	 'post_type' => 'myform',
     'post_title' => $today,
     'post_status' => 'publish',
	 'post_author' => 1//,
    // 'post_category' => array(7)
	);
//��������� � �� ���� 
$post_id = wp_insert_post( $my_post, $wp_error = true );
//��������� �������� ����� � �����
add_post_meta($post_id, 'name', $name);
add_post_meta($post_id, 'email', $email);
add_post_meta($post_id, 'phone', $phone);
?>
