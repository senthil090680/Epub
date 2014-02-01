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

$adjacents                              =               3;

$query                                  =               "SELECT COUNT(*) as totalPages FROM ".EPUB_SET."";
$total_pages                            =               mysql_fetch_array(mysql_query($query));
$total_pages                            =               $total_pages[totalPages];

$targetpage                             =               "epublibrarypaging.php";  //your file name  (the name of this file)
$limit                                  =               PAGE_PER_COUNT;    //how many items to show per page
$page                                   =               $_GET['page'];
if($page)
    $start                              =               ($page - 1) * $limit; 			//first item to display on this page
else
    $start                              =               0;

$dataquery                              =               "SELECT settingId,presetName,presetSet,epubVersion,outputType,bookTitle,coverImage,presetName,resol,supportDevice,fixedLay,openSpread,interActive,specificFont,fontName,oriLock,epubFolder,pdfFile,pdfPages,createdDate FROM ".EPUB_SET." ORDER BY createdDate desc LIMIT $start, $limit";
$dataresquery                           =		mysql_query($dataquery) or die(mysql_error());
$datanoofrows                           =		mysql_num_rows($dataresquery);

require_once("header.php");

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
			$pagination.= "<a onclick=\"pageepub($prev)\"> Previous</a>";
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
					$pagination.= "<a onclick=\"pageepub($counter)\" >$counter</a>";					
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
						$pagination.= "<a onclick=\"pageepub($counter)\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a onclick=\"pageepub($lpm1)\" >$lpm1</a>";
				$pagination.= "<a onclick=\"pageepub($lastpage)\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a onclick=\"pageepub(l)\">1</a>";
				$pagination.= "<a onclick=\"pageepub(2)\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a onclick=\"pageepub($counter)\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a onclick=\"pageepub($lpm1)\">$lpm1</a>";
				$pagination.= "<a onclick=\"pageepub($lastpage)\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a onclick=\"pageepub(l)\">1</a>";
				$pagination.= "<a onclick=\"pageepub(2)\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a onclick=\"pageepub($counter)\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a onclick=\"pageepub($next)\">Next </a>";
		else
			$pagination.= "<span class=\"disabled\">Next </span>";
		$pagination.= "</div>\n";		
	}
?>
    <div class="titleStrip">EPUB Library</div>
    <div class="ePubFrmBox">
      <form name="epublibform" id="epublibform" method="post" action="epubeditview.php" enctype="multipart/form-data">
        <h2>EPUB Library</h2>
        <table width="100%" border="1" cellpadding="10" cellspacing="0" class="frmTbl">
            <tr>
                <td align="center" class="boldText">BOOK TITLE</td><td align="center" class="boldText">EPUB VERSION</td><td align="center" class="boldText">CREATED DATE</td><td align="center" class="boldText">SUPPORTING DEVICE</td><td align="center" class="boldText">PREVIEW</td><td align="center" class="boldText">EDIT</td><td align="center" class="boldText">DOWNLOAD</td>                
            </tr>
            <?php if($datanoofrows > 0) { while($datarow = mysql_fetch_array($dataresquery)) { ?>

            <tr>
                <td ><?php echo ucwords(strtolower($datarow[bookTitle])); ?></td>
                <td align="center"><?php if($datarow[epubVersion] == 'version2') { echo "2"; } else { echo "3"; }; ?></td>
                <td <?php if($datarow[createdDate] == '') { ?> align="center" <?php } ?> ><?php if($datarow[createdDate] == '') echo '-'; else echo $datarow[createdDate]; ?></td>
                <td ><?php $device = ''; $supportdev = explode(',', $datarow[supportDevice]);
                /*echo "<pre>";
                print_r($supportdev);
                echo "</pre>";*/
                foreach($supportdev as $supportvalue) {
                    if($supportvalue == 'ipad') {
                        if($device == '') {
                            $device     .=       "IPAD";
                        } else {
                            $device     .=       ", IPAD";
                        }
                    } elseif ($supportvalue == 'kindle') { 
                        if($device == '') {
                            $device     .=       "KINDLE";
                        } else {
                            $device     .=       ", KINDLE";
                        }
                    } elseif ($supportvalue == 'sony') { 
                        if($device == '') {
                            $device     .=       "SONY";
                        } else {
                            $device     .=       ", SONY";
                        }
                    } elseif ($supportvalue == 'mobl21') { 
                        if($device == '') {
                            $device     .=       "MOBL21";
                        } else {
                            $device     .=       ", MOBL21";
                        }
                    }                
                } echo $device; ?></td>
                <td align="center" ><a href="javascript:void(0)" onclick="previewshow('<?php echo EPUB_FOLDER. "/".$datarow['epubFolder']."/OPS/"; ?>','<?php echo RELATIVE_PATH."/"; ?>','<?php echo base64_encode($datarow['settingId']); ?>');"><img src="images/preview.png" alt="PREVIEW" border="0" title="Preview"/></a></td>
                <td align="center" class="epublibedit"><input type="hidden" name="epubid" id="epubid" value="<?php echo base64_encode($datarow[settingId]); ?>" /><a href="javascript:void(0);" onclick="epublibedit('<?php echo base64_encode($datarow[settingId]); ?>');"><img src="images/edit.png" alt="EDIT" border="0" title="Edit"/></a></td>
                <td align="center">
    <?php $fpath = RELATIVE_PATH . '/' .EPUB_FOLDER .'/' .$datarow[epubFolder] . '/' . $datarow[epubFolder] . '.epub';
    echo '<a href="' . $fpath . '"	target="_blank" class="notextdec"><img src="images/old_go_down.png" alt="DOWNLOAD" border="0" title="DOWNLOAD"/> ePub</a>'; ?></td>                
            </tr>
            
            <?php } } else { ?>
            <tr>
                <td class="aligncenter" colspan="6">NO RECORDS FOUND</td>                
            </tr>
            <?php } ?>
            
        </table>        	

<?php echo $pagination; ?>

        
      </form>
      <div class="frmBottomLeft"></div>
      <div class="frmBottomRight"></div>
      <div class="clr"></div>
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
        header("location:activitylibrary.php?err=success");
    } else {
            die("ZIP NOT MOVED");
    }	
}
?>

