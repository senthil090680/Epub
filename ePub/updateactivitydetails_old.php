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
$newActivtyFolder               =               EPUB_FOLDER."/".$datarow[epubFolder]."/OPS/";
$oldPageCount                   =               $datarow[pdfPages];
$activityFolder                 =               '';
$k                              =               0;



//CREATING NCX AND OPF FILES
$c_dirname                              =               $datarow['epubFolder']; //epub folder to create ncx and opf files
$outputtpe                              =               $datarow['outputType']; //OUTPUT TYPE to create ncx and opf files
$booktitle                              =               $datarow['bookTitle'];  //BOOK TITLE to create ncx and opf files
$updateopfmanifest                      =               '';
$updateopfspine                         =               '';
$updatencxmap                           =               '';
$filecount                              =               1;
$htmlfilecount                          =               0;


$htmlfilepath                          =		opendir($newActivtyFolder);
while ($name                           =		readdir($htmlfilepath)) {    
    $htmlfirstthree                    =		substr($name,0,3);
    $htmlfileexten                     =               GetFileExtension($name);
    
    if ($name != '.' && $name != '..' && $htmlfirstthree == 'pg_' && $htmlfileexten == 'html') {
        $filecount++;
    } else {

    }  
}
$opfFolder                              =               EPUB_FOLDER.'/'.$datarow['epubFolder'].'/OPS/fb.opf';
$ncxFolder                              =               EPUB_FOLDER.'/'.$datarow['epubFolder'].'/OPS/fb.ncx';

$handle                                 =		opendir($newActivtyFolder);
while ($name                            =		readdir($handle)) {    
    $firstthree                         =		substr($name,0,3);
    
    if ($name != '.' && $name != '..' && $firstthree == 'act') {
       
       if($activityFolder == '') {
           $activityFolder              =               $name;     
       } else {
           $activityFolder              .=              ",".$name;
       }
            
       $htmlhandle                      =		opendir($newActivtyFolder.'/'.$name);
       while($htmlname                  =               readdir($htmlhandle)) {
           $htmlexten                   =               GetFileExtension($htmlname);
           $htmlFileName                =               RemoveExtension($htmlname);
           $opfncxPath                  =               $name.'/'.$htmlname;
           if ($name != '.' && $name != '..' && $htmlexten == 'html') {
               
             //Adding Activity Files to the .OPF file  
             $updateopfmanifest .= "<item id='$htmlFileName' href='$opfncxPath' media-type='application/xhtml+xml'/>\n";
             $updateopfspine .= "<itemref idref='$htmlFileName' linear='yes'/>\n";
             //End of Adding Activity Files to the .OPF file  
             
             if($htmlfilecount == 0) {
                $htmlfilecount                              =               $filecount;
             }
             $updatencxmap .= "<navPoint id='navpoint-$htmlfilecount' playOrder='$htmlfilecount'>\n"
                            . "<navLabel>\n"
                                    . "<text>$htmlFileName</text>\n"
                            . "</navLabel>\n"
                            . "<content src='$opfncxPath'/>\n"
                    . "</navPoint>\n";
             $htmlfilecount++;
           }
       }
       $k++;
    }
      
}

//exit(0);


//CHECK IF OPF FILE EXISTS ALREADY
if(!file_exists($opfFolder)) {
    //CREATE OPF FILES
    CreateOpf($filecount);
} else {
    unlink($opfFolder);
    //CREATE OPF FILES
    CreateOpf($filecount);
}

//CHECK IF NCX FILE EXISTS ALREADY
if(!file_exists($ncxFolder)) {
    //CREATE NCX FILES
    CreateNcx($filecount);
} else {
    unlink($ncxFolder);
    //CREATE NCX FILES
    CreateNcx($filecount);
}

//exit(0);

$addPdfPages                    =               $filecount-1+$k;

//Updating the DB with the new pagecount and activity folders
$upsetquery                     =		"UPDATE ". EPUB_SET ." SET pdfPages = '$addPdfPages',activityFolder= '$activityFolder' WHERE settingId = '$insert_id'";
$upressetquery                  =		mysql_query($upsetquery) or die(mysql_error());

echo $activityFolder;
?>