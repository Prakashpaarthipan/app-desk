<?
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
<!-- META SECTION -->
  <title><?=$stats?> Approvals List :: Approval Desk :: <?php echo $site_title; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="icon" href="favicon.ico" type="image/x-icon" />
  <!-- END META SECTION -->

  <!-- CSS INCLUDE -->
  <?  $theme_view = "css/theme-default.css";
      if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
  <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
  <!-- EOF CSS INCLUDE -->


  <!-- angular JS Plugin  -->
  <script src="app/jquery.min.js"></script>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js"></script>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-route.min.js"></script>
  <script src="app/angular-animate.js"></script>
  <script src="app/angular-route-segment.min.js"></script>

  <!-- angular JS date picker  -->
  <link rel="stylesheet" type="text/css" href="app/dp/helper.css">
  <link rel="stylesheet" type="text/css" href="app/dp/awful.css">
  <link rel="stylesheet" type="text/css" href="app/dp/angular-datepicker.css">
  <script type="text/javascript" src="app/dp/angular-datepicker.js"></script>
  <script type="text/javascript" src="app/dp/angular-highlightjs.min.js"></script>
  <style>
    .xn-profile a:hover{
      background: #fff !important;
    }
  </style>

</head>
<body ng-controller="mainController">
    <div id="load_page" style='display:block;padding:12% 40%;'></div>
    <!-- START PAGE CONTAINER -->
    <div class="page-container page-navigation-top-fixed">

        <!-- START PAGE SIDEBAR -->
        <div class="page-sidebar">
            <!-- START X-NAVIGATION -->
            <?// include 'lib/app_left_panel.php'; ?>
            <?
            $currentFile = $_SERVER["SCRIPT_NAME"];
            $parts = Explode('/', $currentFile);
            $currentFile = $parts[count($parts) - 1];
            ?>
            <ul class="x-navigation">
                <li class="xn-logo">
                    <a href="home.php" style="padding: 0px !important;"><img src="images/logo.png" style="padding: 5px 2px;" alt="<?=$site_title?>"/></a>
                    <a href="#" class="x-navigation-control"></a>
                </li>
                <li class="xn-profile" style="background:#fff">
                    <a href="home.php" class="profile-mini">
                        <img src="images/logo-original.png" alt="<?=$site_title?> - Logo"/>
                    </a>

                    <div class="profile">
                        <div class="profile-data">
                            <div class="profile-data-name"><b><?=strtoupper($_SESSION['tcs_empname'])?></b></div>
                            <div class="profile-data-title"><?=strtoupper($_SESSION['tcs_empsubcore'])?></div>
                        </div>
                    </div>
                </li>
                <? /* <li class="xn-title">Navigation</li> */ ?>
                <li class="<?php echo ($currentFile=='home.php')?'active':'';?>">
                    <a href="home.php"><span class="fa fa-dashboard"></span> <span class="xn-text">Dashboard</span></a>
                </li>
                <li class="xn-openable <?php echo ($currentFile=='request_entry.php' or $currentFile=='request_list.php' or $currentFile=='budget_list.php' or $currentFile=='budget_entry.php' or $currentFile=='waiting_approval.php' or $currentFile=='waiting_query.php' or $currentFile=='other_request_list.php' or $currentFile=='md_pending_approvals.php' or $currentFile=='search-result.php')?'active':'';?>">
                    <a href="javascript:void(0)"><span class="fa fa-fw fa-list-alt"></span> <span class="xn-text">Approval Request</span></a>
                    <ul>
                        <li class="<?php echo ($currentFile=='request_entry.php')?'active':'';?>"><a href="request_entry.php"><span class="fa fa-edit"></span>New Request Entry</a></li>
                        <li class="<?php echo ($currentFile=='request_list.php')?'active':'';?>"><a href="request_list.php"><span class="fa fa-table"></span>Request List</a></li>
                        <? /* <li class="<?php echo ($currentFile=='budget_list.php')?'active':'';?>"><a href="budget_list.php"><span class="fa fa-money"></span>Budget List</a></li>
                        <li class="<?php echo ($currentFile=='other_request_list.php')?'active':'';?>"><a href="#"><span class="fa fa-users"></span>Others Request List</a></li>
                        <li class="<?php echo ($currentFile=='md_pending_approvals.php')?'active':'';?>"><a href="#"><span class="fa fa-users"></span>MD Pending Approvals</a></li> */ ?>
                        <li class="<?php echo ($currentFile=='waiting_approval.php')?'active':'';?>"><a href="waiting_approval.php"><span class="fa fa-check-square"></span>Waiting for Approval</a></li>
                        <li class="<?php echo ($currentFile=='waiting_query.php')?'active':'';?>"><a href="waiting_query.php"><span class="fa fa-question-circle"></span>Waiting With Query</a></li>
                        <li class="<?php echo ($currentFile=='search-result.php')?'active':'';?>"><a href="search-result.php"><span class="fa fa-search"></span>Search Result</a></li>
                    </ul>
                </li>
                <li class="xn-openable <?php echo ($currentFile=='approved_approvals.php' or $currentFile=='acknowledge_approvals.php')?'active':'';?>">
                    <a href="javascript:void(0)"><span class="fa fa-bar-chart-o"></span> <span class="xn-text">Reports</span></a>
                    <ul>
                      <li ng-class="{active: ('Approved Approvals' | routeSegmentStartsWith)}"><a ng-href="#/Approved Approvals"><span class="fa fa-check-circle-o"></span> Approved Approvals </a></li>
                      <li ng-class="{active: ('Pending Approvals' | routeSegmentStartsWith)}"><a ng-href="#/Pending Approvals"><span class="fa fa-exclamation-triangle"></span> Pending Approvals</a></li>
                      <li ng-class="{active: ('Rejected Approvals' | routeSegmentStartsWith)}"><a ng-href="#/Rejected Approvals"><span class="fa fa-times-circle"></span> Rejected Approvals</a></li>
                      <li ng-class="{active: ('Internal Verification Approvals' | routeSegmentStartsWith)}"><a ng-href="#/Internal Verification Approvals"><span class="fa fa-check-circle-o"></span> Internal Verification Approvals</a></li>
                      <li class="<?php echo ($currentFile=='acknowledge_approvals.php')?'active':'';?>"><a href="acknowledge_approvals.php"><span class="fa fa-thumbs-o-up"></span>Acknowledge Alternate Approvals</a></li>
                    </ul>
                </li>
            </ul>
            <!-- END X-NAVIGATION -->
        </div>
        <!-- END PAGE SIDEBAR -->

        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
            <? include "lib/app_header.php"; ?>
            <!-- END X-NAVIGATION VERTICAL -->

            <!-- START BREADCRUMB --
            <ul class="breadcrumb">
                <li><a href="home.php">Dashboard</a></li>
                <li class="active">Reports - <?//=$stats?> Approvals List</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="row anim" app-view-segment="0"></div>
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
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>APPROVAL FINAL FINISH</div>
                <div class="modal-body" id="modal-body1"></div>
            </div>
        </div>
    </div>

    <div id="myModal2" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-body" id="modal-body2"></div>
            </div>
        </div>
    </div>
    <!-- Collect Document -->
    <div class='clear'></div>

    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>

