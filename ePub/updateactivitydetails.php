<?php
include_once('includes/config.php');
include_once('includes/functions.php');

//error_reporting(E_ALL);
//error_reporting(0);

//ini_set("display_errors",true);

//DB INITIALIZATION

$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$insert_id				=		base64_decode($_POST['insert_id']);
$totalfiles                             =               explode(';',$_POST[totalfiles]);

/*echo "<pre>";
print_r($totalfiles);
echo "</pre>";
exit(0);*/

$dataquery				=		"SELECT settingId,presetName,presetSet,epubVersion,outputType,bookTitle,coverImage,presetName,resol,supportDevice,fixedLay,openSpread,interActive,specificFont,fontName,oriLock,epubFolder,pdfFile,pdfPages FROM ".EPUB_SET." WHERE settingId = '$insert_id'";

$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow				=		mysql_fetch_array($dataresquery);

//CREATING NCX AND OPF FILES
$c_dirname                              =               $datarow['epubFolder']; //epub folder to create ncx and opf files
$booktitle                              =               $datarow['bookTitle'];  //BOOK TITLE to create ncx and opf files
$updateopfmanifest                      =               '';
$updateopfspine                         =               '';
$updatencxmap                           =               '';
$activityFolder                         =               '';

$opfFolder                              =               EPUB_FOLDER.'/'.$datarow['epubFolder'].'/OPS/fb.opf';
$ncxFolder                              =               EPUB_FOLDER.'/'.$datarow['epubFolder'].'/OPS/fb.ncx';
$filecount                              =               1;
foreach($totalfiles as $totalvalue) {    
    $startfile                          =		substr($totalvalue, 0, 3);
    if($startfile == 'act'){
        $activityFolder                     .=          substr($totalvalue,0,strcspn($totalvalue,'/')).',';
        $htmlFile                           =           substr(strstr($totalvalue,'/'),1);
        $htmlFileName                       =           RemoveExtension($htmlFile);
    } else {
        $htmlFileName                       =           RemoveExtension($totalvalue);
    }
    
    //Adding Activity Files to the .OPF file 
    $updateopfmanifest .= "<item id='$htmlFileName' href='$totalvalue' media-type='application/xhtml+xml'/>\n";
    $updateopfspine .= "<itemref idref='$htmlFileName' linear='yes'/>\n";
    //End of Adding Activity Files to the .OPF file  

    
    $updatencxmap .= "<navPoint id='navpoint-$filecount' playOrder='$filecount'>\n"
                . "<navLabel>\n"
                        . "<text>$htmlFileName</text>\n"
                . "</navLabel>\n"
                . "<content src='$totalvalue'/>\n"
        . "</navPoint>\n";
    $filecount++;    
}
$activityFolder                     =          substr($activityFolder,0,-1);

//exit(0);
//CHECK IF OPF FILE EXISTS ALREADY
if(!file_exists($opfFolder)) {
    //CREATE OPF FILES
    CreateOpf();
} else {
    unlink($opfFolder);
    //CREATE OPF FILES
    CreateOpf();
}

//CHECK IF NCX FILE EXISTS ALREADY
if(!file_exists($ncxFolder)) {
    //CREATE NCX FILES
    CreateNcx();
} else {
    unlink($ncxFolder);
    //CREATE NCX FILES
    CreateNcx();
}

//exit(0);
$addPdfPages                    =               $filecount-1;
//Updating the DB with the new pagecount and activity folders
$upsetquery                     =		"UPDATE ". EPUB_SET ." SET pdfPages = '$addPdfPages',activityFolder= '$activityFolder' WHERE settingId = '$insert_id'";
$upressetquery                  =		mysql_query($upsetquery) or die(mysql_error());
echo $activityFolder;
?>