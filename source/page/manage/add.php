<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if(is_numeric(@$_URL_REQUEST['id'])){
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`user` WHERE `id` = '.$_URL_REQUEST['id'];
	$res = $mysqli->query($sql)->fetch_assoc();
	if(@$res['id']){
		$time = new DateTime($res['expire']);
		$time->format('Y-m-d H:i:s');
		
		$sql = 'DELETE FROM `history` WHERE `used` <= \''.$time->format('Y-m-d H:i:s').'\' AND `create_by`=\''.$res['id'].'\'';
		$mysqli->query($sql);
		$time->modify('+7 day');
		$sql = 'UPDATE `user` SET `expire` = \''.$time->format('Y-m-d').'\' WHERE `id` = \''.$res['id'].'\'';
		$mysqli->query($sql);
	}
}
exit('<script>document.location.href="'.http_url.'manage/accounts";</script>');