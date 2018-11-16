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
<html lang="en">
<head>
<!-- META SECTION -->
<title>Approval List :: Approval Desk :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- CSS INCLUDE -->        
<link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
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
            <div class="page-content-wrap" style="padding:10px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 style="padding:10px;">Budget Approval List</h3>
                     </div>
                    <div class="panel-body">
                        <table id="customers2" class="table datatable table-striped table-bordered table-hover">
                            <thead>
								<tr>
									<th style="text-align: center;vertical-align:middle;" rowspan="2">#</th>
                                    <th style="text-align: center;vertical-align:middle;" colspan="2">Target </th>
                                    <th style="text-align: center;vertical-align:middle;" rowspan="2">Approval Number</th>
                                    <th style="text-align: center;vertical-align:middle;" rowspan="2">Value</th>
                                    <th style="text-align: center;vertical-align:middle;" rowspan="2">Action</th>
								</tr>
                                <tr>
                                    <th style="text-align: center;vertical-align:middle;width:0px; !important">Number</th>
                                    <th style="text-align: center;vertical-align:middle;">Descriptoin</th>
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


														/* 	   $sql_search = select_query_json("select distinct atycode,aprnumb,appstat,
					(select Max(trunc(FINDATE)) from trandata.approval_request@TCSCENTR  where ARQYEAR= app.arqyear and arqcode=app.arqcode  and atycode=app.atycode and atccode=app.atccode) LastAppDate,  
					(select APPFVAL from trandata.approval_request@tcscentr  where ARQYEAR= app.arqyear and arqcode=app.arqcode and arqsrno=(select max(arqsrno) 
					from  trandata.approval_request@tcscentr
					where ARQYEAR= app.arqyear and arqcode=app.arqcode and  atycode=app.atycode and atccode=app.atccode) and atycode=app.atycode and atccode=app.atccode) FinValue 
					from trandata.approval_request@tcscentr app
					where  appstat='A' and   APPFVAL>0 and aprnumb in 
					(SELECT distinct aprnumb FROM trandata.approval_budget_planner@tcscentr bud
					WHERE bud.deleted='N' AND   bud.APPYEAR=TO_CHAR(sysdate,'YYYY') and bud.APPMNTH=TO_CHAR(sysdate,'MM') and bud.APPRVAL>0 )");  */


					/* $sql_search = select_query_json("select distinct atycode,aprnumb,appstat,appfval,arqcode,arqyear,arqsrno,atccode,findate  from trandata.approval_request@tcscentr where (aprnumb,arqsrno) in (
																			select aprnumb,max(arqsrno)
																			from trandata.approval_request@tcscentr app
																			where  appstat='A' and   APPFVAL>0 and aprnumb in 
																			(SELECT distinct aprnumb FROM trandata.approval_budget_planner@tcscentr bud
																			WHERE bud.deleted='N' AND   bud.APPYEAR=TO_CHAR(sysdate,'YYYY') and bud.APPMNTH=TO_CHAR(sysdate,'MM') and bud.APPRVAL>0 )group by aprnumb)"); */


					$sql_search = select_query_json("select distinct atycode,aprnumb,appstat,appfval,arqcode,arqyear,arqsrno,atccode,apprdet,tarnumb,tardesc, 
																			(select Max(trunc(FINDATE)) from trandata.approval_request@TCSCENTR  where ARQYEAR= app.arqyear and arqcode=app.arqcode  and atycode=app.atycode and atccode=app.atccode) LastAppDate
																		  from trandata.approval_request@tcscentr app where (aprnumb,arqsrno) in (select aprnumb,max(arqsrno) from trandata.approval_request@tcscentr where  appstat='A'  and APPFVAL>0 and aprnumb in 
																		(SELECT distinct aprnumb FROM trandata.approval_budget_planner@tcscentr bud WHERE bud.deleted='N' AND   bud.APPYEAR=TO_CHAR(sysdate,'YYYY') and bud.APPMNTH=TO_CHAR(sysdate,'MM') and bud.APPRVAL>0 )group by aprnumb)");

                                $ij = 0;
                                for($search_i = 0; $search_i < count($sql_search); $search_i++) { $ij++;
                                    // A - Approved; N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
                                    $editid = 0; $bgclr = ''; $clr = '#000000';
									?>
                                    <tr class="odd gradeX">

                                        <td style='text-align:center;vertical-align: middle;'><?=$ij?></td>
                                        <td style="vertical-align: middle;text-align:center;"><? echo $sql_search[$search_i]['TARNUMB']; ?></td>
                                        <td style="vertical-align: middle;"><? echo $sql_search[$search_i]['TARDESC']; ?></td>
                                        <td style="vertical-align: middle;"><a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='Approval' alt='Approval' style='color:<?=$clr?>;'> <? echo $sql_search[$search_i]['APRNUMB']; ?></a></td>
										<td style="vertical-align: middle;"><? echo $sql_search[$search_i]['APPFVAL']; ?></td>
                                        <td class="center" style='text-align:center; white-space:nowrap;vertical-align: middle;'>

										<a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='Budget Planner' alt='Budget Planner' style='color:<?=$clr?>;'><i class="fa fa-eye"></i> Budget Planner</a>

                                       <? /* <div class="col-xs-1" style='text-align:left;'>
										<input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='border-radius: 10px;' value='Budget Planner' title='Budget Planner'>
										</div> */ ?>
                                       <!-- <?   if($sql_search[$search_i]['APPSTAT'] != 'A') { ?> / <a href='javascript:void(0)' onclick="cmnt_mail('<?=$sql_search[$search_i]['APRNUMB']?>');" title='mail' alt='mail' style='color:<?=$clr?>;'><i class="fa fa-envelope"></i> mail</a><? }  ?> -->

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