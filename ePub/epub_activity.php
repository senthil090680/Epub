<?php
session_start();
include_once('includes/config.php');
include_once('includes/functions.php');

//echo phpinfo();
//exit;

//error_reporting(E_ALL);
error_reporting(0);

ini_set("display_errors",true);

$_SESSION['insert_id']                  =               base64_encode(3); //This line needs to be removed after testing
//$insert_id				=		base64_decode($_GET['insid']);
$insert_id				=		base64_decode($_SESSION['insert_id']);

//DB INITIALIZATION
$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$dataquery				=		"SELECT settingId,presetName,presetSet,epubVersion,outputType,bookTitle,coverImage,presetName,resol,supportDevice,fixedLay,openSpread,interActive,specificFont,fontName,oriLock,epubFolder,pdfFile,pdfPages FROM ".EPUB_SET." WHERE settingId = '$insert_id'";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow				=		mysql_fetch_array($dataresquery);

require_once("header.php");
?>
<script language="JavaScript" type="text/javascript" src="js/core.js"></script>
<script language="JavaScript" type="text/javascript" src="js/events.js"></script>
<script language="JavaScript" type="text/javascript" src="js/css.js"></script>
<script language="JavaScript" type="text/javascript" src="js/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="js/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="js/dragsort.js"></script>
<script language="JavaScript" type="text/javascript" src="js/cookies.js"></script>
<script language="JavaScript" type="text/javascript"><!--
	var dragsort = ToolMan.dragsort()
	var junkdrawer = ToolMan.junkdrawer()

	window.onload = function() {
            junkdrawer.restoreListOrder("boxes")
            dragsort.makeListSortable(document.getElementById("boxes"))
	}

	

	//-->
</script>

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
            <div class="pageInfo" id="totalpages">Total Pages: <?php echo $datarow['pdfPages']; ?></div>
          </div>
          <div class="pageBox" id="htmlPopBox">
            <ul class="htmlPageList" id="boxes">
				<?php
				$pagecnt			=		0;
				$handle                         =		opendir($outone);
				while ($name                    =		readdir($handle)) {
				$fileexten			=		GetFileExtension($name);
				$startfile			=		substr($name, 0, 3);
                                    if($startfile == 'act') {
                                        $acthandle                      =		opendir($outone.$name);
                                  while ($actname                    =		readdir($acthandle)) {
                                     $actfileexten                   =		GetFileExtension($actname);
                                     
                                     if ($actfileexten == 'html') {
                                     ?> 
                
                
                <li class="htmlPage <?php $actnamval = str_replace(".html","",$actname); $actnamval =   str_replace('_','',$actnamval); $actnamval  =   str_replace('_','',$actnamval); echo $actnamval; ?>" id="<?php echo $actnamval; ?>" linewid="<?php echo $name.'/'.$actname; ?>" ><span id="<?php echo RELATIVE_PATH."/".$outone.$name; ?>" ></span><div class="pgEditDelete"><img src="images/page_delete.png" onclick="deleteAddActivity('<?php echo $name; ?>','<?php echo $outone; ?>','<?php echo $actnamval; ?>');" /></div><a onclick="popupclick('<?php echo $actname; ?>','<?php echo RELATIVE_PATH."/".$outone.$name.'/'; ?>')" id="<?php echo $actname; ?>"><?php echo substr($actname,0,7)."..."; ?></a></li> <?php
                                        }
                                    }
                                } 
                                    if ($startfile == 'pg_' && $fileexten == 'html') {
                                            $pagecnt++;
                                            ?> <li class="htmlPage <?php $namval = str_replace(".html","",$name); $namval =   str_replace('_','',$namval); echo $namval; ?>" id="<?php echo $namval; ?>" linewid="<?php echo $name; ?>" ><div class="pgEditDelete"><img src="images/page_delete.png" onclick="deleteHtmlActivity('<?php echo base64_encode($name); ?>','<?php echo base64_encode($datarow[epubFolder]); ?>','<?php echo $namval; ?>');" /></div><a onclick="popupclick('<?php echo $name; ?>','<?php echo RELATIVE_PATH."/".$outone.'/'; ?>')" id="<?php echo $name; ?>"><?php echo substr($name,0,7)."..."; ?></a></li> <?php						
                                    }
                                }
			?>
              
            </ul>
			<div class="clr"></div>
          </div>
        </div>
		<form name="skipform" id="skipform" method="post" action="publish.php"><input type="hidden" id="insert_id" name="insert_id" value="<?php echo $_SESSION['insert_id']; ?>" /><input type="hidden" id="servername" name="servername" value="<?php echo RELATIVE_PATH."/".$outone; ?>" /><input type="hidden" id="serverpath" name="serverpath" value="<?php echo RELATIVE_PATH."/"; ?>" /><input type="hidden" id="activpath" name="activpath" value="<?php echo EPUB_FOLDER. "/".$datarow['epubFolder']."/OPS/"; ?>" /> 
		</form>
        <div class="rightPanel"><!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" style="text-decoration:none;" onclick="addInstantActivity();"><img src="images/add_activity.png" alt="Add Activity" border="0" title="Add Activity"/></a>-->
          <div class="quickNavi textAlignRight"><a href="javascript:void(0)" onclick="swappreviewshow('<?php echo EPUB_FOLDER. "/".$datarow['epubFolder']."/OPS/"; ?>','<?php echo RELATIVE_PATH."/"; ?>','<?php echo $_SESSION['insert_id']; ?>');"><img src="images/btn_preview.jpg" alt="Preview" border="0" title="Preview"/></a>&nbsp;<a href="javascript:void(0)" id="skiptag"><img src="images/btn_skipactivity.jpg" alt="Skip Activity" border="0" title="Skip Activity"/></a></div>
		  <form name="epubprocess" id="epubprocess">
          <div class="activityBox" style="overflow: auto;">
            <select name="activityselect" id="activityselect" class="selectByActivity">
              <option value="" class="inputGrayText">Select</option>
              <option value="dragdrop">Drag &amp; Drop</option>
              <option value="animatype">Animation</option>
              <option value="videotype">Video</option>
              <option value="slidetype">Slide Show</option>
            </select>
            <div id="choiceofselect">             
              <ul class="activityList">
                <?php
