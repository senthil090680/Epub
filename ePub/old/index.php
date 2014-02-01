<?php
//error_reporting(E_ALL);
error_reporting(0);
//ini_set('upload_max_filesize','30M');
//ini_set('post_max_size','30M');
//echo ini_get('upload_max_filesize');
//echo ini_get('display_errors');
//echo ini_get('post_max_size');
//echo phpinfo();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <script	type="text/javascript" src="jquery.js" language="JavaScript"></script>
        <script	language="javascript" type="text/javascript">
            function PageLoader(state, status) {
                if($('#image_pre_loader').length > 0) {
                    if(state ==	"load")	{
                        $('#image_pre_loader').fadeIn('slow');
                    }

                    if(state ==	"unload") {
                        $('#image_pre_loader').fadeOut('slow');
                    }

                    $('#status').html(status);
                }
            }
		$(document).ready(function(){
			$('#spefon').change(function() {
			  	var spefonts	=	$('#spefon').val();

				if(spefonts == 'spetrue') {
					$('#fontup').show();
					return false;
				} else {
					$('#fontup').hide();
					return false;
				}
			});
			
			$('#form1').submit(function() {
			var filename	=	$('#pdffile').val();
			var extension	=	filename.substr((filename.lastIndexOf('.') +1));
			var lowpdf		=	extension.toLowerCase();
			//var laypro		=	$('#layprop').val();
			var supdev		=	$('#supp').val();
			var uploadfonts	=	$('#upfonts').val();
			var fonext		=	uploadfonts.substr((uploadfonts.lastIndexOf('.') +1));
			var fixlayo		=	$('#fixlay').val();
			var spefont		=	$('#spefon').val();
			var booktitle	=	$('#booktitle').val();
			var dpires		=	$('#dpires').val();
				
				if(booktitle	== '') {
					alert('Please enter book title.');
					$('#booktitle').focus();
					return false;
				}
				if(dpires	== '') {
					alert('Please enter DPI.');
					$('#dpires').focus();
					return false;
				}

				if(spefont	== 'spetrue') {
					if(fonext == '') {
						alert('Please upload a ttf file.');
						return false;
					}
					else if(fonext == 'ttf') {
						//return true;
					} else {
						alert('Please upload only ttf files.');
						return false;
					}
				}				
				if((supdev == 'nodev') || (supdev == null)) {
					alert('Please select device.');
					return false;
				} else {
					//return true;
				}											

				if(lowpdf == '') {
					alert('Please upload a pdf file.');
					return false;
				}
				else if(lowpdf == 'pdf') {
					return true;
				} else {
					alert('Please upload only pdf files.');
					return false;
				}
			});
		});
        </script>
        <meta http-equiv="Content-Type"	content="text/html;	charset=iso-8859-1"	/>
        <title>ePub	conversion</title>
    </head>

    <body>
        <form id="form1" name="form1" id="form1" method="post"	action="" enctype="multipart/form-data">
            <p><strong>ePub Convertor</strong></p>
            <table width="50%" border="0"	style="border:1px solid	#C0C0C0;">
                 <tr>
				  <td width="4%" height="39" nowrap="nowrap">&nbsp;</td>
				  <td nowrap="nowrap"><strong>Output Type :</strong></td>				  
				  <td nowrap="nowrap"><input type="radio" name="outtype" id="ty2" value="2" checked="checked" > Html 
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="outtype" id="ty1" value="1" /> Image </td>
				</tr>
				<tr>
                    <td width="3%" height="39" nowrap="nowrap">&nbsp;</td>
                    <td width="26%" nowrap="nowrap"><strong>BOOK TITLE :</strong></td>
                    <td width="59%" nowrap="nowrap"><input type="text" name="booktitle" id="booktitle" style="width:300px;" value=""/>                            
                    </td>
                </tr>	
				<tr>
                    <td width="3%" height="39" nowrap="nowrap">&nbsp;</td>
                    <td width="26%" nowrap="nowrap"><strong>DPI (Resolution) :</strong></td>
                    <td width="59%" nowrap="nowrap"><input type="text" name="dpires" id="dpires" value=""/>                            
                    </td>
                </tr>                		
				<tr>
                    <td width="3%" height="39" colspan="2" align="center" nowrap="nowrap"><strong>Layout Properties</strong></td>
				</tr>
				<tr>
                    <td width="3%" height="39">&nbsp;</td>
                    <td width="26%"><strong>Fixed-Layout :</strong></td>
                    <td width="59%"><select name="fixlay" id="fixlay">
                            <!--<option value="nofix">-Select-</option>-->
                            <option value="fixfal">False</option>
							<option value="fixtrue">True</option>
                        </select>                            
                    </td>
                </tr>				
                <tr>
                    <td width="3%" height="39">&nbsp;</td>
                    <td width="26%"><strong>Specified-fonts :</strong></td>
                    <td width="59%"><select name="spefon" id="spefon" >
                            <!--<option value="nofon">-Select-</option>-->
                            <option value="spefal">False</option>
                            <option value="spetrue">True</option>
                        </select>                            
                    </td>
                </tr>
				<tr id="fontup" style="display:none;">
                    <td width="3%" height="39">&nbsp;</td>
                    <td width="26%"><strong>Upload Fonts:</strong></td>
                    <td width="59%"><input type="file" name="upfonts" id="upfonts"	/></td>
                </tr>
				<tr>
                    <td width="3%" height="39">&nbsp;</td>
                    <td width="26%"><strong>Open-to-spread :</strong></td>
                    <td width="59%"><select name="openspr" id="openspr">
                            <!--<option value="noope">-Select-</option>-->
                            <option value="opefal">False</option>
                            <option value="opetrue">True</option>
                        </select>                            
                    </td>
                </tr>
				<tr>
                    <td width="3%" height="39">&nbsp;</td>
                    <td width="26%"><strong>Orientation-lock :</strong></td>
                    <td width="59%"><select name="oriloc" id="oriloc" >
                            <!--<option value="noori">-Select-</option>-->
                            <option value="landscape-only">Landscape-only</option>
							<option value="portrait-only">Portrait-only</option>
							<option value="none">None</option>
                        </select>                            
                    </td>
                </tr>
				<tr>
                    <td width="3%" height="39">&nbsp;</td>
                    <td width="26%"><strong>Interactive :</strong></td>
                    <td width="59%"><select name="interac" id="interac" >
                            <!--<option value="noint">-Select-</option>-->
                            <option value="intfal">False</option>
                            <option value="inttrue">True</option>
                        </select>
                    </td>
                </tr>
				<tr>
                    <td width="3%" height="39">&nbsp;</td>
                    <td width="26%"><strong>Supporting Device :</strong></td>                    
                    <td width="59%"><select name="supp" id="supp" multiple>
                            <option value="nodev">-Select-</option>
                            <option value="ipad">IPAD</option>
                            <option value="kindle">KINDLE</option>
                            <option value="sony">Sony</option>
							<option value="mobl21">Mobl21</option>
                        </select>
                    </td>
                </tr>
                <!--<tr>
                  <td height="40">&nbsp;</td>
                  <td height="40"><strong>Image	Type : </strong></td>
                  <td><input type="radio" name="imtype"	id="imty1" value="1" checked="checked" />png</td>
                  <td><input type="radio" name="imtype"	id="imty2" value="2"  />jpg</td>
                </tr>
                <tr>
                  <td height="40">&nbsp;</td>
                  <td height="40"><strong>Image	Resolution:	 </strong></td>
                  <td colspan="2"><input name="res"	type="text"	value="890x640"	size="8" />
                  px</td>
                </tr>
                <tr>
                  <td height="40">&nbsp;</td>
                  <td height="40"><strong>Image	Density	:</strong></td>
                  <td colspan="2"><input name="den"	type="text"	value="150"	size="3" />
                  dbi</td>
                </tr>-->
                <tr>
                    <td height="40">&nbsp;</td>
                    <td height="40"><strong>Upload pdf : </strong></td>
                    <td colspan="2"><input type="file" name="pdffile" id="pdffile"	/></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2"><input type="submit" value="Submit" name="submit"	/></td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
            </table>
            <br />
            <div id="image_pre_loader" style="display:none;">
                <div style="float:left;"><img src="album_loader.gif" alt=""/></div>
                <div style="float:left; padding-left:20px; padding-top:20px;"><span id="status"></span></div>
            </div>
            <p>&nbsp;</p>
        </form>
    </body>
