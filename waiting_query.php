<?
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == "") { ?>
    <script>window.location="index.php";</script>
<?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Waiting with Query Approvals List :: Approval Desk :: <?php echo $site_title; ?></title>
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
</head>
<body>
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> */ ?>
    <!-- START PAGE CONTAINER -->
    <div class="page-container page-navigation-top-fixed">

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
                <li class="active">Waiting with Query Approvals List</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Waiting with Query Approvals List</h3>
                    </div>
                    <div class="panel-body">
                        <table id="customers2" class="table datatable">
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>#</th>
                                <th>Approval Number</th>
                                <th>Details</th>
                                <th>Value &#8377;</th>
                                <th>Request by</th>
                                <? /* <th>Request to</th> */ ?>
                                <th style='text-align:center;'>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?  $sql_search = select_query_json("select ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPFVAL, ar.APRTITL, ar.ARQSRNO, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR,
                                                                            ar.ADDUSER, ar.RQESTTO, decode(ar.APPSTAT, 'N','NEW','F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY',
                                                                            'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2',
                                                                            'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, ar.ADDDATE, (select EMPNAME
                                                                            from employee_office where empsrno = ar.ADDUSER) as reqby, REQSTTO as reqto, ar.APPRDET, 
                                                                            pr.pricode, pr.priname, pr.pricode||' - '||pr.priname priority, (select ADDDATE from APPROVAL_REQUEST where ARQSRNO = 1 
                                                                            and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE 
                                                                            and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE) as ADDEDDATE
                                                                        from APPROVAL_REQUEST ar, approval_priority pr
                                                                        where ar.PRICODE = pr.pricode(+) and pr.deleted(+) = 'N' and ar.DELETED = 'N' and (ar.REQSTFR = '".$_SESSION['tcs_empsrno']."' 
                                                                            or ar.INTPEMP = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'Q' or APPFRWD = 'P' or APPFRWD = 'S' ) And 
                                                                            ar.ARQSRNO = (select max(ARQSRNO) from APPROVAL_REQUEST where APRNUMB = ar.APRNUMB)
                                                                        order by ADDDATE asc, APPORDER asc, APPFVAL desc, APRNUMB desc", "Centra", 'TEST');

                                $ij = 0;
                                for($search_i = 0; $search_i < count($sql_search); $search_i++) { $ij++;
                                    // A - Approved; N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
                                    $editid = 0; $bgclr = ''; $clr = '#000000';
                                    if($sql_search[$search_i]['APPSTAT'] == 'A') { $appstatus = "3 - APPROVED"; $bgclr = '#DFF0D8'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'N') { $appstatus = "1 - NEW"; $editid = 1; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'R') { $appstatus = "7 - REJECTED"; $bgclr = '#F2DEDE'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'F') { $appstatus = "2 - FORWARD"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'C') { $appstatus = "8 - COMPLETED"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'P') { $appstatus = "4 - PENDING"; $editid = 0; $bgclr = '#FAF4D1'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'S') { $appstatus = "5 - RESPONSE"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'Q') { $appstatus = "6 - QUERY"; }
                                    $filename = $sql_search[$search_i]['IMFNIMG'];

                                    $sql_pending = select_query_json("select REQSTFR, RQFRDES from APPROVAL_REQUEST
                                                                        where aprnumb like '".$sql_search[$search_i]['APRNUMB']."' and ARQSRNO = (select max(ARQSRNO)
                                                                            from APPROVAL_REQUEST where appstat in ('F', 'N') and
                                                                            aprnumb like '".$sql_search[$search_i]['APRNUMB']."')", "Centra", 'TEST');
                                    $sql_pending_user = explode(" - ", $sql_pending[0]['RQFRDES']); ?>
                                    <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                        <td class="center" style='text-align:center;'>
                                            <? echo $appstatus; ?>
                                        </td>
                                        <td style='text-align:center;'><?=$ij?></td>
                                        <td><a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_search[$search_i]['APRNUMB']; ?></a></td>
                                        <td>
                                            <?  if($sql_search[$search_i]['APPRFOR'] == '1') {
                                                    $filepathname = $sql_search[$search_i]['APPRSUB'];
                                                    $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                    $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
                                                    $contents = fread($handle, filesize($filename));
                                                    fclose($handle);
                                                    // echo strip_tags(str_replace("&nbsp;", " ", $contents));
                                                    echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);
                                                } else {
                                                    echo $sql_search[$search_i]['APPRDET'];
                                                }
                                            ?>
                                        </td>
                                        <td><? if($sql_search[$search_i]['APPFVAL'] != 0) { echo moneyFormatIndia($sql_search[$search_i]['APPFVAL']); } else { echo "-Nil-"; } ?></td>
                                        <td class="center"><? echo $sql_search[$search_i]['REQBY']; ?></td>
                                        <td class="center" style='text-align:center;'>
                                        <? if($sql_search[$search_i]['APPSTAT'] == 'N') { ?>
                                            <a href='waiting_approvals.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a>
                                        <? } else { ?>
                                            <a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a>
                                        <? } ?>
                                        <?

                                          if($sql_search[$search_i]['APPSTAT'] == 'A' or $sql_search[$search_i]['APPSTAT'] == 'R') {
                                              $sql_vl = select_query_json("select ADDDATE from APPROVAL_REQUEST
                                                                                          where aprnumb = '".$sql_search[$search_i]['APRNUMB']."' and ARQSRNO = (select max(ARQSRNO)
                                                                                              from APPROVAL_REQUEST where aprnumb = '".$sql_search[$search_i]['APRNUMB']."')", "Centra", 'TEST');
                                              $start_time = formatSeconds(strtotime($sql_vl[0]['ADDDATE']) - strtotime($sql_search[$search_i]['ADDEDDATE']));
                                          } else {
                                              $start_time = formatSeconds(strtotime('now') - strtotime($sql_search[$search_i]['ADDEDDATE']));
                                          }
                                          $sql_iv = select_query_json("select count(appfrwd) CNTAPPFRWD from approval_request 
                                                                                where aprnumb like '".$sql_search[$search_i]['APRNUMB']."' and appfrwd = 'I' 
                                                                                order by arqsrno", "Centra", "TEST");
                                          $duedate = 0;
                                          switch ($sql_search[$search_i]['PRICODE']) {
                                              case 1:
                                                  $duedate = 1;
                                                  $css_cls = "#FF0000";
                                                  if($start_time <= 1) {
                                                      $css_clstime = "#299654";
                                                  } else {
                                                      $css_clstime = "#FF0000";
                                                  }
                                                  if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + $sql_iv[0]['CNTAPPFRWD']; }
                                                  break;
                                              case 2:
                                                  $duedate = 2;
                                                  $css_cls = "#D58B0A";
                                                  if($start_time <= 2) {
                                                      $css_clstime = "#299654";
                                                  } else {
                                                      $css_clstime = "#FF0000";
                                                  }
                                                  if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
                                                  break;
                                              case 3:
                                                  $duedate = 3;
                                                  $css_cls = "#299654";
                                                  if($start_time <= 3) {
                                                      $css_clstime = "#299654";
                                                  } else {
                                                      $css_clstime = "#FF0000";
                                                  }
                                                  if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
                                                  break;

                                              default:
                                                  $duedate = 1;
                                                  $css_cls = "#FF0000";
                                                  if($start_time <= 1) {
                                                      $css_clstime = "#299654";
                                                  } else {
                                                      $css_clstime = "#FF0000";
                                                  }
                                                  if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + $sql_iv[0]['CNTAPPFRWD']; }
                                                  break;
                                          }

                                          echo '<br><span class="label label-info label-form" style="background-color:'.$css_cls.'">'.$sql_search[$search_i]['PRIORITY']."</span>";
                                          echo '<br><span class="label label-info label-form" style="background-color:'.$css_clstime.'">Due Date : '.$duedate.' Days & Process Date : '.$start_time.' Days</span>';

                                          ?>
                                        </td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
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
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->
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
<!-- END SCRIPTS -->
</body>
</html>
