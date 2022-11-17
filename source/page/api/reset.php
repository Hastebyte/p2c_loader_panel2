<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
/*
//	data informatin
//	serial
//	application
//	user_auth_data (Field's name is user)

//	return id	001 : application id worng
//	return id	002 : application pause
//	return id	003 : user data empty
//	return id	100 : serial key not found
//	return id	101 : serial key used by other user
//	return id	102 : serial key's application is worng
//	return id	103 : serial key is expire
//	return id	104 : user is already unset
//	return id	200 : OK
*/

$key = $_POST['a']; // 32 bytes
$iv  = $_POST['b']; // 16 bytes
$method = 'aes-256-cfb';


if(empty(@$_POST['serial'])){
	$api_return_data = base64_encode( openssl_encrypt ("100", $method, $key, true, $iv));
}
else{
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `serial` = \''.strtoupper($_POST['serial']).'\'';
	$res_l = $mysqli->query($sql);
	if($res_l->num_rows){
		$res_l = $res_l->fetch_assoc();
		if(!is_null($res_l['user'])){
			$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE `id` = \''.$res_l['application'].'\'';
			$res_a = $mysqli->query($sql)->fetch_assoc();
			$diff = '0';
			$un_time = new DateTime($res_l['expire_at']);
			$time = new DateTime();
			if($time>$un_time){
				$api_return_data = base64_encode( openssl_encrypt ("103", $method, $key, true, $iv));
			}else{
				switch($res_l['days']){
					case 'd':
					    $un_time->modify('-5 hours');
						break;
					case 'w':
					     $un_time->modify('-1 days');
						break;
					case 'm':
					     $un_time->modify('-1 days');
						break;
					case 's':
					case 'y':
					    $un_time->modify('-1 days');
						//$un_time->modify('-'.$res_a[$res_l['days']].'hour');
						//$diff = $res_a[$res_l['days']];
						break;
				}
				$sql ='UPDATE `licenses` SET `expire_at` = \''.$un_time->format('Y-m-d H:i:s').'\', `user` = NULL WHERE `serial` = \''.strtoupper($_POST['serial']).'\'';	
				$mysqli->query($sql);
				$api_return_data = base64_encode( openssl_encrypt ("200", $method, $key, true, $iv));
			 //   $api_return_data = "104";
			}
		}
		else{
			$api_return_data = base64_encode( openssl_encrypt ("104", $method, $key, true, $iv));
		}
	}
	else{
		$api_return_data = base64_encode( openssl_encrypt ("100", $method, $key, true, $iv));
	}
}

