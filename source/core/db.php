<?php if(!defined('IN_HM')) { exit('Access Denied');
 } $mysqli = @new mysqli($_config['db']['dbhost'], $_config['db']['dbuser'], $_config['db']['dbpass'], $_config['db']['dbname']);
 if ($mysqli->connect_errno) { $error_message = $mysqli->connect_errno . ' ' . $mysqli->connect_error;
 } elseif (!$mysqli->set_charset($_config['db']['dbcharset'])) { $error_message = "Error loading character set ".$_config['db']['dbcharset'].": ". $mysqli->error;
 }