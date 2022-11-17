<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
$naaas = 'verify_key';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, strtolower($auth_url));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("verify"=>$$naaas, "domains"=>$_SERVER['HTTP_HOST']))); 
$output = curl_exec($ch); 
curl_close($ch);
$output = json_decode($output);
if($output->reslut!=true){
	echo 'auth fail';
}
?>