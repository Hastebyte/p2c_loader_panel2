<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
$_HTML['title'] = "Forbidden";
header('HTTP/1.1 403 Forbidden');
require './template/'.((file_exists('./template/'.$_config['global']['theme'].'/error/403.htm'))?$_config['global']['theme']:'default').'/error/403.htm';
exit();