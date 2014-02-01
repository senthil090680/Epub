<?php
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

//echo ini_get('upload_max_filesize');
//echo ini_get('display_errors');
//echo ini_get('post_max_size');
//echo ini_get('output_buffering');
//echo phpinfo();


$epubactDelId                           =               base64_decode($_POST['pgDelId']);
$epubId                                 =               base64_decode($_POST['epubId']);
if(substr($_POST['pgDelId'],0,3) == 'act' ) {
    $epubactDelId                       =               $_POST['pgDelId'];
}
//echo $epubactDelId;
//echo $epubId;

//DB INITIALIZATION
$dbconnect                              =		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);
$dataquery				=		"SELECT activityFolder,settingId,pdfPages FROM ".EPUB_SET." WHERE epubFolder = '$epubId'";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow                                =               mysql_fetch_object($dataresquery);

$fromDatabase                           =               $datarow->activityFolder;

if(strstr($fromDatabase,$epubactDelId)) {
    $removeFolder                           =               str_replace($epubactDelId,'',$datarow->activityFolder);


    if(strstr($removeFolder,',,')) { //Removes middle comma
        $removeComma                        =               str_replace(',,',',',$removeFolder);
        //echo "good";
    }

    if($removeFolder[0] == ',') {  //Removes First comma
        $removeComma                        =              substr( $removeFolder, 1 );
        //echo "man";
    }

    $LastLetter                             =              substr($removeFolder, -1);

    if($LastLetter == ',')  {   //Removes Last comma
        $removeComma                        =               substr_replace($removeFolder, "", -1);
        //echo "hel";
    }
} else {
    $removeComma                            =               $datarow->activityFolder;
}
//echo $removeComma;
//exit(0);

$epubActivityFolder                     =               EPUB_FOLDER."/".$epubId."/OPS/".$epubactDelId;

deleteDir($epubActivityFolder);

$updquery                               =		"UPDATE ". EPUB_SET." SET activityFolder='$removeComma' WHERE settingId = '$datarow->settingId'";
$updresquery                            =		mysql_query($updquery) or die(mysql_error());

if($updresquery) {      
    echo "deleted";        
}	
?>