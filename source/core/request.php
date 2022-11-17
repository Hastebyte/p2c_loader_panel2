
<?php if(!defined('IN_HM')) { exit('Access Denied');
 } $_URL_REQUEST = array();
 $request_list = explode("?",$_SERVER['REQUEST_URI']);
 if(!empty($request_list[1])){ $request_list = explode("&",$request_list[1]);
 if(is_array($request_list)){ foreach($request_list as $data){ $data_u = explode("=",$data);
 $_URL_REQUEST[$data_u['0']] = htmlspecialchars(@$data_u['1']);
 } }else{ $data_u = explode("=",$data);
 $_URL_REQUEST[$data_u['0']] = htmlspecialchars(@$data_u['1']);
 } } if(!empty($_POST)){ $_POST = function_change($_POST);
 } function function_change(&$array){ foreach($array as $key=>$data){ if(is_array($data)){ $return[$key] = function_change($data);
 } else{ $return[$key] = addslashes(htmlspecialchars($data));
 } } return @$return;
 }