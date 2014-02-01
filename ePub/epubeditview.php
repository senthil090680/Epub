<?php
session_start();
include_once('includes/config.php');
include_once('includes/functions.php');

//echo phpinfo();
//exit;

/*echo "<pre>";
print_r(get_declared_classes());
echo "</pre>";*/

//error_reporting(E_ALL);
error_reporting(0);

ini_set("display_errors",true);

$insert_id = isset($_REQUEST['epubid']) ? base64_decode($_REQUEST['epubid']) : '';

//DB INITIALIZATION
$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$dataquery				=		"SELECT settingId,presetName,presetSet,epubVersion,outputType,bookTitle,coverImage,presetName,resol,supportDevice,fixedLay,openSpread,interActive,specificFont,fontName,oriLock,epubFolder,pdfFile,pdfPages,activityFolder FROM ".EPUB_SET." WHERE settingId = '$insert_id'";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow				=		mysql_fetch_array($dataresquery);

require_once("header.php");
?>
    <div class="titleStrip">Epub Process</div>
    <!-- Steps -->
    <div class="steps"> <img src="images/step1.jpg" width="301" height="69" alt="Step 1" title="Step 1"/><img src="images/step1_spliter2.jpg" width="30" height="69" /><img src="images/step2_active.jpg" alt="Step 2" width="301" height="69" title="Step 2" /><img src="images/step1_spliter1.jpg" width="30" height="69" /><img src="images/step3.jpg" width="302" height="69" alt="Step 4" title="Step 4"/></div>
    <div class="activityFrmBox">
      
        <div class="clr"></div>
        <div class="leftPanel">
          <div class="bookInfo">
            <div class="lessonTitle"><?php echo ucwords(strtolower($datarow[bookTitle])); ?></div>
			<?php			
			$epubpath				=		EPUB_FOLDER . "/".$datarow['epubFolder'];		
			$outone					=		$epubpath . '/OPS/';			
			?>
            <div class="pageInfo">Total Pages: <?php echo $datarow['pdfPages']; ?></div>
          </div>
          <div class="editPageBox" id="editHtmlPopBox">
            <ul class="editHtmlPageList">
				<?php
				$pagecnt			=		0;
				$handle				=		opendir($outone);
				while ($name                    =		readdir($handle)) {
				$fileexten			=		GetFileExtension($name);
				$startfile			=		substr($name, 0, 4);
			
				if ($startfile == 'pg_0' && $fileexten == 'html') {
					$pagecnt++;
					?> <li class="htmlPage <?php $namval = str_replace(".html","",$name); echo $namval; ?>" id="<?php echo $namval; ?>" ><div class="pgEditDelete"><img src="images/page_delete.png" onclick="deleteHtmlActivity('<?php echo base64_encode($name); ?>','<?php echo base64_encode($datarow[epubFolder]); ?>','<?php echo $namval; ?>');" /></div><a style="text-decoration:none;color:#787878;" href="javascript:void(0);" onclick="editPopupclick('<?php echo $name; ?>');" ><?php echo substr($name,0,7)."..."; ?></a></li> <?php						
				}
			}
                        
                        if($datarow['activityFolder'] != '') {
                            $actFolderArray         =       explode(',',$datarow['activityFolder']);
                            
                            $activtyFolder          =       EPUB_FOLDER."/".$datarow[epubFolder]."/OPS/";
                            foreach($actFolderArray as $actFolder) {
                                $handle				=		opendir($activtyFolder.$actFolder);
                                while ($name                    =		readdir($handle)) {
                                    $fileexten			=		GetFileExtension($name);
                                    if ($fileexten == 'html') {
                                        ?> <li class="editHtmlPage <?php $namval = str_replace(".html","",$name); echo $namval; ?>" id="<?php echo $namval; ?>"><div class="pgEditDelete"><img src="images/page_delete.png" onclick="deleteAddedActivity('<?php echo base64_encode($actFolder); ?>','<?php echo base64_encode($datarow[epubFolder]); ?>','<?php echo $namval; ?>');" /></div><a style="text-decoration:none;color:#787878;" href="javascript:void(0);" onclick="editPopupclick('<?php echo $name; ?>','<?php echo RELATIVE_PATH."/".$activtyFolder.$actFolder.'/'; ?>');" ><?php echo substr($name,0,7)."..."; ?></a></li> <?php	
                                    }                                    
                                }
                                
                            }
                        }
                        
                        
                        
                        
                        
			?>
              
            </ul>
			<div class="clr"></div>
          </div>
        </div>
		<form name="editSkipform" id="editSkipform" method="post" action="publish.php"><input type="hidden" id="insert_id" name="insert_id" value="<?php echo $_REQUEST['epubid']; ?>" /><input type="hidden" id="servername" name="servername" value="<?php echo RELATIVE_PATH."/".$outone; ?>" /><input type="hidden" id="serverpath" name="serverpath" value="<?php echo RELATIVE_PATH."/"; ?>" /><input type="hidden" id="activpath" name="activpath" value="<?php echo EPUB_FOLDER."/".$datarow['epubFolder']."/OPS/"; ?>" />
                    <input type="hidden" id="editActivityFolder" name="editActivityFolder" value="<?php echo base64_encode($datarow['activityFolder']); ?>" />
                    <input type="hidden" id="editEpubFolder" name="editEpubFolder" value="<?php echo base64_encode($datarow['epubFolder']); ?>" />
                    <input type="hidden" id="editSettingId" name="editSettingId" value="<?php echo base64_encode($datarow['settingId']); ?>" />
		</form>
        <div class="rightPanel">
            <div class="quickNavi textAlignRight"><a href="javascript:void(0)" onclick="previewshow('<?php echo EPUB_FOLDER. "/".$datarow['epubFolder']."/OPS/"; ?>','<?php echo RELATIVE_PATH."/"; ?>','<?php echo base64_encode($datarow['settingId']); ?>');"><img src="images/btn_preview.jpg" alt="Preview" border="0" title="Preview"/></a>&nbsp;<a href="javascript:void(0)" id="editSkiptag"><img src="images/btn_skipactivity.jpg" alt="Skip Activity" border="0" title="Skip Activity"/></a></div>
		  <form name="editEpubprocess" id="editEpubprocess">
          <div class="editActivityBox" style="overflow: auto;">
            <select name="editActivityselect" id="editActivityselect" class="selectByActivity">
              <option value="" class="inputGrayText">Select</option>
              <option value="dragdrop">Drag &amp; Drop</option>
              <option value="animatype">Animation</option>
              <option value="videotype">Video</option>
              <option value="slidetype">Slide Show</option>
            </select>
            <div id="editChoiceofselect">             
              <ul class="editActivityList">
                <?php
