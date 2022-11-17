<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if(is_numeric(@$_URL_REQUEST['id'])){
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`user` WHERE `id` = '.$_URL_REQUEST['id'];
	$res = $mysqli->query($sql)->fetch_assoc();
	if(@$res['id']){
		if($res['stop']){
			$sql = 'DELETE FROM `history` WHERE `create_by` = \''.$res['id'].'\'';
			$mysqli->query($sql);
			$sql = 'DELETE FROM `licenses` WHERE `create_by` = \''.$res['id'].'\'';
			$mysqli->query($sql);
			$sql = 'DELETE FROM `user` WHERE `id` = \''.$res['id'].'\'';
			$mysqli->query($sql);
		}
	}
}
exit('<script>document.location.href="'.http_url.'manage/accounts";</script>');