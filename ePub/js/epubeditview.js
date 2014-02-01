// JavaScript Document

$(document).ready(function(){
     
   //Get activity details in EDIT MODE according to the choice of the user
   $('#editActivityselect').change(function() {
       var posturl                  =       'editgetselectedactivity.php';
       var editActivityselect       =       $('#editActivityselect').val();
       
       if(editActivityselect == '') {
           alert('Please select the option');
           $('#editActivityselect').focus();
           return false;
       } else {
            var getactivitylist      =       $.ajax({
                type		: "POST",
                url             : posturl,
                data            : {'activityselect' : editActivityselect},
                cache		: false,
                async		: false,
                dataType	: "text"
            }).responseText;
            //alert(getactivitylist);
            $('#editChoiceofselect').html(getactivitylist);
            editLoad();
       }
   });
    
   //Update activity details in EDIT MODE in the DB
   $('#editAddsave').click(function() {
       var posturl              =	'updateactivitydetails.php';
       var insert_id            =	$('#insert_id').val();
       var updateactivity      =       $.ajax({
                type		: "POST",
                url             : posturl,
                data            : {'insert_id' : insert_id},
                cache		: false,
                async		: false,
                dataType	: "text"
            }).responseText;
            //alert(updateactivity);
            //return false;            
	$("#editSkipform").submit();
    });
    
    //Drag and Drop in EDIT MODE   
    $(".activityRowEdit").mousedown(function () {
        var activityid              =	$(this).attr('id');
        var editActivityFolder      =	$('#editActivityFolder').val();
        var editEpubFolder          =	$('#editEpubFolder').val();
        var editSettingId           =	$('#editSettingId').val();
        var serverpath              =	$('#serverpath').val();
        var insert_id               =	$('#insert_id').val();
        var activpath               =	$('#activpath').val();
        var d                       =   new Date();
        var curmon                  =   d.getMonth()+1;
        if(curmon < 10) {
            var zerovar         =       "0";
        }else {
            var zerovar         =       "";  
        }
        var curdate  	=	""+d.getDate()+zerovar+curmon+d.getFullYear();
        //$( this ).draggable('enable');
        $(this).draggable({
                helper: "clone",
                start : function(e, ui) {
                        $("#editHtmlPopBox").droppable({
                                drop : function handleCardDrop( event, ui ) {
                                        //alert(insert_id);					
                                        //alert(activityid);					
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
                                        //alert(unzipfile);
                                        var str             =       unzipfile;
                                        var substr          =       str.split(';');
                                        var htmlnoext       =       substr[0].replace('.html','');
                                        var shortname       =       htmlnoext.substr(0,7);

                                        $('<li id="'+htmlnoext+'" class="editHtmlPage '+htmlnoext+'"><div class="pgEditDelete"><img onclick="deleteAddedActivity(\''+activityid+'_'+substr[1]+'\',\''+editEpubFolder+'\',\''+htmlnoext+'\');" src="images/page_delete.png"/></div><a style="text-decoration:none;color:#787878;" href="javascript:void(0);" onclick="editPopupclick(\''+substr[0]+'\',\''+serverpath+activpath+activityid+'_'+substr[1]+'/'+'\');" >'+shortname+'...</a></li>').appendTo('.editHtmlPageList');           
                                }
                        });
                },
                        stop : function(e, ui) {
                           
                        }
                });         
    });
    
   //editPopupclick();

    $('#editSkiptag').click(function () {
            $("#editSkipform").submit();	
    });
});

//-->


