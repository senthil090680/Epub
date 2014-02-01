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
    if ($name != '.' && $name != '..' && $fileexten != 'zip') {
        deleteAllExcept($actpath);
    }
}

exit(0);
?>