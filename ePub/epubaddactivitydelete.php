<?php
include_once('includes/config.php');
include_once('includes/functions.php');

error_reporting(0);

ini_set("display_errors",true);

$epubactDelId                           =               $_POST['pgDelId'];
$epubId                                 =               $_POST['epubId'];

//DB INITIALIZATION
$dbconnect                              =		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);
$dataquery				=		"SELECT activityFolder,settingId,pdfPages FROM ".EPUB_SET." WHERE epubFolder = '$epubId'";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow                                =               mysql_fetch_object($dataresquery);

$epubActivityFolder                     =               $epubId.$epubactDelId;

if(deleteDir($epubActivityFolder)) {      
    echo "deleted";        
}	
?>