<?php
	function generate_css($buffer){
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		$buffer = str_replace(': ', ':', $buffer);
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		$buffer = str_replace('{ ', '{', $buffer);
		$buffer = str_replace(' }', '}', $buffer);
		$buffer = str_replace('; ', ';', $buffer);
		
		return $buffer;
	}
	$buffer = "";
	if(file_exists($_REQUEST['file'])){
		if(file_exists('cache/'.$_REQUEST['file'])){
			$edit_cachefile = filemtime('cache/'.$_REQUEST['file']);
			$edit_sourcefile = filemtime($_REQUEST['file']);
			if($edit_sourcefile > $edit_cachefile){
				$file = file_get_contents($_REQUEST['file']);
				$buffer = generate_css($file);
				file_put_contents('cache/'.$_REQUEST['file'],$buffer);
			}
			else{
				$buffer = file_get_contents('cache/'.$_REQUEST['file']);
			}
		}else{
			$file = file_get_contents($_REQUEST['file']);
			$buffer = generate_css($file); 
			file_put_contents('cache/'.$_REQUEST['file'],$buffer);
		}
		ob_start("ob_gzhandler");
		header('Cache-Control: public');
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
		header("Content-type: text/css");
		echo($buffer);
	}
	else{
		header("HTTP/1.0 404 Not Found");
	}
?>