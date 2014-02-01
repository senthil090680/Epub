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
?>
    <div class="titleStrip">Activity Library</div>
    <div class="ePubFrmBox">
      <form name="activityform" id="activityform" method="post" action="" enctype="multipart/form-data">
        <h2>Activity Settings</h2>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">                    
          <tr>
              <td>Activity &nbsp; Add:</td>
            <td><label>
              <a href="activitylibraryadd.php" class="notextdec">Add</a>   
              </label></td>
          </tr>
          <tr>
            <td>Activity &nbsp; View:</td>
            <td><label>
              <a href="activitylibraryview.php" class="notextdec">View</a>   
              </label></td>
          </tr>          
        </table>        
		
      </form>
      <div class="frmBottomLeft"></div>
      <div class="frmBottomRight"></div>
    </div>
  </div>
</div>

<?php
require_once("footer.php");

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
        } else {
                $queryoutput		=	'error';
        }
    }	       
    // Upload zip file to zip folder    
    if(move_uploaded_file($_FILES['actfile']['tmp_name'], $pdfpath)) {
            //die("ZIP MOVED");
        $success                        =       base64_encode("success");
        header("location:activitylibrary.php?err=$success");
    } else {
            die("ZIP NOT MOVED");
    }	
}
?>

