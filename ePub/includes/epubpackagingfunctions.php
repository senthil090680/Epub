<?php

	// function to verify folders and files
	function CheckFoldersandFiles($dir) {
		if(!is_file($dir.'/mimetype')) { echo 'Error : mime type is missing'; exit; }
		if(!is_file($dir.'/META-INF/container.xml')) { echo 'Error: container xml is missing'; exit; }
		if(!is_dir($dir.'/OPS')) { echo 'Error: OPS directory is missing'; exit; }
		if(!is_dir($dir.'/OPS/images')) { echo 'Error: image directory is missing in OPF'; exit; }
	}

	// Create Uniq File Name
	function SetUniqFileName($file) {
		$fname = basename($file);
		$file = substr($fname, 0, strpos($fname, ".")); 
		$extension = pathinfo($fname, PATHINFO_EXTENSION);
		
		$file = preg_replace('/[^a-zA-Z0-9_-]/s', '', $file);
		
		return $filename = strtolower($file."_".mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y')).".".$extension);	
	}

	// Function to create html files
	function CreateHtmlFile($fcount) {
		global $c_dirname;
		global $imagetype;
		
		for($fc=0;$fc<$fcount;$fc++) {
			$imagename = 'page-'.$fc.'.'.$imagetype;
			$htmlpagename = 'page-'.$fc.'.html';
		
			$content = "<html xmlns='http://www.w3.org/1999/xhtml' >\n"
							. "<head>\n"
								. "<title>Page $fc</title>\n"
								. "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>\n"
							. "</head>\n"
							. "<body>\n"
								. "<div align='center'>\n"
									. "<img src='images/$imagename'/>\n"
								. "</div>\n"
							. "</body>\n"
							. "</html>";
							
			$file = EPUB_FOLDER.'/'.$c_dirname."/OPS/".$htmlpagename;
			$fr = fopen($file,"w");
			fwrite($fr, $content);
		}
	}
	
	// Create Opf content
	function CreateOpf($k) {
		global $c_dirname;
		global $imagetype;
		global $outputtpe;
		
		if($outputtpe == 1) $start = 0;
		else $start = 1;
		
		$contentmanifest = '';
		for($p=$start;$p<$k;$p++){
			$contentmanifest .= "<item id='page-$p' href='page-$p.html' media-type='application/xhtml+xml'/>\n";
		}
		
		$contentspine = '';
		for($q=$start;$q<$k;$q++)	 {
			$contentspine .= "<itemref idref='page-$q' linear='yes'/>\n";
		}
		
		$contentopf = "<?xml version='1.0' encoding='UTF-8'?>\n"
					. "<package xmlns='http://www.idpf.org/2007/opf' unique-identifier='EPB-UUID' version='2.0'>\n"
						. "<metadata xmlns:opf='http://www.idpf.org/2007/opf' xmlns:dc='http://purl.org/dc/elements/1.1/'>\n"
							. "<dc:title>$c_dirname</dc:title>\n"
							. "<dc:creator></dc:creator>\n"
							. "<dc:subject></dc:subject>\n"
							. "<dc:description></dc:description>\n"
							. "<dc:contributor>Mac OS X 10.6.7 Quartz PDFContext</dc:contributor>\n"
							. "<dc:date>2011-07-09</dc:date>\n"
							. "<dc:type></dc:type>\n"
							. "<dc:format></dc:format>\n"
							. "<dc:source></dc:source>\n"
							. "<dc:relation></dc:relation>\n"
							. "<dc:coverage></dc:coverage>\n"
							. "<dc:rights></dc:rights>\n"
							. "<dc:identifier id='EPB-UUID'>urn:uuid:123456</dc:identifier>\n"
							. "<dc:language>en-gb</dc:language>\n"
						. "</metadata>\n"
						. "<manifest>\n";
		$contentopf .=	$contentmanifest;
		$contentopf .=	"<item id='ncx' href='fb.ncx' media-type='application/x-dtbncx+xml'/>\n"
						. "</manifest>\n"
						. "<spine toc='ncx'>\n";
		$contentopf .= 	$contentspine;
		$contentopf .=	"</spine>\n"
						. "</package>";
	
		
		$file = EPUB_FOLDER.'/'.$c_dirname."/OPS/fb.opf";
		$fr = fopen($file,"w");
		fwrite($fr, $contentopf);
		
		return true;
	
	}
	
	// Create Ncx content
	function CreateNcx($fcount) {
		global $c_dirname;
		global $outputtpe;
		
		if($outputtpe == 1) $start = 0;
		else $start = 1;
		
		
		$contentnmap = '';
		for($q=$start;$q<$fcount;$q++){
			$contentnmap .= "<navPoint id='navpoint-$q' playOrder='$q'>\n"
							. "<navLabel>\n"
								. "<text>page-$q</text>\n"
							. "</navLabel>\n"
							. "<content src='page-$q.html'/>\n"
						. "</navPoint>\n";
		}
	
		$contentncx = "<?xml version='1.0' encoding='UTF-8'?>\n"
						. "<!DOCTYPE ncx PUBLIC '-//NISO//DTD ncx 2005-1//EN' 'http://www.daisy.org/z3986/2005/ncx-2005-1.dtd'>\n"
						. "<ncx xmlns='http://www.daisy.org/z3986/2005/ncx/' version='2005-1'>\n"
							. "<head>\n"
								. "<meta name='dtb:uid' content='123456'/>\n"
								. "<meta name='dtb:depth' content='1'/>\n"
								. "<meta name='dtb:totalPageCount' content='0'/>\n"
								. "<meta name='dtb:maxPageNumber' content='0'/>\n"
							. "</head>\n"
							. "<docTitle><text>$c_dirname</text></docTitle>\n"
							. "<navMap>\n";
		
		$contentncx .= $contentnmap;
		
		$contentncx .= 	"</navMap>\n"
						. "</ncx>";
		
		
		$file =  EPUB_FOLDER.'/'.$c_dirname."/OPS/fb.ncx";
		$fr = fopen($file,"w");
		fwrite($fr, $contentncx);
						
		return true;
	}
	
	// Copy manifest files from source to dest
	function RecursiveCopy($source, $dest, $diffDir = ''){
		$sourceHandle = opendir($source);
		if(!$diffDir)
			 $diffDir = $source;
	   
		if($diffDir!=$source)
			mkdir($dest . '/' . $diffDir);
		else
			$diffDir = '';
	
		while($res = readdir($sourceHandle)){
			if($res == '.' || $res == '..')
				continue;
		   
			if(is_dir($source . '/' . $res)){
			   RecursiveCopy($source . '/' . $res, $dest, $diffDir . '/' . $res);
			} else {
			   copy($source . '/' . $res, $dest . '/' . $diffDir . '/' . $res);
			}
		}
	}
	
	
	// Removes file extension
	function RemoveExtension($f) {
		$path_parts = pathinfo($f);
		return $path_parts['filename'];
	}
	
	// Gives file extension
	function GetFileExtension($f) {
		$path_parts = pathinfo($f);
		return strtolower($path_parts['extension']);
	}
	
	
	function listdir($dir='.') {
		if (!is_dir($dir)) {
			return false;
		}
	   
		$files = array();
		listdiraux($dir, $files);
	
		return $files;
	}
	
	function listdiraux($dir, &$files) {
		$handle = opendir($dir);
		while (($file = readdir($handle)) !== false) {
			if ($file == '.' || $file == '..' || $file == 'Thumbs.db') {
				continue;
			}
			$filepath = $dir == '.' ? $file : $dir . '/' . $file;
			if (is_link($filepath))
				continue;
			if (is_file($filepath))
				$files[] = $filepath;
			else if (is_dir($filepath))
				listdiraux($filepath, $files);
		}
		closedir($handle);
	} 
	
	function create_zip($files = array(), $destination = '', $overwrite = false) {
		global $c_dirname;
		$str = EPUB_FOLDER.'/'.$c_dirname;
		
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file, str_replace($str, '', $file));
			}
			
			$zip->close();
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}
	
	
	// Delete all
	function deleteAll($directory, $empty = false) {
		if(substr($directory,-1) == "/") {
			$directory = substr($directory,0,-1);
		}
	
		if(!file_exists($directory) || !is_dir($directory)) {
			return false;
		} elseif(!is_readable($directory)) {
			return false;
		} else {
			$directoryHandle = opendir($directory);
		   
			while ($contents = readdir($directoryHandle)) {
				if($contents != '.' && $contents != '..') {
					$path = $directory . "/" . $contents;
				   
					if(is_dir($path)) {
						deleteAll($path);
					} else {
						unlink($path);
					}
				}
			}
		   
			closedir($directoryHandle);
	
			if($empty == false) {
				if(!rmdir($directory)) {
					return false;
				}
			}
		   
			return true;
		}
	} 
	
	// - Removes nobr
	// - Add selfclose for meta and img
	function CleanHtml($pagename) {
		$content = '';
		global $c_dirname;
		
		$myFile = 'epub/'.$c_dirname.'/OPS/'.$pagename;
		$repimagepath = EPUB_FOLDER.'/'.$c_dirname.'/OPS/';
		$handle = fopen($myFile, 'r');
		while(!feof($handle)) {  
			$data = fgets($handle); 
			$data = str_replace($repimagepath, '', $data );
			$data = str_replace('<nobr>', '', $data );
			$data = str_replace('</nobr>', '', $data );  
			
			$res1 = preg_match_all('/<img[^>]*>/Ui', $data, $imgmatches);
			for($i=0;$i<count($imgmatches[0]);$i++) {   
				preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $imgmatches[0][$i], $filematch);
				if($filematch !== false) {
					$data = str_replace($filematch[1], 'images/'.$filematch[1], $data);
				}
			}
			$content .= $data;
			
			/*$res = preg_match_all('/<META[^>]*>/Ui', $data, $matches);
			echo '<pre>';
			print_r($matches);
			exit;
			
			
			for($i=0;$i<count($matches[0]);$i++) {   
				$data = str_replace(">"," />",$matches[0][$i]);     
			}  
			
			$res1 = preg_match_all('/<IMG[^>]*>/Ui', $data, $imgmatches);
			for($i=0;$i<count($imgmatches[0]);$i++) {   
				$data = str_replace(">"," />",$imgmatches[0][$i]);     
			}  */
			
			$content .= $data;
		} 
		
		fclose($handle);
		
		$fw = fopen($myFile, 'w');
		fwrite($fw, $content);
		fclose($fw);
		
		return true;
	}
	
	// Modify image src
	function ModifyImageSrc($pagename) {
		$content = '';
		global $c_dirname;
		
		$myFile = 'epub/'.$c_dirname.'/OPS/'.$pagename;
		$handle = fopen($myFile, 'r');
		while(!feof($handle)) {  
			$data = fgets($handle); 
			$data = str_replace('<nobr>', '', $data );
			$data = str_replace('</nobr>', '', $data );  
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

	

		

?>