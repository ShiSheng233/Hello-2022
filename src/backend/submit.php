<?php
	define('_IN_STATION', true);
	
	require_once('./config.php');
	
	if (!empty($_COOKIE['token'])) {
	    $userToken = $_COOKIE['token'];
	} elseif (!empty($_GET['token'])) {
	    $userToken = $_GET['token'];
	} elseif (!empty($_POST['token'])) {
	    $userToken = $_POST['token'];
	}
	
	if (!empty($_GET['flag'])) {
	    $submitFlag = $_GET['flag'];
	} elseif (!empty($_POST['flag'])) {
	    $submitFlag = $_POST['flag'];
	}
	
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
	
	// 为 Pt0 而做的单独的判断分支
	$userFlag = getSubstr($submitFlag, 'flag{', '}');
	if ($userFlag == hash_hmac('sha256', $userToken, 'ecb20a14-be19-af4f-1a4e-f091b2427096')) {
	    $db->where('id', 1);
	    $subjectData = $db->getOne('subjects');
	    
	    if (!$subjectData) {
    		$return['msg']  = '服务器错误！';
    		$return['code'] = -100;
    		
    		die(json_encode($return));
    	}
    	
	} else {
    	$db->where('flag', $submitFlag);
    	$subjectData = $db->getOne('subjects');
    	
    	if (!$subjectData) {
    		$return['msg']  = '不存在该 Flag！';
    		$return['code'] = -40;
    		
    		die(json_encode($return));
    	}
	}
	
	$userData['finishedSubject'] = json_decode($userData['finishedSubject'], true);
	if ($userData['finishedSubject'] && is_array($userData['finishedSubject']) && in_array($subjectData['id'], $userData['finishedSubject'])) {
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
	
	function getSubstr($str, $leftStr, $rightStr) {
        $left = strpos($str, $leftStr);
        $right = strpos($str, $rightStr,$left);
        
        if($left < 0 or $right < $left) return '';
        return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
    }
?>