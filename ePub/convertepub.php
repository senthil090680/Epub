<?php
include_once('includes/config.php');
include_once('includes/functions.php');

//error_reporting(E_ALL);
//error_reporting(0);

//ini_set("display_errors",true);

//DB INITIALIZATION
$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$insert_id				=		base64_decode($_POST['insert_id']);

$dataquery				=		"SELECT settingId,presetName,presetSet,epubVersion,outputType,bookTitle,coverImage,presetName,resol,supportDevice,fixedLay,openSpread,interActive,specificFont,fontName,oriLock,epubFolder,pdfFile,pdfPages FROM ".EPUB_SET." WHERE settingId = '$insert_id'";

$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow				=		mysql_fetch_array($dataresquery);

//Activity extracted to the OPS folder of the epub folder
$convertPath                            =               EPUB_FOLDER."/".$datarow[epubFolder];
$epubFolder                             =               $datarow[epubFolder];

$epubRemoveHandle                       =               opendir($convertPath);
while($name                             =               readdir($epubRemoveHandle)) {
    $zipExtn                            =               GetFileExtension($name);
    if($name != '.' && $name != '..' && $zipExtn == 'epub') {
        unlink($convertPath."/".$name);
    }
}

//exit(0);
//create zip and rename to epub
$files                                  =               listdir($convertPath);
$converted                              =               create_zip($files, $convertPath . '/' . $epubFolder . '.zip');
rename($convertPath . '/' . $epubFolder . '.zip', $convertPath . '/' . $epubFolder . '.epub');

$dateTime                               =               date('Y-m-d H-i-s');
//Updating the DB with the createdDate
$upsetquery                             =		"UPDATE ". EPUB_SET ." SET createdDate = '$dateTime' WHERE settingId = '$insert_id'";
$upressetquery                          =		mysql_query($upsetquery) or die(mysql_error());

if($converted) {
    echo "success";
}

?>