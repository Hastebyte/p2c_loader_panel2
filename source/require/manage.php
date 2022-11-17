<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if($_SESSION['login']==1){
	$action_type = array('add','stop','del','accounts','application','license');
	$action = !in_array(@$_GET['action'], $action_type) ? (empty(@$_GET['action']) ? $mod.'/list' : 'error/404') : $mod.'/'.htmlspecialchars($_GET['action']);
}else{
	$action = 'error/404';
}
require './source/page/'.$action.'.php';
require './source/core/page.php';