$libquery                              =		"SELECT activityId,activityName,activityType,activityFolder,activityFile FROM ".ACTIVITY_LIBRARY."";
$libresquery                           =		mysql_query($libquery) or die(mysql_error());
while($librow                          =                mysql_fetch_array($libresquery)) {
?>
                  <li class="activityRowEdit" id="<?php echo $librow['activityFolder']; ?>" >
                  <div class="activity"></div>
                  <div class="activityInfo">
                    <div class="lessonName"><?php echo $librow['activityName']; ?></div>
                    <div class="lessonTitle"><?php echo $librow['activityName']; ?></div>
                    <div class="activityType"><?php if($librow['activityType'] == 'dragdrop') {
                        echo "Drag &amp; Drop";
                    } else if($librow['activityType'] == 'animatype') {
                        echo "Animation";
                    } else if($librow['activityType'] == 'videotype') {
                        echo "Video";
                    } else {
                        echo "Slide Show";
                    }
                    ?></div>                    
                  </div>
                  <div class="clr"></div>
                </li>
		<?php } ?>	             
              </ul>
            </div>
          </div>
        </div>
        <div class="clr"></div>
        <br />        
        
        <a id="editAddsave" style="cursor:pointer; cursor:hand;"><img src="images/btn_save.jpg" alt="Save" border="0" title="Save" /></a>&nbsp;
        
        
<!--        <a id="resetclick" ><img src="images/btn_cancel.jpg" alt="Cancel" border="0" title="Cancel"/></a>  -->
        
        </form>
	
	<!-- Popup background Starts here -->

	<div id="backgroundPopup"></div>

	<!-- Popup background Ends here -->

      <div class="clr"></div>
      <div class="actBottomLeft"></div>
      <div class="actBottomRight"></div>
    </div>
  </div>
</div>
<?php 
require_once("footer.php");
?>