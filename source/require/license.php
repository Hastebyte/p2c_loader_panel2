<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
$action_type = array('new','bulk','list','del','reset','add','add_a','del_a','reset_a');
$action = !in_array(@$_GET['action'], $action_type) ? (empty(@$_GET['action']) ? $mod.'/list' : 'error/404') : $mod.'/'.htmlspecialchars($_GET['action']);
require './source/page/'.$action.'.php';
require './source/core/page.php';