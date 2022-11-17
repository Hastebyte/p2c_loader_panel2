<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
if($_POST){
	if($_SESSION['login']!=1){
		// 需檢查餘額
				print_r($_SESSION);
		switch($_POST['lice_type']){
			case 'd':
				if($_SESSION['d_limit']<1||$_SESSION['all_limit']<1){
					if($_SESSION['all_limit']<1){
						$alert = $_LNG['license']['not_enough'].$_LNG['license']['type_total'];
					}
					else{
						$alert = $_LNG['license']['not_enough'].$_LNG['license']['type_d'];
					}
					echo '<script>alert('.$alert.')</script>';
					goto End; 
				}
				break;
			case 'w':
				if($_SESSION['w_limit']<1||$_SESSION['all_limit']<1){
					if($_SESSION['all_limit']<1){
						$alert = $_LNG['license']['not_enough'].$_LNG['license']['type_total'];
					}
					else{
						$alert = $_LNG['license']['not_enough'].$_LNG['license']['type_w'];
					}
					echo '<script>alert('.$alert.')</script>';
					goto End; 
				}
				break;
			case 'm':
				if($_SESSION['m_limit']<1||$_SESSION['all_limit']<1){
					if($_SESSION['all_limit']<1){
						$alert = $_LNG['license']['not_enough'].$_LNG['license']['type_total'];
					}
					else{
						$alert = $_LNG['license']['not_enough'].$_LNG['license']['type_m'];
					}
					echo '<script>alert('.$alert.')</script>';
					goto End; 
				}
				break;
			default:
				echo '<script>alert('.$_LNG['license']['forbidden'].')</script>';
				goto End; 
				break;
		}
	}
	// 檢查應用ID合理性
	$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE `id` = '.$_POST['app_type'];
	$res = $mysqli->query($sql);
	if(!@$res->num_rows){
		//這個跳過
		if($_SESSION['login']==1&&$_POST['app_type']=='n'){
			$_POST['app_type'] = -1;
		}else{
			echo '<script>alert('.$_LNG['license']['forbidden'].')</script>';
			goto End; 
		}
	}
	// 應該都OK了
	if(empty($_POST['cus_ser_name'])){
		$_POST['cus_ser_name'] = key_gen();
	}
	$sql ='INSERT INTO `licenses` (`serial`,`days`,`application`,`create_by`) VALUES (\''.$_POST['cus_ser_name'].'\',\''.$_POST['lice_type'].'\',\''.$_POST['app_type'].'\',\''.$_SESSION['login'].'\')';
	$mysqli->query($sql);
	switch($_POST['lice_type']){
		case 'd':
			$_SESSION['d_limit'] -= 1;
			break;
		case 'w':
			$_SESSION['w_limit'] -= 1;
			break;
		case 'm':
			$_SESSION['m_limit'] -= 1;
			break;
	}
	$_SESSION['all_limit'] -= 1;
	exit('<script>document.location.href="'.http_url.'license/list";</script>');
}
End:
$custum_page['add'][] = '/'.$action.'.htm';
$_HTML['applist'] = '';
$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`application` WHERE 1';
$res = $mysqli->query($sql);
foreach($res as $data){
	$_HTML['applist'] .= '<option value="'.$data['id'].'">'.$data['name'].'</option>';
}


function key_gen(){
	$key = strtoupper(sha1(uniqid('',true)));
	return substr($key,0,5).'-'.substr($key,14,5).'-'.substr($key,28,5).'-'.substr($key,7,5).'-'.substr($key,21,5);
}
