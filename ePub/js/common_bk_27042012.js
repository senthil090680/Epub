// JavaScript Document
<!--

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr;for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document;if(d.images){if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments;for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){d.MM_p[j]=new Image;d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;if(!d) d=document;if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document;n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n];for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n);return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments;document.MM_sr=new Array;for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x;if(!x.oSrc) x.oSrc=x.src;x.src=a[i+2];}
}

 function PageLoader(state, status) {
	if($('#image_pre_loader').length > 0) {
		if(state ==	"load")	{
			$('#image_pre_loader').fadeIn('slow');
		}

		if(state ==	"unload") {
			$('#image_pre_loader').fadeOut('slow');
		}

		$('#status').html(status);
	}
}

$(document).ready(function(){
	
   $(".activityRow").on('mousedown', function () {  
	   var activityid	=	$(this).attr('id');
           var serverpath	=	$('#serverpath').val();
           var insert_id	=	$('#insert_id').val();
           var activpath	=	$('#activpath').val();
           var d                =       new Date();
           var curmon           =       d.getMonth()+1;
           if(curmon < 10) {
             var zerovar         =       "0";
           }else {
             var zerovar         =       "";  
           }
           var curdate  	=	""+d.getDate()+zerovar+curmon+d.getFullYear();           
           $(this).draggable({
		helper: "clone",
		stop: function(e, ui) {
			$("#htmlPopBox").droppable({
				drop : function handleCardDrop( event, ui ) {
					//alert("45");					
					var posturl		=	'unziprearrange.php';
                                        var unzipfile           = $.ajax({
						type		: "POST",
						url         : posturl,
						data        : {'activityid' : activityid, 'insert_id' : insert_id},
						cache		: false,
						async		: false,
						dataType	: "text"
					}).responseText;
                                        //load();
                                        alert(unzipfile);
                                        var str = unzipfile;
                                        var substr = str.split(';');
                                        var htmlnoext = substr[0].replace('.html','');

                                        $('<li class="htmlPage '+htmlnoext+'"><span id="'+serverpath+activpath+activityid+'_'+curdate+'/'+'" ></span><a onclick="droppopupwin();" id="'+substr[0]+'">'+htmlnoext+'</a></li>').appendTo('.htmlPageList');
				}
			});
		  }
		});          
	});
                       
	$('.activityRow').on('mouseup', function(){
            $(this).draggable( "destroy" );
	});
        
        
	$(".htmlPage").click(function(){

		var servername                  =		$('#servername').val();
		var htmldot			=		$(this).find('a').attr("id");
		alert(htmldot);
                var htmlpath			=		$(this).find('span').attr("id");
		var htmlid			=		htmldot.substring(0, htmldot.indexOf(".html"));
		var htmlbox			=		htmlid+"box";
		//var htmlid                    =		htmlid.replace("_", "");
		//alert(servername+htmldot);
		
		//return false;
		$(" <div />" ).attr("id",htmlid).addClass("minihtml").html('<div class="closepbox"><a href="javascript:void(0)" onclick="javascript:return closePrivateConfirm(this,\''+htmlid+'\');"><img src="images/pop-close-small.png" /></a></div><div style="display:block;display:inline;padding-left:20px;padding-right:80px;" id="'+htmlbox+'"></div>').appendTo($( "body" ));
                if(htmlpath == undefined) {
                    $('#'+htmlbox).load(servername+htmldot, function() {
                            //alert('Load was performed.');
                    });
                } else if(htmlpath != '' && htmlpath != undefined) {
                    alert(htmlpath+"ild");
                    $('#'+htmlbox).load(htmlpath+htmldot, function() {
                            //alert('Load was performed.');
                    });
                }
                

		$("#"+htmlid).css("display","block");
			
		//alert($("#"+htmlid).css("display"));
		$("#backgroundPopup").css({"opacity":"0.7"});
		$("#backgroundPopup").fadeIn("fast");
	});

	$('#skiptag').on('click',function () {
		$("#skipform").submit();	
	});


	$('.hideother').hide();

	$('#resetclick').on('click', function () {
		$('.hideother').hide();
		$('#presetbox1').hide();
		$('#presetname').hide();
		//$('#form1')[0].reset(); EASY WAY OF RESETTING THE FORM VALUES IN ONE LINE
		$('#form1').each(function () {
			this.reset();
			return false;
		});
	});

	$('#presetset').on('change', function () {
		var presetval		=	$('#presetset').val();

		if(presetval	!= "") {
			$('#presetname').val('');
			$('#presetcheck').attr("selected",false);
			$('#presetbox').hide();
			var posturl = "bring_preset_values.php";

			var mappresetvalues = $.ajax({
				type		: "POST",
				url         : posturl,
				data        : {'presetval' : presetval},
				cache		: false,
				async		: false,
				dataType	: "text"

			}).responseText;
			

			//alert(mappresetvalues);
			$('.ePubFrmBox').html(mappresetvalues);
			load();

			return false;
		} else {
			$('#presetname').val('');
			$('#presetcheck').attr("selected",false);
			$('#presetbox').show();
			return false;
		}
	});
			
	$('#supp').on('change', function () {
		var supde		=	$('#supp').val();
		//alert(typeof supde);
		if(supde	==	null) {
			$('.hideother').hide();
			return false;
		}
		var splitarray = supde.toString().split(",");
		var splitipad	=	splitarray[0];
		var splitsecond	=	splitarray[1];
		if(splitipad	!= "nodev") {
			if(splitipad == 'ipad') {
				$('.hideother').show();
				$('#upfonts').hide();
				return false;
			} else {
				$('.hideother').hide();
				return false;
			}
		} else {
			alert("Don't select first option");
			$("#supp option[value='nodev']").attr("selected",false);
			if(supde == 'nodev') {
				$('.hideother').hide();
				return false;
			} else {
				if(splitsecond	==	'ipad') {
					$('.hideother').show();
					return false;
				} else {
					$('.hideother').hide();
					return false;
				}
			}
		}
	});
	
	/*$("input[value='spetrue']").click(function () {
		
		alert($(this).val());
		alert("goodf");
		$('#upfonts').show();
	});*/

	$(".spfon").on('click', function () {
		var spefon		=		$(this).val();
		if(spefon	==	'spetrue') {
			$('#upfonts').show();
		} else {
			$('#upfonts').hide();
		}
	});
	
	$('#presetbox').on('click', function () {
		if($("#presetcheck").is(':checked')) {
			$(".presetdrop").hide();
			$("#presetbox1").show();
			$("#presetname").show();
		} else {
			$(".presetdrop").show();
			$("#presetbox1").hide();
			$("#presetname").hide();
			$("#presetname").val('');
		}
	});
	

	$('#form1').on('submit', function() {
	var booktitle	=	$('#booktitle').val();
	var dpires		=	$('#dpires').val();

	var filename	=	$('#pdffile').val();
	var extension	=	filename.substr((filename.lastIndexOf('.') +1));
	var lowpdf		=	extension.toLowerCase();

	//var laypro		=	$('#layprop').val();
	var supdev		=	$('#supp').val();
	var uploadfonts	=	$('#upfonts').val();
	var fonext		=	uploadfonts.substr((uploadfonts.lastIndexOf('.') +1));
	var cusfon		=	$("input[value='spetrue']:checked").val();
	var deffon		=	$("input[value='spefal']:checked").val();
	var versiontype	=	$('#versiontype').val();
	var outtype		=	$('#outtype:checked').val();	
	
		if(versiontype	==	'') {
			alert("Please select version");
			$('#versiontype').focus();
			return false;
		}
		if(!outtype) {
			alert("Please select output type");
			$('#outtype').focus();
			return false;
		}
		if(booktitle	==	'') {
			alert('Please enter book title.');
			$('#booktitle').focus();
			return false;
		}
		if(dpires		!=	'') {
			if(dpires < 100 ) {
				alert('Please enter DPI greater than or equal to 100.');
				$('#dpires').val('');
				$('#dpires').focus();
				return false;
			} else if (isNaN(dpires)) {
				alert('Please enter only numbers.');
				$('#dpires').val('');
				$('#dpires').focus();
				return false;
			}
		}
		if((supdev == 'nodev') || (supdev == null)) {
			alert('Please select device.');
			$('#supp').focus();
			return false;
		} else {
			
		}
		
		var splitarray	=	supdev.toString().split(",");
		var splitipad	=	splitarray[0];

		if(cusfon) {
			if(fonext == '') {
				alert('Please upload a ttf file.');
				$('#upfonts').focus();
				return false;
			}
			else if(fonext == 'ttf') {
				//return true;
			} else {
				alert('Please upload only ttf files.');
				$('#upfonts').focus();
				return false;
			}
		}
		
		var k		=	0;
		var g		=	0;
		if(splitipad		==		"ipad") {
			$(".hideother").each(function()
			{
				if ($(this).css("visibility") == "hidden")
				{
					// handle non visible state
				}
				else
				{	
					if(!deffon && !cusfon) {
						k++;
					}
					if(!$('#oriloc:checked').val()) {
						g++;
					}
					// handle visible state
				}			
			});
		}

		if(k > 0) {
			alert("Please select Font Settings");
			$('#deffon').focus();
			return false;
		}
		if(g > 0) {
			alert("Please select Orientation");
			$('#oriloc').focus();
			return false;
		}

		if(lowpdf == '') {
			alert('Please upload a pdf file.');
			return false;
		}
		else if(lowpdf == 'pdf') {
			//return true;
		} else {
			alert('Please upload only pdf files.');
			return false;
		}
		if($("#presetcheck").is(':checked')) {
			if($("#presetname").val() == '' ) {
				alert('Please enter preset name.');
				$("#presetname").focus();
				return false;
			}
		}
		return true;
	});
});

