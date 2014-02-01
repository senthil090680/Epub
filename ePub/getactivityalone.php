<?php
include_once('includes/config.php');
include_once('includes/functions.php');

//error_reporting(E_ALL);
//error_reporting(0);

//ini_set("display_errors",true);

//DB INITIALIZATION
$dbconnect                                  =		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$activityid                                 =		base64_decode($_POST['activityId']);

$dataquery                                  =		"SELECT activityId,activityName,activityType,activityDesc,activityFile,activityFolder FROM ".ACTIVITY_LIBRARY." WHERE activityId = '$activityid'";

$dataresquery                               =		mysql_query($dataquery) or die(mysql_error());
$datarow                                    =		mysql_fetch_array($dataresquery);

$k                                         =           0;

$activityFolder                             =           $datarow['activityFolder'];
$actpath                                    =		ACTIVITY_FOLDER."/".$activityFolder;

//Unzip the activity zip file and move the activity folder to the particular activity folder 
$handle                                     =		opendir($actpath);
while ($name                                =		readdir($handle)) {    
    $fileexten                              =		GetFileExtension($name);    
    if ($name != '.' && $name != '..' && $fileexten == 'zip') {
        unzipFile($actpath,$actpath,$name);
        //echo "gosdfd";
        //RecursiveMove($actpath, $newActivtyFolder);
        //deleteAll($actnewpath);
    }
}

//Read the activity folder and html file in the particular activity folder
$tempHandle                                 =		opendir($actpath);
while ($tempName                            =		readdir($tempHandle)) {    
    $tempFileexten                          =		GetFileExtension($tempName);    
    if ($tempName != '.' && $tempName != '..' && $tempFileexten == 'html') {
        echo $tempActivityHtml              =           $actpath.'/'.$tempName;
    }
}

exit(0);
?>