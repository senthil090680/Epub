<?php
include_once('includes/config.php');
include_once('includes/functions.php');

//error_reporting(E_ALL);
//error_reporting(0);

//ini_set("display_errors",true);

//DB INITIALIZATION
$dbconnect			=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$insert_id			=		base64_decode($_POST['insert_id']);

$dataquery			=		"SELECT settingId,presetName,presetSet,epubVersion,outputType,bookTitle,coverImage,presetName,resol,supportDevice,fixedLay,openSpread,interActive,specificFont,fontName,oriLock,epubFolder,pdfFile,pdfPages FROM ".EPUB_SET." WHERE settingId = '$insert_id'";

$dataresquery                   =		mysql_query($dataquery) or die(mysql_error());
$datarow			=		mysql_fetch_array($dataresquery);

//EPUB HTML FILES MOVED TO PREVIEW_TEMP FOLDER
$srcDir                         =               EPUB_FOLDER."/".$datarow[epubFolder]."/OPS";
$destDir                        =               PREVIEW_TEMP;
//exit(0);

$handle				=		opendir($srcDir);
while ($name                    =		readdir($handle)) {    
    $fileexten			=		GetFileExtension($name);    
    if(RecursiveCopy($srcDir, $destDir)){
        //Sending the ajax response to the client
        echo "success";
    }
}

$handleTemp			=		opendir($destDir);
while ($nameTemp                =		readdir($handleTemp)) {    
    $fileexten			=		GetFileExtension($nameTemp);
    if($nameTemp != '.' && $nameTemp != '..') {
        $filecontent = file_get_contents($destDir .'/'. $nameTemp);
        $filecontent = str_replace('href="css/', 'href="'.RELATIVE_PATH.'/'.$destDir.'/css/', $filecontent);
        $filecontent = str_replace('src="images/', 'src="'.RELATIVE_PATH.'/'.$destDir.'/images/', $filecontent);
        file_put_contents($destDir . '/'.$nameTemp, $filecontent);
    }
}
?>