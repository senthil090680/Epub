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
   
   //Delete activity from the epub already merged and stored in database
   
   /*$('.pgdelete').on('click', function() {
       //alert('ok');
       //return false;
        var pgDelId         =       $(this).find('#pgdelid').val();
        var epubId          =       $(this).find('#epubid').val();
        var settingid       =       $(this).find('#settingid').val();
        //alert(pgDelId);
        return false;
        
        var posturl          =	'epubactivitydelete.php';
        var delactfromepub   =       $.ajax({
            type            :       "POST",
            url             :       posturl,
            data            :       {'pgDelId' : pgDelId, 'epubId' : epubId },
            cache           :       false,
            async           :       false,
            dataType        :       "text"
        }).responseText;
        //alert(delactfromepub);
        window.location     =       'epubeditview.php?epubid='+settingid;
   });*/
    
   //Activity edit option after clicking the ID
   /*$('.activityedit').on('click', function() {
       var activityId   =   $(this).next('span').html(); //.find will find the inner elements (this is which is present inside the current elements) of the current elements, but .next will find the child elements of the current elements
       //alert(activityId+"good");
       window.location = "activitylibraryedit.php?activityId="+activityId;
   });*/
     
   //Activity edit saving after clicking the Save button
   $('#acteditsubmit').on('click', function() {
       alert('erer');
        var activityname            =	$('#activityname').val();
	var activitytype            =	$('#activitytype').val();

	var filename                =	$('#actfile').val();
	var extension               =	filename.substr((filename.lastIndexOf('.') +1));
	var lowpdf                  =	extension.toLowerCase();
                		
        if(activityname	==	'') {
                alert('Please enter activity name.');
                $('#activityname').focus();
                return false;
        }
        if(activitytype	==	'') {
            alert("Please select activity type");
            $('#activitytype').focus();
            return false;
        }
        if(lowpdf != '') {
            if(lowpdf == 'zip') {
                //return true;
            } else {
                alert('Please upload only zip files.');
                return false;
            }
        }
        return true;
   });
   
   //Get activity details according to the choice of the user
   $('#activityselect').on('change',function() {
       var posturl              =	'getselectedactivity.php';
       var activityselect       =       $('#activityselect').val();
       
       if(activityselect == '') {
           alert('Please select the option');
           $('#activityselect').focus();
           return false;
       } else {
            var getactivitylist      =       $.ajax({
                type		: "POST",
                url             : posturl,
                beforeSend      :       function() {            
                    $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                    $("#showajaxload").show();
                    $("#backColor").css({"opacity":"0.7"});
                    $("#backColor").show();
                    //return false;
                },
                success         :       function(msg) { 
                    $("#showajaxload").hide();
                    $("#backColor").fadeOut("fast");
                },
                data            : {'activityselect' : activityselect},
                cache		: false,
                async		: false,
                dataType	: "text"
            }).responseText;
            //alert(getactivitylist);
            $('#choiceofselect').html(getactivitylist);
       }
   });
    
   //Update activity details in the DB 
   $('#addsave').on('click', function() {
       var posturl              =	'updateactivitydetails.php';
       var insert_id            =	$('#insert_id').val();
       var updateactivity      =       $.ajax({
                type		: "POST",
                url             : posturl,
                beforeSend      :       function() {            
                    $(" <div />" ).attr("id","showajaxload").addClass("miniajaxupdate").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif" border="0"/></span>').appendTo($( "body" ));
                    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                    $("#showajaxload").show();
                    $("#backColor").css({"opacity":"0.7"});
                    $("#backColor").show();
                    //return false;
                },
                success         :       function(msg) { 
                    $("#showajaxload").hide();
                    $("#backColor").fadeOut("fast");
                },
                data            : {'insert_id' : insert_id},
                cache		: false,
                async		: false,
                dataType	: "text"
            }).responseText;
            //alert(updateactivity);
            //return false;            
	$("#skipform").submit();	
    });
    
    //Convert all html to epub
    $('#convertepub').on('click', function() {
       var posturl             =	'convertepub.php';
       var insert_id           =	$('#insert_id').val();
       var convertepub         =        $.ajax({
                type		: "POST",
                url             : posturl,
                beforeSend      :       function() {            
                    $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                    $("#showajaxload").show();
                    $("#backColor").css({"opacity":"0.7"});
                    $("#backColor").show();
                    //return false;
                },
                success         :       function(msg) {                     
                    //$("#backColor").fadeOut("fast");
                    if(msg == 'success') {
                        //alert(convertepub+"1322");
                        //return false;
                        $("#showajaxload").hide();
                        alert('Epub is created successfully');
                        $("#showajaxload").show();
                        //$("#backColor").fadeOut("fast");
                        window.location       =       "epublibrary.php";
                    } else {
                        //alert(convertepub);
                        //return false;
                        $("#showajaxload").hide();
                        alert('Epub is not created');
                        $("#backColor").fadeOut("fast");
                    }
                },
                data            : {'insert_id' : insert_id},
                cache		: false,
                async		: false,
                dataType	: "text"
            }).responseText;
            
            //alert(convertepub);
            //return false;
            
            //return false;            
    });
   
   //Activity add page validation
   $('#actsubmit').on('click', function() {       
        var activityname            =	$('#activityname').val();
	var activitytype            =	$('#activitytype').val();

	var filename                =	$('#actfile').val();
	var extension               =	filename.substr((filename.lastIndexOf('.') +1));
	var lowpdf                  =	extension.toLowerCase();
                		
        if(activityname	==	'') {
                alert('Please enter activity name.');
                $('#activityname').focus();
                return false;
        }
        if(activitytype	==	'') {
            alert("Please select activity type");
            $('#activitytype').focus();
            return false;
        }
        if(lowpdf == '') {
            alert('Please upload a zip file.');
            return false;
        }
        else if(lowpdf == 'zip') {
            //return true;
        } else {
            alert('Please upload only zip files.');
            return false;
        }
        $('#activityaddform').ajaxForm ({
           success      :   function(msg) {
               if(msg == 'success') {
                  $("#actsuccess").show(); 
               } else {
                   $("#actfailure").show(); 
               }
                $("#showajaxload").hide();
                $("#backColor").fadeOut("fast");
                $('#activityaddform').each(function () {
			this.reset();
			return false;
		});
                window.location = "activitylibraryview.php";
           },
           beforeSend   :   function() {
               //alert("122");
               $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                $("#showajaxload").show();
                $("#backColor").css({"opacity":"0.7"});
                $("#backColor").show();
                //return false;
           }
        });
        return true;
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
                                beforeSend      :       function() {            
                                    $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif" border="0"/></span>').appendTo($( "body" ));
                                    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                                    $("#showajaxload").show();
                                    $("#backColor").css({"opacity":"0.7"});
                                    $("#backColor").show();
                                    //return false;
                                },
                                success         :       function(msg) { 
                                    $("#showajaxload").hide();
                                    $("#backColor").fadeOut("fast");
                                },
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
	

	$('#pdfprocess').on('click', function() {
	alert("22376");
        return false;
        var booktitle           =	$('#booktitle').val();
        var coverimage          =	$('#coverimage').val();
        var coimext             =	coverimage.substr((coverimage.lastIndexOf('.') +1));
	var coimage             =	coimext.toLowerCase();
	var dpires		=	$('#dpires').val();

	var filename            =	$('#pdffile').val();
	var extension           =	filename.substr((filename.lastIndexOf('.') +1));
	var lowpdf		=	extension.toLowerCase();

	//var laypro		=	$('#layprop').val();
	var supdev		=	$('#supp').val();
	var uploadfonts         =	$('#upfonts').val();
	var fonext		=	uploadfonts.substr((uploadfonts.lastIndexOf('.') +1));
	var cusfon		=	$("input[value='spetrue']:checked").val();
	var deffon		=	$("input[value='spefal']:checked").val();
	var versiontype         =	$('#versiontype').val();
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
                if(coverimage != ''){
                    if(coimage == '') {
                            alert('Please upload an image file.');
                            $('#coverimage').focus();
                            return false;
                    }
                    else if(coimage == 'jpg' || coimage == 'jpeg' || coimage == 'gif' || coimage == 'png') {
                            //return true;
                    } else {
                            alert('Please upload only image file.');
                            $('#coverimage').focus();
                            return false;
                    }
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
				alert('Please upload a font-containing zip file.');
				$('#upfonts').focus();
				return false;
			}
			else if(fonext == 'zip') {
				//return true;
			} else {
				alert('Please upload only font-containing zip file.');
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
                
		$("#form1").ajaxForm({
                    success:function(msg){
                            if(msg!='success'){
                                $("#showajaxload").hide();
                                $("#backColor").fadeOut("fast");
                                $("#backColor").hide();
                                alert(msg);
                                return false;
                            }
                            else {
                                $("#showajaxload").hide();
                                 $("#backColor").fadeOut("fast");
                                $("#backColor").hide();
                                alert("Successfully converted");
                                window.location = "epub_activity.php";			
                            }
                    },beforeSend : function(){
                        $(" <div />" ).attr("id","showajaxload").addClass("miniload").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                        $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                        $("#showajaxload").show();
                        $("#backColor").css({"opacity":"0.7"});
                        $("#backColor").show();                    
                        return false;                   
                    }                    
                });
	});
});

//-->

function load() {
       //alert("hello");
        
        //Delete activity from the epub already merged and stored in database
        $('.pgdelete').on('click', function() {
            //alert('gdsff');
                var pgDelId         =       $(this).find('#pgdelid').val();
                var epubId          =       $(this).find('#epubid').val();
                var settingid       =       $(this).find('#settingid').val();
                //alert(pgDelId);
                var posturl              =	'epubactivitydelete.php';
                var delactfromepub   =       $.ajax({
                    type            :       "POST",
                    url             :       posturl,
                    beforeSend      :       function() {            
                        $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                        $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                        $("#showajaxload").show();
                        $("#backColor").css({"opacity":"0.7"});
                        $("#backColor").show();
                        //return false;
                    },
                    success         :       function(msg) { 
                        $("#showajaxload").hide();
                        $("#backColor").fadeOut("fast");
                    },
                    data            :       {'pgDelId' : pgDelId, 'epubId' : epubId},
                    cache           :       false,
                    async           :       false,
                    dataType        :       "text"
                }).responseText;
                //alert(delactfromepub);
                window.location     =       'epubeditview.php?epubid='+settingid;
        });
        
        $('#activityselect').on('change', function() {
            var posturl              =      'getselectedactivity.php';
            var activityselect       =      $('#activityselect').val();

            if(activityselect == '') {
                alert('Please select the option');
                $('#activityselect').focus();
                return false;
            } else {
                    var getactivitylist      =       $.ajax({
                        type		: "POST",
                        url             : posturl,
                        beforeSend      :       function() {            
                            $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                            $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                            $("#showajaxload").show();
                            $("#backColor").css({"opacity":"0.7"});
                            $("#backColor").show();
                            //return false;
                        },
                        success         :       function(msg) { 
                            $("#showajaxload").hide();
                            $("#backColor").fadeOut("fast");
                        },
                        data            : {'activityselect' : activityselect},
                        cache		: false,
                        async		: false,
                        dataType	: "text"
                    }).responseText;
                    //alert(getactivitylist);
                    $('#choiceofselect').on('unbind');
                    $('#choiceofselect').html(getactivitylist);
                    load();
            }
        });
    
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
				url             : posturl,
                                beforeSend      :       function() {            
                                    $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                                    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                                    $("#showajaxload").show();
                                    $("#backColor").css({"opacity":"0.7"});
                                    $("#backColor").show();
                                    //return false;
                                },
                                success         :       function(msg) { 
                                    $("#showajaxload").hide();
                                    $("#backColor").fadeOut("fast");
                                },
				data            : {'presetval' : presetval},
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
	

	$('#pdfprocess').on('click', function() {
	
        
        
        alert("223");
        return false;
        var booktitle           =	$('#booktitle').val();
        var coverimage          =	$('#coverimage').val();
        var coimext             =	coverimage.substr((coverimage.lastIndexOf('.') +1));
	var coimage             =	coimext.toLowerCase();
	var dpires		=	$('#dpires').val();

	var filename            =	$('#pdffile').val();
	var extension           =	filename.substr((filename.lastIndexOf('.') +1));
	var lowpdf		=	extension.toLowerCase();

	//var laypro		=	$('#layprop').val();
	var supdev		=	$('#supp').val();
	var uploadfonts         =	$('#upfonts').val();
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
                if(coverimage   !=      ''){
                    if(coimage == '') {
                            alert('Please upload an image file.');
                            $('#coverimage').focus();
                            return false;
                    }
                    else if(coimage == 'jpg' || coimage == 'jpeg' || coimage == 'gif' || coimage == 'png') {
                            //return true;
                    } else {
                            alert('Please upload only image file.');
                            $('#coverimage').focus();
                            return false;
                    }
                }
		if(dpires       !=	'') {
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
				alert('Please upload a font-containing zip file.');
				$('#upfonts').focus();
				return false;
			}
			else if(fonext == 'zip') {
				//return true;
			} else {
				alert('Please upload only font-containing zip file.');
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
		$("#form1").ajaxForm({
                    success:function(msg){
                            if(msg!='success'){
                                $("#showajaxload").hide();
                                $("#backColor").fadeOut("fast");
                                $("#backColor").hide();
                                alert(msg);
                                return false;
                            }
                            else {
                                $("#showajaxload").hide();
                                 $("#backColor").fadeOut("fast");
                                $("#backColor").hide();
                                alert("Successfully converted");
                                window.location = "epub_activity.php";			
                            }
                    },beforeSend : function(){
                        $(" <div />" ).attr("id","showajaxload").addClass("miniload").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                        $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                        $("#showajaxload").show();
                        $("#backColor").css({"opacity":"0.7"});
                        $("#backColor").show();                    
                        return false;                   
                    }
                });
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
        
        var emptyTemp   =   $.ajax({
            data        :   {},
            dataType    :   "text",
            beforeSend  :       function() {            
                $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                $("#showajaxload").show();
                $("#backColor").css({"opacity":"0.7"});
                $("#backColor").show();
                //return false;
            },
            success     :       function(msg) { 
                $("#showajaxload").hide();
                $("#backColor").fadeOut("fast");
            },
            url         :   "ajaxremovetempfiles.php",
            type        :   "POST",
            cache       :   false,
            async       :   false            
        }).responseText;
        
	$("#backgroundPopup").fadeOut("fast");
	$("#"+htmlpageid).css('display','none');
        $.getScript("js/jquery.ui.1.8.18.js", function() {
            //alert("loaded");
        });
}

function popupclick(htmldot,previewTempFol,htmlpath){
    //alert(htmlpath);
    //alert(htmldot);
    //var htmldot                   =		$(this).find('a').attr("id");
    //alert(htmldot);
    //var htmlpath                  =		$(this).find('span').attr("id");
    var htmlid                      =		htmldot.substring(0, htmldot.indexOf(".html"));
    htmlid                          =		htmlid.replace("_", "");
    //alert(servername+htmldot);
    //alert(htmlid);    
    //return false;
            
    if(htmlpath == undefined) {
        //alert(htmlbox)        
        $(" <div />" ).attr("id","backColor").appendTo($( "body" ));
        var src = previewTempFol+htmldot;
        $.modal(' <iframe id="'+htmlid+'" src="' + src + '" height="450" width="830" style="border:none; overflow:auto;">', {
        escClose    :	false,
        closeHTML:'<a href="#" class="closeIcon" title="Close" style="float:right;padding-bottom:-40px;" onclick="closeIframe(\''+htmlid+'\')"><img src="images/pop-close-small.png" /></a>',
        containerCss:{
        backgroundColor:"#fff",
        borderColor:"#fff",
        height:450,
        padding:0,
        width:830
        },
        overlayClose:false
        });                
    } else if(htmlpath != '' && htmlpath != undefined) {
        //alert(htmlpath+"ild");
        $(" <div />" ).attr("id","backColor").appendTo($( "body" ));
        var src = htmlpath+htmldot;
        $.modal(' <iframe id="'+htmlid+'" src="' + src + '" height="450" width="830" style="border:none; overflow:auto;">', {
        escClose    :	false,
        closeHTML:'<a href="#" class="closeIcon" title="Close" style="float:right;padding-bottom:-40px;" onclick="closeIframe(\''+htmlid+'\')"><img src="images/pop-close-small.png" /></a>',
        containerCss:{
        backgroundColor:"#fff",
        borderColor:"#fff",
        height:450,
        padding:0,
        width:830
        },
        overlayClose:false
        });       
    }

    $("#"+htmlid).css("display","block");
    $("#backColor").css({"opacity":"0.7"});
    $("#backColor").show();
}

function previewshow(epubFolder,serverPath,settingId,preOne,nextone,htmlFullPath,firstOne,lastOne) {    
    //alert(epubFolder);
    //alert(serverPath);
    //alert(settingId);
    //$('#previewid').remove();
    $("#previewid").css("display","none");    
    //return false;
    var previewid           =           "previewid";
    var postUrl             =           "gethtmlandactivity.php";
    var substr              =           '';
    var htmlFile            =           '';
    var responseValue       =           '';
    var first               =           '';
    var last                =           '';
    var totalFiles          =           '';
    var nextFile            =           '';
    var noNext              =           '';
    var noPrev              =           '';
    var fullHtmlFiles       =           '';
    
    if(nextone == undefined) {
        $(" <div />" ).attr("id","backColor").appendTo($( "body" ));
        $("#backColor").show();

        responseValue       =   $.ajax({
                                url                 :       postUrl,
                                type                :       "post",
                                beforeSend      :       function() {            
                                    $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                                    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                                    $("#showajaxload").show();
                                    $("#backColor").css({"opacity":"0.7"});
                                    $("#backColor").show();
                                    //return false;
                                },
                                success         :       function(msg) { 
                                    $("#showajaxload").hide();
                                    $("#backColor").fadeOut("fast");
                                },
                                data                :       {"insert_id" : settingId},
                                dataType            :       "text",
                                cache               :       false,
                                async               :       false                            
                            }).responseText;
        //alert(responseValue);
        //return false;
        htmlFullPath    =       responseValue;                      
        substr          =       responseValue.split(',');
        totalFiles      =       substr.length;
        htmlFile        =       substr[0];
        //alert(htmlFile);
        prevFile        =       substr[0];
        first           =       substr[0];
        last            =       substr[totalFiles-1];
        nextFile        =       substr[1];
    } 
        
    if(substr == '') {
       
        if(preOne == '') {              //This loop is after clicking the next button
            first                           =       firstOne;
            last                            =       lastOne;
            fullHtmlFiles                   =       htmlFullPath.split(',');
            
            for (var i = 0; i < fullHtmlFiles.length; i++) {                
                if(nextone == fullHtmlFiles[i]) {
                    nextFile                =       fullHtmlFiles[i+1];
                    prevFile                =       fullHtmlFiles[i-1];
                }
            }            
            htmlFile                        =       nextone;
        }
        
        if(nextone == '') {             //This loop is after clicking the previous button         
            first                           =       firstOne;
            last                            =       lastOne;
            fullHtmlFiles                   =       htmlFullPath.split(',');            
            for (var j = 0; j < fullHtmlFiles.length; j++) {                
                if(preOne == fullHtmlFiles[j]) {
                    nextFile                =       fullHtmlFiles[j+1];
                    prevFile                =       fullHtmlFiles[j-1];
                }
            }            
            htmlFile                        =       preOne;
        }
    }
    
    var newsrc = serverPath+epubFolder+htmlFile;
    htmlids                         =		htmlFile.replace("_", "");
    htmlids1                        =		htmlids.replace("_", "");
    htmlid                          =		htmlids1.replace(".", "");
    
    $.modal(' <iframe id="'+htmlid+'" src="' + newsrc + '" height="450" width="830" style="border:none; overflow:auto;">', {
        escClose    :	false,
        closeHTML:'<a href="#" class="closeIcon" title="Close" style="float:right;padding-bottom:-40px;" onclick="closeIframe(\''+htmlid+'\')"><img src="images/pop-close-small.png" /></a><br/><div id="prevone" style="float:left;display:none;"><a href="javascript:void(0);" ><img src="images/previous.png"/></a></div><div id="nextone" style="float:right;display:none;position:relative;top:0px;right:-40px;"><a href="javascript:void(0);"><img src="images/next.png"/></a></div>',
        containerCss:{
        backgroundColor:"#fff",
        borderColor:"#fff",
        height:450,
        padding:0,
        width:830
        },
        overlayClose:false
        });
    
    //$(" <div />" ).attr("id","previewid").addClass("iframeload").html('<div class="closeiframe"><a href="javascript:void(0)" onclick="javascript:return closePrivateConfirm(this,\''+previewid+'\');"><img src="images/pop-close-small.png" /></a></div><br/><div id="prevone" style="float:left;display:none;"><a href="javascript:void(0);" ><img src="images/previous.png"/></a></div><div id="nextone" style="float:right;display:none; "><a href="javascript:void(0);"><img src="images/next.png"/></a></div><br/><div style="display:block;display:inline;padding-left:20px;padding-right:80px;" id="'+htmlid+'"></div>').appendTo($( "body" ));   
    
$('#previewid').css("display","block");   
    //This loop is called only for the first time
    if(responseValue != '') {  
        $('#prevone').css("display","none");
        if(first == last) {
            $('#nextone').css("display","none");
        } else {
            $('#nextone').css("display","block");
        }
    } else {
        
        //This loop is called to show the next button or not
        if(nextFile != '') {   
            if(htmlFile == last) {
                $('#nextone').css("display","none");
            } else {
                $('#nextone').css("display","block");
            }
        } else {
            $('#nextone').css("display","none");
        }
        
        //This loop is called to show the previous button or not
        if(prevFile != '') {
            if(htmlFile == first) {
                $('#prevone').css("display","none");
            } else {
                $('#prevone').css("display","block");
            }            
        } else {
            $('#prevone').css("display","none");
        }
    }
    
    //$('#'+htmlid).html('<iframe id="newiframe" style="overflow:auto;" frameborder="0" src="'+newsrc+'"></iframe>');
    
    $('#'+htmlid).attr('src',newsrc);
    //$('#newiframe').attr('src',newsrc);
    
    $('#prevone').attr('onclick','previewshow(\''+epubFolder+'\',\''+serverPath+'\',\''+settingId+'\',\''+prevFile+'\',\''+noNext+'\',\''+htmlFullPath+'\',\''+first+'\',\''+last+'\');');
    
    $('#nextone').attr('onclick','previewshow(\''+epubFolder+'\',\''+serverPath+'\',\''+settingId+'\',\''+noPrev+'\',\''+nextFile+'\',\''+htmlFullPath+'\',\''+first+'\',\''+last+'\');');
    
    $("#backColor").css({"opacity":"0.7"});
    $("#backColor").show();
}

function addInstantActivity() {    
    $("#addInstantAct").css("display","block");
    //$("#"+htmlid).css("display","block");
    $("#backgroundPopup").css({"opacity":"0.7"});
    $("#backgroundPopup").fadeIn("fast");
}


function actInstantSubmit() {
    var activityname            =	$('#InstActivityname').val();
    var activitytype            =	$('#InstActivitytype').val();
    var activityDesc            =	$('#InstActdesc').val();

    var filename                =	$('#InstActfile').val();
    var extension               =	filename.substr((filename.lastIndexOf('.') +1));
    var lowpdf                  =	extension.toLowerCase();

    if(activityname	==	'') {
            alert('Please enter activity name.');
            $('#InstActivityname').focus();
            return false;
    }
    if(activitytype	==	'') {
        alert("Please select activity type");
        $('#InstActivitytype').focus();
        return false;
    }
    if(lowpdf == '') {
        alert('Please upload a zip file.');
        return false;
    }
    else if(lowpdf == 'zip') {
        //return true;
    } else {
        alert('Please upload only zip files.');
        return false;
    }
    
    $.ajaxFileUpload
    (
            {
                    url             :   'InstantAddActivity.php',
                    fileElementId   :   'InstActfile',
                    dataType        :   'text',
                    data            :   {activityname:activityname, activitytype:activitytype, activityDesc: activityDesc},                    
                    success: function (data, status)
                    {
                        $("#showajaxload").hide();
                        $("#backColor").fadeOut("fast");
                         //alert(data);
                        $('#choiceofselect').html(data);
                    }
            }
    )
        
    $("#addInstantAct").css("display","none");
    $("#backgroundPopup").fadeOut("fast");
    return false;  
}

function actInstantCancel() {
    $('#activityformInst').each(function () {
        this.reset();
        return false;
    });
}

function activityDrop(activityid) {
    //var activityid	=	$(this).attr('id');           
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
    //$( this ).draggable('enable');
    $('#'+activityid).draggable({
        helper: "clone",
        start : function(e, ui) {
                $("#htmlPopBox").droppable({
                        drop : function handleCardDrop( event, ui ) {
                                //alert(insert_id);					
                                //alert(activityid);					
                                var posturl		=	'unziprearrange.php';
                                var unzipfile           = $.ajax({
                                        type		: "POST",
                                        url             : posturl,
                                        beforeSend      :       function() {            
                                            $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                                            $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                                            $("#showajaxload").show();
                                            $("#backColor").css({"opacity":"0.7"});
                                            $("#backColor").show();
                                            //return false;
                                        },
                                        success         :       function(msg) { 
                                            $("#showajaxload").hide();
                                            $("#backColor").fadeOut("fast");
                                        },
                                        data            : {'activityid' : activityid, 'insert_id' : insert_id},
                                        cache		: false,
                                        async		: false,
                                        dataType	: "text"
                                }).responseText;
                                //load();
                                //alert(unzipfile);
                                //return false;
                                var str = unzipfile;
                                var substr = str.split(';');
                                var htmlnoext = substr[0].replace('.html','');
                                var shortname = htmlnoext.substr(0,7);
                                htmlnoext           =       htmlnoext.replace('_','');
                                htmlnoext           =       htmlnoext.replace('_','');
                                //alert(htmlnoext);

                                $('<li id="'+htmlnoext+'" linewid="'+activityid+'_'+substr[1]+'/'+substr[0]+'" class="htmlPage '+htmlnoext+'"><span id="'+serverpath+activpath+activityid+'_'+substr[1]+'/'+'" ></span><div class="pgEditDelete"><img onclick="deleteAddActivity(\''+activityid+'_'+substr[1]+'\',\''+activpath+'\',\''+htmlnoext+'\');" src="images/page_delete.png"/></div><a id="'+substr[0]+'" onclick="popupclick(\''+substr[0]+'\',\''+serverpath+activpath+activityid+'_'+substr[1]+'/'+'\')">'+shortname+'...</a></li>').appendTo('.htmlPageList');
      junkdrawer.restoreListOrder("boxes");
      dragsort.makeListSortable(document.getElementById("boxes"));
      //alert("done");
                                
                        }
                });
            }
        });
      
}

function deleteHtmlActivity(pgEditDelId,editEpubid,liId) {
    //alert(pgEditDelId);
    //return false;
    
    //alert(editEpubid);
    //alert(liId);
    //return false;
    var posturl          =	'epubhtmldelete.php';
    var delactfromepub   =      $.ajax({
        type            :       "POST",
        beforeSend      :       function() {            
            $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
            $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
            $("#showajaxload").show();
            $("#backColor").css({"opacity":"0.7"});
            $("#backColor").show();
            //return false;
        },
        success         :       function(msg) { 
            $("#showajaxload").hide();
            $("#backColor").fadeOut("fast");
        },
        url             :       posturl,
        data            :       {'pgDelId' : pgEditDelId, 'epubId' : editEpubid},
        cache           :       false,
        async           :       false,
        dataType        :       "text"
    }).responseText;
    //alert(delactfromepub);
    //return false;
    $('#'+liId).remove();    
}

function deleteAddActivity(pgEditDelId,editEpubid,liId) {
    //alert(pgEditDelId);
    //return false;
    
    //alert(editEpubid);
    //alert(liId);
    //return false;
    var posturl          =	'epubaddactivitydelete.php';
    var delactfromepub   =      $.ajax({
        type            :       "POST",
        beforeSend      :       function() {            
            $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
            $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
            $("#showajaxload").show();
            $("#backColor").css({"opacity":"0.7"});
            $("#backColor").show();
            //return false;
        },
        success         :       function(msg) { 
            $("#showajaxload").hide();
            $("#backColor").fadeOut("fast");
        },
        url             :       posturl,
        data            :       {'pgDelId' : pgEditDelId, 'epubId' : editEpubid},
        cache           :       false,
        async           :       false,
        dataType        :       "text"
    }).responseText;
    //alert(delactfromepub);
    //return false;
    $('#'+liId).remove();    
}

//Activity edit option after clicking the EDIT ICON
function activityEdit(activityId) {

    var posturl          =	'getepubeditactivitydetails.php';
    var geteditactivity  = $.ajax({
        type            :       "POST",
        beforeSend      :       function() {            
            $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
            $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
            $("#backColor").css({"opacity":"0.7"});
            $("#backColor").show();
            $("#showajaxload").show();
            //return false;
        },        
        url             :       posturl,
        data            :       {'activityId' : activityId},
        cache           :       false,
        async           :       false,
        dataType        :       "text",
        success         :       function(msg) {
            $("#showajaxload").hide();
            $("#backColor").fadeOut("fast");
        }
    }).responseText;
    $('.ePubAddFrmBox').hide();
    $('.ePubEditFrmBox').show();    
    $('.ePubEditFrmBox').html(geteditactivity);    
}

function actEditSubmit() {
    var activityname            =	$('#activitynameEdit').val();
    var activitytype            =	$('#activitytypeEdit').val();

    var filename                =	$('#actfileEdit').val();
    var extension               =	filename.substr((filename.lastIndexOf('.') +1));
    var lowpdf                  =	extension.toLowerCase();

    if(activityname	==	'') {
            alert('Please enter activity name.');
            $('#activitynameEdit').focus();
            return false;
    }
    if(activitytype	==	'') {
        alert("Please select activity type");
        $('#activitytypeEdit').focus();
        return false;
    }
    if(lowpdf != '') {
        if(lowpdf == 'zip') {
            //return true;
        } else {
            alert('Please upload only zip files.');
            return false;
        }
    }
    $('#formactivityedit').submit();
    return true;
}

function actpreviewShow (activityId,serverPath) {
    //alert(activityId);
    $('#previewid').remove();
    var htmlid              =           "previewid";
    var postUrl             =           "getactivityalone.php";
    var responseValue       =           '';
    
    responseValue       =   $.ajax({
                            url                 :       postUrl,
                            type                :       "post",
                            beforeSend      :       function() {            
                                $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                                $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                                $("#showajaxload").show();
                                $("#backColor").css({"opacity":"0.7"});
                                $("#backColor").show();
                                //return false;
                            },
                            success         :       function(msg) { 
                                $("#showajaxload").hide();
                                $("#backColor").fadeOut("fast");
                            },
                            data                :       {activityId : activityId},
                            dataType            :       "text",
                            cache               :       false,
                            async               :       false                        
                        }).responseText;
    //alert(responseValue);
    //return false;
    
    htmlFullPath    =       responseValue;                      
        
    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));
        var src = serverPath+htmlFullPath;
        $.modal(' <iframe id="'+activityId+'" src="' + src + '" height="450" width="830" style="border:none; overflow:auto;">', {
        escClose    :	false,
        closeHTML:'<a href="#" class="closeIcon" title="Close" style="float:right;padding-bottom:-40px;" onclick="closeActIframe(\''+activityId+'\')"><img src="images/pop-close-small.png" /></a>',
        containerCss:{
        backgroundColor:"#fff",
        borderColor:"#fff",
        height:450,
        padding:0,
        width:830
        },
        overlayClose:false
        });       

    $("#"+activityId).css("display","block");
    $("#backColor").css({"opacity":"0.7"});
    $("#backColor").show();
}

function cancelAction(formId) {
    $('#'+formId).each(function () {
        this.reset();
        return false;
    });
}

function pageepub(pageno) {
    
    var pageResult          =       $.ajax({
        url         :   "epublibrarypaging.php",
        dataType    :   "text",
        beforeSend      :       function() {            
            $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
            $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
            $("#showajaxload").show();
            $("#backColor").css({"opacity":"0.7"});
            $("#backColor").show();
            //return false;
        },
        success         :       function(msg) { 
            $("#showajaxload").hide();
            $("#backColor").fadeOut("fast");
        },
        type        :   "get",        
        data        :   { 'page' : pageno},
        cache       :   false,
        async       :   false
        }).responseText;
    
    //alert(pageResult);
    $('.ePubFrmBox').html(pageResult);
    
}

//EPUB edit option after clicking the EDIT ICON
function epublibedit(epubId) {
    window.location = "epubeditview.php?epubid="+epubId;   
}

function pageactivity(pageno){
    var pageResult      =   $.ajax({
        data        :   { 'page'  : pageno },
        url         :   "activitylibrarypaging.php",
        type        :   "get",
        beforeSend      :       function() {            
            $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
            $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
            $("#showajaxload").show();
            $("#backColor").css({"opacity":"0.7"});
            $("#backColor").show();
            //return false;
        },
        success         :       function(msg) { 
            $("#showajaxload").hide();
            $("#backColor").fadeOut("fast");
        },
        dataType    :   "text",
        cache       :   false,
        async       :   false
    }).responseText;
    //alert(pageResult);
    $('.ePubViewFrmBox').html(pageResult);    
}

//Activity delete option after clicking the DELETE ICON
function activityDelete(activityDelName,activityDelId) {    
    var delChoice         =   confirm('Are you sure you want to delete, '+ activityDelName);       
    if(delChoice) {
        //var activityDelId   =   $(this).find('span').html(); //.find will find the inner elements (this is which is present inside the current elements) of the current elements, but .next will find the child elements of the current elements
        var posturl              =	'activitydelete.php';
        //alert(activityDelId+"alert");
        var deleteactivity   =       $.ajax({
                type            :       "POST",
                url             :       posturl,
                beforeSend      :       function() {            
                    $(" <div />" ).attr("id","showajaxload").addClass("miniajaxloading").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                    $("#showajaxload").show();
                    $("#backColor").css({"opacity":"0.7"});
                    $("#backColor").show();
                    //return false;
                },
                success         :       function(msg) { 
                    $("#showajaxload").hide();
                    $("#backColor").fadeOut("fast");
                },
                data            : {'activityDelId' : activityDelId},
                cache           : false,
                async           : false,
                dataType        : "text"
            }).responseText;
            //alert(deleteactivity);
        //return false;
        window.location = "activitylibraryview.php";
    }    
}

function closeIframe(closeId) {
   $("#"+closeId).css("display","none");
   $("#backColor").fadeOut("fast"); 
    
}

function closeActIframe(closeId) {
    //alert("good");
    $("#"+closeId).css("display","none");
    $("#backColor").fadeOut("fast");
    var responseOne     =       $.ajax({
            url             :       "removeactivitydetails.php",
            data            :       { 'activityId'  :   closeId },
            dataType        :       "text",
            type            :       "POST",
            cache           :       false,
            async           :       false        
    }).responseText;
    //alert(responseOne);
    return false;
}


function pdfconvertpage() {
    alert("22376");
    return false;
    var booktitle           =	$('#booktitle').val();
    var coverimage          =	$('#coverimage').val();
    var coimext             =	coverimage.substr((coverimage.lastIndexOf('.') +1));
    var coimage             =	coimext.toLowerCase();
    var dpires		=	$('#dpires').val();

    var filename            =	$('#pdffile').val();
    var extension           =	filename.substr((filename.lastIndexOf('.') +1));
    var lowpdf		=	extension.toLowerCase();

    //var laypro		=	$('#layprop').val();
    var supdev		=	$('#supp').val();
    var uploadfonts         =	$('#upfonts').val();
    var fonext		=	uploadfonts.substr((uploadfonts.lastIndexOf('.') +1));
    var cusfon		=	$("input[value='spetrue']:checked").val();
    var deffon		=	$("input[value='spefal']:checked").val();
    var versiontype         =	$('#versiontype').val();
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
            if(coverimage != ''){
                if(coimage == '') {
                        alert('Please upload an image file.');
                        $('#coverimage').focus();
                        return false;
                }
                else if(coimage == 'jpg' || coimage == 'jpeg' || coimage == 'gif' || coimage == 'png') {
                        //return true;
                } else {
                        alert('Please upload only image file.');
                        $('#coverimage').focus();
                        return false;
                }
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
                            alert('Please upload a font-containing zip file.');
                            $('#upfonts').focus();
                            return false;
                    }
                    else if(fonext == 'zip') {
                            //return true;
                    } else {
                            alert('Please upload only font-containing zip file.');
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

            $("#form1").ajaxForm({
                success:function(msg){
                        if(msg!='success'){
                            $("#showajaxload").hide();
                            $("#backColor").fadeOut("fast");
                            $("#backColor").hide();
                            alert(msg);
                            return false;
                        }
                        else {
                            $("#showajaxload").hide();
                                $("#backColor").fadeOut("fast");
                            $("#backColor").hide();
                            alert("Successfully converted");
                            window.location = "epub_activity.php";			
                        }
                },beforeSend : function(){
                    $(" <div />" ).attr("id","showajaxload").addClass("miniload").html('<span id="showmsg" style="display:block;"><img id="pic" src="images/ajax-loader.gif"  border="0"/></span>').appendTo($( "body" ));
                    $(" <div />" ).attr("id","backColor").appendTo($( "body" ));        
                    $("#showajaxload").show();
                    $("#backColor").css({"opacity":"0.7"});
                    $("#backColor").show();                    
                    return false;                   
                }                    
            });
}