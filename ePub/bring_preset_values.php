<?php
include_once('includes/config.php');
include_once('includes/functions.php');

//error_reporting(E_ALL);

//ini_set("display_errors",true);

//echo "hi";
//error_reporting(0);
//ini_set('upload_max_filesize','30M');
//ini_set('post_max_size','30M');
//echo ini_get('upload_max_filesize');
//echo ini_get('display_errors');
//echo ini_get('post_max_size');
//echo phpinfo();


$dbconnect				=		dbconnection(DBNAME,SERVERNAME,DBUSERNAME,DBPASS);

$presetid				=		$_POST['presetval'];

$presetvalquery                         =		"SELECT presetId,presetname,versiontype,outputtype,dpires,supdev,fixlay,openspread,interlay,fontset,oriloc FROM ".PRESET_TABLE." WHERE presetId = '$presetid' ";


    $prevalresquery			=		mysql_query($presetvalquery) or die(mysql_error());
$prevalnor				=		mysql_num_rows($prevalresquery);
$valrow					=		mysql_fetch_object($prevalresquery);

?>
      <form id="form1" name="form1" method="post" enctype="multipart/form-data">
          <span style="float:right;">(*) are Mandatory Fields</span>
        <h2 class="presetdrop">Select Preset</h2>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">
          <tr class="presetdrop">
            <td width="13%" nowrap="nowrap">Preset Settings:</td>
            <td width="87%"><label>
              <select name="presetset" id="presetset" class="selectField selectFieldWidth1" onchange="presetselect();">
                <option value='' class="inputGrayText">Select</option>
				<?php $presetquery			=		"SELECT presetId,presetname FROM ".PRESET_TABLE."";
					$preresquery			=		mysql_query($presetquery) or die(mysql_error());
					$prenor					=		mysql_num_rows($preresquery);
					if($prenor > 0) { 
					while($row		=	mysql_fetch_object($preresquery)) { ?>
					<option value="<?php echo $row->presetId; ?>" <?php if($presetid == $row->presetId) { ?> selected  <?php } ?> ><?php echo ucwords(strtolower($row->presetname)); ?></option>
					<?php }} ?>
              </select>
              </label></td>
          </tr>
        </table>
        <h2>Document Settings</h2>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">
          <tr>
            <td width="13%">EPub Version *</td>
            <td width="87%"><label>
              <select name="versiontype" id="versiontype" class="selectField selectFieldWidth1" >
                <option value='' class="inputGrayText">Select</option>
				<option value="version2" <?php if($valrow->versiontype == 'version2') { ?> selected <?php } ?> >Version 2.0</option>
				<option value="version3" <?php if($valrow->versiontype == 'version3') { ?> selected <?php } ?> >Version 3.0</option>
              </select>
              </label></td>
          </tr>
          <tr>
            <td>Output Type *</td>
            <td><input name="outtype" id="outtype" type="radio" value="2" <?php if($valrow->outputtype == '2') { ?> checked <?php } ?> />
              HTML <!--&nbsp;&nbsp;
              <input name="outtype" id="outtype" type="radio" value="1" <?php if($valrow->outputtype == '1') { ?> checked <?php } ?> />
              &nbsp;Image --></td>
          </tr>
          <tr>
            <td>Book Title *</td>
            <td><label>
              <input type="text" name="booktitle" id="booktitle" class="inputField BookTitleInput" maxlength="50"/>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cover Image:&nbsp;&nbsp;
              <span id="uploadFile_div"><input type="file" name="coverimage" id="coverimage" onchange="checkimage();"/></span>
              </label></td>
          </tr>
          <tr>
            <td>Resolution:</td>
            <td>
				<!--<select name="select" class="selectField ">
                <option class="inputGrayText">Select</option>
              </select>&nbsp;&nbsp;-->
              
              <input type="text" name="dpires" id="dpires" class="inputField" maxlength="3" value="<?php echo $valrow->dpires; ?>"/>
              <span class="grayText">(Dbi Default 100) </span> </td>
          </tr>
        </table>
        <h2>Layout Settings</h2>
        <table width="100%" border="0" cellspacing="0" cellpadding="10"  class="frmTbl">
          <tr>
            <th align="left" class="vborder pLeft10"> Supporting Device *
              </td>
			<th align="left" class="vborder hideother">Select Layout:
              </td>
            <th align="left" class="vborder pLeft10 hideother">Font Settings *
              </td>            
            <th align="left" class="pLeft10 hideother">Orientation Type *
              </td>
          </tr>
          <tr>
            <td valign="top" class="vborder pLeft10"><label>
              <select name="supp[]" id="supp" size="1" multiple="multiple" class="supportDivice">
                <option value="nodev" >-Select-</option>
				<?php $supdevarr	=	explode(',',$valrow->supdev); ?>
				<option value="ipad" <?php foreach($supdevarr as $supdevval) { if($supdevval == 'ipad') { ?> selected <?php } } ?> >IPAD</option>
				<option value="kindle" <?php foreach($supdevarr as $supdevval) { if($supdevval == 'kindle') { ?> selected <?php } } ?> >KINDLE</option>
				<option value="sony" <?php foreach($supdevarr as $supdevval) { if($supdevval == 'sony') { ?> selected <?php } } ?> >Sony</option>
				<option value="mobl21" <?php foreach($supdevarr as $supdevval) { if($supdevval == 'mobl21') { ?> selected <?php } } ?> >Mobl21</option>
              </select>
              </label></td>
			<td valign="top" class="vborder hideother"><input type="checkbox" name="fixlay" id="fixlay" value="fixtrue" <?php if($valrow->fixlay == 'fixtrue') { ?> checked <?php } ?> />
              Fixed-Layout <br />
              <input type="checkbox" name="openspr" id="openspr" value="opetrue" <?php if($valrow->openspread == 'opetrue') { ?> checked <?php } ?> />
              Open-to-spread<br />
              <input type="checkbox" name="interac" id="interac" value="inttrue" <?php if($valrow->interlay == 'inttrue') { ?> checked <?php } ?> />
              Interactive</td>
            <td valign="top" class="vborder pLeft10 hideother"><input class="spfon" type="radio" name="spefon" id="spefon" value="spefal"  <?php if($valrow->fontset == 'spefal') { ?> checked <?php } ?> />
              Default Font<br />
              <input type="radio" name="spefon" id="spefon" class="spfon" value="spetrue" <?php if($valrow->fontset == 'spetrue') { ?> checked <?php } ?> />
              Custom Font<br />
              <br />
              <input type="file" name="upfonts" id="upfonts" <?php if($valrow->fontset == 'spetrue') { ?> style="display:block;"  <?php } else { ?> style="display:none;" <?php } ?> /></td>
            
            <td valign="top" class="pLeft10 hideother"><input name="oriloc" id="oriloc" type="radio" value="portrait-only" <?php if($valrow->oriloc == 'portrait-only') { ?> checked="checked" <?php } ?> />
              Portrait<br />
              <input name="oriloc" id="oriloc" type="radio" value="landscape-only" <?php if($valrow->oriloc == 'landscape-only') { ?> checked="checked" <?php } ?> />
              Landscape<br />
              <input name="oriloc" id="oriloc" type="radio" value="none" <?php if($valrow->oriloc == 'none') { ?> checked="checked" <?php } ?> />
             Both</td>
          </tr>
        </table>
		<h2>Upload File</h2>
		<table width="100%" border="0" cellpadding="10" cellspacing="0" class="frmTbl">
          <tr>
            <td width="10%">Select File *</td>
            <td width="90%"><label></label>
              <span class="vborder pLeft10" id="upload_pdf">
              <input type="file" name="pdffile" id="pdffile" onchange="checkpdf();"/>
            </span></td>
          </tr>
        </table>
		<div class="saveBox" id="presetbox"  style="display:none;">
		<input name="presetcheck" id="presetcheck" type="checkbox" value="presetval" />
		&nbsp;Save Settings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="presetbox1" style="display:none;">Preset Name:</span>&nbsp;&nbsp; 
		<label>
		<input type="text" name="presetname" id="presetname" style="display:none;" class="inputField" maxlength="20" />
		</label>
</div>
<br />
<!--<a href="javascript:void(0);" onclick="pdfcovert();"><img src="images/btn_submit.jpg" border="0" /></a>-->
<input type="image" src="images/btn_submit.jpg" border="0" onclick="return pdfcovert();" />
&nbsp;&nbsp;
<a id="resetclick" class="cursorhand"><img src="images/btn_cancel.jpg" alt="Cancel" border="0" title="Cancel"/></a>
      </form>
      <div class="frmBottomLeft"></div>
      <div class="frmBottomRight"></div>

	  <!-- empgi -->