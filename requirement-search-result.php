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

if($inner_menuaccess[0]['VEWVALU'] == 'Y') { // Menu Permission is allowed

    if($search_fromdate != '' or $search_todate != '') {
        if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
        $exp1 = explode("-", $search_fromdate);
        $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

        if($search_todate == '') { $search_todate = date("d-M-Y"); }
        $exp2 = explode("-", $search_todate);
        $to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));

        $and .= " And trunc(req.ADDDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
    }

    switch ($data) {
        case 'TOPCORE':
            $sql_search_result = select_query_json("select ent.reqlike,ent.reqfavi,ent.reqview,ent.adddate,ent.entryyr,ent.entryno,ent.entsrno,pri.priname,pri.pridesc,count(atch.atcname)acount,ent.dspfile,eof.empname,eof.empcode 
from process_requirement_entry ent , approval_topcore atc ,APPROVAL_Priority pri,process_requirement_attachment atch,employee_office eof,userid usid 
where atc.atccode=ent.atccode and pri.pricode=ent.pricode and atch.entryyr=ent.entryyr and atch.entryno=ent.entryno and usid.usrcode=ent.adduser and eof.empsrno=usid.empsrno and ( atc.ATCCODE = '".$term."' or atc.ATCNAME like '%".strtoupper($process)."%' ) 
group by ent.entryyr,ent.entryno,ent.entsrno,ent.dspfile,pri.priname,eof.empname,eof.empcode,pri.pridesc,ent.adddate,ent.reqlike,ent.reqfavi,ent.reqview 
order by ent.entryyr desc,ent.entryno desc,ent.entsrno", 'Centra', 'TEST');
            break;

        case 'PRIORITY':
            $sql_search_result = select_query_json("select ent.reqlike,ent.reqfavi,ent.reqview,ent.adddate,ent.entryyr,ent.entryno,ent.entsrno,pri.priname,pri.pridesc,count(atch.atcname)acount,ent.dspfile,eof.empname,eof.empcode 
from process_requirement_entry ent , approval_topcore atc ,APPROVAL_Priority pri,process_requirement_attachment atch,employee_office eof,userid usid 
where atc.atccode=ent.atccode and pri.pricode=ent.pricode and atch.entryyr=ent.entryyr and atch.entryno=ent.entryno and usid.usrcode=ent.adduser and eof.empsrno=usid.empsrno and ( pri.pricode = '".$term."' or pri.priname like '%".strtoupper($process)."%' ) 
group by ent.entryyr,ent.entryno,ent.entsrno,ent.dspfile,pri.priname,eof.empname,eof.empcode,pri.pridesc,ent.adddate,ent.reqlike,ent.reqfavi,ent.reqview 
order by ent.entryyr desc,ent.entryno desc,ent.entsrno", 'Centra', 'TEST');
            break;

        default:
            # code...
            break;
    }

    $srch_rslt = strtoupper($process);
    if($data != '') { $srch_rslt .= " - ".strtoupper($data); }
    if($search_fromdate != '') { $srch_rslt .= " - ".strtoupper($search_fromdate); }
    if($search_todate != '') { $srch_rslt .= " - ".strtoupper($search_todate); }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <!-- META SECTION -->
    <title><?=strtoupper($srch_rslt)?> :: Search Result :: Approval Desk :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <style>
      .Zebra_DatePicker_Icon_Wrapper{
        width: 100% !important;
      }
      .Zebra_DatePicker_Icon_Inside{

        right:5px !important;
      }
      .in_search{
        padding:5px 5px !important;
      }
    </style>
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
                    <li class="active">Search Result - <?=$srch_rslt?></li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                <div class="row">
                    <div class="col-md-12">

                        <!-- START SEARCH -->
                        <div class="panel panel-default" style="display: none;">
                            <form name="frm_search" id="frm_search" action="" method="get">
                              <div class="panel-body">
                                  <div class="row stacked">
                                      <div class="col-md-12">
                                          <div class="input-group push-down-10" style="width: 100%;">
                                              <? /* <div class="col-md-1">
                                                  <span class="input-group-addon"><span class="fa fa-search"></span></span>
                                              </div> */ ?>
                                              <div class="col-lg-4 col-md-12 col-xs-12 in_search">
                                                  <input type="text" class="form-control" name="process" id="process" placeholder="Search Keywords..." data-original-title="Search Keywords..." style="text-transform:uppercase;" value="<?=$process?>"/>
                                              </div>
                                               <div class="col-lg-2 col-md-6 col-xs-12 in_search">
                                                  <select class="form-control" required name='data' id='data' data-toggle="tooltip" data-placement="top" data-original-title="Process Type">
                                                      <option value='RELATED_APPROVALS' <? if($data == 'RELATED_APPROVALS') { ?> selected <? } ?>>APPROVAL NO</option>
                                                      <option value='PROJECT' <? if($data == 'PROJECT') { ?> selected <? } ?>>PROJECT</option>
                                                      <option value='TYPE_OF_SUBMISSION' <? if($data == 'TYPE_OF_SUBMISSION') { ?> selected <? } ?>>TYPE OF SUBMISSION</option>
                                                      <option value='TOPCORE' <? if($data == 'TOPCORE') { ?> selected <? } ?>>TOPCORE</option>
                                                      <option value='SUBCORE' <? if($data == 'SUBCORE') { ?> selected <? } ?>>SUBCORE</option>
                                                      <option value='DEPARTMENT' <? if($data == 'DEPARTMENT') { ?> selected <? } ?>>DEPARTMENT</option>
                                                      <option value='EXPENSE' <? if($data == 'EXPENSE') { ?> selected <? } ?>>EXPENSE</option>
                                                      <option value='TARGET_NO' <? if($data == 'TARGET_NO') { ?> selected <? } ?>>TARGET NO</option>

                                                      <option value='SUBJECT' <? if($data == 'SUBJECT') { ?> selected <? } ?>>SUBJECT</option>
                                                      <option value='EMPLOYEE' <? if($data == 'EMPLOYEE') { ?> selected <? } ?>>EMPLOYEE</option>
                                                      <option value='BUDGET_MODE' <? if($data == 'BUDGET_MODE') { ?> selected <? } ?>>BUDGET MODE</option>
                                                      <option value='BRANCH' <? if($data == 'BRANCH') { ?> selected <? } ?>>BRANCH</option>
                                                      <option value='PRODUCT' <? if($data == 'PRODUCT') { ?> selected <? } ?>>PRODUCT</option>
                                                      <option value='SUB_PRODUCT' <? if($data == 'SUB_PRODUCT') { ?> selected <? } ?>>SUB PRODUCT</option>
                                                      <option value='SUPPLIER' <? if($data == 'SUPPLIER') { ?> selected <? } ?>>SUPPLIER</option>
                                                      <option value='VALUE' <? if($data == 'VALUE') { ?> selected <? } ?>>VALUE</option>
                                                  </select>
                                              </div>
                                              <div class="col-lg-2 col-md-6 col-xs-12 in_search" style='text-align:center;'>
                                                  <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                              </div>

                                              <div class="col-lg-2 col-md-6 col-xs-12 in_search" style='text-align:center;'>
                                                  <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
                                              </div>
                                              <div class="col-lg-2 col-md-6 col-xs-12 in_search">
                                                  <div class="input-group-btn">
                                                      <button class="btn btn-primary form-control" style="background:#000">Search</button>
                                                  </div>
                                              </div>
                                          </div>

                                          <span class="line-height-30">Search Results for <strong>" <?=$srch_rslt?> "</strong> (<?=count($sql_search_result)?> results)</span>
                                      </div>
                                      <div class="col-md-2">
                                      </div>
                                  </div>
                              </div>
                            </form>
                        </div>
                        <!-- END SEARCH -->

                        <!-- START SEARCH RESULT -->
                        <div class="search-results">
                            <span class="line-height-30">Search Results for <strong>" <?=$srch_rslt?> "</strong> (<?=count($sql_search_result)?> results)</span>

                            <table id="customers2" class="table">
                            <tbody style="width: 100%;">
                            <? foreach ($sql_search_result as $key => $result_value) { ?>
                                <tr class="sr-item"><td style="border: 0px !important; width: 100%;">
                                    <a href="process_requirement_view.php?entryno=<?echo $result_value['ENTRYNO'];?>&entryyr=<?echo $result_value['ENTRYYR'];?>&entsrno=<?echo $result_value['ENTSRNO'];?>"><?=$result_value['APRNUMB']?></a>
                                    <a href="process_requirement_view.php?entryno=<?echo $result_value['ENTRYNO'];?>&entryyr=<?echo $result_value['ENTRYYR'];?>&entsrno=<?echo $result_value['ENTSRNO'];?>"><div class="sr-item-link"><?=$result_value['ATCNAME']?>
                                        <p class="center show_moreless" style="width: 100%;">
                                            <?  $filepathname = $result_value['DSPFILE'];
                                                $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/requirement_entry/".$result_value['ENTRYYR']."/".$filepathname;
                                                $handle = fopen($filename, "r"); 
                                                $contents = file_get_contents($filename);
                                                fclose($handle);
												echo list_summary(str_replace("&nbsp;", " ", $contents), $limit=1000, $strip = false); 
                                            ?></p><div class="tags_clear"></div></a><div class="tags_clear"></div>
                                    <p class="sr-item-links pull-left" style="width: 100%;"><a href="process_requirement_view.php?entryno=<?echo $result_value['ENTRYNO'];?>&entryyr=<?echo $result_value['ENTRYYR'];?>&entsrno=<?echo $result_value['ENTSRNO'];?>"> Read More..</a></p>
                                </td></tr>
                            <? } ?>
                            </tbody></table>

                        </div>
                        <!-- END SEARCH RESULT -->

                        <? /* <ul class="pagination pagination-sm pull-right push-down-20">
                            <li class="disabled"><a href="#">«</a></li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">»</a></li>
                        </ul> */ ?>

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
        <script type="text/javascript" src="js/zebra_datepicker.js"></script>
        <!-- END THIS PAGE PLUGINS-->

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/settings.js"></script>
        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/actions.js"></script>
        <script type="text/javascript" src="js/demo_dashboard.js"></script>
        <!-- END TEMPLATE -->

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
          // direction: [0, '<?=date("d-M-Y")?>'], // ['<?=date("d-M-Y")?>', 0], // 0,
          direction: [1, '<?=date("d-M-Y")?>'], // 0,
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
    <!-- END SCRIPTS -->
    </body>
    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>
