<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
require './source/page/error/404.php';
$custum_page['remove'] = array('/common/header_nav.htm','/common/header_menu.htm','/common/footer.htm');
require './source/core/page.php';