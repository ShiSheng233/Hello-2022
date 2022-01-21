<?php
	define('_IN_STATION', true);
	
	require_once('./config.php');
	
	$userToken = $_COOKIE['token'] || $_GET['token'] || $_POST['token'];
	$submitFlag = $_GET['flag'] || $_POST['flag'];
	
	$return = array();
	
	if (empty($userToken)) {
		$return['msg']  = '用户未登录';
		$return['code'] = -10;
		
		die(json_encode($return));
	}
	
	if (!isset($submitFlag)) {
		$return['msg']  = 'Flag 不能为空！';
		$return['code'] = -20;
		
		die(json_encode($return));
	}
	
	$db->where('userToken', $userToken);
	$userData = $db->getOne('users');
	
	if (!$userData) {
		$return['msg']  = '不存在该 Token！';
		$return['code'] = -30;
		
		die(json_encode($return));
	}
	
	$db->where('flag', $submitFlag);
	$subjectData = $db->getOne('subjects');
	
	if (!$subjectData) {
		$return['msg']  = '不存在该 Flag！';
		$return['code'] = -40;
		
		die(json_encode($return));
	}
	
	$userData['finishedSubject'] = json_decode($userData['finishedSubject'], true);
	if (in_array($subjectData['id'], $userData['finishedSubject'])) {
		$return['msg']  = '您已提交该题目！';
		$return['code'] = -50;
		
		die(json_encode($return));
	}
	
	$userData['finishedSubject'][] = $subjectData['id'];
	$userData['finishedSubject'] = json_encode($userData['finishedSubject']);
	$userData['userScore'] += $subjectData['score'];
	
	$db->where('userId', $userData['userId']);
	if ($db->update('users', $userData)) {
		$return['msg']  = '提交成功！您目前的积分：' . $userData['userScore'];
		$return['code'] = 200;
		
		die(json_encode($return));
		
	} else {
		$return['msg']  = '写入失败！';
		$return['code'] = -50;
		
		die(json_encode($return));
	}
?>