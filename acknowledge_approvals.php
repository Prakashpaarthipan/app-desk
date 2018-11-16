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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentdate = strtoupper(date('d-M-Y h:i:s A'));
    for ($ik = 0; $ik < count($design_approval); $ik++) { 
        $ex1 = explode("||", $design_approval[$ik]);
        // Update in APPROVAL_REQUEST Table
        $tbl_finapprq = "APPROVAL_REQUEST";
        $field_finapprq = array();
        $field_finapprq['ACKUSER'] = $_SESSION['tcs_usrcode'];
        $field_finapprq['ACKSTAT'] = "A";
        $field_finapprq['ACKDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
        $where_finapprq = " APRNUMB = '".$ex1[2]."' and ARQSRNO = '".$ex1[1]."' ";
        // print_r($field_finapprq); // echo "<br>";
        $update_apprq = update_dbquery($field_finapprq, $tbl_finapprq, $where_finapprq);
        // Update in APPROVAL_REQUEST Table
    }
    // exit;

    if($update_apprq == 1) { ?>
        <script>window.location='acknowledge_approvals.php?status=finish';</script>
    <?php exit();
    } else { ?>
        <script>window.location='acknowledge_approvals.php?status=failure';</script>
    <?php exit();
    }
}

$sql_search = select_query_json("select ar.ADDDATE, ar.FINDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPRFOR, ar.APPFVAL, ar.APRTITL, ar.ARQSRNO, ar.RQBYDES, 
                                            ar.adddate altdate, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO,decode(ar.APPSTAT, 'N', 'NEW', 
                                            'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, 
                                            decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, 
                                            (select APPSTAT from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and 
                                            ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT, 
                                            (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as reqto, (select EMPNAME from employee_office 
                                            where empsrno in (select REQSTBY from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and 
                                            ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, 
                                            ar.APPRDET, ar.IMSTATS, ar.IMFNIMG  
                                        from approval_request ar 
                                        where ar.RPTUSER = '".$_SESSION['tcs_empsrno']."' and ar.deleted = 'N' and ACKUSER is null  
                                        order by ADDDATE asc, APPORDER asc, APRNUMB desc", "Centra", 'TEST'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>        
<!-- META SECTION -->
<title>Acknowledge Alternate Approvals List :: Approval Desk :: <?php echo $site_title; ?></title>             
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
                <li class="active">Reports - Acknowledge Alternate Approvals List</li>
            </ul>
            <!-- END BREADCRUMB -->                       
            
            <form name="frm_ackuser" id="frm_ackuser" action="" method="POST">
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Acknowledge Alternate Approvals List</h3>
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
                                    <th>Request to</th>
                                    <th>Alternate Person</th>
                                    <th style='text-align:center; width: 110px;'>
                                    <? if(count($sql_search) > 0) { ?>
                                        <input type='submit' name='sbmt_authorize' id='sbmt_authorize' value='APPROVE' title="APPROVE" onClick="return check_approval()" class="btn btn-success" style="margin-bottom: 3px;"> <? // onClick="save_approval('APPROVE')" ?>
                                        <span id='submitid'> 
                                            <label class="switch">
                                                <input type="checkbox" class="switch" name="ackappr" id="ackappr" title="SELECT ALL" value="0" onClick="Select_All()">
                                                    <? /* <label for="ackappr" style="margin-top:5px;">Select All</label> */ ?>
                                                <span></span>
                                            </label>
                                        </span>
                                    <? } ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?   
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
                                    $filename = $sql_search[$search_i]['IMFNIMG']; ?>
                                    <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                        <td class="center" style='text-align:center;'>
                                            <? echo $appstatus; ?>
                                        </td>
                                        <td style='text-align:center'><?=$ij?></td>
                                        <td>
                                            <a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_search[$search_i]['APRNUMB']; ?></a>
                                        </td>
                                        <td class="center show_moreless">
                                        <?  if($sql_search[$search_i]['APPRFOR'] == '1') {
                                                $filepathname = $sql_search[$search_i]['APPRSUB'];
                                                $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
                                                $contents = fread($handle, filesize($filename));
                                                fclose($handle);
                                                echo strip_tags(str_replace("&nbsp;", " ", $contents));
                                            } else {
                                                echo $sql_search[$search_i]['APPRDET'];
                                            }
                                        ?></td>
                                        <td><? if($sql_search[$search_i]['APPFVAL'] > 0) { echo moneyFormatIndia($sql_search[$search_i]['APPFVAL']); } else { echo "-Nil-"; } ?></td>
                                        <td class="center"><? echo $sql_search[$search_i]['REQBY']; ?></td>
                                        <td class="center"><? echo $sql_search[$search_i]['APRTITL']." ".$sql_search[$search_i]['REQTO']; ?></td>
                                        <td class="center"><? echo $sql_search[$search_i]['RQBYDES']." [ ".strtoupper(date("d-M-Y", strtotime($sql_search[$search_i]['ALTDATE'])))." ] "; ?></td>
                                        <td class="center" style='text-align:center; white-space:nowrap;'>
                                            <small>
                                                <label class="switch">
                                                    <input type="checkbox" autofocus class="brand switch" title='SELECT' name="design_approval[]" id="design_approval_<?=$ij?>" value="<?=$ij?>||<?=$sql_search[$search_i]['ARQSRNO']?>||<?=$sql_search[$search_i]['APRNUMB']?>">
                                                    <? /* <label for="design_approval_<?=$ij?>">SELECT</label> */ ?>
                                                    <span></span>
                                                </label>
                                            </small>
                                            <Br><Br>
                                            <a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> VIEW</a>
                                        </td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </form>
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
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
    function PrintDiv(dataurl) {
        var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
    }
    
    function Select_All() {
      var e = document.getElementsByClassName("brand");
      var elts_cnt  = (typeof(e.length) != 'undefined') ? e.length : 0;
      if (!elts_cnt) {
        return;
      }
      for (var i = 0; i < elts_cnt; i++) {
          if(document.getElementById("ackappr").checked == false) {
            e[i].checked = false;
          } else {
            e[i].checked = true;
          }
      }
    }

    function check_approval(approve_reject){
        var flag = $('[name="design_approval[]"]:checked').length;
        if (flag <= 0) {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Kindly choose atleast one approval here!";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            return false;
        } else {
            return true;
        }
    }

    function save_approval(approve_reject){
        $('body').off("submit", "#frm_ackuser");
        $('body').on('submit', "#frm_ackuser", function(e){
            var flag = $('[name="design_approval[]"]:checked').length;
            if (flag <= 0) {
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly choose atleast one approval here!";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                return false;
            } else {
                if(confirm('Are You Sure To '+approve_reject+' this Approvals!')) {
                    $('#load_page').show(); // show the loading message. 
                    $.ajax({
                        url:"lib/process_connect.php?action=acknowledge_approvals",
                        type: "POST",
                        data:  new FormData(this),
                        contentType: false,
                        cache: false,
                        processData:false,
                        success:function(send){
                            if(send == 1) {
                                window.location.reload();
                            } else {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = send;
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            }
                            
                            $('#load_page').hide(); // hide the loading message. 
                            e.preventDefault();
                        }
                    });
                } else {
                    return false;
                }
            }
            e.preventDefault();
        });
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


    /******************** Change Default Alert Box ***********************/
    var ALERT_BUTTON_TEXT = "OK";
    /* if(document.getElementById) {
        window.alert = function(txt) {
            var ALERT_TITLE = "GA Title";

            var tga = document.getElementById("id_ga").value;
            createCustomAlert(tga, ALERT_TITLE);
        }
    } */

    function createCustomAlert(txt, title) {
        d = document;

        if(d.getElementById("modalContainer")) return;

        mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
        mObj.id = "modalContainer";
        mObj.style.height = d.documentElement.scrollHeight + "px";
        
        alertObj = mObj.appendChild(d.createElement("div"));
        alertObj.id = "alertBox";
        if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
        alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
        alertObj.style.visiblity="visible";

        h1 = alertObj.appendChild(d.createElement("h1"));
        h1.appendChild(d.createTextNode(title));

        msg = alertObj.appendChild(d.createElement("p"));
        //msg.appendChild(d.createTextNode(txt));
        msg.innerHTML = txt;

        btn = alertObj.appendChild(d.createElement("a"));
        btn.id = "closeBtn";
        btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
        btn.href = "#";
        btn.focus();
        btn.onclick = function() { removeCustomAlert();return false; }

        alertObj.style.display = "block";
    }

    function removeCustomAlert() {
        document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
    }

    function ful(){
        //alert('Alert this pages');
    }
    /******************** Change Default Alert Box ***********************/
</script>
<!-- END SCRIPTS -->         
</body>
</html>