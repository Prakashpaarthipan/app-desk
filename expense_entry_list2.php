<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);

$menu_name = 'APPROVAL DESK';
$inner_submenu = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc", "Centra", 'TCS');
if($_SESSION['tcs_empsrno'] != '') {
    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' order by MNUCODE Asc", "Centra", 'TCS');
} else {
    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and SUPCODE = '".$_SESSION['tcs_userid']."' order by MNUCODE Asc", "Centra", 'TCS');
}
if($inner_menuaccess[0]['VEWVALU'] == 'N' or $inner_menuaccess[0]['MNUCODE'] == 'VEWVALU') { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
}

if($inner_menuaccess[0]['VEWVALU'] == 'Y') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Supplier Entry :: Supplier Entry :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">
    <link href="css/jquery-customselect.css" rel="stylesheet" /> 
    <link href="css/facebook_alert.css" rel="stylesheet" type="text/css">

        <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
        <!-- multiple file upload -->
        <link href="css/jquery.filer.css" rel="stylesheet">

<style type="text/css">
.messages .item .text {
  background: #FFF;
  padding: 10px;
  margin: 5px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  border: 1px solid #D5D5D5;
  }
.comment-frame {
    background-color: #f0eeee;
    border-radius: 3px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.23);
    box-shadow: 0 1px 2px rgba(0,0,0,.23);
    margin: 4px 4px 12px 0;
}
.comment-box {
    position: relative;
}
.comment-box {
    position: relative;
}
.comment-box-input {
    background: none;
    height: 75px;
    margin: 0;
    min-height: 75px;
    padding: 9px 11px;
    padding-bottom: 0;
    width: 100%;
    resize: none;
}
.comment-box-options {
    position: absolute;
    bottom: 20px;
    right: 6px;
}
.comment-box-options-item {
    border-radius: 3px;
    float: left;
    height: 18px;
    margin-left: 4px;
    padding: 6px;
}
.fa-at:before {
    content: "\f1fa";
}
textarea {
    color: #333;
    font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
    line-height: 18px;
    font-weight: 400;
}
.label {
white-space: normal !important;
/* white-space: nowrap; */
}
.highlight_redtitle { color: #FF0000; font-size: 20px; }
.highlight_blacktitle { color: #000; font-size: 20px; }

.cls_left { background-color: #eaeaf3 !important; }
.cls_right { background-color: #ebeaea !important; }
.badge-info { background-color: #1caf9a !important; color: #FFFFFF !important; }
.date { font-size: 10px !important; color: #000 !important; font-weight: normal !important; }
#load_page {
 position: fixed;
 left: 0px;
 top: 0px;
 width: 100%;
 height: 100%;
 z-index: 10;
 opacity: 0.4;
 background: url('images/loading.gif') 50% 50% no-repeat rgb(249,249,249);
}
.list-group-status { margin-right: 5px !important; }
.list-group-contacts .list-group-item img { margin-right: 5px !important; }
.modal {
    display: none; /* Hidden by default */
    position:FIXED; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: auto; /* Full width */
    height: auto; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal {
    display: none; /* Hidden by default */
    position:FIXED; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: auto; /* Full width */
    height: auto; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    height :500px;
    overflow-x: scroll;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}


    .comment-frame {
        background-color: #f0eeee;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.23);
        box-shadow: 0 1px 2px rgba(0,0,0,.23);
        margin: 4px 4px 12px 0;
    }
    .comment-box {
        position: relative;
    }
    .comment-box {
        position: relative;
    }
    .comment-box-input {
        background: none;
        height: 75px;
        margin: 0;
        min-height: 75px;
        padding: 9px 11px;
        padding-bottom: 0;
        width: 100%;
        resize: none;
    }
    .comment-box-options {
        position: absolute;
        bottom: 20px;
        right: 6px;
    }
    .comment-box-options-item {
        border-radius: 3px;
        float: left;
        height: 18px;
        margin-left: 4px;
        padding: 6px;
    }
    .fa-at:before {
        content: "\f1fa";
    }
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }
    .content-frame-body { padding: 10px !important; }
</style>
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>


        <? /* <script src="js/angular.js"></script> */ ?>
<style>
.badge-info { background-color: #1caf9a !important; color: #FFFFFF !important; }
</style>
<!-- EOF CSS INCLUDE -->
</head>
<body>
    <div id="load_page" style='display:block;padding:12% 40%;'></div>
    <!-- START PAGE CONTAINER -->
        <div class="page-container page-navigation-toggled">

        <!-- START PAGE SIDEBAR -->
        <div class="page-sidebar">
            <!-- START X-NAVIGATION -->
            <? include 'lib/app_left_panel.php'; ?>
            <!-- END X-NAVIGATION -->
        </div>
        <!-- END PAGE SIDEBAR -->

        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
            <? include "lib/app_header.php"; ?>
            <!-- END X-NAVIGATION VERTICAL -->

            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                <li><a href="home.php">Dashboard</a></li>
                <li class="active">Product Quotation Fix</li>
            </ul>
            <!-- END BREADCRUMB -->
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="panel-heading" style="margin-bottom: 20px;">
                        <!-- <h3 class="panel-title"><strong>PRODUCT QUOTATION FIX</strong></h3> -->
                        <center>
                        <label id = "appnumb" style="font-size: 20px;color: red;font-weight: bold;text-align: center"> <? echo urldecode($_REQUEST['ap']); ?> </label></center>
                        <ul class="panel-controls">
                        </ul>
                    </div>
                      <? $opensearch = select_query_json("select APPSTAT,EXPVALUE from APPROVAL_REQUEST where APPSTAT = 'E' and ARQCODE ='".$_REQUEST['reqid']."' and ARQYEAR='".$_REQUEST['year']."' and ATCCODE='".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."'","Centra","TEST");
                      ?>

                     <div class="form-group trbg non-printable" style="display:block;">
                       <?
                      $ledgerfilter = select_query_json("select distinct Brn.Brncode Code,substr(brn.nicname,3,10) Brnname,brn.brnname Branch,Dep.expsrno, Dep.expname Exphead,Dep.Depname Department,dep.depcode,tar.ptdesc Ledgername,Tar.Ptnumb TargetNumber from trandata.non_purchase_target@tcscentr Tar, trandata.Department_asset@tcscentr Dep,trandata.branch@tcscentr Brn where tar.ptnumb>=9001  and tar.brncode=1 and tar.depcode=dep.depcode and tar.brncode=brn.brncode and dep.Deleted='N' and dep.expsrno='".$opensearch[0]['EXPVALUE']."' order by Dep.expsrno,Dep.expname,tar.ptnumb","Centra","TCS");

                      //$sql_exp = select_query_json("select distinct(expsrno),expname from trandata.department_asset@tcscentr where deleted='N' and expsrno = '".$opensearch[0]['EXPVALUE']."' order by expsrno", "Centra", "TCS");
                      ?>

                      <div class=" row">
                        <div class="col-md-12">
                            <style type="text/css">
                                .dis {
                                                opacity: 0.6;
                                                pointer-events: none;
                                            }
                            </style>

                          <center>

                              <label id = "appnumb" style="font-size: 20px;color: red;font-weight: bold;display: none"> <? echo urldecode($_REQUEST['ap']); ?> </label>
                            </center>
                          </div>
                        </div>
                        
                      </div>
                      <!-- <center> -->
                        <div class='row'>
                           <div class="col-md-4"> 

                              <div class="input-group " style="margin:10px" id="exp">                           
                              <select id="expslist" class="form-control custom-select chosn"  tabindex='1' required  name="expsrno" onchange="call_subproduct_list(this.value)">
                                  <option value="">Choose Expense Head</option>
                                 
                                  <?for($k=0;$k<sizeof($ledgerfilter);$k++){?>
                                  <option  value="<?=$ledgerfilter[$k]['TARGETNUMBER']?>-<?=$ledgerfilter[$k]['LEDGERNAME']?>"><?=$ledgerfilter[$k]['TARGETNUMBER']?>-<?=$ledgerfilter[$k]['LEDGERNAME']?></option>
                                  <?}?>
                               </select>
                              <span class="input-group-btn" style="background-color: black">
                                  <button class="btn btn-default" type="button" style="background-color: black" onclick=""><span  style="background-color: black;color: white;">Expense Head</span></button>
                              </span>     

                              
                              </div>
                                <div class="input-group " style="margin:10px" id="exp1">  
                              <input type="text" name="dropdown" value='' class=" form-control" style="display:none" id="ex_dropvalue" readonly="">
                              <input type="hidden" id="hid_expense" name="ex_dropdown" value='' >
                              <span class="input-group-btn" >
                                  <span class=" fa fa-refresh" title="Reset" style="color:black;margin-left:10px;display:none;font-size: 18px;cursor: pointer;padding-bottom: 5px" onclick="refresh()" id="refresh"></span>
                              </span>   
                                </div>
                         
                            </div>
                            <div class="col-md-4">
                              <div class="input-group " style="margin: 10px;vertical-align: middle;">
                                    <button class="btn btn-warning" type="button" style="display:none"  onclick=""><span class="fa fa-undo"></span> Load</button>
                                    <!-- <button class="btn btn-warning" type="button" style="background-color: " onclick="addtab();"><span class="fa fa-undo"></span> Load</button> -->
                                
                            </div>
                                                             <!-- <button class="btn btn-warning" type="button" style="background-color: " onclick="addtab();"><span class="fa fa-undo"></span> Load</button> -->
                             
                            </div>

                            <div class="col-md-4">
                              <div class="input-group " style="margin: 10px;vertical-align: middle;margin-left:250px">
                              <button class="btn btn-success pull-left" type="button"   id="appbtn" onclick=" moveToapprova()"><span class="fa fa-finish"></span> Finish</button>
                              <button class="btn btn-info pull-right" type="button"  style="margin-left: 10px;" id="prebtn" onclick=" expensePreview()"><span class="fa fa-preview"></span> Preview</button>
                               </div>
                               <div class="input-group " style="margin: 10px;vertical-align: middle;margin-left:250px">
                              
                               </div>
                             </div>
                      </div>
                     

                    <div class="col-md-12" style='clear:both; border-top:1px solid #ADADAD; padding-top: 10px; margin-top: 10px;'>
                      <div id="div_supproduct_supplier" name="dynamic_subproducts" ></div> 
                      <div id="div_supplier_entry" name="dynamic_supplier_entry"></div>                   
                   </div>
                    
                    <div  style='clear:both; border-top:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'>
                        

                        <div id="maintable" style="margin-top: 10px;" >
                        </div>
                     
                    </div>
                  </div>
                </div>
                </div>
                <div id="myModal" class="modal">
                          <!-- Modal content -->
                  <span class="close">&times;</span>
                  <div id="modal_data" class="modal-content">
                    
                  </div>
                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>

    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>

    <!-- Collect Document -->
     <div id="myModal1" class="modal fade">
            <div class="modal-dialog" style='width:85%'>
                <div class="modal-content">
                    <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>PREVIEW</div>
                    <div class="modal-body" id="modal-body1"></div>
                </div>
            </div>
        </div>
    
    <!-- Collect Document -->
    <div class='clear'></div>


<!-- START SCRIPTS -->
    <!-- START PLUGINS -->

 
    <!-- END PLUGINS -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

    <!-- THIS PAGE PLUGINS -->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
    <script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
    <!-- END THIS PAGE PLUGINS -->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>
    <!-- END TEMPLATE -->

    <!-- Custom Scripts - Arun Rama Balan.G -->
    <script src="ajax/ajax_staff_change.js"></script>
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <? /* <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script> */ ?>
    <script src="js/monthpicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <script src="js/form-validation.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>
    <script src="js/jquery.filer.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/jquery-customselect.js"></script>
    <script type="text/javascript" src="js/jquery_facebook.alert.js"></script>      
    <script type="text/javascript">
       $(document).ready(function() {
            $(".chosn").customselect();
            $("#load_page").fadeOut("slow"); 
               $("#appbtn").attr("disabled","disabled");
         
     });
       $(document).on('mouseover', 'div', function(e) {
            
            var btnstat = $("#disablesave1").val();
          
              if(btnstat == 0){
                 $('#btnsave').attr('disabled', 'disabled');
              }
              else{
                 $('#btnsave').removeAttr('disabled');
              }      
      
});
      
       function refresh(){
         $("#load_page").show();
         window.location.reload(true);

       }


       function moveToapprova(){
        var apprnumb = $("#appnumb").text();
         var tar =  $("#hid_expense").val();
         console.log(tar+""+apprnumb);

       }

       function expensePreview(){
         var apprnumb = $("#appnumb").text();
         var tar =  $("#hid_expense").val();
         var tar1 = tar.split("-");
         var prd = $("#slt_subproduct").val();
         if(apprnumb != '' && tar1[0] != '' && prd !='' )
         {
         $.ajax({
             url:"prakash/ajax_load_supplier_entry_list2.php?action=preview&ap="+apprnumb+"&tar="+tar1[0]+"&prd="+prd,
            //url:"viki/post_test.php?ap="+ap+"&tar="+tar+"&prd="+prd,
            type: "POST",                    
            processData: false,
            contentType: false,
            async:true,
            dataType:"html",
            success:function(data){
             //console.log(data);
             //window.open(,'_blank');
              // $("#myModal1").modal('show');
              //       $('#load_page').hide();
              //       document.getElementById('modal-body1').innerHTML=data;
              //       $('#load_page').hide();
            //window.location.reload(true); 
            },
            error:function(error){
              console.log("error");
            }
         });
     }

     else{
        jAlert("Please select the sub-product in the page !",'Alert');
     }
       }


       function removeDupe(t)    {
        var fieldArrayOwner = document.getElementsByName("slp_load1[]");
        console.log(t.value);
       var vals =  t.value.trim();
        var OwnerCount = [];
        for(var owner =0 ; owner <fieldArrayOwner.length ; owner++ )
                    {
                        OwnerCount[owner] = document.getElementsByName("slp_load1[]")[owner].value;
                    }
                var a = OwnerCount.indexOf(vals);     
            // var fieldOwner1 = OwnerCount.slice();
            // var check = $.inArray(t.value, OwnerCount);
            console.log(a);
    if (a != -1){
        console.log("yes");
        jAlert('Supplier exists! Please check the input field.', 'Alert');
        //alert("Supplier exists!");
        t.value='';
    }
        
        else{
            
        }
            
        console.log(OwnerCount);
        console.log(vals);
       }

       function valueNotZero(ls)
       {
        console.log(ls.value);

        if(ls.value != 0){
            return true;
        }
        else{
               ls.value = '';
               //ls.focus();
             return false;
        }
       
       }

//run time check
        function removeDupeRun(t)    {

        var fieldArrayOwner = document.getElementsByName("slp_load[]");
        console.log(fieldArrayOwner);
       var vals =  t.value.trim();
        var OwnerCount = [];

        for(var owner =0 ; owner <fieldArrayOwner.length ; owner++ )
                    {
                        OwnerCount[owner] = document.getElementsByName("slp_load[]")[owner].value;
                    }
                var a = OwnerCount.indexOf(vals);     
            // var fieldOwner1 = OwnerCount.slice();
            // var check = $.inArray(t.value, OwnerCount);
            console.log(a);
            console.log(OwnerCount);
             console.log(vals);
                if (a != -1 ){
                    console.log("yes");
                    jAlert('Supplier exists! Please check the input field run.', 'Alert');
                    //alert("Supplier exists!");
                    t.value='';
                }
                    
                    else{
                        
                    }

            
            
       
       }

       
           
           //run time finish     
  
var flag =0;
               var dupe = 0;

          
      function add_row(count)
      {    
        $("#disablesave1").val(1);
           $(' #btnsave').removeAttr('disabled');
           $(' #btnsave').attr('disabled', false);

        var ntab=count;
       ntab++;
        $('#list_content').append("<div id=content"+ntab+" class='row' style='margin-bottom: 10px;''>"+
                                        "<div class='form-group'>"+
                                         
                                          "<div class='col-sm-3 col-xs-3' style='width:300px' >"+
                                              "<input type='text' class='form-control findsp required' onblur='javascript:return removeDupe(this)' id='slp_load_"+ntab+"' name='slp_load[]' style='text-transform: uppercase;' maxlength='100' required  onblur ='javascript:return removeDupeRun(this)' />"+
                                          "</div><!-- supplier -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:100px'>"+
                                              "<input type='text' class='form-control required' onkeypress='javascript:return isNumber(event);' id='duration_"+ntab+"' name='duration[]' style='text-transform: uppercase;' maxlength='3' required onblur ='javascript:return valueNotZero(this)' />"+
                                          "</div><!-- duration -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:100px'>"+
                                              "<input type='text' class='form-control required rateclass' onkeypress='javascript:return validateFloatKeyPress(this,event);return isNumber(event);' id='rate_"+ntab+"' name='rate[]' style='text-transform: uppercase;' maxlength='8' required onblur ='javascript:return valueNotZero(this)' />"+
                                          "</div><!-- rate -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:100px'>"+
                                              "<input type='text' class='form-control required ' onkeypress='javascript:return validateFloatKeyPress(this,event);return isNumber(event);' id='discount_"+ntab+"' name='discount[]' oninput ='checkNumber(this)' style='text-transform: uppercase;' maxlength='5' required />"+
                                          "</div><!-- discount -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:100px'>"+
                                              "<input type='text' class='form-control required' id='qty_"+ntab+"' name='qty[]' onkeypress='javascript:return isNumber(event);'  style='text-transform: uppercase;' maxlength='7' required onblur ='javascript:return valueNotZero(this)' />"+
                                          "</div><!-- amount -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:100px'>"+
                                             "<input type='text' class='form-control' id='cgst_"+ntab+"' name='cgst[]' readonly />"+
                                          "</div><!-- CGST -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:100px' >"+
                                              "<input type='text' class='form-control' id='sgst_"+ntab+"' name='sgst[]' readonly />"+
                                          "</div><!-- SGST -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:100px' >"+
                                              "<input type='text' class='form-control' id='igst_"+ntab+"' name='igst[]' readonly />"+
                                          "</div><!-- IGSTt -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:100px' >"+
                                          "<input type='text' class='form-control' id='cess_"+ntab+"' name='cess[]' />"+
                                          "</div><!-- IGSTt -->"+
                                          "<div class='col-sm-1 col-xs-1' style='width:200px'>"+
                                              // "<input type='text' class='form-control' id='title' name='title' style='text-transform: uppercase;' maxlength='100' required />"+
                                               "<input type='file' placeholder='Document Attachment' tabindex='8' class='form-control input-group filename' name='attachments[]' id='attachments_"+ntab+"' onchange='ValidateSingleInput(this, 'all'); displayname();'  accept='image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf' value='' data-toggle='tooltip' data-placement='top' title='Document Attachment'>"+
                          "<span class='help-block'>NOTE : ALLOWED ONLY PDF / IMAGES</span>"+
                                          "</div><!-- attachemnt -->"+
                                          "<div class='col-sm-2 col-xs-2' style='width:200px'>"+
                                               "<input type='text' class='form-control' id='remarks_"+ntab+"' name='remarks[]' style='text-transform: uppercase;' maxlength='50'  />"+
                                          "</div><!-- remarks -->"+
                                          "<div class='col-sm-1 col-xs-3' style='width:100px'>"+
                                            
                                            "<div class='col-sm-1' style='width:300px'>"+
                                              "<span style='width: 40%;float:right;display: inline-block;' class='input-group-btn'><button id='add_button' type='button' onclick='remove_row("+ntab+")' class='btn btn-danger' title ='Remove'>-</button></span>"+
                                            "</div>"+
                                          "</div><!-- remarks -->"+
                                        "</div></div>");
       
              $(" .findsp").autocomplete({
                    source: function( request, response ) {
                        $.ajax({
                            url : 'ajax/get_supplier_details.php',
                            dataType: "json",
                            data: {
                               name_startsWith: request.term,
                               slt_core_department: 0,
                               action: 'supplier_details'
                            },
                            success: function( data ) {

                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        });
                    },

                change: function(event,ui)
                { if (ui.item == null) 
                {
                    $(this).val('');
                    $(this).focus(); 
                   jAlert("Enter Valid Supplier Details","Alert");
                } 
                },
                    autoFocus: true,
                    minLength: 0
                });   


           
      }
      
     

      function remove_row(tab)
      {
        $("#content"+tab).remove();
      }


        //Save

        function supplierquote(){
        var prd = $("#slt_subproduct").val();
        if(prd !='' ){
         var form_data = new FormData(document.getElementById("frm_supplier_entry"));
           validate();
           norepeat_sup();
          var ap = $("#appnumb").text();
          var tar =  $("#hid_expense").val();
          //var tar = $("#expslist").val();
          var tar1 = tar.split("-");
          var prd = $("#slt_subproduct").val();
          
          //alert("++"+ap+"++"+tar1[0]+"++"+prd+"++++++++++++++++++");
           if(flag == 1 && dupe == 1 ){
             $('#load_page').show();
            $.ajax({
             url:"prakash/savesupplierquote.php?action=save&ap="+ap+"&tar="+tar1[0]+"&prd="+prd,
            //url:"viki/post_test.php?ap="+ap+"&tar="+tar+"&prd="+prd,
            type: "POST", 
            data:form_data,          
            processData: false,
            contentType: false,
            async:true,
            dataType:"html",
            success:function(data){
             //console.log(data);
             jAlert("Supplier quotations successfully added.","Alert");
              $('#load_page').hide();
            //window.location.reload(true); 
            },
            error:function(error){
              console.log("error");
            }
          });

        }
        else{
          //alert("Input field required");
          flag = 0 ; dupe = 0;
        }
        }
        else{
            jAlert("Please choose the subproduct","Alert");
        }

      }

//validate
  function validate(){
      $(" .required").each(function(){
        var str = $(this).val();
        console.log(str);
        if (str.trim() != '')
         {
          flag  =1;
         }
         // else if(str.trim() == 0){

         // }

         else{
            jAlert("Input fields are empty.","Alert");
            flag = 0;
            return false;
            //alert("Input field required");
         }
         console.log(this);

      });
    }
     function validateFloatKeyPress(el, evt) {
           
             
            var charCode = (evt.which) ? evt.which : event.keyCode;
           
            var number = el.value.split('.');
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            //just one dot
            if(number.length>1 && charCode == 46){
                 return false;
            }
            //get the carat position
             
            var caratPos = getSelectionStart(el);
            var dotPos = el.value.indexOf(".");
            if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
               
                return false;
            }
    
        

           return true;
       
     }
    // function isBelow(el,evt){
    //          if ($(this).val() > 100){
    //             alert("No numbers above 100");
    //             $(this).val('');
    //           }
    //     }
        

    function getSelectionStart(o) {
          if (o.createTextRange) {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '') return o.value.length
            return o.value.lastIndexOf(r.text)
          } else return o.selectionStart
        }

    function checkNumber(val){
            var val2 = parseFloat(val.value);
        if (val2 <99.9){
          
        }
        else{
            val.value = '';
           
        }
    }
    
            function isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var iKeyCode = (evt.which) ? evt.which : evt.keyCode
                //if (iKeyCode != 46 && iKeyCode > 31 && charCode != 34 && charCode != 39 && (iKeyCode < 48 || iKeyCode > 57)){
            if (iKeyCode > 31 && iKeyCode != 39 && iKeyCode != 34  && (iKeyCode < 48 || iKeyCode > 57 || iKeyCode == 46)){
                
                return false;
                }
                return true;
            }    


   
    function norepeat_sup(){

            var fieldArray = [];
            var fieldno = document.getElementsByName("slp_load[]");

            for(var con =0 ; con < fieldno.length ; con++ )
            {
            fieldArray[con] = document.getElementsByName("slp_load[]")[con].value;
            }

            var fieldArray1 = fieldArray.slice();  
            hasDupesIn(fieldArray1);
    function hasDupesIn(fieldArray1) 
          {
              // temporary object 
              var uniqOb = {};
              // create object attribute with name=value in array, this will not keep dupes
              for (var i in fieldArray1)
              uniqOb[fieldArray1[i]] = "";
              // if object's attributes match array, then no dupes!
              if (fieldArray1.length == Object.keys(uniqOb).length){
              //alert('Good'); 
             dupe = 1;
                
              }
              else{
              jAlert('Supplier Entry Has Duplicates. Please check','Alert');
              //$("#txt_ledger_name1").focus(); 
              dupe = 0;
                  }
          }
      
    }
     

      

      //Call Sub Product List

      function call_subproduct_list(expense){
        $('#load_page').show();
    $("#hid_expense").val(expense); 
        var tarnumb = expense.split("-");

       //  alert(tarnumb);
       console.log(tarnumb);

       $.ajax({
            url:"prakash/ajax_load_supplier_entry_list2.php?action=load_subproducts&tarnumb="+tarnumb[0],
            type: "POST",           
            processData: false,
            contentType: false,
            async:true,
            dataType:"html",
            success:function(data){
              $("#div_supproduct_supplier").html(data);
               $.getScript("js/jquery-customselect.js");
              $('#load_page').hide();
              
            },
            error:function(error){
              console.log("error");
            }
          });
      }
      function update_row(rownum,tab){
        $('#load_page').show();
        
       $.ajax({
            url:"prakash/ajax_load_supplier_entry_list2.php",
            type: "POST",           
           data:{action:'update_row',
                 entnumb:rownum},
            dataType:"html",
            success:function(data){
                 $("#content"+tab).remove();
             //console.log(data);
             $('#load_page').hide();
             jAlert('Supplier removed successfully.','Alert');
             
              
            },
            error:function(error){
              console.log("error");
            }
          });
      }
      

       function getsuppliertable(subproduct,tarnum){

        $('#load_page').show();

        console.log(tarnum);
        console.log(subproduct);
        var subproducts = subproduct.split(":");
        var prdcode=subproducts[0];
        var depcode=subproducts[1];
        var subcode=subproducts[2];        
       
        
        console.log("ss");
       $.ajax({
            url:"prakash/ajax_load_supplier_entry_list2.php",
            type: "POST",
            data:{action:'load_supplier_entry',
                    prdcode:prdcode,
                    depcode:depcode,
                    subcode:subcode,
                    tarnum:tarnum},           
            
            //contentType: false,
            //async:true,
            dataType:"html",
            success:function(data){
                //console.log(data);
              $("#div_supplier_entry").html(data);
               $.getScript("js/jquery-customselect.js");
               
               $("#panel_exp_title").text($("#slt_subproduct option:selected").text());
              $("#panel_exp_head").css("background-color","gray");
              $("#panel_exp_title").css("font-weight","bold");
              $("#panel_exp_title").css("color","white");
              $("#expslist").removeClass("custom-select");
              $("#expslist").removeClass("chosn");
              $("#exp").remove();
              $("#ex_dropvalue").val($("#hid_expense").val());
              $("#ex_dropvalue").css("display","inline-block");
               $("#refresh").css("display","inline-block");

             
            

                
              $('#load_page').hide();
              
            },
            error:function(error){
              console.log("error");
            }
          });
      }

 function ValidateSingleInput(oInput, file_ext) {
        $('#load_page').show();
    
        if(file_ext == 'pdf') {
            var _validFileExtensions = [".pdf",".PDF"];
      //alert(oInput+" 1\n "+file_ext);
        } else {
            var _validFileExtensions = [".jpg",".jpeg",".png",".gif",".pdf",".JPG",".JPEG",".PNG",".GIF",".PDF"];
      //  alert(oInput+" 2\n "+file_ext);
        }
        if (oInput.type == "file") {
            var sFileName = oInput.value;
      //alert(sFileName+" 3\n "+file_ext);
             if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }

                if (!blnValid) {
                   
          jAlert("Kindly Upload Only PDF file. Other Formats not allowed!!");
          $("#load_page").fadeOut("slow");
                    oInput.value = "";
                    return false;
                }
            }
      
            $('#load_page').hide();
        }
        return true;
    }

    
         

   
    

    </script>
<!-- END SCRIPTS -->
</body>
</html>
<? } else { ?>
    <script>window.location="home.php";</script>
<?php exit();
}