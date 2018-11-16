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

$menu_name = 'SERVICE REQUEST';
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
<title>Purchase Order Track Fixing :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
<link rel="icon" href="favicon.ico" type="image/x-icon" />

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

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
    .tabs {
    margin-top: 10px !important;

    }
    .nav-tabs-vertical .nav-tabs > li.active > a, .nav-tabs-vertical .nav-tabs > li.active > a:hover, .nav-tabs-vertical .nav-tabs > li.active > a:focus, .nav-tabs-vertical .nav-tabs > .dropdown.active.open > a:hover {
         background-color: rgba(112, 167, 215, 0.89) !important;
         color: white;
    }


    .content-frame-body { padding: 10px !important; }
</style>
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
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
                <li class="active">Purchase Order Track Fixing</li>
            </ul>
            <!-- END BREADCRUMB -->
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="panel-heading" style="margin-bottom: 20px;">
                        <h3 class="panel-title"><strong>Purchase Order Track Fixing</strong></h3>
                        <ul class="panel-controls">
                        </ul>
                    </div>
                    

                     <div class="form-group trbg non-printable" style="display:block;">
                      <?
                           $sql_po = select_query_json("select poryear||' - '||pornumb PONUMB,count(distinct znestat) ss from order_tracking_detail where deleted='N' group by poryear||' - '||pornumb,poryear,pornumb having count(distinct znestat)=1 order by poryear,pornumb", "Centra", "TEST");
                      ?>
                      <input type="hidden" name="po_numb" id="po_numb"/>
                        <!-- <div class='row'>
                            <div class="col-md-4">
                              <div class="input-group " style="margin:10px;">                           
                                  <select class="form-control custom-select chosn" autofocus tabindex='1' required id="po_numb" name="po_numb" >
                                      <option data-selected="" value="">Choose PO. Number</option>
                                      <?for($k=0;$k<sizeof($sql_po);$k++){?>
                                      <option data-selected="<?=$sql_po[$k]['PONUMB']?>" value="<?=$sql_po[$k]['PONUMB']?>"><?=$sql_po[$k]['PONUMB']?></option>
                                      <?}?>
                                   </select>
                                  <span class="input-group-btn" style="background-color: black">
                                      <button class="btn btn-info" type="button" ><span >PO. Number</span></button>
                                  </span>                                
                              </div>
                            </div>
                      </div> -->
                      <div class="row">
                        <div class="col-md-4">
                          <h3 style="text-align: center;"> Po. Number </h3>
                        </div>
                        <div class="col-md-7">
                          <h3 style="text-align: center;"> Particulars </h3>
                        </div>
                      </div>
                      <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-4">
                           <div class="panel panel-default tabs nav-tabs-vertical" style="height:600px;overflow-y: scroll;overflow-x: hidden;border:1px solid black; border-radius: 10px;">                   
                              <ul class="nav nav-tabs " style="width: 100%;border-radius: 10px;">
                                <?for($k=0;$k<sizeof($sql_po);$k++){?>
                                  <li><a data-toggle="tab" onclick="load_process('<?=$sql_po[$k]['PONUMB']?>');" style="border-radius: 10px;"><?=$sql_po[$k]['PONUMB']?></a></li>
                                <?}?>
                              </ul>
                          </div>
                        </div>
                        <div class="col-md-7">
                           <div class="col-md-8" style='clear:both; border:1px solid black; margin-top: 10px;height: 600px;width: 100%; border-radius: 10px; padding:2% 10%;overflow-y: auto;overflow-x: hidden;'>
                              <div class="panel-body">
                                   <form class="form-horizontal" role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                                      <div id="check_list">

                                      </div>
                                  </form>
                              </div>
                          </div>
                        </div>
                   
                    <!-- viki -->
                    
                  </div>
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
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>

    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>

    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <!-- Select2 -->
    <script src="../dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
     $('#load_page').fadeOut('fast');
     $(document).ready(function(){
      $('.all_check').click(function(){
        console.log("hi");
      })
     });
     function load_process(po_numb){
      console.log("loading staqrted");
      $('#po_numb').val(po_numb);
      $.ajax({
        url:"viki/ajax_purchase_order_entry.php",
        data:{
          po_numb:po_numb,
          action:'load'
        },
        dataType:'html',
        success:function(data){
         // console.log(data);
          $('#check_list').html(data);
          $('.all_check').prop("checked",false);
          $('.toggle').click(function(){
            var temp=$(this).val();
            console.log(temp);
            if(temp=='A')
            {
              $(this).val('N');
              console.log($(this).val());
            }
            else{
              $(this).val('A');
            }
          });
          $('.all_check').click(function(){
            var temp=$(this).val();
            if(temp=='A')
            {
              $(this).val('N');
            }
            else{
              $(this).val('A');
            }
            if($(this).val()=='A')
            {
              $('.check').prop("checked",true);
              $('.check').val('A');
            }
            else
            {
              $('.check').prop("checked",false);
              $('.check').val('N');
            }
          });
        }
      });
     }

     function nsubmit()
     {   var po_numb= $('#po_numb').val();
      $('#load_page').fadeIn('slow');
      var check = jQuery("#frm_request_entry").serializeArray();
      check = check.concat(
        jQuery('#frm_request_entry input[type=checkbox]:not(:checked)').map(
                function() {
                    return {"name": this.name, "value": this.value}
                }).get()
      );
      console.log(check);
      $.ajax({
        data:{
          check,
          action:"submit",
          po_numb:po_numb
        },
        url:"viki/ajax_purchase_order_entry.php",
        dataType:'html',
        success:function(data)
        {$('#load_page').fadeOut('slow');
          //console.log(data);
          alert("Submitted Successfully");
          window.location.href("purchase_order_track.php");
        }
      });
     }

     function nsubmit1(){
      var check1 = [];
      var po_numb= $('#po_numb').val();
      $('.check').each(function(){
        var name=$(this).attr('name');
        if(name != '')
        {
          check1[name]=$(this).val();
        }
      });
      console.log(check1);
      $.ajax({
        data:{
          nums:check1
        },
        url:"viki/post_test.php",
        success:function(data){
          console.log(data);
        }
      });   
     }
    </script>
<!-- END SCRIPTS -->
</body>
</html>
<? } else { ?>
    <script>window.location="home.php";</script>
<?php exit();
}