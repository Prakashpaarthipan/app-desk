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
                <li class="active">Budget Entry</li>
            </ul>
            <!-- END BREADCRUMB -->
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="panel-heading" style="margin-bottom: 20px;">
                        <h3 class="panel-title"><strong>BUDGET ENTRY</strong></h3>
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

                          <center>

                              <label id = "appnumb" style="font-size: 20px;color: red;font-weight: bold"> <? echo urldecode($_REQUEST['ap']); ?> </label>
                            </center>
                          </div>
                        </div>
                        
                      </div>
                      <!-- <center> -->
                        <div class='row'>
                           <div class="col-md-4"> 

                              <div class="input-group " style="margin:10px">                           
                              <select class="form-control custom-select chosn" autofocus tabindex='1' required id="expslist" name="expsrno" onchange="call_subproduct_list(this.value)">
                                  <option value="">Choose Expense Head</option>
                                 
                                  <?for($k=0;$k<sizeof($ledgerfilter);$k++){?>
                                  <option  value="<?=$ledgerfilter[$k]['TARGETNUMBER']?>"><?=$ledgerfilter[$k]['TARGETNUMBER']?>-<?=$ledgerfilter[$k]['LEDGERNAME']?></option>
                                  <?}?>
                               </select>
                              <span class="input-group-btn" style="background-color: black">
                                  <button class="btn btn-default" type="button" style="background-color: black"><span  style="background-color: black;color: white;">Expense Head</span></button>
                              </span>     


                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="input-group " style="margin: 10px;vertical-align: middle;">
                                    <button class="btn btn-warning" type="button"  onclick=""><span class="fa fa-undo"></span> Load</button>
                                    <!-- <button class="btn btn-warning" type="button" style="background-color: " onclick="addtab();"><span class="fa fa-undo"></span> Load</button> -->
                                
                            </div>
                                                             <!-- <button class="btn btn-warning" type="button" style="background-color: " onclick="addtab();"><span class="fa fa-undo"></span> Load</button> -->
                             
                            </div>

                            <div class="col-md-4">
                              <div class="input-group " style="margin: 10px;vertical-align: middle;margin-left:250px">
                              <button class="btn btn-success pull-right" type="button"   onclick=""><span class="fa fa-finish"></span> Finish</button>
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
    <script type="text/javascript">
       $(document).ready(function() {
            $(".chosn").customselect();
            $("#load_page").fadeOut("slow"); 
               var flag =0;
               var dupe = 0;
         
     });
       
        

          var ntab=1;
      function add_row()
      {
       ntab++;
        $('#list_content').append("<div id=content"+ntab+" class='row' style='margin-bottom: 10px;''>"+
                                        "<div class='form-group'>"+
                                          "<div class='col-md-1 col-xs-1' style='text-align: center;'>"+
                                              "<label >"+ntab+"</label>"+
                                          "</div>"+
                                          "<div class='col-md-3 col-xs-3'>"+
                                              "<input type='text' class='form-control findsp required' id='#slp_load_"+ntab+"' name='slp_load[]' style='text-transform: uppercase;' maxlength='100' required />"+
                                          "</div><!-- supplier -->"+
                                          "<div class='col-md-1 col-xs-3'>"+
                                              "<input type='text' class='form-control required' id='duration_"+ntab+"' name='duration[]' maxlength='2' style='text-transform: uppercase;'  required />"+
                                          "</div><!-- duration -->"+
                                          "<div class='col-md-1 col-xs-3'>"+
                                              "<input type='text' class='form-control required rateclass' id='rate_"+ntab+"' name='rate[]' maxlength='8' maxlength='2' style='text-transform: uppercase;'  required />"+
                                          "</div><!-- rate -->"+
                                          "<div class='col-md-1 col-xs-3'>"+
                                              "<input type='text' class='form-control required ' id='discount_"+ntab+"' name='discount[]' style='text-transform: uppercase;' maxlength='2' required />"+
                                          "</div><!-- discount -->"+
                                          "<div class='col-md-1 col-xs-3'>"+
                                              "<input type='text' class='form-control required' id='qty_"+ntab+"' name='qty[]' style='text-transform: uppercase;' maxlength='5' required />"+
                                          "</div><!-- amount -->"+
                                          "<div class='col-md-2 col-xs-3'>"+
                                              // "<input type='text' class='form-control' id='title' name='title' style='text-transform: uppercase;' maxlength='100' required />"+
                                               "<input type='file' placeholder='Document Attachment' tabindex='8' class='form-control input-group filename' name='attachments[]' id='attachments_"+ntab+"' onchange='ValidateSingleInput(this, 'all'); displayname();'  accept='image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf' value='' data-toggle='tooltip' data-placement='top' title='Document Attachment'>"+
                          "<span class='help-block'>NOTE : ALLOWED ONLY PDF / IMAGES</span>"+
                                          "</div><!-- attachemnt -->"+
                                          "<div class='col-md-1 col-xs-3'>"+
                                               "<input type='text' class='form-control' id='remarks_"+ntab+"' name='remarks[]' style='text-transform: uppercase;' maxlength='50' required />"+
                                          "</div><!-- remarks -->"+
                                          "<div class='col-md-1 col-xs-3'>"+
                                            
                                            "<div class='col-md-3'>"+
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
         var form_data = new FormData(document.getElementById("frm_supplier_entry"));
           validate();
           norepeat_sup();
          var ap = $("#appnumb").text();
          
          var tar = $("#expslist").val();
          var prd = $("#slt_subproduct").val();

          alert("++"+ap+"++"+tar+"++"+prd+"++++++++++++++++++");
           if(flag == 1 && dupe == 1 ){
            $.ajax({
             url:"prakash/savesupplierquote.php?action=save&ap="+ap+"&tar="+tar+"&prd="+prd,
            //url:"viki/post_test.php?ap="+ap+"&tar="+tar+"&prd="+prd,
            type: "POST", 
            data:form_data,          
            processData: false,
            contentType: false,
            async:true,
            dataType:"html",
            success:function(data){
             console.log(data);
              $('#load_page').hide();
              
            },
            error:function(error){
              console.log("error");
            }
          });

        }
        else{
          alert("Input field required");
          flag == 0 ; dupe == 0;
        }
      }

//validate
  function validate(){
      $(" .required").each(function(){
        var str = $(this).val();
        if (str.trim() != '')
         {
          flag  =1;
         }

      });
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
              alert('Supplier Entry Has Duplicates. Please check');
              //$("#txt_ledger_name1").focus(); 
              dupe = 0;
                  }
          }
      // $(" .findsp").each(function(){
      //   var str = $(this).val();
      //   if (str.trim() != '')
      //    {
      //     flag  =1;
      //    }

      // });
    }
     

      

      //Call Sub Product List

      function call_subproduct_list(expense){
        $('#load_page').show();
        var tarnumb = expense.split(" - ");
       $.ajax({
            url:"prakash/ajax_load_supplier_entry.php?action=load_subproducts&tarnumb="+tarnumb,
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

      

       function getsuppliertable(subproduct){

        $('#load_page').show();
        var subproducts = subproduct.split(" # ");
       
       $.ajax({
            url:"prakash/ajax_load_supplier_entry.php?action=load_supplier_entry",
            type: "POST",           
            processData: false,
            contentType: false,
            async:true,
            dataType:"html",
            success:function(data){
              $("#div_supplier_entry").html(data);
               $.getScript("js/jquery-customselect.js");
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
                    // alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                    // alert("Sorry, Upload Only PDF file Format");
                    //var ALERT_TITLE = "Message";
                    //var ALERTMSG = "Kindly Upload Only PDF file. Other Formats not allowed!!";
                    //createCustomAlert(ALERTMSG, ALERT_TITLE);
          alert("Kindly Upload Only PDF file. Other Formats not allowed!!");
          $("#load_page").fadeOut("slow");
                    oInput.value = "";
                    return false;
                }
            }
      
            $('#load_page').hide();
        }
        return true;
    }

    
         

   function load1(){
    alert("hi");

    var val = document.getElementsByName('attachment');
    console.log(val);

   }

    

    </script>
<!-- END SCRIPTS -->
</body>
</html>
<? } else { ?>
    <script>window.location="home.php";</script>
<?php exit();
}