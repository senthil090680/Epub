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
?>
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
                        <td ><?php echo ucfirst($datarow->activityDesc); ?></td>
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

