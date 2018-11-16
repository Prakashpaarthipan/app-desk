<?
session_start();
error_reporting(0);
header('X-UA-Compatible: IE=edge');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

/* if($_REQUEST['action'] == 'finish')
{
    if($_FILES['txt_finaldoc']['type'] == "application/pdf") {
        $source = $_FILES['txt_finaldoc']['tmp_name'];
        $flname = preg_replace('/[^a-zA-Z0-9_%\[\]\.\(\)%&-]/s', '', $aprnumb);
        $upload_img1 = $flname.".pdf";
        $complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
        $complogos1 = str_replace(" ", "-", $upload_img1);
        $complogos1 = strtolower($complogos1);
        $original_complogos1 = "uploads/request_entry/finish_status/".$complogos1;
        $suc = move_uploaded_file($source, $original_complogos1);
        // echo "<br>++".$suc."++".$source."++"+$original_complogos1+"++";

        // Upload into FTP
        $local_file = "uploads/request_entry/finish_status/".$complogos1;
        $dir1 = "approval_desk/request_entry/finish_status/";
        $server_file = $dir1.$complogos1;

        if ((!$conn_id) || (!$login_result)) {
            $upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
            unlink($local_file);
        }
        // Upload into FTP


        $currentdate = strtoupper(date('d-M-Y h:i:s A'));
        $sysip = $_SERVER['REMOTE_ADDR'];
        // Update in approval_request Table
        $tbl_appreq = "approval_request";
        $field_appreq = array();
        $field_appreq['IMUSRCD'] = $_SESSION['tcs_usrcode'];
        $field_appreq['IMSTATS'] = 'Y';
        $field_appreq['IMFINDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
        $field_appreq['IMFNIMG'] = $complogos1;
        // print_r($field_appreq);
        $where_appreq = " ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '".$_REQUEST['rsrid']."' and ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."' ";
        $insert_appreq = update_query($field_appreq, $tbl_appreq, $where_appreq);
        // Update in approval_request Table
    }

    // exit;
    if($insert_appreq == 1) { ?>
        <script>window.location='request_list.php?status=finish_success';</script>
    <?php exit();
    } else { ?>
        <script>window.location='request_list.php?status=finish_failure';</script>
    <?php exit();
    }
} */

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

