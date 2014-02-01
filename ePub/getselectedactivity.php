<?php
include_once('includes/config.php');
include_once('includes/functions.php');

$dbconnect                  =		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$activityselect             =           isset($_POST["activityselect"]) ? $_POST["activityselect"] : '';
?>
<ul class="activityList">
<?php
$libquery                              =		"SELECT activityId,activityName,activityType,activityFolder,activityFile FROM ".ACTIVITY_LIBRARY." WHERE activityType='$activityselect'";
$libresquery                           =		mysql_query($libquery) or die(mysql_error());
while($librow                          =                mysql_fetch_array($libresquery)) {
?>
    <li class="activityRow" onclick="activityDrop('<?php echo $librow['activityFolder']; ?>')" id="<?php echo $librow['activityFolder']; ?>">
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