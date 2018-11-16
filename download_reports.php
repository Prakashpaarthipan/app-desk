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

$menu_name = 'ADMIN DASHBOARD';
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

if($inner_menuaccess[0]['VEWVALU'] == 'Y') { // Menu Permission is allowed ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <!-- META SECTION -->
    <title>Approval Detail Report :: Approval Desk :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <!-- Select2 -->
    <link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

    <style type="text/css">
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
        }
    </style>
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->

    <script type="text/javascript">
        var tableToExcel = (function () {
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            return function (table, name) {
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
                window.location.href = uri + base64(format(template, ctx))
            }
        })()
    </script>
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
                    <li><a href="#">Admin Dashboard</a></li>
                    <li class="active">Approval Detail Report</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Approval Detail Report</h3>
                        </div>
                        <div class="panel-body" style="overflow-x: scroll !important;">
                            <div class="form-group trbg non-printable">
                                <form role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                                    <? /* <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 0;'>
                                        <input type='text' class="form-control" tabindex='1' autofocus name='search_subject' id='search_subject' value='<?=$_REQUEST['search_subject']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Details' title="Details" style='text-transform: uppercase;'>
                                    </div> */ ?>

                                    <div class="col-lg-2 col-lg-offset-2 col-sm-3" style='text-align:center; padding:5px 5px 0 5px;'>
                                        <input type='text' class="form-control" tabindex='1' name='search_aprno' id='search_aprno' value='<?=$_REQUEST['search_aprno']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Approval No' title="Approval No" style='text-transform: uppercase;'>
                                    </div>

                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px;'>
                                        <input type='text' class="form-control" tabindex='2' name='search_value' id='search_value' value='<?=$_REQUEST['search_value']?>' data-toggle="tooltip" data-placement="top" maxlength="10" placeholder='Value' title="Value" style='text-transform: uppercase;'>
                                    </div>

                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px 5px 0 6px;'>
                                        <input type='hidden' name='search_add_findate' id='search_add_findate' value='ADDDATE' >
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                    </div>

                                    <div class="col-lg-2 col-sm-2" style='text-align:center; padding:5px;'>
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
                                    </div>

                                    <div class="col-lg-2 col-sm-1" style='text-align:left; padding:5px;'>
                                        <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
                                    </div>
                                </form>
                            </div>
                            <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                            <table id="customers2" class="table datatable" style="overflow-x: scroll !important;">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>#</th>
                                        <th>Approval Number</th>
                                        <th>Details</th>
                                        <th>Value &#8377;</th>
                                        <th>Requested by</th>
                                        <th>Waiting Person</th>
                                        <th>Request to</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?  $and = "";
                                        if($search_aprno != '') {
                                            $and .= " And ar.APRNUMB like '%".strtoupper($search_aprno)."%' ";
                                        }
                                        if($search_subject != '') {
                                            $and .= " And ar.APPRDET like '%".strtoupper($search_subject)."%' ";
                                        }
                                        if($dept != '') {
                                            $and .= " And ar.ATCCODE = '".$dept."' ";
                                        }
                                        if($prty != '') {
                                            $and .= " And ar.APPSTAT in ('A') And ar.PRICODE = '".$prty."' ";
                                        }
                                        if($search_fromdate != '' or $search_todate != '') {
                                            if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_fromdate);
                                            $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                            if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                            $exp2 = explode("-", $search_todate);
                                            $to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));

                                            if($search_add_findate == 'ADDDATE') {
                                                $and .= " And trunc(ar.ADDDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                            } elseif($search_add_findate == 'FINDATE') {
                                                $and .= " And trunc(ar.FINDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                            }
                                        }
                                        if($search_value != '') {
                                            $and .= " And ar.APPFVAL like '%".strtoupper($search_value)."%' ";
                                        }
                                        if($search_finishornot != '') {
                                            if($search_finishornot == 1) {
                                                $and .= " And ar.appstat in ('A', 'R') ";
                                            } elseif($search_finishornot == 2) {
                                                $and .= " And ar.appstat not in ('A', 'R') ";
                                            }
                                        }

                                    // echo "**".$and."**";
                                    if($and != '') {
                                        $sql_search = select_query_json("select distinct ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPFVAL, ar.APRTITL, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE,
                                                                                    ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO, decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED',
                                                                                    'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS,
                                                                                    decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9')
                                                                                    APPORDER, (select EMPNAME from employee_office where empsrno = ar.ADDUSER) as reqby, (select EMPNAME
                                                                                    from employee_office where empsrno = ar.RQESTTO) as reqto, ar.adddate, (select APPRDET
                                                                                    from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATYCODE = ar.ATYCODE and
                                                                                    ATCCODE = ar.ATCCODE and APRNUMB = ar.APRNUMB and DELETED = 'N' and ARQSRNO = (select max(ARQSRNO)
                                                                                    from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATYCODE = ar.ATYCODE and
                                                                                    ATCCODE = ar.ATCCODE and DELETED = 'N')) APPRDET, (select EMPNAME from employee_office
                                                                                    where empsrno = (select REQSTFR from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR
                                                                                    and ATYCODE = ar.ATYCODE and ATCCODE = ar.ATCCODE and DELETED = 'N' and ARQSRNO = (select max(ARQSRNO)
                                                                                    from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATYCODE = ar.ATYCODE and
                                                                                    ATCCODE = ar.ATCCODE and DELETED = 'N'))) as pndingby, ar.APPRFOR
                                                                                from APPROVAL_REQUEST ar
                                                                                where ar.DELETED = 'N' and ar.ARQSRNO = 1 ".$and."
                                                                                group by ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPFVAL, ar.APRTITL, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE,
                                                                                    ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO, ar.adddate, ar.REQSTFR, ar.APPRFOR
                                                                                order by APPORDER asc, ar.adddate asc, APRNUMB desc", "Centra", 'TCS');
                                    }

                                    $ij = 0; $priority_based = '';
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
                                                                                aprnumb like '".$sql_search[$search_i]['APRNUMB']."')", "Centra", 'TCS');
                                        $sql_pending_user = explode(" - ", $sql_pending[0]['RQFRDES']); ?>

                                        <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                            <td class="center" style='text-align:center;'>
                                                <? echo $appstatus; ?>
                                            </td>
                                            <td style='text-align:center'><?=$ij?></td>
                                            <td><a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_search[$search_i]['APRNUMB']; ?></a>
                                                <?  $sql_internal = select_query_json("select appfrwd from approval_request
                                                                                            where (arqsrno,aprnumb) in (select max(arqsrno) arqsrno, aprnumb from approval_request
                                                                                                where aprnumb in ('".$sql_search[$search_i]['APRNUMB']."')
                                                                                            group by aprnumb)", "Centra", 'TCS');
                                                if($sql_internal[0]['APPFRWD'] == 'I') { ?>
                                                    <br><span class="badge badge-danger" style="background-color:#AF2B28 !important; color: #FFFFFF !important; clear:both; font-size:10px;">INTERNAL VERIFICATION</span>
                                                <? } ?>
                                            </td>
                                            <td class="center show_moreless" style="max-width: 800px; overflow-x: auto;">
                                            <?  // echo "==".$sql_search[$search_i]['APPRFOR']."==";
                                                if($sql_search[$search_i]['APPRFOR'] == '1') {
                                                    $filepathname = $sql_search[$search_i]['APPRSUB'];
                                                    $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                    $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
                                                    // $contents = fread($handle, filesize($filename));
                                                    $contents = file_get_contents($filename);
                                                    fclose($handle);
                                                    // echo substr(strip_tags(str_replace("&nbsp;", " ", $contents)), 0, 500);
                                                    // echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);
													echo $contents;
													// echo list_summary(str_replace("&nbsp;", " ", $contents), $limit=1000, $strip = false);
                                                } else {
                                                    echo $sql_search[$search_i]['APPRDET'];
                                                }
                                            ?></td>
                                            <td><? if($sql_search[$search_i]['APPFVAL'] > 0) { echo moneyFormatIndia($sql_search[$search_i]['APPFVAL']); } else { echo "-Nil-"; } ?></td>
                                            <td class="center"><? echo $sql_search[$search_i]['REQBY']; ?></td>
                                            <td class="center"><? if($sql_search[$search_i]['APPSTAT'] == 'N' or $sql_search[$search_i]['APPSTAT'] == 'F' or $sql_search[$search_i]['APPSTAT'] == 'P') { echo $sql_pending_user[1]; } else { echo "-"; } ?></td>
                                            <td class="center"><? echo $sql_search[$search_i]['APRTITL']." ".$sql_search[$search_i]['REQTO']; ?></td>
                                            <td class="center"><a href='javascript:void(0)' onclick="PrintDiv('print_request.php?action=print&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>');" title='Print' alt='Print' style='color:<?=$clr?>;'><i class="fa fa-print"></i> Print</a></td>
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

        function cmnt_mail(aprnumb)
        {
            var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
            $.ajax({
            url:sendurl,
            success:function(data){
                $("#myModal2").modal('show');
                $('#modal-body2').html(data);
                $('#txtmailcnt').val("");
                }
            });
        }

        function cmt_usr()
        {
            $('#cmtusr').css("display", "block");
            $('.select2').select2();
            $('#mailusr').focus();
            //$("#mailusr").select2("open");
            $('#mailusr').select2({
            placeholder: 'Enter EC No/Name to Select an mail user',
            allowClear: true,
            dropdownAutoWidth: true,
            minimumInputLength: 3,
            maximumSelectionLength: 3,
            ajax: {
              url: 'ajax/ajax_general_temp.php?action=MAILUSER',
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                return {
                  results: data
                };
              },
              cache: true
            }
          });
        }
        </script>
    <!-- END SCRIPTS -->
    </body>
    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>
