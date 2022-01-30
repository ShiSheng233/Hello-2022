<?php
	define('_IN_STATION', true);
	
	require_once('./config.php');
	
	$return = array();
	
	if (empty($_GET['id'])) {
		$subjects = $db->get('subjects', null, array('id', 'title', 'score'));
		
		if ($subjects) {
			$return['msg']  = '';
			$return['code'] = 200;
			
			$return['data'] = $subjects;
			
		} else {
			$return['msg']  = '与数据库通讯失败！';
			$return['code'] = -80;
		}
		
		die(json_encode($return));
		
	} else {
	
		if ($_GET['id'] + 0 <= 0) {
			$return['msg']  = '不存在该题目！';
			$return['code'] = -50;
			
			die(json_encode($return));
		}
		
		$db->where('id', $_GET['id'] + 0);
		$subject = $db->getOne('subjects');
		
		if ($subject) {
			$return['msg']  = '';
			$return['code'] = 200;
			
			$return['data'] = $subject;
			
		} else {
			$return['msg']  = '不存在该题目！';
			$return['code'] = -50;
		}
		
		die(json_encode($return));
	}
	
	
?>
