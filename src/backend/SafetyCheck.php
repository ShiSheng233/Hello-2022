<?php
	_InputCheck();
	
	function _InputCheck() {
		$_INPUT_DATA = $_POST ? $_POST : $_GET;
		
		if (!$_INPUT_DATA) return;
		
		$preg = Array(
			'/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU',
			'/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU',
			'/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|dump/is'
		);
		
		for ($i = 0; $i < count($preg); $i++) {
			foreach ($_INPUT_DATA as $x => $_array) {
				if (preg_match($preg[$i], $_array)) {
					header('Location: http://' . $_SERVER['HTTP_HOST'] . '/error.html');
					die();
				}
			}
		}
	}
?>