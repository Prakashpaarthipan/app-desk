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


<form class="form-horizontal" role="form" id="frm_requirement_entry" name="" action="" method="post" enctype="multipart/form-data">
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> process_requirement_view.php*/ ?>
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
                <li class="active">Service Report</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Service Report</strong></h3>
                        <ul class="panel-controls">

                        </ul>
                    </div>
                     <div class="form-group trbg non-printable" style="display:block;">
            <form role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
              <div class="col-lg-2 col-sm-2" style='text-align:center; padding:5px 5px 0 5px;'>
                <input type='text' class="form-control" tabindex='1' name='search_sprno' id='search_sprno' value='<?=$_REQUEST['search_sprno']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='SUPPLIER CODE / NAME / MOBILE NO' title="SUPPLIER CODE / NAME / MOBILE NO" style='text-transform: uppercase;'>
              </div>

              <div class="col-lg-2 col-sm-2" style='text-align:center; padding:5px;'>
                <input type='text' class="form-control" tabindex='2' name='search_value' id='search_value' value='<?=$_REQUEST['search_value']?>' data-toggle="tooltip" data-placement="top" maxlength="20" placeholder='REQUEST ID' title="REQUEST ID" style='text-transform: uppercase;'>
              </div>
              <div class="col-lg-2 col-sm-2" style='text-align:center; padding:5px;'>
                <input type='text' class="form-control" tabindex='3' style="text-transform: uppercase;" name='search_user' id='search_user' value='<?=$_REQUEST['search_user']?>' data-toggle="tooltip" data-placement="top" placeholder='ASSIGNED USER' title="ASSIGNED USER" style='text-transform: uppercase;' autocomplete='off'> 
              </div>
              <div class="col-lg-1 col-sm-1" style='text-align:center; padding:5px;'>
                <select class="form-control" tabindex='4' name='status_type' id='status_type' data-toggle="tooltip" data-placement="top" title="STATUS TYPE" >
                   <option value='' selected>ALL</option>
                   <option value="A" <? if($_REQUEST['status_type'] == 'A') { ?> selected <? } ?>>ASSIGNED</option>
                   <option value="C" <? if($_REQUEST['status_type'] == 'C') { ?> selected <? } ?>>CLOSED</option>
                </select>
              </div>

              <div class="col-lg-2 col-sm-2" style='text-align:center; padding:5px 5px 0 6px;'>
                <input type='hidden' name='search_add_findate' id='search_add_findate' value='ADDDATE' >
                <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
              </div>

              <div class="col-lg-2 col-sm-2" style='text-align:center; padding:5px;'>
                <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
              </div>

              <div class="col-lg-1 col-sm-1" style='padding:5px;'>
                <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
              </div>
            </form>
                    </div>
                    <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                      <table  class="table datatable table-striped  no-footer">
                          <thead>
                              <tr>
                                <th class="center" style='text-align:center'>S.No</th>
                                <th class="center" style='text-align:center'>Request Id</th>
                                <th class="center" style='text-align:center'>Date</th>
                                <th class="center" style='text-align:center'>Supplier Name</th>
                                <th class="center" style='text-align:left'>Supplier Contact</th>
                                <th class="center" style='text-align:left'>Request Mode</th>
                                <th class="center" style='text-align:center'>Response Member</th>
                                <th class="center" style='text-align:center'>Desk No.</th>
                                <th class="center" style='text-align:center'>Status</th>
                                <th class="center" style='text-align:center'>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                          <?  
              $and = "";
              if($search_value != '') {
                $and .= " And TO_CHAR(SRE.REQNUMB) like '%".strtoupper($search_value)."%' ";
              }
              if($status_type != '') {
                $and .= " and SRE.REQSTAT = '".strtoupper($status_type)."' ";
              }
              if($search_sprno != '') {
                $exp1 = explode(" - ", $search_sprno);
                $and .= " and (SUP.SUPCODE like '%".$exp1[0]."%' or SUP.SUPNAME like '%".$exp1[0]."%' or SUP.SUPMOBI like '%".$exp1[0]."%' or SRE.REQMAIL like '%".$exp1[0]."%' or SRE.REQCONT like '%".$exp1[0]."%')";
              }
              if($search_fromdate != '' or $search_todate != '') {
                if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                $exp1 = explode("-", $search_fromdate);
                $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                if($search_todate == '') { $search_todate = date("d-M-Y"); }
                $exp2 = explode("-", $search_todate);
                $to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));

                $and .= " And trunc(SRE.REQDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
              }
              $open_search = select_query_json("SELECT SRR.RESTUSR,to_char(SRE.REQDATE,'dd/MM/yyyy HH:mi:ss AM') REQDATE, SRE.REQCONT,SRE.REQMAIL,SRE.REQDESKNO,SRE.REQALTCONT,TO_CHAR(SRE.REQNUMB) REQNUMB,SUP.SUPCODE,SUP.SUPNAME,SUP.SUPMOBI,SRE.REQSTAT,SRE.REQDESKNO,SRE.REQMODE
                                                                FROM service_request SRE, SUPPLIER SUP, service_response SRR
                                                                WHERE SRR.REQNUMB(+)=SRE.REQNUMB AND SRR.REQSRNO(+)=SRE.REQSRNO AND SRR.RESSRNO(+)='1' AND SUP.SUPCODE=SRE.REQUSER AND SRE.REQSTAT IN ('A','N','C')".$and."
                                Order by REQNUMB desc, REQDATE desc", "Centra", 'TCS');

                          //print_r($open_search);
                              $ki = 0;


                              for($k=0;$k<sizeof($open_search);$k++)
                              {?>     <?
                                $req_name = select_query_json
                                ("select app.comname from app_complaint_master app where app.comcode='".$open_search[$k]['REQMODE']."'", "Centra", 'TCS');

                                $check1 = select_query_json("SELECT MAX(REQSRNO) MAXREQ FROM SERVICE_REQUEST WHERE REQNUMB='".$open_search[$k]['REQNUMB']."'", "Centra", 'TCS');
                                
                                $check2 = select_query_json("SELECT MAX(RESSRNO) MAXRES FROM SERVICE_RESPONSE WHERE REQNUMB='".$open_search[$k]['REQNUMB']."' AND REQSRNO='".$check1[0]['MAXREQ']."'", "Centra", 'TCS');
                                
                                $employee = select_query_json("select eof.empname,eof.empcode from employee_office eof,service_response srr where srr.resfusr=eof.empsrno and srr.reqnumb='".$open_search[$k]['REQNUMB']."' and srr.reqsrno='".$check1[0]['MAXREQ']."' and srr.ressrno='".$check2[0]['MAXRES']."'", "Centra", 'TCS');


                              //print_r($employee);?>
                                      <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                      <td class="center" style='text-align:center;'>
                                        <? echo $k+1; // SERIAL NUMBER OF THE RECORD 
                                        echo("----SELECT MAX(REQSRNO) MAXREQ FROM SERVICE_REQUEST WHERE REQNUMB='".$open_search[$k]['REQNUMB']."'-----");
                                        echo("-----SELECT MAX(RESSRNO) MAXRES FROM SERVICE_RESPONSE WHERE REQNUMB='".$open_search[$k]['REQNUMB']."' AND REQSRNO='".$check1[0]['MAXREQ']."'----");
                                        echo("-----select eof.empname,eof.empcode from employee_office eof,service_response srr where srr.resfusr=eof.empsrno and srr.reqnumb='".$open_search[$k]['REQNUMB']."' and srr.reqsrno'".$check1[0]['MAXREQ']."' and srr.reqsrno='".$check2[0]['MAXRES']."'-----");?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for entryno-->
                                        <? echo $open_search[$k]['REQNUMB'] ?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for entryno-->
                                        <? echo $open_search[$k]['REQDATE'] ?>
                                      </td>
                                      <td class="center" style='text-align:left'><!-- for top core-->
                                        <? echo $open_search[$k]['SUPCODE']." - ".$open_search[$k]['SUPNAME'].""; ?><br>

                                      </td>
                                      <td class="center" style='text-align:left'><!-- for  editor details-->
                                        Contact :<?echo ' '.$open_search[$k]['REQCONT'];?><br>
                                        <? if($open_search[$k]['REQALTCONT']!='-'){ echo 'Alternate Contact : '.$open_search[$k]['REQALTCONT'].'<br>'; } ?>
                                        Mail : <? echo ' '.$open_search[$k]['REQMAIL']; ?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for attachment count-->
                                        <? if($req_name[0]['COMNAME']!=''){echo $req_name[0]['COMNAME'];}else{echo "TEST";} ?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for attachment count-->
                                        <? if($employee[0]['EMPCODE']!=''){echo $employee[0]['EMPCODE'].' - '.$employee[0]['EMPNAME'];}else{
                                          
                                        } ?>
                                        
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for attachment count-->
                                        <? echo $open_search[$k]['REQDESKNO']?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for USER DETAIL-->
                                        <?if($open_search[$k]['REQSTAT']=='A'){echo("ASSIGNED");}if($open_search[$k]['REQSTAT']=='N'){echo("NOT ASSIGNED");}
                                        if($open_search[$k]['REQSTAT']=='C'){echo("CLOSED");}?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for USER DETAIL-->
                                        <btn id="view_msg" class="btn btn-warning btn-sm" onclick="getmsg(<? echo $open_search[$k]['REQNUMB'] ?>)"><span class="fa fa-eye"></span></btn>
                                      </td>
                                    </tr>
                                  <?
                                } ?>


                          </tbody>
                      </table>
                  </div>
                </div>

                <div class="content-frame-body content-frame-body-left" style="background-color: #FFFFFF;">
                    <form id="mainform" enctype="multipart/form-data">
                    <div  class="messages messages-img" id="all-messages">
                      <div class="page-title">
                        <div id="myModal" class="modal">
                          <!-- Modal content -->
                          <span class="close">&times;</span>
                          <div id="modal_data" class="modal-content">
                            <p>Some text in the Modal..</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    </form>
                </div>



                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
</form>
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

    // Get the modal
                                        var modal = document.getElementById('myModal');

                                        // Get the button that opens the modal
                                        var btn = document.getElementById("view_msg");

                                        // Get the <span> element that closes the modal
                                        var span = document.getElementsByClassName("close")[0];

                                        // When the user clicks the button, open the modal
                                        function getmsg(reqnumb)
                                        {
                                              // alert(reqnumb);
                                              //alert($('#reqnumb').val());
                                              //$('#load_page').show();
                                              var send_url = "ajax/request_details.php"
                                              $.ajax({
                                              url:send_url,
                                              type: "POST",
                                              data:{
                                                reqnumb:reqnumb,
                                                choice:'1'
                                              },
                                              dataType:'html',
                                              success:function(data){
                                                      //$("#myModal1").modal('show');
                                                      //$('#load_page').hide();
                                                      //document.getElementById('modal-body1').innerHTML=data;
                                                      //$('#load_page').hide();
                                                      //alert("hi");
                                                      //alert(data);

                                                    $("#modal_data").html(data);
                          // head_loader(reqnumb);
                                                  }
                                              });
                                              modal.style.display = "block";
                                        }
                                        // btn.onclick = function()
                                        // {   //alert("hi");
                                        // function details(reqnumb,reqsrno)
                                        // {
                                        //     // alert($('#reqnumb').val());
                                        //     $('#load_page').show();
                                        //     var strURL="ajax/request_details.php";
                                        //     //var strURL="test.php";
                                        //     $.ajax({
                                        //           type: "POST",
                                        //           url: strURL,
                                        //       dataType:'html',
                                        //       success: function(data1) {
                                        //         $.getScript("js/plugins/jquery/jquery-ui.min.js");
                                        //         $.getScript("http://code.jquery.com/ui/1.10.2/jquery-ui.js");
                                        //
                                        //         $("#modal-content").html(data1);
                                        //         greqnumb=reqnumb;
                                        //         greqsrno=reqsrno;
                                        //         $("#reqnumb").val(reqnumb);
                                        //         head_loader(greqnumb);
                                        //         //$("#reqsrno").val(reqsrno);
                                        //         // alert($('#reqnumb').val());
                                        //
                                        //         $('#txt_assign').autocomplete({
                                        //             source: function( request, response ) {
                                        //               $.ajax({
                                        //                   url : 'ajax/ajax_employee_details.php',
                                        //                   dataType: "json",
                                        //                   data: {
                                        //                      name_startsWith: request.term,
                                        //                      type: 'employee'
                                        //                   },
                                        //                   success: function( data ) {
                                        //                       response( $.map( data, function( item ) {
                                        //                           return {
                                        //                               label: item,
                                        //                               value: item
                                        //                           }
                                        //                       }));
                                        //                   }
                                        //               });
                                        //           },
                                        //           autoFocus: true,
                                        //           minLength: 0
                                        //       });
                                        //       $('#load_page').hide();
                                        //     },
                                        //     data: {
                                        //        reqnumb: reqnumb,
                                        //        reqsrno: reqsrno
                                        //     },
                                        //     error: function(response, status, error)
                                        //     {
                                        //         //alert(error);
                                        //     }
                                        //     });
                                        // }
                                        //
                                        //     modal.style.display = "block";
                                        // }

                                        // When the user clicks on <span> (x), close the modal
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


  $('#search_user').autocomplete({
        source: function( request, response ) {
            $.ajax({
              url : 'ajax/ajax_employee_details.php',
              dataType: "json",
              data: {
                 name_startsWith: request.term,
                 type: 'employee'
              },
              success: function( data ) {
                if(data == 'No User Available in this Top core and Sub Core') {
                  $('#txt_workintiator').val('');
                  var ALERT_TITLE = "Message";
                  var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                  createCustomAlert(ALERTMSG, ALERT_TITLE);
                } else {
                  response( $.map( data, function( item ) {
                    return {
                      label: item,
                      value: item
                    }
                  }));
                }
              }
            });
        },
        autoFocus: true,
        minLength: 0
    });

  $('#search_sprno').autocomplete({
    source: function( request, response ) {
      $.ajax({
        url : 'ajax/ajax_suppliers_details.php',
        dataType: "json",
        data: {
           name_startsWith: request.term,
           type: 'supplier'
        },
        success: function( data ) {
          response( $.map( data, function( item ) {
            return {
              label: item,
              value: item
            }
          }));
          
          document.getElementById('datepicker-example3').value = "";
          document.getElementById('datepicker-example4').value = "";
        }
      });
    },
    autoFocus: true,
    minLength: 0
  });  
  
    function PrintDiv(dataurl) {
        var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
    }
  
  function head_loader(reqnumb)
  {
    $('#load_page').show();
    var strURL="ajax/head_loader.php";
      $.ajax({
        type: "POST",
        url: strURL,
        data:{
        reqnumb:reqnumb
        },
          dataType:'html',
        success: function(data1) {
          $("#head_titile").html(data1);
          //reqnumb=$('#reqnumb').val();
          $('#load_page').hide();
        },
      error: function(response, status, error)
      {
      // alert(error);
          //alert(response);
          //alert(status);
      }
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
<? } else { ?>
    <script>window.location="home.php";</script>
<?php exit();
}