function editLoad() {
       //alert("hello");
        
        //Get activity details in EDIT MODE according to the choice of the user
            $('#editActivityselect').change(function() {
                var posturl                  =       'editgetselectedactivity.php';
                var editActivityselect       =       $('#editActivityselect').val();

                if(editActivityselect == '') {
                    alert('Please select the option');
                    $('#editActivityselect').focus();
                    return false;
                } else {
                        var getactivitylist      =       $.ajax({
                            type		: "POST",
                            url             : posturl,
                            data            : {'activityselect' : editActivityselect},
                            cache		: false,
                            async		: false,
                            dataType	: "text"
                        }).responseText;
                        //alert(getactivitylist);
                        $('#editChoiceofselect').html(getactivitylist);
                        editLoad();
                }
            });
            
            //Update activity details in EDIT MODE in the DB 
            $('#editAddsave').click(function() {
                var posturl              =	'updateactivitydetails.php';
                var insert_id            =	$('#insert_id').val();
                var updateactivity      =       $.ajax({
                            type		: "POST",
                            url             : posturl,
                            data            : {'insert_id' : insert_id},
                            cache		: false,
                            async		: false,
                            dataType	: "text"
                        }).responseText;
                        //alert(updateactivity);
                        //return false;            
                    $("#editSkipform").submit();
                });
        
            //Drag and Drop in EDIT MODE   
            $(".activityRowEdit").mousedown(function () {
                //alert("edfsd");
                var activityid              =	$(this).attr('id');
                var editActivityFolder      =	$('#editActivityFolder').val();
                var editEpubFolder          =	$('#editEpubFolder').val();
                var editSettingId           =	$('#editSettingId').val();
                var serverpath              =	$('#serverpath').val();
                var insert_id               =	$('#insert_id').val();
                var activpath               =	$('#activpath').val();
                var d                       =   new Date();
                var curmon                  =   d.getMonth()+1;
                if(curmon < 10) {
                    var zerovar         =       "0";
                }else {
                    var zerovar         =       "";  
                }
                var curdate  	=	""+d.getDate()+zerovar+curmon+d.getFullYear();
                //$( this ).draggable('enable');
                $(this).draggable({
                        helper: "clone",
                        start : function(e, ui) {
                                $("#editHtmlPopBox").droppable({
                                        drop : function handleCardDrop( event, ui ) {
                                                //alert(insert_id);					
                                                //alert(activityid);					
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
                                                //alert(unzipfile);
                                                var str             =       unzipfile;
                                                var substr          =       str.split(';');
                                                var htmlnoext       =       substr[0].replace('.html','');
                                                var shortname       =       htmlnoext.substr(0,7);
                                                
                                                $('<li id="'+htmlnoext+'" class="editHtmlPage '+htmlnoext+'"><div class="pgEditDelete"><img onclick="deleteAddedActivity(\''+activityid+'_'+substr[1]+'\',\''+editEpubFolder+'\',\''+htmlnoext+'\');" src="images/page_delete.png"/></div><a style="text-decoration:none;color:#787878;" href="javascript:void(0);" onclick="editPopupclick(\''+substr[0]+'\',\''+serverpath+activpath+activityid+'_'+substr[1]+'/'+'\');" >'+shortname+'...</a></li>').appendTo('.editHtmlPageList');           
                                        }
                                });
                        },
                                stop : function(e, ui) {

                                }
                        });         
            });
    $('#editSkiptag').click(function () {
        $("#editSkipform").submit();	
    });
}

function editClosePrivateConfirm(abc,htmlpageid) {
    $("#backgroundPopup").fadeOut("fast");
    $("#"+htmlpageid).css('display','none');
    $.getScript("js/jquery.ui.1.8.18.js", function() {
        //alert("loaded");
    });
    //editPopupclick();
    //deleteAddedActivity();
}

function editPopupclick(edithtmldot,edithtmlpath){
    //alert(htmldot);
    //alert(edithtmlpath);
    //return false;

    var servername                  =		$('#servername').val();
    var edithtmlids                  =		edithtmldot.substring(0, edithtmldot.indexOf(".html"));
    //alert(edithtmlids);
    var edithtmlid                =		edithtmlids.replace('_','');
    
    //alert(edithtmlid);
    var edithtmlbox                 =		edithtmlid+"box";
    //var htmlid                    =		htmlid.replace("_", "");
    //alert(htmlid);
    //alert(htmlbox);
    //alert(servername+edithtmldot);
    //return false;
    //return false;

    $(" <div />" ).attr("id",edithtmlid).addClass("minihtml").html('<div class="closepbox"><a href="javascript:void(0)" onclick="javascript:return editClosePrivateConfirm(this,\''+edithtmlid+'\');"><img src="images/pop-close-small.png" /></a></div><div class="edithtmlcontent" style="display:block;display:inline;padding-left:20px;padding-right:80px;" id="'+edithtmlbox+'"></div>').appendTo($( "body" ));
    if(edithtmlpath == undefined) {
        $('#'+edithtmlbox).load(servername+edithtmldot, function() {
                //alert('Load was performed.');
        });
    } else if(edithtmlpath != '' && edithtmlpath != undefined) {
        //alert(edithtmlpath+edithtmldot);
        $('#'+edithtmlbox).load(edithtmlpath+edithtmldot, function() {
                //$('#'+htmlbox).html(e);
                //alert('All Load was performed.');                            
        });
    }

    //$("#"+htmlbox).css("display","block");                
    //$(".editminihtml").css("display","block");
    $("#"+edithtmlid).css("display","block");

    //alert($("#"+edithtmlbox).html());

    //alert($("#"+htmlid).css("display"));
    $("#backgroundPopup").css({"opacity":"0.7"});
    $("#backgroundPopup").fadeIn("fast");
}


function deleteAddedActivity(pgEditDelId,editEpubid,liId) {
    //alert(pgEditDelId);
    //return false;
    
    //alert(editEpubid);
    //alert(liId);
    //return false;
    var posturl          =	'epubactivitydelete.php';
    var delactfromepub   =      $.ajax({
        type            :       "POST",
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