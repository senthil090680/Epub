<?php
	
	include_once('includes/config.php');
	include_once('includes/functions.php');

	
	if(isset($_POST['submit']) && isset($_FILES['pdffile']) && $_FILES['pdffile']!='') {		
		
		// Getting posted values
		$outputtpe 	=	$_POST['outtype'];
		$imagetype 	=	(isset($_POST['imtype']) && $_POST['imtype']==2)?'jpg':'png';
		$resolution =	(isset($_POST['res']) && $_POST['res']!='')?$_POST['res']:DEFAULT_RESOLUTION;
		$density 	=	(isset($_POST['den']) && $_POST['den']!='')?$_POST['den']:DEFAULT_DESITY;
		$pdfname 	=	$_FILES['pdffile']['name'];
		
		// Genrate unique file name
		$c_filename =	SetUniqFileName($pdfname);
		$c_dirname  =	RemoveExtension($c_filename);
		
		$pdfpath = PDF_FOLDER.'/'.$c_filename;
		$despath = EPUB_FOLDER.'/'.$c_dirname;
		
		// Upload pdf file to pdf folder
		move_uploaded_file($_FILES['pdffile']['tmp_name'], PDF_FOLDER.'/'.$c_filename);
		
		// Creates epub folder
		if(!mkdir(EPUB_FOLDER.'/'.$c_dirname)) { echo 'ePub directory creation failed'; exit; }
		
		// Copy epub source files
		RecursiveCopy(EPUB_SOURCE_FOLDER,EPUB_FOLDER.'/'.$c_dirname);
		
		// Check folders and files
		CheckFoldersandFiles(EPUB_SOURCE_FOLDER,EPUB_FOLDER.'/'.$c_dirname);
		
		// Output type : Image
		if($outputtpe == 1) {
			// extracts image files
			exec("convert \"{$pdfpath}\" -colorspace RGB -geometry $resolution -density $density \"$despath/OPS/images/page.$imagetype\"");
			
			// fetch image files
			$imgfilecount = 0;
			$imgfiles = scandir(EPUB_FOLDER.'/'.$c_dirname.'/OPS/images');
			for($i=0;$i<count($imgfiles);$i++) {
				if($imgfiles[$i]!='.' && $imgfiles[$i]!='..' && $imgfiles[$i]!='Thumbs.db') {
					$imgfilecount++;
				}
			}
			
			// rename the image(file.png to file-0.png) if the pdf page count is 1
			if($imgfilecount == 1)
			rename($despath.'/OPS/images/page.png', $despath.'/OPS/images/page-0.png');
			
			
			// create html, opf and ncx files
			CreateHtmlFile($imgfilecount);
			CreateOpf($imgfilecount);
			CreateNcx($imgfilecount);
			
		}
		
		
		// Output type : Html
		if($outputtpe == 2) {
			// move pdf to des folder
			copy($pdfpath, $despath.'/OPS/images/page.pdf');
			
			// create html files
			system('pdftohtml -c '.$despath.'/OPS/images/page.pdf');
			
			// delete pdf and page and page index
			unlink($despath.'/OPS/images/page.pdf');
			unlink($despath.'/OPS/images/page.html');
			unlink($despath.'/OPS/images/page_ind.html');
			
			//move html files to parent directory
			$filecount=1;
			$files = scandir($despath.'/OPS/images');
			for($i=0;$i<count($files);$i++) {
				if($files[$i]!='.' && $files[$i]!='..' && $files[$i]!='Thumbs.db') {
					if(GetFileExtension($files[$i]) == 'html') {
						
						// Move to parent 
						$source = $despath.'/OPS/images/'.$files[$i];
						$destination = $despath.'/OPS/'.$files[$i];
						copy($source,$destination);
						
						// Delete source html
						unlink($source);
						
						// Clean html
						$tidy = new tidy();
						$repaired = $tidy->repairfile($destination);
						file_put_contents($destination, $repaired);
						
						CleanHtml($files[$i]);
						
						$filecount++;
					}
				}
			}
			// create opf and ncx files
			CreateOpf($filecount);
			CreateNcx($filecount);
		}
		
		
		// create zip and rename
		$files = listdir($despath);
		create_zip($files, $despath.'/'.$c_dirname.'.zip');
		rename($despath.'/'.$c_dirname.'.zip', $despath.'/'.$c_dirname.'.epub');

		
		// download epub
		$fpath = RELATIVE_PATH.'/epub/'.$c_dirname.'/'.$c_dirname.'.epub';
		echo '<br> Converted Successfully:<a href="'.$fpath.'" target="_blank">Click here to download ePub file</a>';

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
  <p><strong>ePub Convertor</strong></p>
  <table width="50%" border="0" style="border:1px solid #C0C0C0;">
    <tr>
      <td width="3%" height="39">&nbsp;</td>
      <td width="26%"><strong>Output Type :</strong></td>
      <td width="12%"><input type="radio" name="outtype" id="ty1" value="1" checked="checked" />
      Image </td>
      <td width="59%"><input type="radio" name="outtype" id="ty2" value="2">
      Html </td>
    </tr>
    <tr>
      <td height="40">&nbsp;</td>
      <td height="40"><strong>Image Type : </strong></td>
      <td><input type="radio" name="imtype" id="imty1" value="1" checked="checked" />png</td>
      <td><input type="radio" name="imtype" id="imty2" value="2"  />jpg</td>
    </tr>
    <tr>
      <td height="40">&nbsp;</td>
      <td height="40"><strong>Image Resolution:  </strong></td>
      <td colspan="2"><input name="res" type="text" value="890x640" size="8" /> 
      px</td>
    </tr>
    <tr>
      <td height="40">&nbsp;</td>
      <td height="40"><strong>Image Density :</strong></td>
      <td colspan="2"><input name="den" type="text" value="150" size="3" /> 
      dbi</td>
    </tr>
    <tr>
      <td height="40">&nbsp;</td>
      <td height="40"><strong>Upload pdf : </strong></td>
      <td colspan="2"><input type="file" name="pdffile" /></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td colspan="2"><input type="submit" value="Submit" name="submit" /></td>
    </tr>
    <tr>
      <td colspan="4"><span id="status"></span></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