<!-- START SCRIPTS -->
    <!-- START PLUGINS -->


    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
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
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
    function PrintDiv(dataurl) {
        var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
    }

    $(function() {
        var showTotalChar = 200, showChar = "Show (+)", hideChar = "Hide (-)";
        $('.show_moreless').each(function() {
            var content = $(this).text();
            if (content.length > showTotalChar) {
                var con = content.substr(0, showTotalChar);
                var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                var txt= '<b>'+con +  '</b><span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="javascript:void(0)" class="showmoretxt">' + showChar + '</a></span>';
                $(this).html(txt);
            }
        });

        $(".showmoretxt").click(function() {
            if ($(this).hasClass("sample")) {
                $(this).removeClass("sample");
                $(this).text(showChar);
            } else {
                $(this).addClass("sample");
                $(this).text(hideChar);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });

    $(document).ready(function() {
        $("#load_page").fadeOut("slow");
        $(".finish_confirm").click( function() {
        });
    });

    $(document).keypress(function(e) {
        if (e.keyCode == 27) {
            $("#myModal1").fadeOut(500);
        }
    });

    $('#datepicker-example3').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example4')
    });

    $('#datepicker-example4').Zebra_DatePicker({
      direction: [1, '<?=date("d-M-Y")?>'], // ['<?=date("d-M-Y")?>', 0], // 0,
      format: 'd-M-Y'
    });

    function call_confirm(ivalue, reqid, year, rsrid, creid, typeid, aprnumb)
    {
        $('#load_page').show();
        var send_url = "final_finish.php?aprnumb="+aprnumb+"&reqid="+reqid+"&year="+year+"&rsrid="+rsrid+"&creid="+creid+"&typeid="+typeid;
        $.ajax({
        url:send_url,
        type: "POST",
        success:function(data){
                $("#myModal1").modal('show');
                $('#load_page').hide();
                document.getElementById('modal-body1').innerHTML=data;
                $('#load_page').hide();
            }
        });
    }
    </script>
    <script src="app/app.js"></script>
<!-- END SCRIPTS -->
</body>
</html>
