<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
header('HTTP/1.1 423 Locked');
require './template/'.((file_exists('./template/'.$_config['global']['theme'].'/error/423.htm'))?$_config['global']['theme']:'default').'/error/423.htm';
exit();