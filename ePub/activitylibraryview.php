<?php
ob_start();
//ob_end_flush();
include_once('includes/config.php');
include_once('includes/functions.php');

//var_dump (get_declared_classes());

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

/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/    
//exit;

//Start of Edit activity after clicking the SAVE button
if (isset($_POST['activityeditId'])) {

    /*echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    exit;*/
    
    // Getting posted values
    $activityname                       =		$_POST['activitynameEdit'];
    $activitytype                       =		$_POST['activitytypeEdit'];
    $activitydesc                       =		$_POST['actdescEdit'];
    $activityFolder                     =		$_POST['activityFolder'];
    $activityeditId                     =               base64_decode($_POST['activityeditId']);
        
    $actname				=		(isset($_FILES['actfileEdit']['name']) && $_FILES['actfileEdit']['name'] != '') ? $_FILES['actfileEdit']['name'] : '';
    
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
        if(move_uploaded_file($_FILES['actfileEdit']['tmp_name'], $pdfpath)) {
            //die("ZIP MOVED");
            $successEdit                            =       "success";
        } else {
                die("ZIP NOT MOVED");
        }
     } else {
        $successEdit                                =       "success";
        $updfields                                  =               "";        
     }
     
     $updquery                 =		"UPDATE ". ACTIVITY_LIBRARY." SET activityName='$activityname',activityType='$activitytype',activityDesc='$activitydesc'".$updfields." WHERE activityId='$activityeditId'";
     $updresquery               =		mysql_query($updquery) or die(mysql_error());
    
}
//End of Edit activity after clicking the SAVE button


//Add activity part comes after submitting
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
                    echo $success                    =       "success";
                    exit(0);
                    //header("location:activitylibraryadd.php?err=$success");
                } else {
                    die("ZIP NOT MOVED");
                }	
        } else {
                echo $success                        =       'error';
                exit(0);
        }
    }	           
}
//End of Add activity part comes after submitting


$adjacents                              =               3;

$query                                  =               "SELECT COUNT(*) as totalPages FROM ".ACTIVITY_LIBRARY."";
$total_pages                            =               mysql_fetch_array(mysql_query($query));
$total_pages                            =               $total_pages[totalPages];

$targetpage                             =               "activitylibrarypaging.php";  //your file name  (the name of this file)
$limit                                  =               PAGE_PER_COUNT;    //how many items to show per page
$page                                   =               $_GET['page'];
if($page)
    $start                              =               ($page - 1) * $limit; 			//first item to display on this page
else
    $start                              =               0;


$dataquery				=		"SELECT activityId,activityName,activityType,activityDesc,activityFile,activityFolder FROM ".ACTIVITY_LIBRARY." LIMIT $start, $limit";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datanor				=		mysql_num_rows($dataresquery);

if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;
              
$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a onclick=\"pageactivity($prev)\"> Previous</a>";
		else
			$pagination.= "<span class=\"disabled\"> Previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a onclick=\"pageactivity($counter)\" >$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a onclick=\"pageactivity($counter)\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a onclick=\"pageactivity($lpm1)\" >$lpm1</a>";
				$pagination.= "<a onclick=\"pageactivity($lastpage)\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a onclick=\"pageactivity(l)\">1</a>";
				$pagination.= "<a onclick=\"pageactivity(2)\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a onclick=\"pageactivity($counter)\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a onclick=\"pageactivity($lpm1)\">$lpm1</a>";
				$pagination.= "<a onclick=\"pageactivity($lastpage)\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a onclick=\"pageactivity(l)\">1</a>";
				$pagination.= "<a onclick=\"pageactivity(2)\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a onclick=\"pageactivity($counter)\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a onclick=\"pageactivity($next)\">Next </a>";
		else
			$pagination.= "<span class=\"disabled\">Next </span>";
		$pagination.= "</div>\n";		
	}



