<?php
	if (!_IN_STATION) die();
	
	require_once('./MysqliDb.php');
	require_once('./SafetyCheck.php');
	
	$_CONFIG = array();
	
	$_CONFIG['db']['host']     = 'localhost';
	$_CONFIG['db']['port']     = 3306;
	$_CONFIG['db']['user']     = '2022';
	$_CONFIG['db']['password'] = '2022';
	$_CONFIG['db']['name']     = '2022';
	$_CONFIG['db']['charset']  = 'utf8';
	
	
	$db = new MysqliDb(array(
		'host'     => $_CONFIG['db']['host'],
		'username' => $_CONFIG['db']['user'],
		'password' => $_CONFIG['db']['password'],
		'db'       => $_CONFIG['db']['name'],
		'port'     => $_CONFIG['db']['port'],
		'charset ' => $_CONFIG['db']['charset']
	));
	
?>