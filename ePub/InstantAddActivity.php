<?php
include_once('includes/config.php');
include_once('includes/functions.php');

$dbconnect                  =		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

// Getting posted values
    $activityname                       =		$_REQUEST['activityname'];    
    //exit(0);
    $activitytype                       =		$_REQUEST['activitytype'];
    $activitydesc                       =		$_REQUEST['activityDesc'];
    
    $actname				=		(isset($_FILES['InstActfile']['name']) && $_FILES['InstActfile']['name'] != '') ? $_FILES['InstActfile']['name'] : '';
   
    $createActFolder                    =               "act".time();
    // Genrate unique zip file name
    $z_filename				=		SetUniqFileName($actname);
    $z_dirname				=		RemoveExtension($z_filename);
    
    if(!mkdir(ACTIVITY_FOLDER .'/'. $createActFolder. '/')) {        
        die("No activity folder created");
        /*echo "{";
        echo				"msg: 'No activity folder created'";
        echo "}";
        exit(0);*/
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
                if(move_uploaded_file($_FILES['InstActfile']['tmp_name'], $pdfpath)) {
                    /*echo "{";
                    echo				"msg: 'success'";
                    echo "}";*/
                    $success                        =       "success"; ?>
                    <ul class="activityList">
                    <?php
                    $libquery                              =		"SELECT activityId,activityName,activityType,activityFolder,activityFile FROM ".ACTIVITY_LIBRARY."";
                    $libresquery                           =		mysql_query($libquery) or die(mysql_error());
                    while($librow                          =            mysql_fetch_array($libresquery)) {
                    ?>
                        <li class="activityRow" onclick="activityDrop('<?php echo $librow['activityFolder']; ?>');" id="<?php echo $librow['activityFolder']; ?>">
                            <div class="activity"></div>
                            <div class="activityInfo">
                            <div class="lessonName"><?php echo $librow['activityName']; ?></div>
                            <div class="lessonTitle">Activity Title</div>
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
                    <?php
                    
                    
                    //header("location:activitylibraryadd.php?err=$success");
                } else {
                        die("ZIP NOT MOVED");
                    /*echo "{";
                    echo				"msg: 'ZIP NOT MOVED'";
                    echo "}";
                    exit(0);*/
                }	
        } else {
                /*echo "{";
                echo				"msg: 'error'";
                echo "}";*/
                $success		=	'error';
        }
    }	
    
?>