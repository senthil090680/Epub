<?php
include_once('includes/config.php');
include_once('includes/functions.php');

error_reporting(0);

ini_set("display_errors",false);

$epubactDelId                           =               base64_decode($_POST['pgDelId']);
$epubId                                 =               base64_decode($_POST['epubId']);

$epubActivityFolder                     =               EPUB_FOLDER."/".$epubId."/OPS/".$epubactDelId;

if(unlink($epubActivityFolder)) {      
    echo "deleted";        
}	
?>