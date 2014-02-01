<?php

// Modify image src
function ModifyImageSrc($pagename) {
	$content = '';
	$c_dirname = 'n1_1331712816';
	
	$myFile = 'epub/'.$c_dirname.'/OPS/'.$pagename;
	$handle = fopen($myFile, 'r');
	while(!feof($handle)) {  
		$data = fgets($handle); 
		$res1 = preg_match_all('/<img[^>]*>/Ui', $data, $imgmatches);
		for($i=0;$i<count($imgmatches[0]);$i++) {   
			preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $imgmatches[0][$i], $filematch);
			if($filematch !== false) {
				$data = str_replace($filematch[1], 'images/'.$filematch[1], $data);
			}
		}
		$content .= $data;
	} 
	
	fclose($handle);
	
	$fw = fopen($myFile, 'w');
	fwrite($fw, $content);
	fclose($fw);
	return true;
}


ModifyImageSrc('page-1.html');


?>