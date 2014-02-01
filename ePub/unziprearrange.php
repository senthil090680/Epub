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
$activityid                     =               $_POST['activityid'];
$actpath			=		ACTIVITY_FOLDER."/".$activityid;
$nowtime                        =               time();
$actnewpath                     =		ACTIVITY_FOLDER."/".$activityid."/".$activityid."_".$nowtime;
$newActivtyFolder               =               EPUB_FOLDER."/".$datarow[epubFolder]."/OPS/";

$handle				=		opendir($actpath);
while ($name                    =		readdir($handle)) {    
    $fileexten			=		GetFileExtension($name);    
    if ($name != '.' && $name != '..' && $fileexten == 'zip') {
        unzipFile($actpath,$actnewpath,$name);
        RecursiveMove($actpath, $newActivtyFolder);
        deleteAll($actnewpath);
    }
}

$handleforimage                         =               opendir($newActivtyFolder.$activityid."_".$nowtime."/");
while ($nameofhtml                      =		readdir($handleforimage)) {
    $fileexten                          =		GetFileExtension($nameofhtml);
    
    if ($fileexten == 'html') {
        $oldimagename                   =               $nameofhtml;
        $htmlFileName                   =               RemoveExtension($nameofhtml);
    } else {
        //echo "helo";
    }
}

//Renaming the html file
$htmlrand                       =               $htmlFileName."_".$nowtime;

if(rename($newActivtyFolder."/".$activityid."_".$nowtime."/".$oldimagename,$newActivtyFolder."/".$activityid."_".$nowtime."/".$htmlrand.".html")) {
    $htmlrame                   =               $htmlrand.".html";
    $ajaxrequest                =               $htmlrame;
} else {
    echo "No rename happened"; exit;
}

//Changing the path to Relative path for activity CSS, IMAGES AND JS

$relat_path             =       RELATIVE_PATH."/";
$activityPath           =       EPUB_FOLDER.'/'.$datarow['epubFolder'].'/OPS/';
$filecontent            =       file_get_contents($activityPath.$activityid.'_'.$nowtime.'/'.$htmlrand.'.html');

//$filecontent            =       str_replace('src="images/', 'src="'.$relat_path.$activityPath.$activityid."_".$nowtime."/images/", $filecontent);

//$filecontent            =       str_replace('src="js/', 'src="'.$activityid."_".$nowtime."/js/", $filecontent);

//$filecontent            =       str_replace('href="css/', 'href="'.$activityid."_".$nowtime."/css/", $filecontent);

file_put_contents($activityPath.$activityid."_".$nowtime."/".$htmlrand.".html", $filecontent);

//Sending the ajax response to the client
echo $ajaxrequest               =               $ajaxrequest.";".$nowtime;

?>