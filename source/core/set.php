<?php
define('IN_HM','1');
// Setting 
//error_reporting(1);
session_start();

// Set default Value
define('http_url',((isset($_SERVER['HTTPS']))?'https://':'http://').substr($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'], 0, -strlen($check_url)));

require './source/setting/db.php';
require './source/setting/setting.php';
date_default_timezone_set(($_config['db']['timezone'])?$_config['db']['timezone']:'Asia/Taipei');

// Set default header
header("Content-Type:text/html; charset=".(($_config['global']['webcharset'])?$_config['global']['webcharset']:'utf8'));


require './source/core/db.php';
if (@$error_message) {
	// Show 503 Error
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	require './template/'.((file_exists('./template/'.$_config['global']['theme'].'/error/503.htm'))?$_config['global']['theme']:'default').'/error/503.htm';
	exit();
}

require './source/core/request.php';

$time = new DateTime();
$sql = 'DELETE FROM `licenses` WHERE `active` = \'1\' AND `expire_at` <= \''.$time->format('Y-m-d H:i:s').'\'';
$mysqli->query($sql);
unset($time);