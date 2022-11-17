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
//	return id	105 : seller is expire
//	return id	200 : OK
*/
$user_decrypt = $_POST['a'];
$key = $_POST['b']; // 32 bytes
$iv  = $_POST['c']; // 16 bytes
$method = 'aes-256-cfb';

$now_time = new DateTime('now');
if(empty(@$_POST['e'])){
	$api_return_data = base64_encode( openssl_encrypt ("100", $method, $key, true, $iv));
	goto End;
}
if(empty(@$user_decrypt)){
	$api_return_data = base64_encode( openssl_encrypt ("203", $method, $key, true, $iv));
	goto End;
}else{
	$IP = $_SERVER['REMOTE_ADDR'];
	if($_SERVER["HTTP_CF_CONNECTING_IP"]){
		$IP = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
//	$user_decrypt = $user_decrypt.''.$IP;
}
$sql = 'update `licenses` SET `hwid`=\'' . $user_decrypt . '\' WHERE `serial` = \''.strtoupper($_POST['e']).'\'';
if($mysqli->query($sql) === TRUE){
	$api_return_data = base64_encode( openssl_encrypt ("200", $method, $key, true, $iv));
} else {
	$api_return_data = base64_encode( openssl_encrypt ("201", $method, $key, true, $iv));
}
End:
