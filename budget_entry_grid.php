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
<title>Service Report :: Service Request :: <?php echo $site_title; ?></title>
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
                    

                     <div class="form-group trbg non-printable" style="display:block;">
                       <?
                      $sql_brn = select_query_json("select distinct b.brncode,b.brnname from branch b,approval_branch_head ab where b.brncode=ab.brncode order by brncode", "Centra", "TCS");
                      $sql_exp = select_query_json("select distinct(expsrno),expname from trandata.department_asset@tcscentr where deleted='N' and expsrno>0 order by expsrno", "Centra", "TCS");
                      ?>
                      <!-- <center> -->
                        <div class='row'>
                            <div class="col-md-4">
                              <div class="input-group " style="margin:10px;">                           
                                  <select class="form-control custom-select chosn" autofocus tabindex='1' required id="branchname" name="branchname">
                                      <option data-selected="" value="">Choose branch</option>
                                      <?for($k=0;$k<sizeof($sql_brn);$k++){?>
                                      <option data-selected="<?=$sql_brn[$k]['BRNNAME']?>" value="<?=$sql_brn[$k]['BRNCODE']?>"><?=$sql_brn[$k]['BRNNAME']?></option>
                                      <?}?>
                                   </select>
                                  <span class="input-group-btn" style="background-color: black">
                                      <button class="btn btn-default" type="button" style="background-color: black"><span  style="background-color: black;color: white;">Branch</span></button>
                                  </span>                                
                              </div>
                            </div>
                          
                           <div class="col-md-4">
                              <div class="input-group " style="margin:10px">                           
                                  <select class="form-control custom-select chosn" autofocus tabindex='1' required id="expsrno" name="expsrno">
                                      <option value="">Choose Expense Head</option>
                                      <option value="all">ALL</option>
                                      <?for($k=0;$k<sizeof($sql_exp);$k++){?>
                                      <option data-selected="<?=$sql_exp[$k]['EXPNAME']?>" value="<?=$sql_exp[$k]['EXPSRNO']?>"><?=$sql_exp[$k]['EXPNAME']?></option>
                                      <?}?>
                                   </select>
                                  <span class="input-group-btn" style="background-color: black">
                                      <button class="btn btn-default" type="button" style="background-color: black"><span  style="background-color: black;color: white;">Expense Head</span></button>
                                  </span>                                
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="input-group " style="margin: 10px;vertical-align: middle;">
                                    <button class="btn btn-warning" type="button"  onclick="nload();"><span class="fa fa-undo"></span> Load</button>
                                     <button class="btn btn-danger" type="button" style="margin-left: 10px; float: right;" onclick="nview();"><span class="fa fa-file-text"></span> View</button>
                                    <!-- <button class="btn btn-warning" type="button" style="background-color: " onclick="addtab();"><span class="fa fa-undo"></span> Load</button> -->
                              </div>
                            </div>
                      </div>
                      <!-- /////////////////////////tabtest -->
                    <div class="col-md-12" style='clear:both; border-top:1px solid #ADADAD; padding-top: 10px; margin-top: 10px;'>
                            <!-- START VERTICAL TABS WITH HEADING -->
                            <div class="panel panel-default nav-tabs-vertical" >                   
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title">Work Sheet</h3>
                                </div>
                                <div class="tabs">
                                    <ul class="nav nav-tabs" id="tab_header">
                                    </ul>                    
                                    <div class="panel-body tab-content" id="tab_content" style="border-left: 1px solid #ADADAD;width: 88%;margin-bottom:10px; margin-top: 10px;">                 
                                    </div>
                                </div>
                            </div>                        
                            <!-- END VERTICAL TABS WITH HEADING -->
                        </div>
                    <!-- ////////////////tab test -->
                       <!--  </center> -->
                    </div>
                    <!-- viki -->
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
      var ntab=0;
      var modal = document.getElementById('myModal');

      // Get the button that opens the modal
      var btn = document.getElementById("view_msg");

      // Get the <span> element that closes the modal
      var span = document.getElementsByClassName("close")[0];
      span.onclick = function() {
          modal.style.display = "none";
            $("#modal_data").html(' ');
      }

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
          if (event.target == modal) {
              modal.style.display = "none";
              $("#modal_data").html(' ');
          }
      }

      $('#load_page').fadeOut('slow');
      function nload()
      { $('#load_page').fadeIn('fast');
        ntab++;
        $.ajax({
          url:"viki/budget_entry.php",
          data:{
            branch:$('#branchname').val(),
            expsrno:$('#expsrno').val(),
            tabid:ntab,
            action:'load'
          },
          dataType:'html',
          success:function(data1)
          {
              //alert("success");
              var name=$('#expsrno option:selected').text();
              $('#tab_header').append("<li><a aria-expanded='false' href='#tab"+ntab+"' data-toggle='tab'>"+name+"</a></li>")
              $('#tab_content').append("<div class='tab-pane' id='tab"+ntab+"'>"+data1+"</div>");
              if(ntab==1)
              {
                $('#tab'+ntab).addClass('active');
              }
              //$('#maintable').html(data1);
              $('#ntable'+ntab).dataTable();
              $('#load_page').fadeOut('fast');
          },
          error:function(err){
            console.log(err);
          }
        });
      }
      function nsubmit(tab)
      { var form_data = new FormData(document.getElementById("frm_budget_entry"+tab));
        $('#load_page').fadeIn('fast');
        $.ajax({
         url:"viki/budget_entry.php",
          //url:"viki/post_test.php",
          type: "POST",
          data: form_data,
          processData: false,
          contentType: false,
          async:true,
          }).done(function(data)
          {
              console.log(data);
              $("#load_page").fadeOut("slow");
              //window.location.reload();
              
          });
      }
      function nview()
      {   $('#load_page').fadeIn('slow');
          
          $.ajax({
          url:"viki/budget_entry.php",
          data:{
            action:'view'
          },
          dataType:'html',
          success:function(data1)
          {
              //alert("success");
              $('#load_page').fadeOut('fast');
              $('#modal_data').html(data1);
              $('#viewtable').dataTable();
              modal.style.display = "block";
          },
          error:function(err){
            console.log(err);
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