//-->


function load() {
	$('#resetclick').on('click', function () {
		$('.hideother').hide();
		$('#presetbox1').hide();
		$('#presetname').hide();
		//$('#form1')[0].reset(); EASY WAY OF RESETTING THE FORM VALUES IN ONE LINE
		$('#form1').each(function () {
			this.reset();
			return false;
		});
	});

	$('#presetset').on('change', function () {
		var presetval		=	$('#presetset').val();

		if(presetval	!= "") {
			var posturl = "bring_preset_values.php";
			var mappresetvalues = $.ajax({
				type		: "POST",
				url         : posturl,
				data        : {'presetval' : presetval},
				cache		: false,
				async		: false,
				dataType	: "text"

			}).responseText;
			
			//alert(mappresetvalues);
			$('.ePubFrmBox').html(mappresetvalues);
			$('#presetname').val('');
			$('#presetcheck').attr("selected",false);
			$('#presetbox').hide();

			load();
			return false;
		} else {
			$('#presetname').val('');
			$('#presetcheck').attr("selected",false);
			$('#presetbox').show();
			return false;
		}
	});
			
	$('#supp').on('change', function () {
		var supde		=	$('#supp').val();
		//alert(typeof supde);
		if(supde	==	null) {
			$('.hideother').hide();
			return false;
		}
		var splitarray = supde.toString().split(",");
		var splitipad	=	splitarray[0];
		var splitsecond	=	splitarray[1];
		if(splitipad	!= "nodev") {
			if(splitipad == 'ipad') {
				$('.hideother').show();
				$('#upfonts').hide();
				return false;
			} else {
				$('.hideother').hide();
				return false;
			}
		} else {
			alert("Don't select first option");
			$("#supp option[value='nodev']").attr("selected",false);
			if(supde == 'nodev') {
				$('.hideother').hide();
				return false;
			} else {
				if(splitsecond	==	'ipad') {
					$('.hideother').show();
					return false;
				} else {
					$('.hideother').hide();
					return false;
				}
			}
		}
	});
	
	/*$("input[value='spetrue']").click(function () {
		
		alert($(this).val());
		alert("goodf");
		$('#upfonts').show();
	});*/

	$(".spfon").on('click', function () {
		var spefon		=		$(this).val();
		if(spefon	==	'spetrue') {
			$('#upfonts').show();
		} else {
			$('#upfonts').hide();
		}
	});
	
	$('#presetbox').on('click', function () {
		if($("#presetcheck").is(':checked')) {
			$(".presetdrop").hide();
			$("#presetbox1").show();
			$("#presetname").show();
		} else {
			$(".presetdrop").show();
			$("#presetbox1").hide();
			$("#presetname").hide();
			$("#presetname").val('');
		}
	});
	

	$('#form1').on('submit', function() {
	var booktitle	=	$('#booktitle').val();
	var dpires		=	$('#dpires').val();

	var filename	=	$('#pdffile').val();
	var extension	=	filename.substr((filename.lastIndexOf('.') +1));
	var lowpdf		=	extension.toLowerCase();

	//var laypro		=	$('#layprop').val();
	var supdev		=	$('#supp').val();
	var uploadfonts	=	$('#upfonts').val();
	var fonext		=	uploadfonts.substr((uploadfonts.lastIndexOf('.') +1));
	var cusfon		=	$("input[value='spetrue']:checked").val();
	var deffon		=	$("input[value='spefal']:checked").val();
	var versiontype	=	$('#versiontype').val();
	var outtype		=	$('#outtype:checked').val();	
	
		if(versiontype	==	'') {
			alert("Please select version");
			$('#versiontype').focus();
			return false;
		}
		if(!outtype) {
			alert("Please select output type");
			$('#outtype').focus();
			return false;
		}
		if(booktitle	==	'') {
			alert('Please enter book title.');
			$('#booktitle').focus();
			return false;
		}
		if(dpires		!=	'') {
			if(dpires < 100 ) {
				alert('Please enter DPI greater than or equal to 100.');
				$('#dpires').val('');
				$('#dpires').focus();
				return false;
			} else if (isNaN(dpires)) {
				alert('Please enter only numbers.');
				$('#dpires').val('');
				$('#dpires').focus();
				return false;
			}
		}
		if((supdev == 'nodev') || (supdev == null)) {
			alert('Please select device.');
			$('#supp').focus();
			return false;
		} else {
			
		}
		
		var splitarray	=	supdev.toString().split(",");
		var splitipad	=	splitarray[0];

		if(cusfon) {
			if(fonext == '') {
				alert('Please upload a ttf file.');
				$('#upfonts').focus();
				return false;
			}
			else if(fonext == 'ttf') {
				//return true;
			} else {
				alert('Please upload only ttf files.');
				$('#upfonts').focus();
				return false;
			}
		}
		
		var k		=	0;
		var g		=	0;
		if(splitipad		==		"ipad") {
			$(".hideother").each(function()
			{
				if ($(this).css("visibility") == "hidden")
				{
					// handle non visible state
				}
				else
				{	
					if(!deffon && !cusfon) {
						k++;
					}
					if(!$('#oriloc:checked').val()) {
						g++;
					}
					// handle visible state
				}			
			});
		}

		if(k > 0) {
			alert("Please select Font Settings");
			$('#deffon').focus();
			return false;
		}
		if(g > 0) {
			alert("Please select Orientation");
			$('#oriloc').focus();
			return false;
		}

		if(lowpdf == '') {
			alert('Please upload a pdf file.');
			return false;
		}
		else if(lowpdf == 'pdf') {
			//return true;
		} else {
			alert('Please upload only pdf files.');
			return false;
		}
		if($("#presetcheck").is(':checked')) {
			if($("#presetname").val() == '' ) {
				alert('Please enter preset name.');
				$("#presetname").focus();
				return false;
			}
		}
		return true;
	});

	/*$("input[type=radio][checked]").each( function() { 			
		  var spefon		=	 $(this).val();
		   if(spefon	==	'spetrue') {
				$('#upfonts').show();
			} else {
				$('#upfonts').hide();
			}
		} 
	);*/
}

