<?php
	define('_IN_STATION', true);
	
	require_once('./config.php');
	
	$_GITHUB = array();
	
	$_GITHUB['clientId']     = '2022';
	$_GITHUB['clientSecret'] = '2022';
	$_GITHUB['appName']      = '2022';
	
	session_start();
	
	$return = array();
	
	if (empty($_GET)) {
		if (!empty($_COOKIE['token'])) {
			$db->where('userToken', $_COOKIE['token']);
			$user = $db->getOne('users', array('userName', 'userPic', 'userScore', 'userToken'));
			
			if ($user) {
				$return['msg']  = '';
				$return['code'] = 200;
				
				$return['data'] = $user;
				
				setCookie('token', $user['userToken']);
				
			} else {
				$return['msg']  = '没有该用户的信息！';
				$return['code'] = -30;
			}
			
		} else {
			$_SESSION['userRand'] = RandomString(12);
			
			$return['msg']  = '用户未登录';
			$return['code'] = -10;
			
			$return['data']['loginUrl'] = 'https://github.com/login/oauth/authorize?client_id=' . $_GITHUB['clientId'] . '&state=' . $_SESSION['userRand'];
			$return['data']['userRand'] = $_SESSION['userRand'];
		}
		
		die(json_encode($return));
		
	} else {
		/**
		if (empty($_SESSION['userRand']) || empty($_COOKIE['userRand'])) {
			die('用户登录标识消失了=_=||');
		}
		
		if ($_GET['state'] !== $_SESSION['userRand'] || $_GET['state'] !== $_COOKIE['userRand']) {
			die('用户标识好像不对，重新登录试试吧=_=||');
		}
		
		**/
		if (empty($_GET['code'])) {
			die('GitHub 没有给我们返回你的用户信息！=_=||');
		}
		
		$_callback = HttpGet('https://github.com/login/oauth/access_token' .
			'?client_id=' . $_GITHUB['clientId'] .
			'&client_secret=' . $_GITHUB['clientSecret'] .
			'&code=' . $_GET['code'], 'POST', '', array(
			'Content-type: application/json; charset=utf-8',
			'Accept: application/json')
		);
		$callback = json_decode($_callback, true);
		
		$_userInfo = HttpGet('https://api.github.com/user', 'GET', '', array(
			'Authorization: ' . $callback['token_type'] . ' ' . $callback['access_token'],
			'Accept: application/vnd.github.v3+json'
		), $_GITHUB['appName']);
		$userInfo = json_decode($_userInfo, true);
		
		if (!empty($userInfo['id'])) {
			$db->where('userId', $userInfo['id']);
			$user = $db->getOne('users');
			
			if (!$user) {
				$user['userId']    = $userInfo['id'];
				$user['userName']  = $userInfo['login'];
				$user['userPic']   = $userInfo['avatar_url'];
				$user['userToken'] = base64_encode(RC4('ShishengIsRBQ', $userInfo['id'] . $userInfo['login'] . $userInfo['node_id'] . $_SESSION['userRand']));
				
				if (!$db->insert('users', $user)) {
					die('写入数据库失败了=_=||');
				}
			}
			
			// 这里应该是 301 到首页
			echo '你好，' . $user['userName'] . '，欢迎登录！';
			header('Location: http://49.234.211.217:1145');
			setCookie('token', $user['userToken']);
			
		} else {
			echo '<!--' . $_callback . '--><br>';
			echo '<!--' . $_userInfo . '--><br>';
			die('GitHub 没有给我们返回你的用户信息！=_=||<br>也许这些错误信息可以帮你的忙：(1)' . $callback['error'] . ' (2)' . $userInfo['message']);
		}
	}
	
	
	
	function RandomString ($length) {
		$string = str_split('abcdefghijklmnopqrstuvwxyz0123456789');
		$keys = array_rand($string, $length);
		$randString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randString .= $string[$keys[$i]];
		}
		
		return $randString;
	}
	
	function HttpGet ($url, $method = 'GET', $data = '', $contentType = '', $userAgent = '', $timeout = 10) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
        if (!empty($contentType)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $contentType);
        }
        if (!empty($userAgent)) {
        	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
       }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
	
	function RC4($pwd, $data) {
		$cipher      = '';
		$key[]       = "";
		$box[]       = "";
		$pwd_length  = strlen($pwd);
		$data_length = strlen($data);
		for ($i = 0; $i < 256; $i++) {
			$key[$i] = ord($pwd[$i % $pwd_length]);
			$box[$i] = $i;
		}
		for ($j = $i = 0; $i < 256; $i++) {
			$j       = ($j + $box[$i] + $key[$i]) % 256;
			$tmp     = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		for ($a = $j = $i = 0; $i < $data_length; $i++) {
			$a       = ($a + 1) % 256;
			$j       = ($j + $box[$a]) % 256;
			$tmp     = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$k       = $box[(($box[$a] + $box[$j]) % 256)];
			$cipher .= chr(ord($data[$i]) ^ $k);
		}
		return $cipher;
	}
?>