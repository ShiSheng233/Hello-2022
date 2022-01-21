<?php
	define('_IN_STATION', true);
	
	require_once('./config.php');
	
	$currentPage = (!empty($_GET['page']) && $_GET['page'] >= 1) ? $_GET['page'] + 0 : 1;
	$return = array();
	
	$db->pageLimit = 10;
	$db->orderBy('userScore', 'desc');
	$userRank = $db->arrayBuilder()->paginate('users', $currentPage, array('userName', 'userPic', 'userScore'));
	
	if ($userRank) {
		$return['msg']  = '';
		$return['code'] = 200;
		
		$return['data']['totalUsers']  = $db->getValue('users', 'count(*)');
		$return['data']['totalPages']  = $db->totalPages;
		$return['data']['currentPage'] = $currentPage;
		$return['data']['rank']        = $userRank;
		
	} else {
		$return['msg']  = '获取数据失败！' . $db->getLastError();
		$return['code'] = -100;
	}
	
	die(json_encode($return));
?>