if($inner_menuaccess[0]['VEWVALU'] == 'Y') { // Menu Permission is allowed ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <!-- META SECTION -->
    <title>Request List :: Approval Desk :: <?php echo $site_title; ?></title>
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
                    <li class="active">Approval Request List</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Approval Request List</h3>
                            <? /* <div class="btn-group pull-right">
                                <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'json',escape:'false'});"><img src='img/icons/json.png' width="24"/> JSON</a></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'json',escape:'false',ignoreColumn:'[2,3]'});"><img src='img/icons/json.png' width="24"/> JSON (ignoreColumn)</a></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'json',escape:'true'});"><img src='img/icons/json.png' width="24"/> JSON (with Escape)</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'xml',escape:'false'});"><img src='img/icons/xml.png' width="24"/> XML</a></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'sql'});"><img src='img/icons/sql.png' width="24"/> SQL</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'csv',escape:'false'});"><img src='img/icons/csv.png' width="24"/> CSV</a></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'txt',escape:'false'});"><img src='img/icons/txt.png' width="24"/> TXT</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'excel',escape:'false'});"><img src='img/icons/xls.png' width="24"/> XLS</a></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'doc',escape:'false'});"><img src='img/icons/word.png' width="24"/> Word</a></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'powerpoint',escape:'false'});"><img src='img/icons/ppt.png' width="24"/> PowerPoint</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'png',escape:'false'});"><img src='img/icons/png.png' width="24"/> PNG</a></li>
                                    <li><a href="#" onClick ="$('#customers2').tableExport({type:'pdf',escape:'false'});"><img src='img/icons/pdf.png' width="24"/> PDF</a></li>
                                </ul>
                            </div> */ ?>

                        </div>
                        <div class="panel-body" style="overflow-x: scroll !important;">
                            <div class="form-group trbg non-printable">
                                <form role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                                    <div class="col-xs-2" style='text-align:center; padding:5px;'></div>

                                    <? /* <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 0;'>
                                        <input type='text' class="form-control" tabindex='1' autofocus name='search_subject' id='search_subject' value='<?=$_REQUEST['search_subject']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Details' title="Details" style='text-transform: uppercase;'>
                                    </div> */ ?>

                                    <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 5px;'>
                                        <input type='text' class="form-control" tabindex='1' name='search_aprno' id='search_aprno' value='<?=$_REQUEST['search_aprno']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Approval No' title="Approval No" style='text-transform: uppercase;'>
                                    </div>

                                    <div class="col-xs-2" style='text-align:center; padding:5px;'>
                                        <input type='text' class="form-control" tabindex='2' name='search_value' id='search_value' value='<?=$_REQUEST['search_value']?>' data-toggle="tooltip" data-placement="top" maxlength="10" placeholder='Value' title="Value" style='text-transform: uppercase;'>
                                    </div>

                                    <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 6px;'>
                                        <input type='hidden' name='search_add_findate' id='search_add_findate' value='ADDDATE' >
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                    </div>

                                    <div class="col-xs-2" style='text-align:center; padding:5px;'>
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
                                    </div>

                                    <div class="col-xs-2" style='text-align:left; padding:5px;'>
                                        <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
                                    </div>
                                </form>
                            </div>
                            <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                            <table id="customers2" class="table datatable" style="overflow-x: scroll !important;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Status</th>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Approval Number</th>
                                        <th rowspan="2">Details</th>
                                        <th rowspan="2" style='text-align:center; white-space:nowrap;'>Value &#8377;</th>
                                        <th rowspan="2">Request by</th>
                                        <th rowspan="2">Waiting Person</th>
                                        <th rowspan="2">Request to</th>
                                        <th colspan="4" style="text-align: center;">Year / Number</th>
                                        <th rowspan="2" style='text-align:center;'>Action</th>
                                    </tr>
                                    <tr>
                                        <th>Budget Planner Yr / No</th>
                                        <th>Requirement Yr / No</th>
                                        <th>Non-Textile Order Yr / No</th>
                                        <th>Self Cheque Yr / No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?  $and = "";
                                    if($search_aprno != '') {
                                        $and .= " And ar.APRNUMB like '%".strtoupper($search_aprno)."%' ";
                                    }
                                    if($search_subject != '') {
                                        // echo "**".$ftp_conn."**".$ftp_user_name_apdsk."**".$ftp_user_pass_apdsk."**".$ftp_server_apdsk.$ftp_srvport_apdsk."**"."ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/";
                                        echo ftp_nlist($ftp_conn, "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/")."++";

                                        /* $sql_yr = array('2017-18', '2018-19', '2019-20', '2020-21', '2021-22');
                                        if(count($sql_yr) > 0) {
                                            $and .= " ( ";
                                            for ($yri=0; $yri < count($sql_yr); $yri++) {
                                                $searchfor = strtoupper($search_subject);

                                                // get the file contents, assuming the file to be readable (and exist)
                                                $contents = file_get_contents($file);

                                                // escape special characters in the query
                                                $pattern = preg_quote($searchfor, '/');

                                                // finalise the regular expression, matching the whole line
                                                $pattern = "/^.*$pattern.*\$/m";

                                                /* // search, and store all matching occurences in $matches
                                                if(preg_match_all($pattern, $contents, $matches)){
                                                   echo "Found matches:\n";
                                                   echo implode("\n", $matches[0]);
                                                }
                                                else{
                                                   echo "No matches found";
                                                // }
                                            }
                                            $and .= " ) ";
                                        } */

                                        $and .= " And ar.APPRDET like '%".strtoupper($search_subject)."%' ";
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

                                    // echo "**".$_SESSION['tcs_empsrno']."**";
                                    if($and != '') {
                                        $rqusr = " and ar.ADDUSER = '".$_SESSION['tcs_empsrno']."' ";
                                        $sql_brn = select_query_json("select BRNCODE, esecode from employee_office where empsrno = '".$_SESSION['tcs_userid']."'", "Centra", 'TCS');
                                        if($sql_brn[0]['BRNCODE'] == 888 or $sql_brn[0]['BRNCODE'] == 100) {
                                            // $rqusr = " and ar.REQESEC=".$sql_brn[0]['ESECODE']." ";
                                            $rqusr = " and ar.REQESEC=".$_SESSION['tcs_esecode']." ";
                                        }

                                        $sql_search = select_query_json("select to_char(ar.ADDDATE,'dd-MON-yyyy hh:mi:ss AM') ADDDATE, ar.FINDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPRFOR,
                                                                                    ar.APPFVAL, ar.APRTITL, ar.ARQSRNO, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO,
                                                                                    decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE',
                                                                                    'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4',
                                                                                    'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST where ARQSRNO = 1 and
                                                                                    DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and
                                                                                    ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT, (select EMPNAME from employee_office
                                                                                    where empsrno = ar.RQESTTO) as reqto, (select EMPNAME from employee_office where empsrno in (select REQSTBY
                                                                                    from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and
                                                                                    ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby,
                                                                                    ar.APPRDET, ar.IMSTATS, ar.IMFNIMG, pr.pricode, pr.priname, pr.pricode||' - '||pr.priname priority
                                                                                from approval_request ar, approval_priority pr
                                                                                where ar.PRICODE = pr.pricode(+) and pr.deleted(+) = 'N' and ar.ARQSRNO = 1 ".$rqusr." ".$and." and ar.deleted = 'N'
                                                                                order by APPORDER asc, ADDDATE desc, APRNUMB desc", "Centra", 'TCS');
                                    } else {
                                        // if($_SESSION['tcs_empsrno'] == '43878') { echo "**".$_SESSION['tcs_esecode']."**"; }
                                        $rqusr = " and ar.ADDUSER = '".$_SESSION['tcs_empsrno']."' and trunc(ar.adddate) between trunc(sysdate-10) and trunc(sysdate) and ar.REQESEC=".$_SESSION['tcs_esecode']." ";
                                        $sql_search = select_query_json("select to_char(ar.ADDDATE,'dd-MON-yyyy hh:mi:ss AM') ADDDATE, ar.FINDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPRFOR,
                                                                                    ar.APPFVAL, ar.APRTITL, ar.ARQSRNO, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO,
                                                                                    decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE',
                                                                                    'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4',
                                                                                    'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST where ARQSRNO = 1 and
                                                                                    DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and
                                                                                    ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT, (select EMPNAME from employee_office
                                                                                    where empsrno = ar.RQESTTO) as reqto, (select EMPNAME from employee_office where empsrno in (select REQSTBY
                                                                                    from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and
                                                                                    ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby,
                                                                                    ar.APPRDET, ar.IMSTATS, ar.IMFNIMG, pr.pricode, pr.priname, pr.pricode||' - '||pr.priname priority
                                                                                from approval_request ar, approval_priority pr
                                                                                where ar.PRICODE = pr.pricode(+) and pr.deleted(+) = 'N' and ar.ARQSRNO = 1 ".$rqusr." ".$and." and ar.deleted = 'N'
                                                                                order by APPORDER asc, ADDDATE desc, APRNUMB desc", "Centra", 'TCS');
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
                                        $sql_pending_user = explode(" - ", $sql_pending[0]['RQFRDES']);

                                        /* // echo "<br>**".$sql_search[$search_i]['PRIORITY']."**".$priority_based."**";
                                        if($sql_search[$search_i]['PRIORITY'] != $priority_based) { ?>
                                            <tr><td colspan="9" style="background-color: #a0a0a0;">
                                                <?=$sql_search[$search_i]['PRIORITY']?>
                                            </td></tr>
                                        <? }
                                        $priority_based = $sql_search[$search_i]['PRIORITY']; */ ?>


                                        <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                            <td class="center" style='text-align:center;'>
                                                <? echo $appstatus; // echo $sql_search[$search_i]['PRIORITY']; ?>
                                            </td>
                                            <td style='text-align:center'><?=$ij?></td>
                                            <td><a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_search[$search_i]['APRNUMB']; ?></a>
                                                <?  $sql_internal = select_query_json("select appfrwd, APPRFOR, APPRSUB, APPRDET from approval_request
                                                                                            where (arqsrno,aprnumb) in (select max(arqsrno) arqsrno, aprnumb from approval_request
                                                                                                where aprnumb in ('".$sql_search[$search_i]['APRNUMB']."')
                                                                                                 group by aprnumb)", "Centra", 'TCS');
                                                if($sql_internal[0]['APPFRWD'] == 'I') { ?>
                                                <br><span class="badge badge-danger" style="background-color:#AF2B28 !important; color: #FFFFFF !important; clear:both; font-size:10px;">INTERNAL VERIFICATION</span>
                                                <? } ?>
                                            </td>
                                            <td class="center show_moreless" style="max-width: 800px; overflow-x: auto;">
                                            <?  if($sql_internal[0]['APPRFOR'] == '1' or $sql_internal[0]['APPRFOR'] == '2' or $sql_internal[0]['APPRFOR'] == '3' or $sql_internal[0]['APPRFOR'] == '4' or $sql_internal[0]['APPRFOR'] == '5') {
                                                    $filepathname = $sql_internal[0]['APPRSUB'];
                                                    $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                    $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
                                                    // $contents = fread($handle, filesize($filename));
                                                    $contents = file_get_contents($filename);
                                                    fclose($handle);
                                                    // echo substr(strip_tags(str_replace("&nbsp;", " ", $contents)), 0, 500);
                                                    // echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);
													// echo list_summary(str_replace("&nbsp;", " ", $contents), $limit=1000, $strip = false);
													echo $contents;
                                                } else {
                                                    echo $sql_internal[0]['APPRDET'];
                                                }
                                            ?></td>
                                            <td style='text-align:center; white-space:nowrap;'><? if($sql_search[$search_i]['APPFVAL'] > 0) { echo moneyFormatIndia($sql_search[$search_i]['APPFVAL']); } else { echo "-Nil-"; } ?></td>
                                            <td class="center"><? echo $sql_search[$search_i]['REQBY']; //$sql_rqby = select_query_json("select EMPNAME from employee_office where empsrno = ".$sql_search[$search_i]['ADDUSER']); echo $sql_rqby[0][0]; ?></td>
                                            <td class="center"><? if($sql_search[$search_i]['APPSTAT'] == 'N' or $sql_search[$search_i]['APPSTAT'] == 'F' or $sql_search[$search_i]['APPSTAT'] == 'P') { echo $sql_pending_user[1]; } else { echo "-"; } ?></td>
                                            <td class="center"><? echo $sql_search[$search_i]['REQTO']; //$sql_rqto = select_query_json("select EMPNAME from employee_office where empsrno = ".$sql_search[$search_i]['RQESTTO']); echo $sql_search[$search_i]['APRTITL']." ".$sql_rqto[0][0]; ?></td>

                                            <?  $sectionrow=select_query_json("select distinct substr(bpl.appnumb,1,100) aprnumb, bpl.bplyear, bpl.bplnumb, nrq.nrqyear, nrq.nrqnumb, nto.ntoyear, nto.ntonumb, 
                                                                                        '-' crqyear, 0 crqnumb
                                                                                    from budget_planner_summary bpl, non_req_Summary nrq, non_req_po_convert con, non_ord_Summary nto 
                                                                                    where bpl.bplyear=nrq.bplyear and bpl.bplnumb=nrq.bplnumb and nrq.nrqyear(+)=con.nrqyear and nrq.nrqnumb(+)=con.nrqnumb 
                                                                                        and con.ntoyear(+)=nto.ntoyear and con.ntonumb(+)=nto.ntonumb and bpl.appnumb = '".$sql_search[$search_i]['APRNUMB']."'
                                                                                union 
                                                                                    select distinct substr(bpl.appnumb,1,100) aprnumb, bpl.bplyear, bpl.bplnumb, '-' nrqyear, 0 nrqnumb, '-' ntoyear, 0 ntonumb, 
                                                                                        nvl(chqs.CRQYEAR,'-') crqyear, nvl(chqs.CRQNUMB,0) crqnumb
                                                                                    from budget_planner_summary bpl,self_cheque_req_summary chqs 
                                                                                    where bpl.bplyear=chqs.bplyear(+) and bpl.bplnumb=chqs.bplnumb(+) and bpl.appnumb in (select apr.aprnumb 
                                                                                        from approval_request apr where apr.aprnumb='".$sql_search[$search_i]['APRNUMB']."' and budcode in(2,3,4)) 
                                                                                        and bpl.appnumb = '".$sql_search[$search_i]['APRNUMB']."'", "Centra", "TCS");

                                            if ($sectionrow[0]['BPLYEAR'] != '' and $sectionrow[0]['BPLNUMB'] != '') { ?>
                                            <td class="center">
                                                <?  for ($seci=0; $seci < count($sectionrow); $seci++) { 
                                                        if ($sectionrow[$seci]['BPLYEAR'] != '' and $sectionrow[$seci]['BPLNUMB'] != '') { 
                                                            echo $sectionrow[$seci]['BPLYEAR']." / ".$sectionrow[$seci]['BPLNUMB']."<br>";
                                                        }
                                                    } ?>
                                            </td>
                                            <? } else { ?>
                                                <td class="center">-- NIL --</td>
                                            <? }

                                            if ($sectionrow[0]['NRQYEAR'] != '' and $sectionrow[0]['NRQNUMB'] != '') { ?>
                                            <td class="center">
                                                <?  for ($seci=0; $seci < count($sectionrow); $seci++) { 
                                                        if ($sectionrow[$seci]['NRQYEAR'] != '' and $sectionrow[$seci]['NRQNUMB'] != '') { 
                                                            echo $sectionrow[$seci]['NRQYEAR']." / ".$sectionrow[$seci]['NRQNUMB']."<br>";
                                                        } 
                                                    } ?>
                                            </td>
                                             <? } else { ?>
                                                 <td class="center">-- NIL --</td>
                                            <? }

                                            if ($sectionrow[0]['NTOYEAR'] != '' and $sectionrow[0]['NTONUMB'] != '') { ?>
                                            <td class="center">
                                                <?  for ($seci=0; $seci < count($sectionrow); $seci++) { 
                                                        if ($sectionrow[$seci]['NTOYEAR'] != '' and $sectionrow[$seci]['NTONUMB'] != '') { 
                                                            echo $sectionrow[$seci]['NTOYEAR']." / ".$sectionrow[$seci]['NTONUMB']."<br>";
                                                        }
                                                    } ?>
                                            </td>
                                            <? } else { ?>
                                                <td class="center">-- NIL --</td>
                                            <? }

                                            if ($sectionrow[0]['CRQYEAR'] != '' and $sectionrow[0]['CRQNUMB'] != '') { ?>
                                            <td class="center">
                                                <?  for ($seci=0; $seci < count($sectionrow); $seci++) { 
                                                        if ($sectionrow[$seci]['CRQYEAR'] != '' and $sectionrow[$seci]['CRQNUMB'] != '') { 
                                                            echo $sectionrow[$seci]['CRQYEAR']." / ".$sectionrow[$seci]['CRQNUMB']."<br>";
                                                        }
                                                    } ?>
                                            </td>
                                            <? } else { ?>
                                                  <td class="center">-- NIL --</td>
                                            <? } ?>

                                            <td class="center" style='text-align:center; white-space:nowrap;'>
                                                <? if($sql_search[$search_i]['APPSTAT'] != 'A') { if($editid == 1) { /* ?><a href='request_entry.php?action=edit&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='Edit' alt='Edit' style='color:<?=$clr?>;'><i class="fa fa-edit"></i> Edit</a> / <? */ } } ?>

                                                <a target="_blank" href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>;'><i class="fa fa-eye"></i> View</a>

                                                <? ////// HIDE FINISH APPRVOAL //////
                                                    // if($sql_search[$search_i]['APPSTAT'] == 'A') { */ ?>
                                                    <? ////// if($_SESSION['tcs_usrcode'] == 9938358 or $_SESSION['tcs_usrcode'] == 9193333 or $_SESSION['tcs_usrcode'] == 3000000) {
                                                    if($sql_search[$search_i]['APPSTAT'] != 'A' and $sql_search[$search_i]['APPSTAT'] != 'R') { ?>
                                                         / <a target="_blank" href='print_request.php?action=print&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-print"></i> Print</a>
                                                    <? } elseif($sql_search[$search_i]['APPSTAT'] != 'R') { ?>
                                                         / <a href='javascript:void(0)' onclick="PrintDiv('print_request.php?action=print&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>');" title='Print' alt='Print' style='color:<?=$clr?>;'><i class="fa fa-print"></i> Print</a>
                                                    <? } elseif($sql_search[$search_i]['APPSTAT'] == 'A') { ?>
                                                         / <a href='javascript:void(0)' onclick="PrintDiv('print_request.php?action=print&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>');" title='Print' alt='Print' style='color:<?=$clr?>;'><i class="fa fa-print"></i> Print</a>
                                                    <? } ?>
                                                <? // } ////// HIDE FINISH APPRVOAL ////// ?>

                                                <? if(($sql_search[$search_i]['IMSTATS'] == '' or $sql_search[$search_i]['IMSTATS'] == 'N') && $sql_search[$search_i]['APPSTAT'] == 'A') { /* ?> / <br><a href='javascript:void(0)' id="finish_confirm" onclick="call_confirm(<?=($project_i+1)?>, '<? echo $sql_search[$search_i]['ARQCODE']; ?>', '<? echo $sql_search[$search_i]['ARQYEAR']; ?>', '<? echo $sql_search[$search_i]['ARQSRNO']; ?>', '<? echo $sql_search[$search_i]['ATCCODE']; ?>', '<? echo $sql_search[$search_i]['ATYCODE']; ?>', '<? echo $sql_search[$search_i]['APRNUMB']; ?>')" title='Finish' alt='Finish' style='color:#000000;'><i class="fa fa-check-square"></i> Finish</a><? */ } elseif(($sql_search[$search_i]['IMSTATS'] != '' and $sql_search[$search_i]['IMSTATS'] != 'N') && $filename != '') { ?> / <br><a href="ftp://<?=$ftp_user_name?>:<?=$ftp_user_pass?>@<?=$ftp_server?>/approval_desk/request_entry/finish_status/<?=$filename?>" target="_blank" style='color:#000000;'><i class="fa fa-download"></i> Document</a>
                                                <? } ?>

                                                <?  if($sql_search[$search_i]['APPSTAT'] != 'A') { ?> / <a href='javascript:void(0)' onclick="cmnt_mail('<?=$sql_search[$search_i]['APRNUMB']?>');" title='mail' alt='mail' style='color:<?=$clr?>;'><i class="fa fa-envelope"></i> mail</a><? }

                                                if($sql_search[$search_i]['APPSTAT'] == 'A' or $sql_search[$search_i]['APPSTAT'] == 'R') {
                                                    $sql_vl = select_query_json("select ADDDATE from APPROVAL_REQUEST
                                                                                                where aprnumb = '".$sql_search[$search_i]['APRNUMB']."' and ARQSRNO = (select max(ARQSRNO)
                                                                                                    from APPROVAL_REQUEST where aprnumb = '".$sql_search[$search_i]['APRNUMB']."')", "Centra", 'TCS');
                                                    $start_time = formatSeconds(strtotime($sql_vl[0]['ADDDATE']) - strtotime($sql_search[$search_i]['ADDDATE']));
                                                } else {
                                                    $start_time = formatSeconds(strtotime('now') - strtotime($sql_search[$search_i]['ADDDATE']));
                                                }
                                                $sql_iv = select_query_json("select count(appfrwd) CNTAPPFRWD from approval_request
                                                                                    where aprnumb like '".$sql_search[$search_i]['APRNUMB']."' and appfrwd = 'I'
                                                                                    order by arqsrno", "Centra", "TCS");
                                                $duedate = 0;
                                                switch ($sql_search[$search_i]['PRICODE']) {
                                                    case 1:
                                                        $duedate = 1;
                                                        $clrcod = 'badge-ap1';
                                                        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + $sql_iv[0]['CNTAPPFRWD']; }
                                                        $css_cls = "#FF0000";
                                                        if($start_time <= $duedate) {
                                                            $css_clstime = "#299654";
                                                        } else {
                                                            $css_clstime = "#FF0000";
                                                        }
                                                        break;
                                                    case 2:
                                                        $duedate = 2;
                                                        $clrcod = 'badge-ap2';
                                                        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
                                                        $css_cls = "#D58B0A";
                                                        if($start_time <= $duedate) {
                                                            $css_clstime = "#299654";
                                                        } else {
                                                            $css_clstime = "#FF0000";
                                                        }
                                                        break;
                                                    case 3:
                                                        $duedate = 3;
                                                        $clrcod = 'badge-ap3';
                                                        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
                                                        $css_cls = "#299654";
                                                        if($start_time <= $duedate) {
                                                            $css_clstime = "#299654";
                                                        } else {
                                                            $css_clstime = "#FF0000";
                                                        }
                                                        break;
                                                    case 4:
                                                        $duedate = 4;
                                                        $clrcod = 'badge-ap4';
                                                        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
                                                        $css_cls = "#299654";
                                                        if($start_time <= $duedate) {
                                                            $css_clstime = "#299654";
                                                        } else {
                                                            $css_clstime = "#FF0000";
                                                        }
                                                        break;
                                                    case 5:
                                                        $duedate = 5;
                                                        $clrcod = 'badge-ap5';
                                                        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
                                                        $css_cls = "#299654";
                                                        if($start_time <= $duedate) {
                                                            $css_clstime = "#299654";
                                                        } else {
                                                            $css_clstime = "#FF0000";
                                                        }
                                                        break;

                                                    default:
                                                        $duedate = 1;
                                                        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + $sql_iv[0]['CNTAPPFRWD']; }
                                                        $css_cls = "#FF0000";
                                                        if($start_time <= $duedate) {
                                                            $css_clstime = "#299654";
                                                        } else {
                                                            $css_clstime = "#FF0000";
                                                        }
                                                        break;
                                                }


                                                echo '<br><span class="label label-info label-form '.$clrcod.'" style="background-color:'.$css_cls.'">AP-'.$sql_search[$search_i]['PRIORITY']."</span>";
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
