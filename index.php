<?php
$check_url = basename(__FILE__);
require './source/core/set.php';

include('source/lang/'.$_config['global']['lang'].'.php');
if(empty(@$_SESSION['login'])&&(@$_GET['mod']!='login'&&@$_GET['mod']!='api')){
	echo '<script>document.location.href="'.http_url.'login";</script>';
	exit();
}
$timenow = new DateTime;

$mod_type = array('login',
				  'logout',
				  'accounts',
				  'license',
				  'manage',
				  'api');		
				 
$_G['Site_Name'] = 'CheatFX';
$mod = !in_array(@$_GET['mod'], $mod_type) ? (empty(@$_GET['mod']) ? 'home' : 'error') : htmlspecialchars($_GET['mod']);

require './source/require/'.$mod.'.php';
$mysqli->close();