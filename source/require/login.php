<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}

if(@$_SESSION['login']){
	exit('<script>document.location.href="'.http_url.'";</script>');
	
}

if($_POST){
	if(!empty($_POST['login_name'])&&!empty($_POST['login_passwd'])){
		$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`user` WHERE `name` = \''.$_POST['login_name'].'\'';
		$res = $mysqli->query($sql)->fetch_assoc();
		if($res['id']){
			if(password_verify($_POST['login_passwd'], $res['pwd'])){
				$def_res = $res;
				if($res['id']!='1'){
					$time = new DateTime($res['expire']);
					$now = new Datetime();
					if($now>$time){
						$_HTML['error_txt'] = 'Account expire';
						goto End;
					}
					elseif($res['stop']){
						$_HTML['error_txt'] = 'Account expire';
						goto End;
					}
					else{
						$def_res = $res;
						$sql = 'SELECT * FROM `'.$_config['db']['dbname'].'`.`licenses` WHERE `create_by` = \''.$res['id'].'\'';
						$limit = $mysqli->query($sql);
						foreach($limit as $data){
							$res['limit_num'] -= 1;
							switch($data['days']){
								case 'd':
									$res['day'] -= 1;
									break;
								case 'w':
									$res['week'] -= 1;
									break;
								case 'm':
									$res['month'] -= 1;
									break;
								//case 's':
								//case 'y':
								//case 'n':
							}
						}
					}
				}
				else{
					$res['limit_num'] = 999999;
					$res['day'] = 999999;
					$res['week'] = 999999;
					$res['month'] = 999999;
					$def_res = $res;
				}
				$_SESSION['login'] = $res['id'];
				$_SESSION['m_limit'] = $res['month'];
				$_SESSION['w_limit'] = $res['week'];
				$_SESSION['d_limit'] = $res['day'];
				$_SESSION['m_limit_a'] = $def_res['month'];
				$_SESSION['w_limit_a'] = $def_res['week'];
				$_SESSION['d_limit_a'] = $def_res['day'];
				$_SESSION['all_limit'] = $res['limit_num'];
				$_SESSION['all_limit_a'] = $def_res['limit_num'];
				exit('<script>document.location.href="'.http_url.'";</script>');
			}
			else{
				$_HTML['error_txt'] = $_LNG['login']['worng_pwd_accounts'];
			}
		}
		else{
			$_HTML['error_txt'] = $_LNG['login']['worng_pwd_accounts'];
		}
	}
	elseif(empty($_POST['login_name'])){
		$_HTML['error_txt'] = $_LNG['login']['no_accounts'];
	}
	elseif(empty($_POST['login_passwd'])){
		$_HTML['error_txt'] = $_LNG['login']['no_password'];
	}
}
End:
$custum_page['add'] = array('/login/login.htm');
require './source/core/page.php';