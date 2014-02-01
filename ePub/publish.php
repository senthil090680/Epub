<?php 
include_once('includes/config.php');
include_once('includes/functions.php');

//echo phpinfo();
//error_reporting(E_ALL);
error_reporting(0);

ini_set("display_errors",true);

// Get the parameters
$insert_id				=		isset($_POST["insert_id"]) ? base64_decode($_POST["insert_id"]) : '';

//DB INITIALIZATION
$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);
	
$dataquery				=		"SELECT settingId,presetName,presetSet,epubVersion,outputType,bookTitle,coverImage,presetName,resol,supportDevice,fixedLay,openSpread,interActive,specificFont,fontName,oriLock,epubFolder,pdfFile,pdfPages FROM ".EPUB_SET." WHERE settingId = '$insert_id'";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow				=		mysql_fetch_array($dataresquery);
$epubpath				=		RELATIVE_PATH."/".EPUB_FOLDER . "/".$datarow['epubFolder'];			
$outone					=		$epubpath . '/OPS/images/cover.jpg';

	//exit;
	require_once("header.php"); ?>
    <div class="titleStrip">Epub Process</div>
    <!-- Steps -->
    <div class="steps"> <img src="images/step1.jpg" width="301" height="69" alt="Step 1" title="Step 1"/><img src="images/step3_spliter1.jpg" width="30" height="69" /><img src="images/step2.jpg" alt="Step 2" title="Step 2" /><img src="images/step1_spliter2.jpg" width="30" height="69" /><img src="images/step3_active.jpg" width="302" height="69" alt="Step 4" title="Step 4"/></div>
    <div class="activityFrmBox">
      <form name="epubprocess">
        <div class="clr"></div>
        <div class="publishFrmBox">
          <div class="bookInfo">
		  <div class="fr quickNavi"><a href="javascript:void(0)" onclick="previewshow('<?php echo EPUB_FOLDER. "/".$datarow['epubFolder']."/OPS/"; ?>','<?php echo RELATIVE_PATH."/"; ?>','<?php echo base64_encode($datarow['settingId']); ?>');"><img src="images/btn_preview.jpg" alt="Preview" border="0" title="Preview"/></a></div>
            <div class="lessonTitle"><?php echo $datarow['bookTitle']; ?></div>
            <div class="pageInfo">Total Pages: <?php echo $datarow['pdfPages']; ?></div>
          </div>
          <div class="pageBox">
            <table border="0" cellspacing="0" cellpadding="10">
               <tr>
                 <td rowspan="8" valign="top" class="boldText"><img src="<?php echo $outone; ?>" width="100" height="125" alt="Cover Image" title="Cover Image"/></td>
                 <td class="boldText">EPub Version: </td>
                 <td><?php if($datarow['epubVersion'] == 'version2') { echo "Version 2"; } else { echo "Version 3"; } ?></td>
               </tr>
               <tr>
                 <td class="boldText">Output Type:</td>
                 <td><?php if($datarow['outputType'] == 2) { echo "HTML"; } else { echo "IMAGE"; }  ?></td>
               </tr>
               <tr>
                 <td class="boldText">Resolution:</td>
                 <td><?php echo $datarow['resol']; ?> DBI</td>
               </tr>
               <tr>
                 <td class="boldText">Select Layout: </td>
                 <td><?php if($datarow['fixedLay'] == 'fixtrue') { echo "Fixed-Layout"; } else { echo "No Fixed-Layout"; }  ?></td>
               </tr>
               <tr>
                 <td class="boldText">Fonts: </td>
                 <td><?php if($datarow['fontName'] != '') { echo ucfirst($datarow['fontName']); } else { echo "No Fonts"; }  ?></td>
               </tr>
               <tr>
                 <td class="boldText">Supporting Device:</td>
                 <td><?php	$supdev			=		str_replace(',',', ',$datarow['supportDevice']);
							echo ucwords(strtolower($supdev)); ?></td>
               </tr>
               <tr>
                 <td class="boldText">Orientation Type:</td>
                 <td><?php if($datarow['oriLock'] == 'portrait-only') { echo "Portrait-Only"; } elseif($datarow['oriLock'] == 'landscape-only') { echo "Landscape-Only"; } else { "Both"; }  ?></td>
               </tr>
               <tr>
                 <td class="boldText">Usee PDF:</td>
                 <td><?php echo ucfirst($datarow['pdfFile']); ?></td>
               </tr>
             </table>
          </div>
        </div>
        <div class="clr"></div>
        <br />
        <input type="hidden" id="insert_id" name="insert_id" value="<?php echo $_POST['insert_id']; ?>" />
        <a id="convertepub" class="cursorhand"><img src="images/btn_save.jpg" alt="Save" border="0" title="Save" /></a> <!--&nbsp;<a href="#"><img src="images/btn_save_publish.jpg" alt="Save & Publish" border="0" title="Save & Publish" /></a>&nbsp;<a href="#"><img src="images/btn_cancel.jpg" alt="Cancel" border="0" title="Cancel"/></a>-->
      </form>
      <div class="clr"></div>
      <div class="actBottomLeft"></div>
      <div class="actBottomRight"></div>
    </div>
  </div>
</div>
<?php require_once("footer.php"); ?>