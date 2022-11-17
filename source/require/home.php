<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}

$custum_page['add'] = array('/home/default.htm');
require './source/page/'.$mod.'/default.php';
require './source/core/page.php';