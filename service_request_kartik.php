<?
session_start();
error_reporting(0);
header( 'Content-Type: text/html; charset=utf-8' );
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

if($inner_menuaccess[0]['VEWVALU'] == 'Y') {  // Menu Permission is allowed ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- META SECTION -->
        <title>Support Messages :: Call Centre :: <?php echo $site_title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
        <link href="css/jquery-ui-1.10.3.custom.min.css" rel="Stylesheet"></link>

        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->

        <!-- Select2 -->
        <link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

        <!-- CSS INCLUDE -->
        <?  $theme_view = "css/theme-default.css";
            if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
        <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
        <!-- EOF CSS INCLUDE -->
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

      .section2 {
                float: right;
    margin-top: -270%;
    margin-right: 4%;
      }

      .section2active {
           float: left;
    width: 119%;
    height: 99%;
  
    margin-top: -253%;
      }
      .leftsection{
        width:50%;
      }
      .section1active{
    margin-right: 110%;
    margin-left: -122%;
      }

      #btn1{
                position: relative;
    border-radius: 3px 3px 3px 3px;
    padding: 0px;
   
    width: 15px;
      }

      #listAll{
        margin-top: 10%;
    width: 85%;
      }
    </style>
<script type="text/javascript">

  function sidebars(){
       $("#btn1").click(function(){
        $("#div1").toggleClass("section2");
        $("#div1").toggleClass("section2active");
        $("#div2").toggleClass("leftsection");
        $("#div3").toggleClass("section1active");
        document.getElementById("listAll").hidden=true;
        document.getElementById("listsupplier").value='';
    });
  }

 
</script>
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
                <ul class="breadcrumb push-down-0">
                    <li><a href="home.php">Dashboard</a></li>
                    <li class="active">Service Request & Response</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- START CONTENT FRAME -->
                <div class="content-frame">

                    <div class="row">
                            <div class="col-sm-4">
                                 <input type="submit" id="start-clock" value="Click here to start timer" name="submit" onClick="startClock()"/>
                                <div id="timer">0</div>
                                <input type="submit" id="stop-clock" value="Click here to stop and reset the timer" name="submit" onClick="stopClock()"/>
                            </div>

                            <div class="col-sm-4">
                                 <input type="submit" id="start-clock" value="Click here to start timer" name="submit" onClick="startClock()"/>
                                <div id="timer">0</div>
                                <input type="submit" id="stop-clock" value="Click here to stop and reset the timer" name="submit" onClick="stopClock()"/>
                            </div>


                            <div class="col-sm-4">
                                 <input type="submit" id="start-clock" value="Click here to start timer" name="submit" onClick="startClock()"/>
                                <div id="timer">0</div>
                                <input type="submit" id="stop-clock" value="Click here to stop and reset the timer" name="submit" onClick="stopClock()"/>
                            </div>

                    </div>

                    </div>
                    <!-- END CONTENT FRAME RIGHT -->

                    <!-- START CONTENT FRAME BODY xxxxxxxx-->
                      <div id="div2" class="content-frame-body content-frame-body-left" style="background-color: #FFFFFF;">
                          <form id="mainform" enctype="multipart/form-data">
                          <div  class="messages messages-img" id="all-messages">
                            <div class="page-title"><br><br>
                                <h3>Welcome to Supplier Service Request & Response!!</h3>
                            </div>
                          </div>
                          </form>
                      </div>

                    <!-- END CONTENT FRAME BODY -->
                </div>
                <!-- END PAGE CONTENT FRAME -->
            </div>
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

   


    </body>
</html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>
