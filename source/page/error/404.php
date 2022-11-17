<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
$_HTML['title'] = "Page Not Found";
header('HTTP/1.1 404 Not Found');
require './template/'.((file_exists('./template/'.$_config['global']['theme'].'/error/404.htm'))?$_config['global']['theme']:'default').'/error/404.htm';
exit();