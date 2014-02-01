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

//DB INITIALIZATION
$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

require_once("header.php");

/*echo "<pre>";
print_r($_POST);
echo "</pre>";

exit;*/


/*echo "<pre>";
print_r($_FILES);
echo "</pre>";

exit;*/

if (isset($_POST['actsubmit_x']) && isset($_FILES['actfile']['name']) && $_FILES['actfile']['name'] != '') {

    // Getting posted values
    $activityname                       =		$_POST['activityname'];
    $activitytype                       =		$_POST['activitytype'];
    $activitydesc                       =		$_POST['actdesc'];
    
    $actname				=		(isset($_FILES['actfile']['name']) && $_FILES['actfile']['name'] != '') ? $_FILES['actfile']['name'] : '';
			
    $createActFolder                    =               "act".time();
    // Genrate unique zip file name
    $z_filename				=		SetUniqFileName($actname);
    $z_dirname				=		RemoveExtension($z_filename);
    
    if(!mkdir(ACTIVITY_FOLDER .'/'. $createActFolder. '/')) {        
        die('No activity folder created');
    }
    $pdfpath				=		ACTIVITY_FOLDER .'/'. $createActFolder. '/'. $z_filename;
  
    if($activityname != '' && $activitytype != '') {
        $selquery			=		"SELECT `activityName` FROM ".ACTIVITY_LIBRARY." WHERE activityName= '$activityname' ";
        $selresquery            	=		mysql_query($selquery) or die(mysql_error());
        $selnor				=		mysql_num_rows($selresquery);

        if($selnor == 0 ) {
                $insquery		=		"INSERT INTO ". ACTIVITY_LIBRARY." (`activityName`,`activityType`,`activityDesc`,`activityFile`,`activityFolder`) values ('$activityname','$activitytype','$activitydesc','$z_filename','$createActFolder')";
                $insresquery            =		mysql_query($insquery) or die(mysql_error());	
                
                // Upload zip file to zip folder    
                if(move_uploaded_file($_FILES['actfile']['tmp_name'], $pdfpath)) {
                        //die("ZIP MOVED");
                    $success                        =       "success";
                    //header("location:activitylibraryadd.php?err=$success");
                } else {
                        die("ZIP NOT MOVED");
                }	
        } else {
                $success		=	'error';
        }
    }	           
}
?>

<div class="titleStrip">Activity Library</div>
    <div class="ePubFrmBox">
      <form id="form1" name="activityform" id="activityform" method="post" action="" enctype="multipart/form-data">
        <h2>Activity Settings</h2>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">                    
          <?php //$succ = base64_decode($_GET['err']); 
          if(isset($success) && $success == 'success') { ?>
            <tr>
            <td nowrap="nowrap" style="font-weight:bold;">Activity successfully added</td>            
          </tr> <?php } elseif(isset($success) && $success == 'error') { ?>
            <tr>
                <td nowrap="nowrap" style="font-weight:bold;">Activity name already added</td>            
            </tr>
          <?php } unset($success); ?>
          <tr>
            <td>Activity Name:</td>
            <td><label>
              <input type="text" name="activityname" id="activityname" class="inputField BookTitleInput"/>   
              </label></td>
          </tr>
          <tr>
            <td width="13%">Activity Type:</td>
            <td width="87%"><label>
              <select name="activitytype" id="activitytype" class="selectField selectFieldWidth1">
                <option value='' class="inputGrayText">Select</option>
				<option value="dragdrop">Drag & Drop</option>
				<option value="animatype">Animation</option>
                                <option value="videotype">Video</option>
                                <option value="slidetype">Slide Show</option>
              </select>
              </label></td>
          </tr>
          <tr>
            <td>Description:</td>
            <td>
                <textarea name="actdesc" id="actdesc" ></textarea>
              </td>
          </tr>
          <tr>
            <td width="13%">Select File:</td>
            <td width="87%"><label></label>
              <span class="vborder">
              <input type="file" name="actfile" id="actfile" />
            </span></td>
          </tr>
        </table>        
		
<br />
<input type="image" src="images/btn_submit.jpg" name="actsubmit" id="actsubmit" value="Submit" border="0" title="Submit Activity" alt="Submit"/>
&nbsp;&nbsp;
<a id="resetclick" style="cursor:pointer; cursor:hand;"><img src="images/btn_cancel.jpg" alt="Cancel" border="0" title="Cancel"/></a>
      </form>
      <div class="frmBottomLeft"></div>
      <div class="frmBottomRight"></div>
    </div>
  </div>
</div>

<?php require_once("footer.php"); ?>