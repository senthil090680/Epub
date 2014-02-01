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

$activityDelid                          =               base64_decode($_POST['activityDelId']);

//DB INITIALIZATION
$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);
$dataquery				=		"SELECT activityId,activityName,activityType,activityDesc,activityFile,activityFolder FROM ".ACTIVITY_LIBRARY." WHERE activityId = '$activityDelid'";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow                                =               mysql_fetch_object($dataresquery);

/*echo "<pre>";
print_r($_POST);
echo "</pre>";

exit;*/

$dir                                    =               ACTIVITY_FOLDER."/".$datarow->activityFolder;

deleteDir($dir);

$updquery                               =		"DELETE FROM ". ACTIVITY_LIBRARY." WHERE activityId='$activityDelid'";
$updresquery                            =		mysql_query($updquery) or die(mysql_error());

if($updresquery) {      
    echo "deleted";        
}	
?>