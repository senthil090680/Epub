<?php
//ob_start();
      
        //This function will destroy all the directories and files in the given directory
        function destroy($dir) {
            $mydir = opendir($dir);
            while(false !== ($file = readdir($mydir))) {
                if($file != "." && $file != "..") {
                    chmod($dir.$file, 0777);
                    if(is_dir($dir.$file)) {
                        chdir('.');
                        destroy($dir.$file.'/');
                        rmdir($dir.$file) or DIE("couldn't delete $dir$file<br />");
                    }
                    else
                        unlink($dir.$file) or DIE("couldn't delete $dir$file<br />");
                }
            }
            closedir($mydir);
        }

        //destroy(ACTIVITY_TEMP."/");
        
	//OPENING A DATABASE CONNECTION
	function dbconnection ($dbname = '',$servername = '',$username = '',$password = '') {
                
		$con = mysql_connect($servername,$username,$password,false,65536) or die(mysql_error().' DB NOT CONNECTED, CHECK DATABASE SETTINGS');
		mysql_select_db($dbname,$con) or die(mysql_error().' CHECK DATABASE SETTINGS');
		return 'connected';
	}

	function script_redirect ($url) {
		//echo RELATIVE_PATH . "/". $url;
		header("location : epub_activity.php");			
	}

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
	function CreateOpf() {
		global $c_dirname;
		global $booktitle;
                global $updateopfmanifest;
                global $updateopfspine;

		$contentopf = "<?xml version='1.0' encoding='UTF-8'?>\n"
					. "<package xmlns='http://www.idpf.org/2007/opf' unique-identifier='EPB-UUID' version='2.0'>\n"
						. "<metadata xmlns:opf='http://www.idpf.org/2007/opf' xmlns:dc='http://purl.org/dc/elements/1.1/'>\n"
							. "<dc:title>$booktitle</dc:title>\n"
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
							. "<meta name='cover' content='coverimage' />\n"
						. "</metadata>\n"
						. "<manifest>\n";
                $contentopf .=	$updateopfmanifest;
		$contentopf .=	"<item id='coverimage' href='images/cover.jpg' media-type='image/jpeg'/>\n";
		$contentopf .=	"<item id='ncx' href='fb.ncx' media-type='application/x-dtbncx+xml'/>\n"
						. "</manifest>\n"
						. "<spine toc='ncx'>\n";
                $contentopf .= 	$updateopfspine;
		$contentopf .=	"</spine>\n"
						. "</package>";
	
		
		
		$file = EPUB_FOLDER.'/'.$c_dirname."/OPS/fb.opf";
		$fr = fopen($file,"w");
		fwrite($fr, $contentopf);		
		return true;	
	}
	
	// Create Ncx content
	function CreateNcx() {
		global $c_dirname;
		global $booktitle;
                global $updatencxmap;
	
		$contentncx = "<?xml version='1.0' encoding='UTF-8'?>\n"
						. "<!DOCTYPE ncx PUBLIC '-//NISO//DTD ncx 2005-1//EN' 'http://www.daisy.org/z3986/2005/ncx-2005-1.dtd'>\n"
						. "<ncx xmlns='http://www.daisy.org/z3986/2005/ncx/' version='2005-1'>\n"
							. "<head>\n"
								. "<meta name='dtb:uid' content='123456'/>\n"
								. "<meta name='dtb:depth' content='1'/>\n"
								. "<meta name='dtb:totalPageCount' content='0'/>\n"
								. "<meta name='dtb:maxPageNumber' content='0'/>\n"
							. "</head>\n"
							. "<docTitle><text>$booktitle</text></docTitle>\n"
							. "<navMap>\n";		
                $contentncx .= $updatencxmap;
		
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
		
		if(!isset($path_parts['filename'])){
			$path_parts['filename'] = substr($path_parts['basename'], 0,strpos($path_parts['basename'],'.'));
		}
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
		global $epubFolder;
		$str = EPUB_FOLDER.'/'.$epubFolder;
		
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
	
	
        function unzipFile ($actpath = '',$actnewpath = '',$name = '') {
            $zip                =               new ZipArchive;
            $res                =               $zip->open($actpath."/".$name);
            if ($res === TRUE) {
                $zip->extractTo($actnewpath."/");
                $zip->close();
                return 'ok';
            } else {
                return 'failed';
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
        
        
        // Delete all, except zip file
	function deleteAllExcept($directory, $empty = false) {
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
                            $fileexten      =       GetFileExtension($contents); 
				if($contents != '.' && $contents != '..' && $fileexten != 'zip') {
					$path = $directory . "/" . $contents;
                                        
					if(is_dir($path)) {
						deleteAllExcept($path);
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
			$data = str_replace($repimagepath, '', $data);
			$data = str_replace('<nobr>', '', $data );
			$data = str_replace('</nobr>', '', $data );
			
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
        
        // Move manifest files from source to dest
	function RecursiveMove($source, $dest, $diffDir = ''){
		$sourceHandle = opendir($source);
		if(!$diffDir)
			 $diffDir = $source;
	   
		if($diffDir!=$source)
			mkdir($dest . '/' . $diffDir);
		else
			$diffDir = '';
	
		while($res = readdir($sourceHandle)){
                    $getExt             =   GetFileExtension($res);
			if($res == '.' || $res == '..' || $getExt == 'zip')
				continue;
		   
			if(is_dir($source . '/' . $res)){
			   RecursiveCopy($source . '/' . $res, $dest, $diffDir . '/' . $res);
			} else {
                            move_uploaded_file($source . '/' . $res, $dest . '/' . $diffDir . '/' . $res);
			}
		}
	}
                        
        function deleteDir($path)
        {
            return is_file($path) ?
                    @unlink($path) :
                    array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
        }       
?>
