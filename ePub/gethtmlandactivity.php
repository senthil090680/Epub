<?php
include_once('includes/config.php');
include_once('includes/functions.php');

//error_reporting(E_ALL);
//error_reporting(0);

//ini_set("display_errors",true);

//DB INITIALIZATION
$dbconnect                                  =		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$insert_id                                  =		base64_decode($_POST['insert_id']);

$dataresquery                               =		mysql_query('CALL getSettingId('.$insert_id.',@settingId,@presetName,@presetSet,@epubVersion,@outputType,@bookTitle,@coverImage,@resol,@supportDevice,@fixedLay,@openSpread,@interActive,@specificFont,@fontName,@oriLock,@epubFolder,@pdfFile,@pdfPages,@activityFolder,@createdDate)');
$dataresquery                               =		mysql_query('SELECT @settingId,@presetName,@presetSet,@epubVersion,@outputType,@bookTitle,@coverImage,@resol,@supportDevice,@fixedLay,@openSpread,@interActive,@specificFont,@fontName,@oriLock,@epubFolder,@pdfFile,@pdfPages,@activityFolder,@createdDate');

$datarow                                    =		mysql_fetch_array($dataresquery);

//Activity extracted to the OPS folder of the epub folder
$newActivtyFolder                           =           EPUB_FOLDER."/".$datarow['@epubFolder']."/OPS/";
//exit(0);
$activityFolder                             =           '';
$htmlFolder                                 =           '';
$k                                          =           0;

$htmlfilepath                               =		opendir($newActivtyFolder);
while ($name                                =		readdir($htmlfilepath)) {    
    $htmlfirstthree                         =		substr($name,0,3);
    $htmlfileexten                          =               GetFileExtension($name);
    
    if ($name != '.' && $name != '..' && $htmlfirstthree == 'pg_' && $htmlfileexten == 'html') {       
       if($htmlFolder == '') {
           $htmlFolder                      =               $name;     
       } else {
           $htmlFolder                      .=              ",".$name;
       }
    } else {

    }  
}

$handle                                     =		opendir($newActivtyFolder);
while ($name                                =		readdir($handle)) {    
    $firstthree                             =		substr($name,0,3);
    
    if ($name != '.' && $name != '..' && $firstthree == 'act') {
       
       
            
       $htmlhandle                          =		opendir($newActivtyFolder.'/'.$name);
       while($htmlname                      =               readdir($htmlhandle)) {
           $htmlexten                       =               GetFileExtension($htmlname);
           $htmlFileName                    =               RemoveExtension($htmlname);
           if ($htmlname != '.' && $htmlname != '..' && $htmlexten == 'html') {
               
               if($activityFolder == '') {
                   if($htmlFolder != '') {
                        $htmlFolder         .=               ",";
                    }
                    $htmlFolder             .=               $name."/".$htmlname;     
                } else {
                    $htmlFolder             =              ",".$name."/".$htmlname;
                }
           }
       }
       $k++;
    }      
}

echo $htmlFolder;
exit(0);
?>