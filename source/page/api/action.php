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
$sql = "";

$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `serial` = \''.strtoupper($_POST['e']).'\'';
$res_l = $mysqli->query($sql);
if($res_l->num_rows){
	$res_l = $res_l->fetch_assoc();
	if($res_l['hwid'] != $user_decrypt){
		$api_return_data = base64_encode( openssl_encrypt ("115", $method, $key, true, $iv));
		goto End;
	}
}
$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `serial` = \''.strtoupper($_POST['e']).'\' and `hwid` = \'' . $user_decrypt . '\'';
$res_l = $mysqli->query($sql);
if($res_l->num_rows){
	$res_l = $res_l->fetch_assoc();
	if($res_l['application']!='-1'&&$res_l['application']!=$_POST['d']){
		$api_return_data = base64_encode( openssl_encrypt ("102", $method, $key, true, $iv));
		goto End;
	}
	
	if(!is_numeric(@$_POST['d'])){
		$api_return_data = base64_encode( openssl_encrypt ("201", $method, $key, true, $iv));
		goto End;
	}
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE `id` = \''.$_POST['d'].'\'';
	$res_a = $mysqli->query($sql);
	if(!$res_a->num_rows){
		$api_return_data = base64_encode( openssl_encrypt ("201", $method, $key, true, $iv));
		goto End;
	}

	$res_a = $res_a->fetch_assoc();
	if($res_a['pause_expire']){
		$check_time = new DateTime($res_a['pause_expire']);
		if($check_time>=$now_time){
			$api_return_data = base64_encode( openssl_encrypt ("202", $method, $key, true, $iv));
			goto End;
		}
	}
	
	if(!is_null($res_l['user'])){
		if($res_l['user'] != $user_decrypt){
			$api_return_data = base64_encode( openssl_encrypt ("104", $method, $key, true, $iv));
			goto End;
		}
	}
	
	$check_time = new DateTime('now');
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`user` WHERE `id` = \''.$res_l['create_by'].'\'';
	$res_check = $mysqli->query($sql)->fetch_assoc();
	$check_time_2 = new DateTime($res_check['expire']);
	if($check_time_2<$check_time||$res_check['stop']){
		$api_return_data = base64_encode( openssl_encrypt ("105", $method, $key, true, $iv));
		goto End;
		
	}
	
	if($res_l['expire_at']=='1000-01-01 00:00:00'){
		$check_time = new DateTime('now');
		$sql ='INSERT INTO `history` (`card`, `days`, `used`,`create_by`) VALUES (\''.$res_l['id'].'\',\''.$res_l['days'].'\',CURRENT_TIMESTAMP,\''.$res_l['create_by'].'\')';
		$mysqli->query($sql);
		// not used yet, new serial key
		switch($res_l['days']){
			case 'h':
				$check_time->modify('+1 hour');
				break;
			case 'd':
				$check_time->modify('+1 day');
				break;
			case 'w':
				$check_time->modify('+7 day');
				break;
			case 'm':
				$check_time->modify('+30 day');
				break;
			case 's':
				$check_time->modify('+90 day');
				break;
			case 'y':
				$check_time->modify('+1 year');
				break;	
		}
		if($res_l['days']=='n'){
			$expire_at = '2099-12-31 23:59:59';
		}
		else{
			$expire_at = $check_time->format('Y-m-d H:i:s');
		}
		$sql ='UPDATE `licenses` SET `expire_at` = \''.$expire_at.'\', `user` = \''.$user_decrypt.'\', `active` = \'1\' WHERE `serial` = \''.strtoupper($_POST['e']).'\'';	
		$mysqli->query($sql);
		$api_return_data = base64_encode( openssl_encrypt ("200", $method, $key, true, $iv));
		goto End;
		
	}else{
		$check_time = new DateTime($res_l['expire_at']);
		if($now_time>=$check_time){
			$api_return_data = base64_encode( openssl_encrypt ("103", $method, $key, true, $iv));
			goto End;
		}
		else{
			// used , auth ok
			$sql ='UPDATE `licenses` SET `user` = \''.$user_decrypt.'\' WHERE `serial` = \''.strtoupper($_POST['e']).'\'';	
			$mysqli->query($sql);
			$api_return_data = base64_encode( openssl_encrypt ("200", $method, $key, true, $iv));
			goto End;
		}
	}
	
}else{
    
    
	$api_return_data = base64_encode( openssl_encrypt ("100", $method, $key, true, $iv));
	goto End;
}
End: