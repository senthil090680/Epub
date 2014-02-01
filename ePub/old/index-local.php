<?php
	if(isset($_POST['submit']) && isset($_FILES['pdffile']) && $_FILES['pdffile']!='') {		
		// intialize values
		$k=0;
		
		// getting posted values
		$imagetype = (isset($_POST['imtype']) && $_POST['imtype']==1)?'jpg':'png';
		$resolution = (isset($_POST['res']) && $_POST['res']!='')?$_POST['res']:'890x640';
		$density = (isset($_POST['den']) && $_POST['den']!='')?$_POST['den']:'72';
		
		$fname = $_FILES['pdffile']['name'];
		move_uploaded_file($_FILES['pdffile']['tmp_name'], "pdf/".$fname);
		
		// creates epub folder
		$dirname = RemoveExtension($fname);
		if(is_dir('epub/'.$dirname)) {
			deleteAll('epub/'.$dirname);
		} 
		mkdir('epub/'.$dirname, 0777);
		
		// copy epub source files
		RecursiveCopy('epubsource','epub/'.$dirname);
		
		if($_POST['type']==1) {
			// extracts image files
			$strPDF = "pdf/".$fname;
			exec("convert \"{$strPDF}\" -colorspace RGB -geometry $resolution -density $density \"epub/$dirname/OPS/images/file.$imagetype\"");
			
			// create HTML files
			$files = scandir('epub/'.$dirname.'/OPS/images');
			for($i=0;$i<count($files);$i++) {
				if($files[$i]!='.' && $files[$i]!='..' && $files[$i]!='Thumbs.db') {
					CreateHtmlFile($k);
					$k++;
				}
			}
			
			
			// rename image file for single page pdf
			if($k==1) {
				rename('epub/'.$dirname.'/OPS/images/file.png', 'epub/'.$dirname.'/OPS/images/file-0.png');
			}
			
			// create opf and ncx files
			CreateOpf($k);
			CreateNcx($k);
			
			// create zip and rename
			$targetdir = 'epub/'.$dirname;
			$files = listdir($targetdir);
			create_zip($files, $targetdir.'/'.$dirname.'.zip');
			rename($targetdir.'/'.$dirname.'.zip', $targetdir.'/'.$dirname.'.epub');
			
			// download epub
			$finalepub = $targetdir.'/'.$dirname.'.epub';
			header("Location: $finalepub");
			exit;
		} else {
		
			// create temp directory
			//$rand = md5(microtime().rand(0,999999));
			//$temdir = substr($rand, 1, 5);
			
			$imagetype = 'png';
			$temdir = 'images';
			$temdest = $temdir.'/page.pdf';
			mkdir($temdir);
			
			//copy pdf file to temp dir
			copy('pdf/'.$fname, $temdest);
			
			// create image and html files
			system('pdftohtml -c '.$temdest);
			
			//Copy image and html files to epub directory
			$files = scandir($temdir);
			for($i=0;$i<count($files);$i++) {
				if($files[$i]!='.' && $files[$i]!='..' && $files[$i]!='Thumbs.db') {
					if(GetFileExtension($files[$i]) == 'html') {
						$epubloca = "epub/".$dirname."/OPS/".$files[$i];
						$k++;
					}
					
					if(GetFileExtension($files[$i]) == 'png') {
						$epubloca = "epub/".$dirname."/OPS/images/".$files[$i];
					}
					
					copy($temdir.'/'.$files[$i], $epubloca);
					
					//Clean html
					if(GetFileExtension($files[$i]) == 'html') {
						$tidy = new tidy();
						$repaired = $tidy->repairfile($epubloca);
						file_put_contents($epubloca, $repaired);
					}
				}
			}
		
			// delete temp directory
			deleteAll($temdir);
			
			// unlink unwanted html
			unlink('epub/'.$dirname.'/OPS/page.html');
			unlink('epub/'.$dirname.'/OPS/page_ind.html');
			
			//create title page
			CreateHtmlFile(0, 'page001');
			
			// create opf and ncx files
			CreateOpf($k-1); // omit the page.html and page_ind.html
			CreateNcx($k-1);
			
			
			// create zip and rename
			$targetdir = 'epub/'.$dirname;
			$files = listdir($targetdir);
			create_zip($files, $targetdir.'/'.$dirname.'.zip');
			rename($targetdir.'/'.$dirname.'.zip', $targetdir.'/'.$dirname.'.epub');
			
			// download epub
			$finalepub = $targetdir.'/'.$dirname.'.epub';
			header("Location: $finalepub");
			exit;
			
		}
	}
	
	// Function to create html files
	function CreateHtmlFile($c, $n='') {
		global $dirname;
		global $imagetype;
	
		if($c == 0) $pagname = 'titlepage';
		else $pagname = 'page-'.$c;
		
		$name = ($n == '')?'file-'.$c:$n;
	
		$content = "<html xmlns='http://www.w3.org/1999/xhtml' >\n"
						. "<head>\n"
							. "<title>Page $c</title>\n"
							. "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>\n"
						. "</head>\n"
						. "<body>\n"
							. "<div align='center' >\n"
								. "<img src='images/$name.$imagetype'/>\n"
							. "</div>\n"
						. "</body>\n"
						. "</html>";
						
		$file = "epub/$dirname/OPS/".$pagname.".html";
		$fr = fopen($file,"w");
		fwrite($fr, $content);
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
	
	// Create Opf content
	function CreateOpf($k) {
		global $dirname;
		global $imagetype;
		
		$contentmanifest = '';
		for($p=1;$p<$k;$p++){
			$contentmanifest .= "<item id='page-$p' href='page-$p.html' media-type='application/xhtml+xml'/>\n";
		}
		
		$contentspine = '';
		for($q=1;$q<$k;$q++)	 {
			$contentspine .= "<itemref idref='page-$q' linear='yes'/>\n";
		}
		
		$contentopf = "<?xml version='1.0' encoding='UTF-8'?>\n"
					. "<package xmlns='http://www.idpf.org/2007/opf' unique-identifier='EPB-UUID' version='2.0'>\n"
						. "<metadata xmlns:opf='http://www.idpf.org/2007/opf' xmlns:dc='http://purl.org/dc/elements/1.1/'>\n"
							. "<dc:title>$dirname</dc:title>\n"
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
		$contentopf .=	"<item id='titlepage' href='titlepage.html' media-type='application/xhtml+xml'/>\n"
						. "<item href='images/file-0.$imagetype' id='cover' media-type='image/png'/>\n"
						. "<item id='ncx' href='fb.ncx' media-type='application/x-dtbncx+xml'/>\n"
						. "</manifest>\n"
						. "<spine toc='ncx'>\n"
						. "<itemref idref='titlepage' linear='yes'/>\n";
		$contentopf .= 	$contentspine;
		$contentopf .=	"</spine>\n"
						. "</package>";
	
		
		
		$file = "epub/$dirname/OPS/fb.opf";
		$fr = fopen($file,"w");
		fwrite($fr, $contentopf);
		
		return true;
	
	}
	
	// Create Ncx content
	function CreateNcx($k) {
		global $dirname;
		$contentnmap = '';
		for($q=1;$q<$k;$q++){
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
							. "<docTitle><text>$dirname</text></docTitle>\n"
							. "<navMap>\n";
		
		$contentncx .= $contentnmap;
		
		$contentncx .= 	"</navMap>\n"
						. "</ncx>";
		
		
		$file = "epub/$dirname/OPS/fb.ncx";
		$fr = fopen($file,"w");
		fwrite($fr, $contentncx);
						
		return true;
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
		global $dirname;
		$str = 'epub/'.$dirname;
		
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
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ePub conversion</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
  <p><strong>ePub Covertor</strong></p>
  <table width="50%" border="0" bgcolor="#F5F5F5" style="border:1px solid #C0C0C0;">
    <tr>
      <td width="23%" height="39"><strong>Output Type :</strong> </td>
      <td width="12%"><input type="radio" name="type" id="ty1" value="1" checked="checked" />
        Image </td>
      <td width="65%"><input type="radio" name="type" id="ty2" value="2">
        Html </td>
    </tr>
    <tr>
      <td height="40"><strong>Image Type : </strong></td>
      <td><input type="radio" name="imtype" id="imty1" value="1" checked="checked" />jpg</td>
      <td><input type="radio" name="imtype" id="imty2" value="2"  />png</td>
    </tr>
    <tr>
      <td height="40"><strong>Image Resolution : </strong></td>
      <td colspan="2"><input name="res" type="text" value="890x640" size="8" /> 
      px</td>
    </tr>
    <tr>
      <td height="40"><strong>Image Density : </strong></td>
      <td colspan="2"><input name="den" type="text" value="72" size="3" /> 
      dbi</td>
    </tr>
    <tr>
      <td height="40"><strong>Upload pdf : </strong></td>
      <td colspan="2"><input type="file" name="pdffile" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><input type="submit" value="Submit" name="submit" /></td>
    </tr>
    <tr>
      <td colspan="3"><span id="status"></span></td>
    </tr>
  </table>
  <p>&nbsp;  </p>
</form>
</body>
</html>