function closePrivateConfirm(abc,htmlpageid) {
	$("#backgroundPopup").fadeOut("fast");
	$("#"+htmlpageid).css('display','none');	
}

function droppopupwin(){
    $(".htmlPage").click(function(){
		var servername                  =		$('#servername').val();
		var htmldot			=		$(this).find('a').attr("id");
		alert(htmldot);
                var htmlpath			=		$(this).find('span').attr("id");
		var htmlid			=		htmldot.substring(0, htmldot.indexOf(".html"));
		var htmlbox			=		htmlid+"box";
		//var htmlid                    =		htmlid.replace("_", "");
		//alert(servername+htmldot);
		
		//return false;
		$(" <div />" ).attr("id",htmlid).addClass("minihtml").html('<div class="closepbox"><a href="javascript:void(0)" onclick="javascript:return closePrivateConfirm(this,\''+htmlid+'\');"><img src="images/pop-close-small.png" /></a></div><div style="display:block;display:inline;padding-left:20px;padding-right:80px;" id="'+htmlbox+'"></div>').appendTo($( "body" ));
                if(htmlpath == undefined) {
                    $('#'+htmlbox).load(servername+htmldot, function() {
                            //alert('Normal Load was performed.');
                    });
                } else if(htmlpath != '' && htmlpath != undefined) {
                    alert(htmlpath+htmldot);
                    $('#'+htmlbox).load(htmlpath+htmldot, function() {
                            //alert('NEw Load was performed.');
                    });
                }
                

		$("#"+htmlid).css("display","block");
			
		//alert($("#"+htmlid).css("display"));
		$("#backgroundPopup").css({"opacity":"0.7"});
		$("#backgroundPopup").fadeIn("fast");
	});    
}