<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if(is_numeric(@$_URL_REQUEST['id'])){
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE `id` = '.$_URL_REQUEST['id'];
	if($mysqli->query($sql)->num_rows){
		$action .="-info";
		//可能是更新
		if($_POST){
			$array = array(`d`, `w`, `m`, `s`, `y`);
			$update = array();
			foreach($array as $data){
				if((is_numeric(@$_POST[$data])||@$_POST[$data]==0)){
					$sql ='UPDATE `user` SET `'.$data.'` = '.$_POST[$data].' WHERE `id` = '.$_URL_REQUEST['id'].';';
					$mysqli->query($sql);
				}
			}
			exit('<script>document.location.href="'.http_url.'manage/application";</script>');
		}
		$res = $mysqli->query($sql)->fetch_assoc();
	}
}
elseif(is_numeric(@$_URL_REQUEST['pid'])){
	// 暫停
	$time = new DateTime();
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE `id` = '.$_URL_REQUEST['pid'];
	$res_a = $mysqli->query($sql);
	if($res_a->num_rows){
		$cann = false;
		$res_a = $res_a->fetch_assoc();
		if($res_a['pause_expire']){
			$time_c = new DateTime($res_a['pause_expire']);
			if($time<$time_c){
				$sql ='UPDATE `application` SET `pause_expire` = NULL WHERE `id` = \''.$_URL_REQUEST['pid'].'\'';
				$mysqli->query($sql);
				$cann = true;
			}
		}
		if(!$cann){
			$time = new DateTime();
			$time->modify('+ 1 day');
			$sql ='UPDATE `application` SET `pause_expire` = \''.$time->format('Y-m-d H:i:s').'\' WHERE `id` = \''.$_URL_REQUEST['pid'].'\'';
			$mysqli->query($sql);
			
			$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `active` != \'0\' AND `application` = '.$_URL_REQUEST['pid'];
			$res = $mysqli->query($sql);
			foreach($res as $data){
				$time = new DateTime($data['expire_at']);
				$time->modify('+ 1 day');
				$sql ='UPDATE `licenses` SET `expire_at` = \''.$time->format('Y-m-d H:i:s').'\' WHERE `id` = \''.$data['id'].'\'';
				$mysqli->query($sql);
			}
		}
	}	
	exit('<script>document.location.href="'.http_url.'manage/application";</script>');
}
else{
	if($_POST){
		//可能是新增
		if((is_numeric(@$_POST['d'])||@$_POST['d']==0)&&(is_numeric(@$_POST['w'])||@$_POST['w']==0)&&(is_numeric(@$_POST['m'])||@$_POST['m']==0)&&(is_numeric(@$_POST['s'])||@$_POST['s']==0)&&(is_numeric(@$_POST['y'])||@$_POST['y']==0)&&!empty(@$_POST['name'])){
			$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE `name` = \''.$_POST['name'].'\'';
			if($mysqli->query($sql)->num_rows){
				echo '<script>alert("'.$_LNG['manage']['application']['exists'].'");</script>';
			}
			else{
				$sql ='INSERT INTO `application` (`name`, `d`, `w`, `m`, `s`, `y`) VALUES (\''.$_POST['name'].'\',\''.$_POST['d'].'\',\''.$_POST['w'].'\',\''.$_POST['m'].'\',\''.$_POST['s'].'\',\''.$_POST['y'].'\')';
				$mysqli->query($sql);
				exit('<script>document.location.href="'.http_url.'manage/application?id='.$mysqli->insert_id.'";</script>');
			}
		}
	}
	$_HTML['table'] = '';
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE 1';
	$res = $mysqli->query($sql);
	foreach($res as $data){
		$dothing = '<a href="?id='.$data['id'].'" class="btn btn-secondary">'.$_LNG['manage']['user']['btn_edit'].'</a>';
		if($data['pause_expire']){
			$time_c = new DateTime($data['pause_expire']);
			$time = new DateTime();
			if($time<$time_c){
				$dothing .= '<a href="?pid='.$data['id'].'" class="btn btn-warning">'.$_LNG['license']['btn_upause'].'</a>';
			}else{
				$dothing .= '<a href="?pid='.$data['id'].'" class="btn btn-danger">'.$_LNG['license']['btn_pause'].'</a>';
			}
		}else{
			$dothing .= '<a href="?pid='.$data['id'].'" class="btn btn-danger">'.$_LNG['license']['btn_pause'].'</a>';
		}
		$diff_info = $data['d'].'/'.$data['w'].'/'.$data['m'].'/'.$data['s'].'/'.$data['y'];
		$_HTML['table'].='<tr><td>'.$data['id'].'</td><td>'.$data['name'].'</td><td>'.$diff_info.'</td><td>'.$dothing.'</td></tr>';
	}
}
$custum_page['add'][] = '/'.$action.'.htm';