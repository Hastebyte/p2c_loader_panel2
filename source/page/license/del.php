<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if(is_numeric($_URL_REQUEST['id'])){
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `id` = \''.$_URL_REQUEST['id'].'\'';
	$res_l = $mysqli->query($sql)->fetch_assoc();
	if($res_l['create_by']==$_SESSION['login']){
		// myself
		$sql = 'DELETE FROM `licenses` WHERE `id` = \''.$_URL_REQUEST['id'].'\'';
		$res_l = $mysqli->query($sql);
		exit('<script>document.location.href="'.http_url.'license/list";</script>');
	}
}
echo '<script>alert('.$_LNG['license']['forbidden'].')</script>';
exit();