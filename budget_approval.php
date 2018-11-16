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
<title>Budget List :: Approval Desk :: <?php echo $site_title; ?></title>
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
    $month_no=date('m')+1;
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<!-- EOF CSS INCLUDE -->
</head>
<body>
<form class="form-horizontal" role="form" id="frm_budget_approve" name="frm_budget_approve" action="process_requirement_view.php" method="post" enctype="multipart/form-data">
    <div id="load_page" style='display:block;padding:12% 40%;'></div>

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
                <li class="active">Reserved Budget Approval List</li>
            </ul>
            <!-- END BREADCRUMB -->
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                          <strong>Reserved Budget Approval List</strong>
                        </h3>
                        
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>
                    <?$month=date('m')+1; 
                        $sql_search_test = select_query_json("select bbr.*,brn.brnname from branch_budget_request bbr,branch brn where tarmont='".$month."' and taryear='".date('Y')."' and empsrno='".$_SESSION['tcs_empsrno']."' and bbr.deleted='N' and REQAPPR in ('N','F') and bbr.brncode in(select brncode from employee_office where empsrno in (select empsrno from userid where usrcode='".$_SESSION['tcs_usrcode']."')) and brn.brncode=bbr.brncode order by expsrno ", "Centra", 'TEST');
                          $arr=array();
                          foreach($sql_search_test as $key => $value)
                          {
                            $temp=count($arr[$value['EXPSRNO']]);
                            $arr[$value['EXPSRNO']][$temp]=$value;
                          }
                        ?>
                    <div class="panel-body">
                       <center><span class="label label-primary" style="font-size: 20px">Month : <?$month = date('F',strtotime('+1 month'));echo($month);?> - <?echo date('y');?></span></center>
                        <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>
                        <div class="row">
                          <center><span class="label label-warning" style="font-size: 20px">Branch : <?=$sql_search_test[0]['BRNNAME'];?></span></center><br>
                        </div>
                        <div class="row">   
                         </div>
                         
                         <?$back_select = select_query_json("select lstimpp,LSTSALES,TARSALES,(select nvl(max(CUREXPP),0) from branch_budget_request where DELETED='N' AND taryear='".date('Y')."' and tarmont='".$month_no."' and empsrno='".$_SESSION['tcs_empsrno']."' and brncode in(select brncode from employee_office where empsrno in (select empsrno from userid where usrcode='".$_SESSION['tcs_usrcode']."'))) expcur from branch_budget_request where empsrno='".$_SESSION['tcs_empsrno']."' and tarmont='".$month_no."' and rownum<=1 AND DELETED='N' and brncode in(select brncode from employee_office where empsrno in (select empsrno from userid where usrcode='".$_SESSION['tcs_usrcode']."')) and reqappr in ('N','F')", "Centra", "TEST");?>
                         <?//echo("select lstimpp,LSTSALES,TARSALES,(select nvl(max(CUREXPP),0) from branch_budget_request where taryear='".date('Y')."' and tarmont='".$month_no."' and empsrno='".$_SESSION['tcs_empsrno'].") expcur from branch_budget_request where empsrno='".$_SESSION['tcs_empsrno']."' and tarmont='".$month_no."' and rownum<=1 and reqappr in ('N','F')");?>
                         <!-- <?echo('<pre>');?>
                         <?print_r($back_select);?>
                         <?echo('</pre>');?> -->
                         <?if(count($back_select)>0 && count($sql_search_test)>0){?>
                         <div class="row">
                            <div class="input-group " style="margin: 10px;vertical-align: middle;float: right;">
                                  <button class="btn btn-success" type="button" style="margin-left: 10px; float: right;" onclick="nsubmit();"><span class="fa fa-file-text"></span> nsubmit</button>
                            </div>
                            <div class="col-md-5" style="float: left;left:5%;">
                              <table class="table table-bordered">
                                  <tbody> 
                                      <tr>
                                          <td style="background: #f1f5f9;width: 50%;">LAST YEAR SALES <?$month = date('M',strtotime('+1 month'));echo($month);?> - <?echo date('y')-1;?> (in Lakhs) </td>
                                          <td style="min-width: 50%;"><span id="txt_last_year_sale" name="txt_last_year_sale"> <?=$back_select[0]['LSTSALES']?> </span></td>
                                          <input type="hidden" id="lst_year_val" name="lst_year_val" value="<?=$back_select[0]['LSTSALES']?>"/>
                                      </tr>
                                      <tr>
                                          <td style="background: #f1f5f9">LAST  MONTH IMPROVEMENT %</td>
                                          <td><span name="txt_lst_mnt_imp" id="txt_lst_mnt_imp"> <?=$back_select[0]['LSTIMPP']?> </span><span> %</span></td>
                                      </tr>
                                      <tr>
                                          <td style="background: #f1f5f9">RUN TARGET ( Lakhs)</td>
                                          <td ><span id="tar_val" ><?$val=0.1;$val=$val*$back_select[0]['LSTSALES'];$val=$val+$back_select[0]['LSTSALES'];echo(round($val,2));?></span></td>
                                      </tr>
                                      <tr>
                                          <td style="background: #f1f5f9">TARGETED SALES (<span id="tar_val" ><?$val=$back_select[0]['TARSALES']/100;$val=$val*$back_select[0]['LSTSALES'];$val=$val+$back_select[0]['LSTSALES'];echo(round($val,2));?></span> Lakhs)</td>
                                          <td ><input type="number" disabled name="txt_tar_sal" id="txt_tar_sal" style="width: 100%;box-sizing: border-box;" onblur="calculate('a');" value="<?=$back_select[0]['TARSALES']?>"/></td>
                                      </tr>
                                      <tr>
                                          <td style="background: #f1f5f9">ESTIMATED EXP %</td>
                                          <td><span class="blinking" ><span id="txt_estimated_val" name="txt_estimated_val"> <?=$back_select[0]['EXPCUR']?> </span><span> %</span></span></td>
                                          <input type="hidden" name="txt_cur_exp" id="txt_cur_exp" value="<?=$back_select[0]['EXPCUR']?>"/>
                                          <input type="hidden" name="new_cur_expense" id="new_cur_expense" value="<?=$back_select[0]['EXPCUR']?>"/>
                                      </tr>
                                  </tbody>
                              </table>
                            </div>
                           
                         </div>
                         <?}else{?>
                              <center><h3 style="color: rgba(28, 175, 154, 1);">NO APPROVAL WAITING</h3></center>
                          <?}?>
                          

                        
                        <!-- ////////////////////////// -->
                         

                        <input type="hidden" name="taryear" id="taryear" value="<?=$sql_search_test[0]['TARYEAR']?>"/>
                        <input type="hidden" name="tarmont" id="tarmont" value="<?=$sql_search_test[0]['TARMONT']?>"/>
                        <input type="hidden" name="entyear" id="entyear" value="<?=$sql_search_test[0]['ENTYEAR']?>"/>
                        <input type="hidden" name="action" id="action" value="approve_user"/>
                        <input type="hidden" name="appstat" id="appstat" value="" />
                        
                        <div class="panel-group accordion" style="padding: 10px 20px;">
                          <?$flag=0;foreach($arr as $key => $value){
                            $flag++;
                             $expname = select_query_json("select distinct(expname) from department_asset WHERE expsrno='".$value[0]['EXPSRNO']."'", "Centra", 'TCS');
                           // $exp_part = select_query_json("select distinct(ptdesc) from non_purchase_target where depcode='".$sql_search[$k]['DEPCODE']."' and brncode='".$sql_search[$k]['BRNCODE']."' and ptnumb='".$sql_search[$k]['PTNUMB']."'order by ptnumb", "Centra", 'TCS');?>
                                <div class="panel panel-primary">
                                    <div class="panel-heading ui-draggable-handle">
                                       <div class="row">
                                         <div class="col-md-4">
                                           <h2 class="panel-title">
                                             <a href="#acc_<?=$value[0]['EXPSRNO']?>"> 
                                                <?=$expname[0]['EXPNAME'];?>
                                             </a>
                                          </h2>
                                         </div>
                                         <div class="col-md-4" style="line-height: 30px;">
                                          <label id="txt_dep_expense_<?=$value[0]['EXPSRNO'];?>" name="txt_dep_expense_<?=$value[0]['EXPSRNO'];?>" style="font-size: 20px; ;font-weight: bolder; color: rgba(169, 68, 66, 1);height: 100%;"><?=$value[0]['DEPEXPP'];?></label><span style="color: rgba(169, 68, 66, 1);height: 100%;"> %</span> 
                                          <input type="hidden" name="dep_expense_<?=$value[0]['EXPSRNO'];?>" id="dep_expense_<?=$value[0]['EXPSRNO'];?>"
                                          value="<?=$value[0]['DEPEXPP'];?>"/>
                                          <input type="hidden" name="new_dep_expense[<?=$value[0]['EXPSRNO'];?>]" id="new_dep_expense" value="<?=$value[0]['DEPEXPP'];?>"/>
                                         </div>
                                         <div class="col-md-4" style="vertical-align: middle;">
                                           <SELECT style="float: right;width: 50%;" class="form-control" tabindex="1" name="chk_cnfrm[<?=$value[0]['EXPSRNO'];?>][]" id="chk_cnfrm_<?=$value[0]['EXPSRNO'];?>" onchange="notify('acc_<?=$value[0]['EXPSRNO']?>','chk_cnfrm_<?=$value[0]['EXPSRNO'];?>');">
                                             <option value="N">None</option>
                                             <option style="font-size: 15px;" value="A">Approve</option>
                                             <option style="font-size: 15px;" value="R">Reject</option>
                                           </SELECT>
                                         </div>
                                       </div>
                                    </div>          
                                    <?if($flag==1){?>
                                         <div class="panel-body panel-body-open" id="acc_<?=$value[0]['EXPSRNO']?>" style="display: block; border: 1px solid rgba(149, 183, 93, 1);border-radius: 10px;padding:20px 20px;">    
                                    <?}else{?>
                                          <div class="panel-body" id="acc_<?=$value[0]['EXPSRNO']?>" style="display: none; border: 1px solid rgba(149, 183, 93, 1);border-radius: 10px;padding:20px 20px;">
                                    <?}?>    
                                      <table  class="table datatableS table-striped" style="border-radius: 20px;margin-bottom: 0px;">
                                          <thead>
                                              <tr>
                                                  <th class="center" style='text-align:center'>S.No</th>
                                                  <th class="center" style='text-align:center'>TARGETN NO.</th>
                                                  <th class="center" style='text-align:center'>PARTICULARS</th>
                                                  <th class="center" style='text-align:center'>FIXED BUDGET</th>
                                                  <th class="center" style='text-align:center'>REQUESTED VAlUE</th>
                                                  <th class="center" style='text-align:left'>APPROVE VALUE</th>  
                                                  <th class="center" style='text-align:left'>REASON</th>                                                
                                                  <th class="center" style='text-align:left'>REMARK</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                          <?for($k=0;$k<count($value);$k++){
                           $exp_part = select_query_json("select distinct(ptdesc) from non_purchase_target where depcode='".$value[$k]['DEPCODE']."' and brncode='".$value[$k]['BRNCODE']."' and ptnumb='".$value[$k]['PTNUMB']."'order by ptnumb", "Centra", 'TCS');?>
                                            <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                              <td class="center" style='text-align:center;'>
                                                <?=$k;?>
                                              </td>
                                              <td class="center" style='text-align:center;'>
                                                <? echo $value[$k]['PTNUMB']; ?> 
                                                <input type="hidden" name="expsrno[]" id="expsrno" value="<?=$value[$k]['EXPSRNO'];?>"/>
                                                <input type="hidden" name="ptnumb[]" id="ptnumb" value="<?=$value[$k]['PTNUMB']; ?>"/>
                                                <input type="hidden" name="depcode[]" id="depcode" value="<?=$value[$k]['DEPCODE']?>"/>
                                                <input type="hidden" name="appstag[]" id="appstag" value="<?=$value[$k]['APPSTAG']?>" />
                                                <input type="hidden" class="number" name="reqvalue[<?=$value[$k]['EXPSRNO'];?>][<? echo $value[$k]['PTNUMB']; ?>][]" value="<?echo $value[$k]['REQVALU'];?>"/>
                                                 <input type="hidden" name="entnumb[]" id="entnumb" value="<?=$value[$k]['ENTNUMB']?>"/>
                                                 <input type="hidden" name="depexpp[]" id="depexpp" value="<?=$value[$k]['DEPEXPP']?>"/>
                                                 <input type="hidden" name="curexpp[]" id="curexpp" value="<?=$value[$k]['CUREXPP']?>"/>
                                              </td>
                                              <td class="center" style='text-align:center;'>
                                                <? echo $exp_part[0]['PTDESC']; ?> 
                                              </td>
                                              <td class="center" style='text-align:center;'>
                                                <? echo $value[$k]['BUDVALU']; ?>
                                              </td>
                                              <td class="center" style='text-align:center;'>
                                                <? echo $value[$k]['REQVALU']; ?>
                                              </td>
                                             
                                              <td class="center" style='text-align:center;'>
                                                <input style="width: 100%;height: 100%;" type="number" maxlength="8" class="Number expense_<?=$value[$k]['EXPSRNO'];?>" name="appvalue[<?=$value[$k]['EXPSRNO'];?>][<? echo $value[$k]['PTNUMB']; ?>][]" value="<?echo $value[$k]['APPVALU'];?>" onkeyup="expense_calculate('expense_<?=$value[$k]['EXPSRNO'];?>');"/>
                                              </td>
                                               <td class="center" style='text-align:center;'>
                                                <? echo $value[$k]['APPRESN']; ?>
                                              </td>
                                              <td class="center" style='text-align:center;'>
                                                 <input style="width: 100%;height: 100%;" type="text" id="txt_reason" maxlength="100" name="txt_reason[]" />
                                              </td>
                                            </tr>
                                          <?}?>
                                          </tbody>
                                      </table>
                                    </div>                                
                                </div>
                              <?}?>
                            </div>
                        <!-- /////////////////////// -->
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
    $('#load_page').fadeOut('fast');
    function notify(body_id,slt_id)
    {
      
      var val=$('#'+slt_id).val();
      if(val=='R')
      {
        $('#'+body_id).css('border','2px solid rgba(224, 75, 74, 1)');
      }
      if(val=='A' || val=='N')
      {
        $('#'+body_id).css('border','2px solid rgba(149, 183, 93, 1)');
      }


    }
    function expense_calculate(clsname){
      var cur_app_val=0;
      $("."+clsname).each(function (){
        cur_app_val=cur_app_val+Number(this.value);
      });
      if(cur_app_val=='')
      {
        //intialize(clsname);
        cur_app_val=0;
        //console.log(clsname);
      }
      //else
      {
        var dep_exp=Number($("#dep_"+clsname).val());
        var last_year=Number($('#lst_year_val').val())*100000;
        var cur_exp=Number($('#txt_cur_exp').val());
        percent(dep_exp,last_year,cur_app_val,cur_exp,clsname);
        console.log(cur_app_val);
      }
    }
    function percent(dep,last,req,cur,clsname)
    {
      var val=(Number(req)/Number(last))*100;
      $("#txt_dep_"+clsname).html((val).toFixed(2));
      $('#new_dep_expense').val((val).toFixed(2));
      $("#txt_estimated_val").html((cur-dep+val).toFixed(2));
      $('#new_cur_expense').val((cur-dep+val).toFixed(2));
    }
    function intialize(clsname)
    {
      var dep_exp=Number($("#dep_"+clsname).val());
      $("#txt_dep_"+clsname).html(dep_exp);
      var cur_exp=Number($("#txt_cur_exp").val());
      $("#txt_estimated_val").html(cur_exp);
    }
    $('.Number').keypress(function (event) {
          var keycode = event.which;
          if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
              event.preventDefault();
          }
      });
    function nsubmit()
    {
      if(confirm("Are Sure to Commit !"))
        {
          $('#load_page').fadeIn('slow');
          var form_data = new FormData(document.getElementById("frm_budget_approve"));
          $.ajax({
            url:"viki/budget_entry.php",
            //url:'viki/post_test.php',
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            async:true,
            dataType:"html",
            success:function(data){

              //nview();
              console.log(data);
              var action=$('#appstat').val();
              alert("Action Successfull !");
              window.location.reload();
              $('#load_page').fadeOut('fast');
            }
          });
        }
    }
    </script>
<!-- END SCRIPTS -->
</body>
</html>
