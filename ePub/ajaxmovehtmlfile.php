<?php
include_once('includes/config.php');
include_once('includes/functions.php');

//error_reporting(E_ALL);
//error_reporting(0);

//ini_set("display_errors",true);
/*if(!defined($venki)){
    echo "good";
}*/
$srcDir                         =		$_POST['epubfolder'];
$srcHtml                        =		$_POST['htmlfile'];
$source                         =               $srcDir.$srcHtml;
//exit(0);
//EPUB PARTICULAR HTML FILE MOVED TO PREVIEW_TEMP FOLDER
$destDir                        =               PREVIEW_TEMP.'/'.$srcHtml;
copy($source, $destDir) or die("Unable to copy $source to $destDir.");

$filecontent = file_get_contents($destDir);

if (preg_match('#href="css/(.+).css#iU', $filecontent, $css)) {
    echo $css[1];
} else {
    echo 'No Css File Found';
}

$k=0;
foreach($css as $cssvalue){
    if($k != 0) {        
        $cssFile        =   $cssvalue.'.css';
        $cssSource      =   $srcDir.'css/'.$cssFile;
        $cssDest        =   PREVIEW_TEMP.'/'.$cssFile;
        copy($cssSource, $cssDest) or die("Unable to copy $cssSource to $cssDest.");
    }
    $k++;
}

if (preg_match('#src="images/(.+)"#iU', $filecontent, $images)) {
    echo $images[1];
} else {
    echo 'No Images File Found';
}

$v=0;
foreach($images as $imagesvalue){
    if($v != 0) {        
        $imagesFile        =   $imagesvalue;
        $imagesSource      =   $srcDir.'images/'.$imagesFile;
        $imagesDest        =   PREVIEW_TEMP.'/'.$imagesFile;
        copy($imagesSource, $imagesDest) or die("Unable to copy $imagesSource to $imagesDest.");
    }
    $v++;
}

$filecontent = str_replace('href="css/', 'href="'.RELATIVE_PATH.'/'.PREVIEW_TEMP.'/', $filecontent);
$filecontent = str_replace('src="images/', 'src="'.RELATIVE_PATH.'/'.PREVIEW_TEMP.'/', $filecontent);
file_put_contents($destDir, $filecontent);

?>