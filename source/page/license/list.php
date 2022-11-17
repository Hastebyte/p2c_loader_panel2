<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
$_HTML['table'] = '';
$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `create_by` = '.$_SESSION['login'];
$res = $mysqli->query($sql);
$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE 1';
$res_app = $mysqli->query($sql);
$app = array('-1' => $_LNG['license']['s_key']);
foreach($res_app as $data){
	$app[$data['id']] = $data['name'];
}
foreach($res as $data){
	$dothing = 	'<a href="'.http_url.'license/reset?id='.$data['id'].'" class="btn btn-secondary">'.$_LNG['license']['btn_reset'].'</a> '.
				'<a href="'.http_url.'license/del?id='.$data['id'].'" class="btn btn-danger">'.$_LNG['license']['btn_del'].'</a>';
	if($data['active']){
		$check_time = new DateTime($data['expire_at']);
		$now_time = new DateTime();
		if($now_time>=$check_time){
			$data['active'] = '2';
		}
		else{
			$dothing .= ' <a href="'.http_url.'license/add?id='.$data['id'].'" class="btn btn-info">'.$_LNG['license']['btn_24h'].'</a> ';	
		}
	}
	$_HTML['table'] .='<tr><td>'.$app[$data['application']].'</td><td>'.$data['serial'].'<p><small>'.$_LNG['license']['user'].$data['user'].'</small></p></td><td>'.$data['create_at'].'</td><td>'.$_LNG['license']['type_'.$data['days']].'</td><td>'.$data['expire_at'].'</td><td>'.$_LNG['license']['active_'.$data['active']].'</td><td>'.$dothing.'</td></tr>';
}
$custum_page['add'][] = '/'.$action.'.htm';