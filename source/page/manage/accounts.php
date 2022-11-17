<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if(!is_numeric(@$_URL_REQUEST['id'])){
	if($_POST){
		//可能是新增
		if((is_numeric(@$_POST['day'])||@$_POST['day']==0)&&(is_numeric(@$_POST['week'])||@$_POST['week']==0)&&(is_numeric(@$_POST['month'])||@$_POST['month']==0)&&(is_numeric(@$_POST['total'])||@$_POST['total']==0)&&!empty(@$_POST['name'])&&!empty(@$_POST['pwd'])){
			$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`user` WHERE `name` = \''.$_POST['name'].'\'';
			if($mysqli->query($sql)->num_rows){
				echo '<script>alert("'.$_LNG['manage']['user']['exists'].'");</script>';
			}
			else{
				
				$time = new DateTime();
				$time->modify('+8 day');
				$sql ='INSERT INTO `user` (`name`, `pwd`, `day`,`week`,`month`, `limit_num`, `expire`) VALUES (\''.$_POST['name'].'\',\''.password_hash($_POST['pwd'],PASSWORD_DEFAULT).'\',\''.$_POST['day'].'\',\''.$_POST['week'].'\',\''.$_POST['month'].'\',\''.$_POST['total'].'\', \''.$time->format('Y-m-d').'\')';
				$mysqli->query($sql);
				exit('<script>document.location.href="'.http_url.'manage/accounts?id='.$mysqli->insert_id.'";</script>');
			}
		}
	}
	$_HTML['table'] = '';
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`user` WHERE `id` != 1';
	$res = $mysqli->query($sql);
	foreach($res as $data){
		$dothing = '<a href="?id='.$data['id'].'" class="btn btn-secondary">'.$_LNG['manage']['user']['btn_edit'].'</a> <a href="'.http_url.'manage/add?id='.$data['id'].'" class="btn btn-info" onClick="return confirm(\'Are you sure?\');">'.$_LNG['manage']['user']['btn_pay'].'</a>';
		if($data['stop']){
			$dothing .= ' <a href="'.http_url.'manage/stop?id='.$data['id'].'" class="btn btn-success">'.$_LNG['manage']['user']['btn_start'].'</a> <a href="'.http_url.'manage/del?id='.$data['id'].'" class="btn btn-danger" onClick="return confirm(\'Are you sure?\');">'.$_LNG['manage']['user']['btn_del'].'</a>';	
		}else{
			$dothing .= ' <a href="'.http_url.'manage/stop?id='.$data['id'].'" class="btn btn-warning">'.$_LNG['manage']['user']['btn_stop'].'</a>';	
		}
		$sql = 'SELECT `id` FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `days` = \'d\' AND `create_by` = \''.$data['id'].'\'';
		$res_d = $mysqli->query($sql)->num_rows;
		$sql = 'SELECT `id` FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `days` = \'w\' AND `create_by` = \''.$data['id'].'\'';
		$res_w = $mysqli->query($sql)->num_rows;
		$sql = 'SELECT `id` FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `days` = \'m\' AND `create_by` = \''.$data['id'].'\'';
		$res_m = $mysqli->query($sql)->num_rows;
		
		$time_end = new DateTime($data['expire']);
		$sql = 'SELECT `card` FROM `'.$_config['db']['dbname'].'`.`history` WHERE `used` < \''.$time_end->format('Y-m-d H:i:s').'\' AND `days` = \'d\' AND `create_by` = \''.$data['id'].'\'';
		$res_d_w = $mysqli->query($sql)->num_rows;
		$sql = 'SELECT `card` FROM `'.$_config['db']['dbname'].'`.`history` WHERE `used` < \''.$time_end->format('Y-m-d H:i:s').'\' AND `days` = \'w\' AND `create_by` = \''.$data['id'].'\'';
		$res_w_w = $mysqli->query($sql)->num_rows;
		$sql = 'SELECT `card` FROM `'.$_config['db']['dbname'].'`.`history` WHERE `used` < \''.$time_end->format('Y-m-d H:i:s').'\' AND `days` = \'m\' AND `create_by` = \''.$data['id'].'\'';
		$res_m_w = $mysqli->query($sql)->num_rows;

		$start = new DateTime($data['expire']);
		$start->modify('-7 day');
		$_HTML['table'].='<tr><td>'.$data['name'].'</td><td>'.$res_d.'/'.$res_d_w.'/'.$data['day'].'</td><td>'.$res_w.'/'.$res_w_w.'/'.$data['week'].'</td><td>'.$res_m.'/'.$res_m_w.'/'.$data['month'].'</td><td>'.$start->format('Y-m-d').'~'.$data['expire'].'</td><td>'.$dothing.'</td></tr>';
	}
}
else{
	
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`user` WHERE `id` = '.$_URL_REQUEST['id'];
	if($mysqli->query($sql)->num_rows){
		$action .="-info";
		//可能是更新
		if($_POST){
			$array = array('day','week','month','limit_num');
			$update = array();
			foreach($array as $data){
				if((is_numeric(@$_POST[$data])||@$_POST[$data]==0)){
					$sql ='UPDATE `user` SET `'.$data.'` = '.$_POST[$data].' WHERE `id` = '.$_URL_REQUEST['id'].';';
					$mysqli->query($sql);
				}
			}
			if($_POST['pwd']){
				$sql ='UPDATE `user` SET `pwd` = '.password_hash($_POST['pwd'],PASSWORD_DEFAULT).' WHERE `id` = '.$_URL_REQUEST['id'].';';
				$mysqli->query($sql);
			}
			exit('<script>document.location.href="'.http_url.'manage/accounts";</script>');
		}
		$res = $mysqli->query($sql)->fetch_assoc();
		
	}
	
}
$custum_page['add'][] = '/'.$action.'.htm';