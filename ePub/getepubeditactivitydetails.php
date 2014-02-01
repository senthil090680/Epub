<?php
include_once('includes/config.php');
include_once('includes/functions.php');

$dbconnect                  =		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$activityid                 =           isset($_POST["activityId"]) ? base64_decode($_POST["activityId"]) : '';

$dataquery				=		"SELECT activityId,activityName,activityType,activityDesc,activityFile,activityFolder FROM ".ACTIVITY_LIBRARY." WHERE activityId = '$activityid'";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datarow                                =               mysql_fetch_object($dataresquery);


?>
<form id="formactivityedit" name="formactivityedit" method="post" enctype="multipart/form-data">
                        <h2>Edit Activity</h2>
                        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">                               <tr>
                            <td>Activity Name:</td>
                            <td><label>
                            <input type="text" name="activitynameEdit" id="activitynameEdit" value="<?php echo $datarow->activityName; ?>" class="inputField ActivityNameAjax" maxlength="20"/>   
                            </label></td>
                        </tr>
                        <tr>
                            <td width="13%">Activity Type:</td>
                            <td width="87%"><label>
                            <select name="activitytypeEdit" id="activitytypeEdit" class="selectField selectFieldWidth1">
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
                                <textarea name="actdescEdit" id="actdescEdit" maxlength="100"><?php if($datarow->activityDesc != '-') { echo $datarow->activityDesc; } ?></textarea>
                                <input type="hidden" name="activityeditId" id="activityeditId" value="<?php echo $_POST['activityId']; ?>" />
                                <input type="hidden" name="activityFolder" id="activityFolder" value="<?php echo $datarow->activityFolder; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td nowrap="nowrap" width="13%">Select New Zip File:</td>
                            <td width="87%"><label></label>
                            <span class="vborder">
                                <input type="file" name="actfileEdit" id="actfileEdit" /><br/>
                                <label>Current Zip File: </label>&nbsp;<span><?php echo $datarow->activityFile; ?></span>
                            </span></td>
                        </tr>
                        </table>        

                <br />
                <a href="javascript:void(0);" onclick="return actEditSubmit();"><img src="images/btn_save.jpg" border="0" title="Edit Activity" alt="Save"/> </a>
                
                <!--&nbsp;<a href="javascript:void(0);" onclick="cancelAction('formactivityedit')"><img src="images/btn_cancel.jpg" border="0" title="Cancel" alt="Cancel"/></a>-->
                </form>
                    <div class="frmBottomLeft"></div>
                    <div class="frmBottomRight"></div>                  