</html>
<?php
include_once('includes/config.php');
include_once('includes/functions.php');

if (isset($_POST['submit']) && isset($_FILES['pdffile']) && $_FILES['pdffile'] != '') {

    flushstatus('load', 'Started...');

    // Getting posted values
    //$outputtpe = $_POST['outtype'];
	$outputtpe		=		2;   // It is hardcoded as 2 for html option since we have only html option required
    $imagetype		=		(isset($_POST['imtype']) && $_POST['imtype'] == 2) ? 'jpg' : 'png';
    $resolution		=		(isset($_POST['res']) && $_POST['res'] != '') ? $_POST['res'] : DEFAULT_RESOLUTION;
    $density		=		(isset($_POST['den']) && $_POST['den'] != '') ? $_POST['den'] : DEFAULT_DESITY;
    $pdfname		=		$_FILES['pdffile']['name'];
	$fontname		=		$_FILES['upfonts']['name'];
	$fixlay			=		$_POST['fixlay'];
	$orilock		=		$_POST['oriloc'];
	//$supdev		=		$_POST['supp'];

	$supdev			=		"ipad";
	$specfon		=		$_POST['spefon'];
	$openspread		=		$_POST['openspr'];
	$interac		=		$_POST['interac'];
	$dpires			=		$_POST['dpires'];
	$booktitle		=		$_POST['booktitle'];
	
	/*echo "<pre>";
	print_r($_FILES);
	echo "</pre>";
	
	exit;*/
    flushstatus('load', 'Creating epub directory...');

    // Genrate unique file name
    $c_filename		=		SetUniqFileName($pdfname);
    $c_dirname		=		RemoveExtension($c_filename);

    $pdfpath		=		PDF_FOLDER . '/' . $c_filename;
    $despath		=		EPUB_FOLDER . '/' . $c_dirname;

	$fontpath		=		FONT_FOLDER . '/' . $fontname;
	
	if($fontname != '') {
		// Upload font file to font folder
		move_uploaded_file($_FILES['upfonts']['tmp_name'], FONT_FOLDER . '/' . $fontname);
	}

    // Upload pdf file to pdf folder    
	if(move_uploaded_file($_FILES['pdffile']['tmp_name'], PDF_FOLDER . '/' . $c_filename)) {
		//echo "PDF MOVED";
	} else {
		echo "PDF NOT MOVED";
	}

    // Creates epub	folder
    if (!mkdir(EPUB_FOLDER . '/' . $c_dirname)) {
        echo 'ePub	directory creation failed';
        exit;
    }

    // Copy	epub source	files
    RecursiveCopy(EPUB_SOURCE_FOLDER, EPUB_FOLDER . '/' . $c_dirname);

    flushstatus('load', 'Checking resources...');

    // Check folders and files
    CheckFoldersandFiles(EPUB_SOURCE_FOLDER, EPUB_FOLDER . '/' . $c_dirname);


    // Output type : Image
    if ($outputtpe == 1) {

        flushstatus('load', 'Image conversion started...');

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

        flushstatus('load', 'Adding	manifest files...');

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

        $outputone			=	$despath . '/OPS/images/';
        $outone				=	$despath . '/OPS/';
		$xmlfol				=	$despath . '/'.META_FILE;

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


                    // Move	to parent
                    $source = $despath . '/OPS/images/' . $files[$i];
                    $destination = $despath . '/OPS/' . $files[$i] . "l";
                    copy($source, $destination);

                    // Delete source html
                    unlink($source);

                    // Clean html (Not necessary below Loop	will do	this)
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

        // create opf and ncx files
        CreateOpf($filecount);
        CreateNcx($filecount);
    }

    flushstatus('load', 'Packaging epub...');

	
	if($fixlay == 'fixfal') {
		$fixlayout	=	"true";
		//$fixlayout	=	"false";
	} else {
		$fixlayout	=	"true";
	}

	if($specfon == 'spefal') {
		$specialfon	=	"false";
	} else {
		$specialfon	=	"true";
	}

	if($openspread == 'opefal') {
		$opensp	=	"false";
	} else {
		$opensp	=	"true";
	}

	if($interac == 'intfal') {
		$intact	=	"false";
	} else {
		$intact	=	"true";
	}

	unlink($xmlfol);

	$xmlcont		=	'<display_options>
	<platform name="'.$supdev.'">
		<option name="fixed-layout">'.$fixlayout.'</option>
		<option name="specified-fonts">'.$specialfon.'</option>
		<option name="open-to-spread">'.$opensp.'</option>
		<option name="orientation-lock">'.$orilock.'</option>
		<option name="open-to-spread">'.$intact.'</option>
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
            if ($loopcount == 0) {
                $loopcount++;
                if (copy($outputone . $image_path . ".png", $outputone . "cover.jpg")) {
                    
                } else {
                    
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
            $filecontent = str_replace('<body', '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>' . $title . '</title>
<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8"/>
<meta name="viewport" content="width=' . $width . ', height=' . $height . '"/>
<link rel="stylesheet" href="css/c1.css" type="text/css"/>
</head>
<body style="width: ' . $width.'px; height : ' . $height.'px" ', $filecontent);

            $filecontent = str_replace('ALT=""', 'ALT="" /', $filecontent);
            $filecontent = str_replace('src="', 'src="images/', $filecontent);
            $filecontent = str_replace('<html>', '', $filecontent);
            $filecontent = str_replace('<nobr>', '', $filecontent);
            $filecontent = str_replace(';l', 'px;l', $filecontent);

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

            $bodybefore = $countwhite - 3;

            $bodyafter = $bodybefore - 1;

            $linewhite = array('0', '1', '2', '3', '12', '13', '14', $bodybefore, $bodyafter);

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

    // create zip and rename
    $files = listdir($despath);
    create_zip($files, $despath . '/' . $c_dirname . '.zip');
    rename($despath . '/' . $c_dirname . '.zip', $despath . '/' . $c_dirname . '.epub');

    // download	epub
    $fpath = RELATIVE_PATH . '/epub/' . $c_dirname . '/' . $c_dirname . '.epub';
    echo '<br>Converted	Successfully : <a href="' . $fpath . '"	target="_blank">Click here to download ePub	file</a>';

    flushstatus('unload', 'Completed');
}

function flushstatus($state, $status) {
    echo '<script>PageLoader("' . $state . '", "' . $status . '");</script>';
    ob_flush();
    flush();
    sleep(1);
}
?>