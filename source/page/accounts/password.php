<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if($_POST){
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`user` WHERE `id` = \''.$_SESSION['login'].'\'';
	$res = $mysqli->query($sql)->fetch_assoc();
	if(!password_verify($_POST['pwd1'], $res['pwd'])){
		$_HTML['error_txt'] = 'Worng old password';
	}elseif($_POST['pwd3']!=$_POST['pwd2']){
		$_HTML['error_txt'] = 'Re-enter your new password';
	}else{
		$sql ='UPDATE `user` SET `pwd` = \''.password_hash($_POST['pwd2'],PASSWORD_DEFAULT).'\' WHERE `id` = \''.$_SESSION['login'].'\'';	
		$mysqli->query($sql);
		exit('<script>document.location.href="'.http_url.'";</script>');
	}
}
End:
$custum_page['add'][] = '/'.$action.'.htm';