require_once("header.php");
?>
    <div class="titleStrip">Activity Library</div>
    <div>
        <div class="floatLeft">
            <div class="ePubViewFrmBox">
                <form name="activityform" id="activityform" method="post" action="" enctype="multipart/form-data">
                <h2>View Activity</h2>                                   
                    <table width="100%" border="1" cellpadding="10" cellspacing="0" class="frmTbl">                      
                    <tr>
                        <td class="aligncenter elementNowrap boldText">ACTIVITY NAME</td>
                        <td class="aligncenter boldText">ACTIVITY TYPE</td>
                        <td class="aligncenter boldText">ACTIVITY DESCRIPTION</td>
                        <td class="aligncenter boldText">EDIT</td>
                        <td class="aligncenter boldText">DELETE</td>
                        <td class="aligncenter boldText">PREVIEW</td>
                    </tr>

                    <?php if($datanor > 0) { 
                    while($datarow  =  mysql_fetch_object($dataresquery)) {  ?>          
                    <tr>
                        <td ><?php echo ucfirst($datarow->activityName); ?></td>
                        <td class="elementNowrap"><?php if($datarow->activityType == dragdrop) {
                        echo "Drag & Drop";                    
                        } elseif($datarow->activityType == animatype) {
                        echo "Animation";                    
                        } elseif($datarow->activityType == videotype) {
                        echo "Video";                    
                        } else {
                        echo "Slide Show";                    
                        } ?></td>
                        <td <?php if($datarow->activityDesc == '-') { ?> class="aligncenter" <?php } ?> ><?php echo ucfirst($datarow->activityDesc); ?></td>
                        <td class="aligncenter"><a href="javascript:void(0);" onclick="activityEdit('<?php echo base64_encode($datarow->activityId); ?>');" class="notextdec activityedit" ><img src="images/edit.png" alt="EDIT" border="0" title="Edit"/></a><span style="display:none;"><?php echo base64_encode($datarow->activityId); ?></span></td>
                        <td class="activitydelete aligncenter" ><a href="javascript:void(0);" onclick="activityDelete('<?php echo $datarow->activityName; ?>','<?php echo base64_encode($datarow->activityId); ?>');" class="notextdec" ><img src="images/delete.png" alt="DELETE" border="0" title="Delete"/></a><span style="display:none;"><?php echo base64_encode($datarow->activityId); ?></span><div style="display:none;"><?php echo $datarow->activityName; ?></div></td>
                        <td class="activitypreview aligncenter" ><a href="javascript:void(0);" onclick="actpreviewShow('<?php echo base64_encode($datarow->activityId); ?>','<?php echo RELATIVE_PATH."/"; ?>');" class="notextdec" ><img src="images/preview.png" alt="PREVIEW" border="0" title="Preview"/></a><span style="display:none;"><?php echo base64_encode($datarow->activityId); ?></span></td>
                    </tr>          
                    <?php } }  else { ?>
                        <tr>
                        <td class="aligncenter" colspan="6">NO RECORDS FOUND</td>            
                    </tr>
                    <?php } ?>           
                    </table>
                <?php echo $pagination; ?>                
                </form>
                <div class="frmBottomLeft"></div>
                <div class="frmBottomRight"></div>
                </div>
            
        </div>

        <div class="floatRight">
            <div class="ePubAddFrmBox">
                <form name="activityaddform" id="activityaddform" method="post" action="" enctype="multipart/form-data">
                    <h2>Add Activity</h2>
                    <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">                    
                    <tr id="actsuccess" style="display:none;">
                        <td colspan="2" style="font-weight:bold;">Activity successfully added</td>           
                    </tr> 
                        <tr id="actfailure" style="display:none;">
                            <td colspan="2" style="font-weight:bold;">Activity name already added</td>      
                        </tr>
                    <tr>
                        <td class="elementNowrap">Activity Name:</td>
                        <td><label>
                        <input type="text" name="activityname" id="activityname" class="inputField ActivityNameInput" maxlength="20"/>   
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
                            <textarea name="actdesc" id="actdesc" maxlength="100"></textarea>
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
            <a id="resetclick" class="cursorhand"><img src="images/btn_cancel.jpg" alt="Cancel" border="0" title="Cancel"/></a>
                </form>
                <div class="frmBottomLeft"></div>
                <div class="frmBottomRight"></div>
           </div>
                <!-- Start of Edit Activity  -->
                <div class="ePubEditFrmBox stylenone">
                    </div>
                <!-- End of Edit Activity  -->

            
            
            
        </div>
        
        <div class="clr"></div>
    </div>
    
  </div>
</div>



<?php
require_once("footer.php");
?>