$libquery                              =		"SELECT activityId,activityName,activityType,activityFolder,activityFile FROM ".ACTIVITY_LIBRARY."";
$libresquery                           =		mysql_query($libquery) or die(mysql_error());
while($librow                          =                mysql_fetch_array($libresquery)) {
?>
                <li class="activityRow" onclick="activityDrop('<?php echo $librow['activityFolder']; ?>');" id="<?php echo $librow['activityFolder']; ?>">
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
        
        <a href="javascript:void(0);" onclick="addsaveepub();" class="cursorhand"><img src="images/btn_save.jpg" alt="Save" border="0" title="Save" /></a>&nbsp;
        
        
<!--        <a id="resetclick" ><img src="images/btn_cancel.jpg" alt="Cancel" border="0" title="Cancel"/></a>  -->
        
        </form>
	
          
	<!-- Popup background Starts here -->

	<div id="backgroundPopup"></div>

	<!-- Popup background Ends here -->

      <div class="clr"></div>
      <div class="actBottomLeft"></div>
      <div class="actBottomRight"></div>
    </div>
    
    <div class="ePubFrmBoxInstant" id="addInstantAct" style="display:none;z-index:2;position:absolute;top:30%;left:20%;">
        <div class="closeInstbox"><a href="javascript:void(0)" onclick="javascript:return closePrivateConfirm(this,'addInstantAct');"><img src="images/pop-close-small.png" /></a></div>
      <form name="activityformInst" id="activityformInst" enctype="multipart/form-data">
        <h2>Activity Settings</h2>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">                    
          <tr>
            <td nowrap="nowrap">Activity Name:</td>
            <td><label>
              <input type="text" name="InstActivityname" id="InstActivityname" class="inputField BookTitleInput"/>   
              </label></td>
          </tr>
          <tr>
            <td width="13%" nowrap="nowrap">Activity Type:</td>
            <td width="87%"><label>
              <select name="InstActivitytype" id="InstActivitytype" class="selectField selectFieldWidth1">
                <option value='' class="inputGrayText">Select</option>
				<option value="dragdrop">Drag & Drop</option>
				<option value="animatype">Animation</option>
                                <option value="videotype">Video</option>
                                <option value="slidetype">Slide Show</option>
              </select>
              </label></td>
          </tr>
          <tr>
            <td nowrap="nowrap">Description:</td>
            <td>
                <textarea name="InstActdesc" id="InstActdesc" ></textarea>
              </td>
          </tr>
          <tr>
            <td width="13%" nowrap="nowrap">Select File:</td>
            <td width="87%"><label></label>
              <span class="vborder">
              <input type="file" name="InstActfile" id="InstActfile" />
            </span></td>
          </tr>
        </table>        
		
<br />
<a class="cursorhand"><img src="images/btn_submit.jpg" alt="Submit" border="0" title="Submit"  onclick="actInstantSubmit();"/></a>&nbsp;&nbsp;
<a class="cursorhand"><img src="images/btn_cancel.jpg" alt="Cancel" border="0" title="Cancel" onclick="actInstantCancel();"/></a>
      </form>
      <div class="frmBottomLeft"></div>
      <div class="frmBottomRight"></div>
    </div>
    
    
    
  </div>
</div>
<?php 
require_once("footer.php");
?>