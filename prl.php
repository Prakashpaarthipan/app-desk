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
?>
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
</style>
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<!-- EOF CSS INCLUDE -->
</head>
<body>
<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="process_requirement_view.php" method="post" enctype="multipart/form-data">
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> process_requirement_view.php*/ ?>
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
                <li class="active">Request List</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Request List</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                       
                        <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                        <table  class="table datatableS table-striped"">
                            <thead>
                                <tr>
                                    <th class="center" style='text-align:center'>S.No</th>
                                    <th class="center" style='text-align:center'>POLICY SUBJECT</th>
                                    <th class="center" style='text-align:center'>EFFECTIVE DATE</th>
                                    <th class="center" style='text-align:center'>VALID UPTO</th>
                                    <th class="center" style='text-align:center'>APPROVAL DATE</th>
                                    <th class="center" style='text-align:center'>USERLIST</th>
                                    <th class="center" style='text-align:center'>DESK PROCEDURE</th>
                                    <th class="center" style='text-align:center'>POLICY DOCUMENTS</th>
                                    <th class="center" style='text-align:center'>POLICY TYPE</th>
                                    <th class="center" style='text-align:center'>CREATOR</th>
                                    <th class="center" style='text-align:center'>CO-ORDINATOR</th>
                                    <th class="center" style='text-align:center'>ASSIST BY</th>
                                    <th class="center" style='text-align:center'>ACTION</th>




                                </tr>
                            </thead>
                            <tbody>
                            <?  $sql_search = select_query_json("select ent.entstat, ent.entryyr, ent.entryno, ent.entsrno, atc.atcname, pri.priname, pri.pridesc, count(atch.atcname) acount, 
                                                                            ent.dspfile, eof.empname, eof.empcode,ent.reqtitl
                                                                        from process_requirement_entry ent, trandata.approval_topcore atc, trandata.APPROVAL_Priority pri, 
                                                                            process_requirement_attachment atch, employee_office eof,userid usid
                                                                        where atc.atccode=ent.atccode and pri.pricode=ent.pricode and atch.entryyr=ent.entryyr and atch.entryno=ent.entryno and 
                                                                            usid.usrcode=ent.adduser and eof.empsrno=usid.empsrno 
                                                                        group by ent.entryyr, ent.entryno, ent.entsrno, atc.atcname, ent.dspfile, pri.priname, eof.empname, eof.empcode, pri.pridesc, ent.entstat,ent.reqtitl
                                                                        order by ent.entryyr, ent.entryno desc", "Centra", 'TEST');
                            /*echo("select ent.entstat, ent.entryyr, ent.entryno, ent.entsrno, atc.atcname, pri.priname, pri.pridesc, count(atch.atcname) acount, 
                                                                            ent.dspfile, eof.empname, eof.empcode,ent.reqtitl
                                                                        from process_requirement_entry ent, trandata.approval_topcore atc, trandata.APPROVAL_Priority pri, 
                                                                            process_requirement_attachment atch, employee_office eof,userid usid
                                                                        where atc.atccode=ent.atccode and pri.pricode=ent.pricode and atch.entryyr=ent.entryyr and atch.entryno=ent.entryno and 
                                                                            usid.usrcode=ent.adduser and eof.empsrno=usid.empsrno 
                                                                        group by ent.entryyr, ent.entryno, ent.entsrno, atc.atcname, ent.dspfile, pri.priname, eof.empname, eof.empcode, pri.pridesc, ent.entstat,ent.reqtitl
                                                                        order by ent.entryyr, ent.entryno desc");*/
							$ki = 0;
                            for($k=0;$k<sizeof($sql_search);$k++){//echo $sql_search[$k]['ENTSTAT'];
								if($sql_search[$k]['ENTSTAT']=='y'){ $ki++; ?>
								  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
									<td class="center" style='text-align:center;'>
										<? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
									</td>
									<td class="center" style='text-align:center'><!-- for entryno-->
										<? echo $sql_search[$k]['ENTRYYR'].'-'.$sql_search[$k]['ENTRYNO'].'-'.$sql_search[$k]['ENTSRNO']; // SERIAL NUMBER OF THE RECORD ?>
									</td>
									<td class="center" style='text-align:center'><!-- for top core-->
										<? echo $sql_search[$k]['ATCNAME']; ?> 
									</td>
									<td class="center" style='text-align:center'><!-- for priority-->
										<? echo $sql_search[$k]['PRINAME']; ?>
									</td>
									<td class="center" style='text-align:center'><!-- for  editor details-->
										<?	
											/*$filepathname = $sql_search[$k]['DSPFILE'];
											$filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/requirement_entry/".$sql_search[$k]['ENTRYYR'].'/'.$filepathname;
											//echo $filename;
											$handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
											$contents = fread($handle, filesize($filename));
											fclose($handle);
											// echo substr(strip_tags(str_replace("&nbsp;", " ", $contents)), 0, 500);
											echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);*/
											echo $sql_search[$k]['REQTITL'];

										?>
									</td>
									<td class="center" style='text-align:center'><!-- for attachment count-->
										<? echo $sql_search[$k]['ACOUNT']; ?>
									</td>
									<td class="center" style='text-align:center'><!-- for USER DETAIL-->
										<? echo $sql_search[$k]['EMPNAME'];echo $sql_search[$k]['EMPCODE']; ?>
									</td>
									<td class="center" style='text-align:center'><!-- for USER DETAIL-->
										<a href="process_requirement_view.php?entryno=<?echo $sql_search[$k]['ENTRYNO'];?>&entryyr=<?echo $sql_search[$k]['ENTRYYR'];?>&entsrno=<?echo $sql_search[$k]['ENTSRNO'];?>" class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
                                        <? /* <button class="btn btn-warning btn-sm" style="text-align: center;" type="submit"><span class="fa fa-eye"></span></button>
										<input type="hidden" name="entryno" id='entryno' value=<?echo $sql_search[$k]['ENTRYNO'];?>/>
										<input type="hidden" name="entryyr" id='entryyr' value=<?echo $sql_search[$k]['ENTRYYR'];?>/>
										<input type="hidden" name="entsrno" id='entsrno' value=<?echo $sql_search[$k]['ENTSRNO'];?>/> */ ?>
									</td>
								</tr>
								<? }
                            } ?>

							<tr><td colspan="8" style="background-color: #22262e; TEXT-ALIGN: CENTER; COLOR: #fff;">NOT CONVERTED PROJECTS LIST</td></tr>

							<? $kii = 0;
                            for($k=0;$k<sizeof($sql_search);$k++){ // echo $k;
							    if($sql_search[$k]['ENTSTAT']=='N'){ $kii++; ?>
								  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
									<td class="center" style='text-align:center;'>
										<? echo $kii; // SERIAL NUMBER OF THE RECORD ?>
									</td>
									<td class="center" style='text-align:center'><!-- for entryno-->
										<? echo $sql_search[$k]['ENTRYYR'].'-'.$sql_search[$k]['ENTRYNO'].'-'.$sql_search[$k]['ENTSRNO']; // SERIAL NUMBER OF THE RECORD ?>
									</td>
									<td class="center" style='text-align:center'><!-- for top core-->
										<? echo $sql_search[$k]['ATCNAME']; ?>
									</td>
									<td class="center" style='text-align:center'><!-- for priority-->
										<? echo $sql_search[$k]['PRINAME']; ?>
									</td>
									<td class="center" style='text-align:center'><!-- for  editor details-->
										<?		
											/*$filepathname = $sql_search[$k]['DSPFILE'];
											$filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/requirement_entry/".$sql_search[$k]['ENTRYYR'].'/'.$filepathname;
											//echo $filename;
											$handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
											$contents = fread($handle, filesize($filename));
											fclose($handle);
											// echo substr(strip_tags(str_replace("&nbsp;", " ", $contents)), 0, 500);
											echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);*/
											echo $sql_search[$k]['REQTITL'];
										?>
									</td>
									<td class="center" style='text-align:center'><!-- for attachment count-->
										<? echo $sql_search[$k]['ACOUNT']; ?>
									</td>
									<td class="center" style='text-align:center'><!-- for USER DETAIL-->
										<? echo $sql_search[$k]['EMPNAME'].'-'.$sql_search[$k]['EMPCODE']; ?>
									</td>
									<td class="center" style='text-align:center'><!-- for USER DETAIL-->
										<a href="process_requirement_view.php?entryno=<?echo $sql_search[$k]['ENTRYNO'];?>&entryyr=<?echo $sql_search[$k]['ENTRYYR'];?>&entsrno=<?echo $sql_search[$k]['ENTSRNO'];?>" class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
                                        <? /* <button class="btn btn-warning btn-sm" style="text-align: center;" type="submit"><span class="fa fa-eye"></span></button>
										<input type="hidden" name="entryno" id='entryno' value="<?echo $sql_search[$k]['ENTRYNO'];?>"/>
										<input type="hidden" name="entryyr" id='entryyr' value="<?echo $sql_search[$k]['ENTRYYR'];?>"/>
										<input type="hidden" name="entsrno" id='entsrno' value="<?echo $sql_search[$k]['ENTSRNO'];?>"/> */ ?>
									</td>
								</tr>
							<? }
                            } ?>

                            </tbody>
                        </table>
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
