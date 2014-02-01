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

$activityid                             =               base64_decode($_GET['activityId']);

//DB INITIALIZATION
$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);
$dataquery				=		"SELECT activityId,activityName,activityType,activityDesc,activityFile,activityFolder FROM ".ACTIVITY_LIBRARY." WHERE activityId = '$activityid'";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow                                =               mysql_fetch_object($dataresquery);

require_once("header.php");
?>
    

<?php






/*echo "<pre>";
print_r($_FILES);
echo "</pre>";

exit;*/



if (isset($_POST['acteditsubmit_x'])) {

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    //exit;
    
    // Getting posted values
    $activityname                       =		$_POST['activityname'];
    $activitytype                       =		$_POST['activitytype'];
    $activitydesc                       =		$_POST['actdesc'];
    $activityFolder                       =		$_POST['activityFolder'];
    $activityeditId                     =               base64_decode($_POST['activityeditId']);
        
    $actname				=		(isset($_FILES['actfile']['name']) && $_FILES['actfile']['name'] != '') ? $_FILES['actfile']['name'] : '';
    
    if($actname != '') {
        $createActFolder                        =               "act".time();
        // Genrate unique zip file name
        $z_filename				=		SetUniqFileName($actname);
        $z_dirname				=		RemoveExtension($z_filename);

        if(!mkdir(ACTIVITY_FOLDER .'/'. $createActFolder. '/')) {        
            die('No activity folder created');
        }
        $pdfpath				=		ACTIVITY_FOLDER .'/'. $createActFolder. '/'. $z_filename;
        $updfields                              =               ",activityFile='$z_filename',activityFolder='$createActFolder'";
        
        $dir                                    =               ACTIVITY_FOLDER."/".$activityFolder;
        
        deleteDir($dir);
                
        // Upload zip file to zip folder      
        if(move_uploaded_file($_FILES['actfile']['tmp_name'], $pdfpath)) {
            //die("ZIP MOVED");
            $success                            =       "success";
        } else {
                die("ZIP NOT MOVED");
        }
     } else {
        $success                                =       "success";
        $updfields                              =               "";        
     }
     
     $updquery                 =		"UPDATE ". ACTIVITY_LIBRARY." SET activityName='$activityname',activityType='$activitytype',activityDesc='$activitydesc'".$updfields." WHERE activityId='$activityeditId'";
     $updresquery               =		mysql_query($updquery) or die(mysql_error());
    
    if(isset($success) && $success == 'success') {      
        header("location:activitylibraryview.php");        
    }	
}
?>

<div class="titleStrip">Activity Library</div>
    <div class="ePubFrmBox">
      <form id="formactivityedit" name="formactivityedit" method="post" enctype="multipart/form-data">
        <h2>Edit Activity</h2>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">                               <tr>
            <td>Activity Name:</td>
            <td><label>
              <input type="text" name="activityname" id="activityname" value="<?php echo $datarow->activityName; ?>" class="inputField BookTitleInput"/>   
              </label></td>
          </tr>
          <tr>
            <td width="13%">Activity Type:</td>
            <td width="87%"><label>
              <select name="activitytype" id="activitytype" class="selectField selectFieldWidth1">
                <option value='' class="inputGrayText">Select</option>
				<option value="dragdrop" <?php if($datarow->activityType == 'dragdrop') { ?> selected <?php } ?> >Drag & Drop</option>
				<option value="animatype" <?php if($datarow->activityType == 'animatype') { ?> selected <?php } ?> >Animation</option>
                                <option value="videotype" <?php if($datarow->activityType == 'videotype') { ?> selected <?php } ?> >Video</option>
                                <option value="slidetype" <?php if($datarow->activityType == 'slidetype') { ?> selected <?php } ?>>Slide Show</option>
              </select>
              </label></td>
          </tr>
          <tr>
            <td>Description:</td>
            <td>
                <textarea name="actdesc" id="actdesc" ><?php echo $datarow->activityDesc; ?></textarea>
                <input type="hidden" name="activityeditId" id="activityeditId" value="<?php echo $_GET['activityId']; ?>" />
                <input type="hidden" name="activityFolder" id="activityFolder" value="<?php echo $datarow->activityFolder; ?>" />
              </td>
          </tr>
          <tr>
            <td nowrap="nowrap" width="13%">Select New Zip File:</td>
            <td width="87%"><label></label>
              <span class="vborder">
                  <input type="file" name="actfile" id="actfile" /><label>Current Zip File: </label>&nbsp;<span><?php echo $datarow->activityFile; ?></span>
            </span></td>
          </tr>
        </table>        
		
<br />
<input type="image" src="images/btn_save.jpg" name="acteditsubmit" id="acteditsubmit" value="Submit" border="0" title="Edit Activity" alt="Save"/>&nbsp;<a href="activitylibraryview.php">BACK</a>
  </form>
      <div class="frmBottomLeft"></div>
      <div class="frmBottomRight"></div>
    </div>
  </div>
</div>

<?php require_once("footer.php"); ?>