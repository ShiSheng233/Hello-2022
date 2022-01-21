<?php
	define('_IN_STATION', true);
	
	require_once('./config.php');
	
	$currentNotice = (!empty($_GET['id']) && $_GET['id'] >= 1) ? $_GET['id'] + 0 : 1;
	$return = array();
	
	$db->where('id', $currentNotice);
	$notice = $db->getOne('notice');
	
	if ($notice) {
		$return['code'] = 200;
		$return['msg']  = '';
		
		$return['data']['totalNotices'] = $db->getValue('notice', 'count(*)');
		$return['data']['notice'] = $notice;
		
	} else {
		$return['code'] = -400;
		$return['msg']  = '不存在这条公告！';
	}
	
	die(json_encode($return));
?>