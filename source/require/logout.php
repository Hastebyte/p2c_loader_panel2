<?php
if(!defined('IN_HM')) {
	exit('Access Denied');
}
session_destroy();
echo '<script>document.location.href="'.http_url.'login";</script>';