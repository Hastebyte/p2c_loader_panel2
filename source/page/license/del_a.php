<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if(is_numeric($_URL_REQUEST['id'])){
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `id` = \''.$_URL_REQUEST['id'].'\'';
	$res_l = $mysqli->query($sql)->fetch_assoc();
	if('1'==$_SESSION['login']){
		// myself
		$sql = 'DELETE FROM `licenses` WHERE `id` = \''.$_URL_REQUEST['id'].'\'';
		$res_l = $mysqli->query($sql);
		exit('<script>document.location.href="'.http_url.'manage/license";</script>');
	}
}
exit('<script>alert('.$_LNG['license']['forbidden'].')</script>');