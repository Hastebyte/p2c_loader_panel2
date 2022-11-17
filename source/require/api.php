<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
//header('Content-Type: application/json; charset=utf-8');
$action_type = array('action','reset','info', 'update');
$action = !in_array(@$_GET['action'], $action_type) ? '404' : htmlspecialchars($_GET['action']);

require './source/page/'.$mod.'/'.$action.'.php';
echo $api_return_data;