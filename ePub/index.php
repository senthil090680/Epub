<?php
session_start();
ob_start();
//ob_end_flush();
include_once('includes/config.php');
include_once('includes/functions.php');

//echo phpinfo();
//error_reporting(E_ALL);
error_reporting(0);

ini_set("display_errors",true);

//ini_set('upload_max_filesize','30M');
//ini_set('post_max_size','30M');
//ini_set('output_buffering','off');

//ini_get('max_execution_time');
//ini_get('max_input_time');

//echo ini_get('upload_max_filesize');
//echo ini_get('display_errors');
//echo ini_get('post_max_size');
//echo ini_get('max_execution_time');
//echo ini_get('output_buffering');
//echo phpinfo();

//DB INITIALIZATION
$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$presetquery                            =		"SELECT presetId,presetname FROM ".PRESET_TABLE."";
$preresquery                            =		mysql_query($presetquery) or die(mysql_error());
$prenor					=		mysql_num_rows($preresquery);

/*echo "<pre>";
print_r($_POST);
echo "</pre>";
	
echo "<pre>";
print_r($_FILES['pdffile']);
echo "</pre>";

//exit(0);*/


if (isset($_FILES['pdffile']['name']) && $_FILES['pdffile']['name'] != '') {
    
/*echo "<pre>";
print_r($_POST);
echo "</pre>";
	
echo "<pre>";
print_r($_FILES['pdffile']);
echo "</pre>";

//exit(0);*/

    
    //flushstatus('load', 'Started...');

	/*echo "<pre>";
	print_r($_POST);
	echo "</pre>";

	exit;*/
    // Getting posted values
    $outputtpe				=		$_POST['outtype'];
    $imagetype				=		(isset($_POST['imtype']) && $_POST['imtype'] == 2) ? 'jpg' : 'png';
    $resolution				=		(isset($_POST['res']) && $_POST['res'] != '') ? $_POST['res'] : DEFAULT_RESOLUTION;
    $density				=		(isset($_POST['den']) && $_POST['den'] != '') ? $_POST['den'] : DEFAULT_DESITY;
    $pdfname				=		$_FILES['pdffile']['name'];
     $fontname                           =		(isset($_FILES['upfonts']['name']) && $_FILES['upfonts']['name'] != '') ? $_FILES['upfonts']['name'] : '';
    $coverimageup			=		(isset($_FILES['coverimage']['name']) && $_FILES['coverimage']['name'] != '') ? $_FILES['coverimage']['name'] : '';
	
	//echo $_FILES['coverimage']['name']."werewe";
	/*echo "<pre>";
	print_r($_FILES);
	echo "</pre>";

	exit;*/

	$fixlay					=		$_POST['fixlay'];
	$orilock				=		$_POST['oriloc'];
	$supdev					=		implode(',',$_POST['supp']);
	$specfon				=		$_POST['spefon'];
	$openspread				=		$_POST['openspr'];
	$interac				=		$_POST['interac'];
	$dpires					=		(isset($_POST['dpires']) && $_POST['dpires'] != '') ? $_POST['dpires'] : DEFAULT_DPI;
	$booktitle				=		$_POST['booktitle'];
	$presetset				=		(isset($_POST['presetset']) && $_POST['presetset'] != '') ? $_POST['presetset'] : 0;
	$versiontype                            =		$_POST['versiontype'];
	$presetname				=		$_POST['presetname'];
	$presetcheck                            =		$_POST['presetcheck'];
		
	/*echo "<pre>";
	print_r($supdev);
	echo "</pre>";
	
	exit;*/
	
	/*echo "<pre>";
	print_r($_FILES);
	echo "</pre>";
	
	exit;*/
    //flushstatus('load', 'Creating epub directory...');
		
	if($presetname != '' && $presetcheck != '') {
		$selquery			=		"SELECT presetname FROM ". PRESET_TABLE ." WHERE presetname = '$presetname' ";
		$selresquery                    =		mysql_query($selquery) or die(mysql_error());
		$selnor				=		mysql_num_rows($selresquery);

		if($selnor == 0 ) {
			$insquery		=		"INSERT INTO ". PRESET_TABLE ." (presetname,versiontype,outputtype,dpires,supdev,fixlay,openspread,interlay,fontset,oriloc) values ('$presetname','$versiontype','$outputtpe','$dpires','$supdev','$fixlay','$openspread','$interac','$specfon','$orilock')";
			$insresquery            =		mysql_query($insquery) or die(mysql_error());		
		} else {
			$queryoutput		=	'error';
		}
	}		
	
    // Generate unique file name for epub html files
    $c_filename                                 =		SetUniqFileName($pdfname);
    $c_dirname                                  =		RemoveExtension($c_filename);
	
    $pdfpath                                    =		PDF_FOLDER . '/' . $c_filename;
    $despath                                    =		EPUB_FOLDER . '/' . $c_dirname;
    
        // Upload pdf file to pdf folder    
	if(move_uploaded_file($_FILES['pdffile']['tmp_name'], PDF_FOLDER . '/' . $c_filename)) {
		//echo "PDF MOVED"; exit(0);
	} else {
		echo "PDF FILE NOT MOVED TO LOCATION"; exit(0);
	}
                                
	$selsetquery                            =		"SELECT bookTitle FROM ". EPUB_SET ." WHERE bookTitle = '$booktitle' ";
	$selressetquery                         =		mysql_query($selsetquery) or die(mysql_error());
	$selsetnor                              =		mysql_num_rows($selressetquery);

	if($selsetnor == 0 ) {
		$inssetquery                    =		"INSERT INTO ". EPUB_SET ." (presetName,presetSet,epubVersion,outputType,bookTitle,coverImage,resol,supportDevice,fixedLay,openSpread,interActive,specificFont,oriLock,epubFolder,pdfFile) values ('$presetname','$presetset','$versiontype','$outputtpe','$booktitle','$coverimageup','$dpires','$supdev','$fixlay','$openspread','$interac','$specfon','$orilock','$c_dirname','$pdfname')";
		$insressetquery                 =		mysql_query($inssetquery) or die(mysql_error());
		$insert_id                      =		base64_encode(mysql_insert_id());
                $_SESSION['insert_id']          =               $insert_id;
                //echo "good";
                //exit;
	} else {
			echo 'Book title already exists'; exit(0);
	}

    // Creates epub	folder
    if (!mkdir(EPUB_FOLDER . '/' . $c_dirname)) {
        echo 'ePub directory creation failed'; exit(0);
    }

    // Copy	epub source	files
    RecursiveCopy(EPUB_SOURCE_FOLDER, EPUB_FOLDER . '/' . $c_dirname);

    //flushstatus('load', 'Checking resources...');

    // Check folders and files
    CheckFoldersandFiles(EPUB_SOURCE_FOLDER, EPUB_FOLDER . '/' . $c_dirname);


    // Output type : Image
    if ($outputtpe == 1) {

        //flushstatus('load', 'Image conversion started...');

        // extracts	image files
        //exec("convert	\"{$pdfpath}\" -colorspace RGB -geometry $resolution -density $density \"$despath/OPS/images/page.$imagetype\"");
        exec("convert \"{$pdfpath}\" -colorspace RGB -geometry $resolution -density	$density \"$despath/OPS/images/page.$imagetype\"");

        // fetch image files
        $imgfilecount = 0;
        $imgfiles = scandir(EPUB_FOLDER . '/' . $c_dirname . '/OPS/images');
        for ($i = 0; $i < count($imgfiles); $i++) {
            if ($imgfiles[$i] != '.' && $imgfiles[$i] != '..' && $imgfiles[$i] != 'Thumbs.db') {
                $imgfilecount++;
            }
        }

        // rename the image(file.png to	file-0.png)	if the pdf page	count is 1
        if ($imgfilecount == 1)
            rename($despath . '/OPS/images/page.png', $despath . '/OPS/images/page-0.png');

        //flushstatus('load', 'Adding	manifest files...');

        // create html,	opf	and	ncx	files
        CreateHtmlFile($imgfilecount);
        CreateOpf($imgfilecount);
        CreateNcx($imgfilecount);
    }

    // Output type : Html
    if ($outputtpe == 2) {
        // move	pdf	to des folder
        copy($pdfpath, $despath . '/OPS/images/page.pdf');

        // create html files (OLD FORM OF CONVERSION)s
        //system('pdftohtml	-c '.$despath.'/OPS/images/page.pdf');

        $outputone                              =		$despath . '/OPS/images/';
        $outone                                 =		$despath . '/OPS/';
		$xmlfol				=		$despath . '/'.META_FILE;

        //PAYWARE TO CONVERT PDF TO	HTML FILES
        //passthru("pdf2html\pdf2html.exe -$ 54XSD4234455P7TWET28 $force $outone");
        //system("pdf2html\pdf2html.exe	-$ 54XSD4234455P7TWET28	$force $outone");

        exec("pdf2html\pdf2html.exe	-$ 54XSD4234455P7TWET28	-r $dpires $pdfpath $outputone");
		
        // delete pdf and page and page	index
        unlink($despath . '/OPS/images/page.pdf');

        //NOT NEEDED FOR NEW FILES
        /* unlink($despath.'/OPS/images/page.html');
          unlink($despath.'/OPS/images/page_ind.html'); */

        //move html	files to parent	directory
        $filecount = 1;
        $files = scandir($despath . '/OPS/images');
        for ($i = 0; $i < count($files); $i++) {
            $imageexten = GetFileExtension($files[$i]);
            if ($imageexten == 'gif') {
                unlink($despath . '/OPS/images/' . $files[$i]);
            } else {
                
            }
            if ($files[$i] != '.' && $files[$i] != '..' && $files[$i] != 'Thumbs.db') {
                if (GetFileExtension($files[$i]) == 'htm') {

                    // Move to parent
                    $source = $despath . '/OPS/images/' . $files[$i];
                    $destination = $despath . '/OPS/' . $files[$i] . "l";
                    copy($source, $destination);

                    // Delete source html
                    unlink($source);

                    // Clean html (Not necessary, now below Loop will do this)
                    /* $tidy	= new tidy();
                      $repaired =	$tidy->repairfile($destination);
                      file_put_contents($destination,	$repaired);

                      CleanHtml($files[$i]); (Not necessary below	Loop will do this)
                     */

                    $startfile_one = substr($files[$i], 0, 4);
                    $fileexten_one = GetFileExtension($files[$i]);
                    if ($startfile_one == 'pg_0' && $fileexten_one == 'htm') {
                        $filecount++;
                    } else {
                        unlink($destination);
                    }
                }
            }
        }		
		
		$upfilecount                    =		$filecount - 1;
		$upsetquery                     =		"UPDATE ". EPUB_SET ." SET pdfPages = '$upfilecount' WHERE bookTitle = '$booktitle'";
		$upressetquery                  =		mysql_query($upsetquery) or die(mysql_error());

        // create opf and ncx files
        CreateOpf($filecount);
        CreateNcx($filecount);
    }

    //flushstatus('load', 'Packaging epub...');

	if($fixlay == 'fixtrue') {
		$fixlayout		=	"true";
		
	} else {
		//$fixlayout	=	"false";
		$fixlayout		=	"true";
	}

	if($specfon == 'spetrue') {
		$specialfon	=	"true";
	} else {
		$specialfon	=	"false";	
	}

	if($openspread == 'opetrue') {
		$opensp	=	"true";
	} else {
		$opensp	=	"false";
	}

	if($interac == 'inttrue') {
		$intact	=	"true";
	} else {
		$intact	=	"false";
	}

	unlink($xmlfol);

	$xmlcont		=	'<display_options>
	<platform name="'.$supdev.'">
		<option name="fixed-layout">'.$fixlayout.'</option>
		<option name="specified-fonts">'.$specialfon.'</option>
		<option name="open-to-spread">'.$opensp.'</option>
		<option name="orientation-lock">'.$orilock.'</option>
		<option name="interactive">'.$intact.'</option>
	</platform>
</display_options>';

	$xmlfile		=	fopen($xmlfol,'w');
	fwrite($xmlfile,$xmlcont);
	fclose($xmlfile);

    $handle = opendir($outone);
    $loopcount = 0;
    while ($name = readdir($handle)) {

        $fileexten = GetFileExtension($name);
        $startfile = substr($name, 0, 4);

        if ($startfile == 'pg_0' && $fileexten == 'html') {

            $filecontent = file_get_contents($outone . $name);

            $image_path = RemoveExtension($outone . $name);
            $imagedata = getimagesize($outputone . $image_path . ".png");
            if (($loopcount == 0) && ($coverimageup == '')) {				
                $loopcount++;
                if (copy($outputone . $image_path . ".png", $outputone . "cover.jpg")) {
                    //die("good");
                } 
            } else if(($loopcount == 0) && ($coverimageup != '')) {
				$loopcount++;
				if(move_uploaded_file($_FILES['coverimage']['tmp_name'], $outputone . "cover.jpg")) {
					//die("bad");
				}
			}
            $width = $imagedata[0];
            $height = $imagedata[1];
            if (preg_match("#<title>(.+)<\/title>#iU", $filecontent, $match)) {
                $title = $match[1];
            } else {
                $title = '';
            }
            $filecontent = preg_replace('#</nobr(.*?)>(.*?)</font></div>#is', '', $filecontent);
            $filecontent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $filecontent);
            $filecontent = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $filecontent);
            $filecontent = preg_replace('#<!DOCTYPE(.*?)>(.*?)#is', '', $filecontent);
            $filecontent = preg_replace('#<head(.*?)>(.*?)</head>#is', '', $filecontent);
            $filecontent = preg_replace('#<!--	saved(.*?)>(.*?)#is', '', $filecontent);
            $filecontent = str_replace('<body vlink="#FFFFFF" link="#FFFFFF" bgcolor="#ffffff">', '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>' . $title . '</title>
<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8"/>
<meta name="viewport" content="width=' . $width . ', height=' . $height . '"/>
<link rel="stylesheet" href="css/c1.css" type="text/css"/>
</head>
<body vlink="#FFFFFF" link="#FFFFFF" bgcolor="#ffffff">
<div style="width:'.$width.'px; margin:0 auto; position:relative;">', $filecontent);

            $filecontent = str_replace('ALT=""', 'ALT="" /', $filecontent);
            //$filecontent = str_replace('src="', 'src="'.RELATIVE_PATH.'/'.$outone.'images/', $filecontent);
            $filecontent = str_replace('src="', 'src="images/', $filecontent);
            $filecontent = str_replace('<html>', '', $filecontent);
            $filecontent = str_replace('<nobr>', '', $filecontent);
            $filecontent = str_replace(';l', 'px;l', $filecontent);
			$filecontent = str_replace('</body>', '</div>
</body>', $filecontent);

            if (preg_match_all("#;left:(.+)\"#iU", $filecontent, $matchpx)) {
                $matchk = 0;
                foreach ($matchpx as $matfirst) {
                    if ($matchk == 0) {
                        foreach ($matfirst as $matsec) {
                            $exactstring = substr_replace($matsec, "", -1);
                            $leftpx = $exactstring . "px;\"";
                            $filecontent = str_replace($matsec, $leftpx, $filecontent);
                        }
                    }
                    $matchk++;
                }
            }

            file_put_contents($outone . $name, $filecontent);

            $filewhite = $outone . $name;

            $linewhite = '';

            $arraywhite = file($filewhite);

            $countwhite = count(file($filewhite));

            $bodybefore = $countwhite - 4;

            $bodyafter = $bodybefore - 1;

            $linewhite = array('0', '1', '2', '3', '13', '14', '15', $bodybefore, $bodyafter);

            foreach ($linewhite as $lineone) {
                unset($arraywhite[$lineone]);
            }
            $fp_white = fopen($filewhite, 'w+');

            foreach ($arraywhite as $line_white)
                fwrite($fp_white, $line_white);

            fclose($fp_white);
			
            //echo '<a href="' . $outone . $name . '" target="_blank">Click	here to	download file</a>';
            //exit;
        }
    }
	
	//exit;	
	if($fontname != '') {              
            // Generate unique file name for font zip file
            $c_fontname                                 =		SetUniqFileName($fontname);
            $c_fontdirname                              =		RemoveExtension($c_fontname);
            // Create a Font folder
            $fontpath                                   =		$despath . '/OPS/'. FONT_FOLDER . '/' . $c_fontdirname;
            if (!mkdir($fontpath)) {
                echo 'Font directory creation failed'; exit(0);
            }
            // Upload font zip file to the newly created font folder and unzip the file
            if(move_uploaded_file($_FILES['upfonts']['tmp_name'], $fontpath . '/' .$c_fontname))  {
                unzipFile($fontpath,$fontpath,$c_fontname);
                //echo "FONT ZIP MOVED";
                //die("FONT ZIP MOVED");
            } else {
                echo "FONT ZIP FILE NOT MOVED TO LOCATION"; exit(0);
            }
            
            $fontToBeAdded                              =               "";
            $openFontFolder                             =               opendir($fontpath);
            while($FontNames    =   readdir($openFontFolder)) {
                $notZip         =   GetFileExtension($FontNames);
                //($FontNames);
                if($FontNames != '.' && $FontNames != '..' && $notZip != 'zip') {
                    //echo $FontNames;
                    
                    //Add Uploaded Font Style to the CSS File		
                    $remExtFont                         =      RemoveExtension($FontNames);
                    $fontToBeAdded                      .=	    "
@font-face {
    font-family: ". $remExtFont. ";
    font-weight: normal;
    font-style: italic;
    src: url(../fonts/".$c_fontdirname."/".$FontNames.");
}
";
                }
            }
            $csspath			=		EPUB_FOLDER . '/' . $c_dirname . '/OPS/css/c1.css';
            $fwritecss			=		fopen($csspath, 'a+');
            $upsetquery                 =		"UPDATE ". EPUB_SET ." SET fontName = '$remExtFont' WHERE bookTitle = '$booktitle'";
            $upressetquery	=		mysql_query($upsetquery) or die(mysql_error());
            if(fwrite($fwritecss, $fontToBeAdded)) {
                    //die("File written");
            } else {
                    echo "Uploaded TTF File Not Written in the CSS File"; exit(0);
            }
            fclose($fwritecss);
            //chmod($csspath, 0644);
            //exit;		
	}        
        //$booktitle			=		base64_encode($booktitle);
        //header("location:epub_activity.php?tate=$booktitle&insid=$insert_id");
        echo 'greatone';
        exit(0);
}
require_once("header.php");
?>
    <div class="titleStrip">Epub Process</div>
    <!-- Steps -->
    <div class="steps"> <img src="images/step1_active.jpg" width="301" height="69" alt="Step 1" title="Step 1"/><img src="images/step1_spliter1.jpg" /><img src="images/step2.jpg" alt="Step 2" title="Step 2" /><img src="images/step3_spliter1.jpg" /><img src="images/step3.jpg" width="302" height="69" alt="Step 4" title="Step 4"/></div>
    
    
    
    <div class="ePubFrmBox">
      <form id="form1" name="form1" method="post" enctype="multipart/form-data">
        <span style="float:right;">(*) are Mandatory Fields</span>
        <h2 class="presetdrop">Select Preset</h2>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">
          <tr class="presetdrop">
            <td width="13%" nowrap="nowrap">Preset Settings:</td>
            <td width="87%"><label>
              <select name="presetset" id="presetset" class="selectField selectFieldWidth1" onchange="presetselect();">
                <option value='' class="inputGrayText">Select</option>
				<?php if($prenor > 0) { 
					while($row		=	mysql_fetch_object($preresquery)) { ?>
					<option value="<?php echo $row->presetId; ?>" ><?php echo ucwords(strtolower($row->presetname)); ?></option>
					<?php }} ?>
              </select>
              </label></td>
          </tr>
        </table>
        <h2>Document Settings</h2>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">
          <tr>
            <td width="13%">EPub Version * </td>
            <td width="87%"><label>
              <select name="versiontype" id="versiontype" class="selectField selectFieldWidth1">
                <option value='' class="inputGrayText">Select</option>
				<option value="version2">Version 2.0</option>
				<option value="version3">Version 3.0</option>
              </select>
              </label></td>
          </tr>
          <tr>
            <td>Output Type * </td>
            <td><input name="outtype" id="outtype" type="radio" value="2" />
              HTML <!--&nbsp;&nbsp;
              <input name="outtype" id="outtype" type="radio" value="1" />
              &nbsp;Image--> </td>
          </tr>
          <tr>
            <td>Book Title * </td>
            <td><label>
              <input type="text" name="booktitle" id="booktitle" class="inputField BookTitleInput" maxlength="50"/>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cover Image:&nbsp;&nbsp;
              <span id="uploadFile_div"><input type="file" name="coverimage" id="coverimage" onchange="checkimage();"/></span>
              </label></td>
          </tr>
          <tr>
            <td>Resolution:</td>
            <td>
				<!--<select name="select" class="selectField ">
                <option class="inputGrayText">Select</option>
              </select>&nbsp;&nbsp;-->
              
              <input type="text" name="dpires" id="dpires" class="inputField" maxlength="3"/>
              <span class="grayText">(Dbi Default 100) </span> </td>
          </tr>
        </table>
        <h2>Layout Settings</h2>
        <table width="100%" border="0" cellspacing="0" cellpadding="10"  class="frmTbl">
          <tr>
            <th align="left" class="vborder pLeft10"> Supporting Device * 
              </td>
			<th align="left" class="vborder hideother">Select Layout:
              </td>
            <th align="left" class="vborder pLeft10 hideother">Font Settings *
              </td>            
            <th align="left" class="pLeft10 hideother">Orientation Type *
              </td>
          </tr>
          <tr>
            <td valign="top" class="vborder pLeft10"><label>
              <select name="supp[]" id="supp" size="1" multiple="multiple" class="supportDivice">
                <option value="nodev">-Select-</option>
				<option value="ipad">IPAD</option>
				<option value="kindle">KINDLE</option>
				<option value="sony">Sony</option>
				<option value="mobl21">Mobl21</option>
              </select>
              </label></td>
			<td valign="top" class="vborder hideother"><input type="checkbox" name="fixlay" id="fixlay" value="fixtrue" />
              Fixed-Layout <br />
              <input type="checkbox" name="openspr" id="openspr" value="opetrue" />
              Open-to-spread<br />
              <input type="checkbox" name="interac" id="interac" value="inttrue" />
              Interactive</td>
            <td valign="top" class="vborder pLeft10 hideother"><input class="spfon" type="radio" name="spefon" id="spefon" value="spefal" />
              Default Font<br />
              <input type="radio" name="spefon" id="spefon" class="spfon" value="spetrue" />
              Custom Font<br />
              <br />
              <input type="file" name="upfonts" id="upfonts" style="display:none;"/></td>
            
            <td valign="top" class="pLeft10 hideother"><input name="oriloc" id="oriloc" type="radio" value="portrait-only" />
              Portrait<br />
              <input name="oriloc" id="oriloc" type="radio" value="landscape-only" />
              Landscape<br />
              <input name="oriloc" id="oriloc" type="radio" value="none" />
             Both</td>
          </tr>
        </table>
		<h2>Upload File</h2>
		<table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">
          <tr>
            <td width="10%">Select File *</td>
            <td width="90%"><label></label>
              <span class="vborder pLeft10" id="upload_pdf">
              <input type="file" name="pdffile" id="pdffile" onchange="checkpdf();"/>
            </span></td>
          </tr>
        </table>
		<div class="saveBox" id="presetbox" >
		<input name="presetcheck" id="presetcheck" type="checkbox" value="presetval" />
		&nbsp;Save Settings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="presetbox1" style="display:none;">Preset Name:</span>&nbsp;&nbsp; 
		<label>
		<input type="text" name="presetname" id="presetname" style="display:none;" class="inputField" maxlength="20" />
		</label>
</div>
<br />
<input type="image" src="images/btn_submit.jpg" border="0" onclick="return pdfcovert();" />
<!--<a href="javascript:void(0);" onclick="pdfcovert();"><img src="images/btn_submit.jpg" border="0" /></a>-->
&nbsp;&nbsp;
<a id="resetclick" class="cursorhand"><img src="images/btn_cancel.jpg" alt="Cancel" border="0" title="Cancel"/></a>
      </form>
      <div class="frmBottomLeft"></div>
      <div class="frmBottomRight"></div>
    </div>
  </div>
</div>

<!-- Popup background Starts here -->

    <div id="backgroundPopup"></div>

<!-- Popup background Ends here -->


<?php


require_once("footer.php");
?>

