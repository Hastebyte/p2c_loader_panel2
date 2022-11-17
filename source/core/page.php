<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
require './template/'.((file_exists('./template/'.$_config['global']['theme'].'/common/header_common.htm'))?$_config['global']['theme']:'default').'/common/header_common.htm';
if(@$_SESSION['login']){
	require './template/'.((file_exists('./template/'.$_config['global']['theme'].'/common/header_nav.htm'))?$_config['global']['theme']:'default').'/common/header_nav.htm';	
}
if(@is_array($custum_page['add'])){
	foreach(@$custum_page['add'] as $value){
		require './template/'.((file_exists('./template/'.$_config['global']['theme'].$value))?$_config['global']['theme']:'default').$value;
	}
}
require './template/'.((file_exists('./template/'.$_config['global']['theme'].'/common/footer.htm'))?$_config['global']['theme']:'default').'/common/footer.htm';