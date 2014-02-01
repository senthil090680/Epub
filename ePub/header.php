<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>EPUB CONVERSION</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="js/jquery-1.7.1.js"></script> <!-- This is JAVASCRIPT LIBRARY --> 
<script language="javascript" type="text/javascript" src="js/common.js"></script>
<script language="javascript" type="text/javascript" src="js/epubeditview.js"></script>
<script language="javascript" type="text/javascript" src="js/jqueryForm.js"></script> <!--This is a plugin for form -->
<script language="javascript" type="text/javascript" src="js/ajaxfileupload.js"></script> <!--This is a plugin for File Upload -->
<script language="javascript" type="text/javascript" src="js/jquery.simplemodal.js"></script> <!--This is a plugin for IFRAME POPUP -->
<script language="javascript" type="text/javascript" src="js/jquery.ui.1.8.18.js"></script> <!-- This is for drag and drop functionality in the activity page -->
</head>
<body onload="MM_preloadImages('images/epub_process_active.jpg','images/active_library_active.jpg','images/epub_library_active.jpg')">
<?php   //destroy(PREVIEW_TEMP."/"); ?>    
<!-- Top Strip -->
<div class="topStriip">
  <div class="logo"><img src="images/logo.jpg" width="193" height="42" alt="Emantras" title="Emantras"/></div>
  <div class="menu">
      <?php $scriptName         =       $_SERVER["SCRIPT_NAME"];
            $excludeFolder      =       explode('/', $scriptName);
            $fileName           =       $excludeFolder[count($excludeFolder) - 1];  
            
            if($fileName == 'index.php' || $fileName == 'epub_activity.php' || $fileName == 'publish.php') {
                $epubProcess    =   "images/epub_process_active.jpg";
            } else {
                $epubProcess    =   "images/epub_process.jpg";
            }
            
            if($fileName == 'activitylibrary.php' || $fileName == 'activitylibraryadd.php' || $fileName == 'activitylibraryview.php' || $fileName == 'activitylibraryedit.php') {
                $activityLibrary    =   "images/active_library_active.jpg";
            } else {
                $activityLibrary    =   "images/active_library.jpg";
            }
            
            if($fileName == 'epublibrary.php' || $fileName == 'epubeditview.php') {
                $epubLibrary    =   "images/epub_library_active.jpg";
            } else {
                $epubLibrary    =   "images/epub_library.jpg";
            }                  
      ?>      
      <a href="index.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image2','','images/epub_process_active.jpg',1)"><img src="<?php echo $epubProcess; ?>" name="Image2" width="201" height="38" border="0" id="Image2" alt="EPUB Process" title="EPUB Process"/></a>
      <a href="activitylibraryview.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image3','','images/active_library_active.jpg',1)"><img src="<?php echo $activityLibrary; ?>" name="Image3" width="209" height="38" border="0" id="Image3" alt="Activity Library" title="Activity Library"/></a>
      <a href="epublibrary.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image4','','images/epub_library_active.jpg',1)"><img src="<?php echo $epubLibrary; ?>" name="Image4" width="183" height="38" border="0" id="Image4" alt="EPUB Library" title="EPUB Library" /></a></div>
</div>
<!-- Blue Strip -->
<div class="blueStrip"></div>
<!-- Body Content -->
<div class="bodyContent">
  <div class="bodyBox">