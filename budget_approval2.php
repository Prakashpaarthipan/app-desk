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
    .blinking{
    animation: blinkingText 1s infinite;
    }
    @keyframes blinkingText{
        0%{     color: rgba(224, 75, 74, 1);    }
        30%{    color: transparent; }
        30%{    color: transparent; }
        30%{    color:transparent;  }
        100%{   color:rgba(224, 75, 74, 1);    }
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
            <?
              //$check_login = select_query_json("select decode(descode||'-'||brncode,'19-888',1,0) user_chk from employee_office where empsrno=94095", "Centra", 'TEST');
            $check_login = select_query_json("select decode(brncode,'888',1,0) user_chk from employee_office where empsrno='".$_SESSION['tcs_empsrno']."'", "Centra", 'TEST');
              
            ?>
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
                    // echo('<pre>');
                    // print_r($check_login);
                    // echo('</pre>');
                    if($check_login[0]['USER_CHK']=='1')
                    { 
                      $sql_search_test = select_query_json("select bbr.*,brn.brnname,regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch from branch_budget_request bbr,branch brn where tarmont='".$month."' and taryear='".date('Y')."' and empsrno='".$_SESSION['tcs_empsrno']."' and bbr.deleted='N' and REQAPPR in ('N','F')  and brn.brncode=bbr.brncode order by expsrno,brn.brncode", "Centra", 'TEST');
                      //$back_select = select_query_json("select distinct lstimpp,LSTSALES,TARSALES,brncode,(select nvl(max(CUREXPP),0) from branch_budget_request bbr2 where bbr1.brncode=bbr2.brncode and DELETED='N' AND taryear='".date('Y')."' and tarmont='".$month."' and empsrno='".$_SESSION['tcs_empsrno']."') expcur from branch_budget_request bbr1 where empsrno='".$_SESSION['tcs_empsrno']."' and tarmont='".$month."' AND DELETED='N' and reqappr in ('N','F')", "Centra", "TEST");
                     // echo("select bbr.*,brn.brnname,regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch from branch_budget_request bbr,branch brn where tarmont='".$month."' and taryear='".date('Y')."' and empsrno='".$_SESSION['tcs_empsrno']."' and bbr.deleted='N' and REQAPPR in ('N','F')  and brn.brncode=bbr.brncode order by expsrno,brn.brncode");
                      $estimated_expense = select_query_json("select BRNCODE,LSTSALES,TARSALES,ROUND(((SUM(APPVALU)/100000)/LSTSALES*100),2) ESTPER from branch_budget_request where TARMONT=11 AND delEted='N' AND REQAPPR NOT IN('R') AND APPSTAG>=1 GROUP BY BRNCODE,TARSALES,LSTSALES", "Centra", 'TEST');
                      $head_expense = select_query_json("select brncode,EXPSRNO,ROUND(((SUM(APPVALU)/100000)/LSTSALES*100),2) EXPPER from branch_budget_request where TARMONT=11 AND delEted='N' AND REQAPPR NOT IN('R') AND APPSTAG>=1 GROUP BY BRNCODE,EXPSRNO,TARSALES,LSTSALES", "Centra", 'TEST');

                    }
                    else
                    {
                       $sql_search_test = select_query_json("select bbr.*,brn.brnname,regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch from branch_budget_request bbr,branch brn where tarmont='".$month."' and taryear='".date('Y')."' and empsrno='".$_SESSION['tcs_empsrno']."' and bbr.deleted='N' and REQAPPR in ('N','F') and bbr.brncode in(select brncode from employee_office where empsrno in (select empsrno from userid where usrcode='".$_SESSION['tcs_usrcode']."')) and brn.brncode=bbr.brncode order by expsrno ", "Centra", 'TEST');
                      $estimated_expense = select_query_json(" select BRNCODE,LSTSALES,TARSALES,ROUND(((SUM(APPVALU)/100000)/LSTSALES*100),2) ESTPER from branch_budget_request where BRNCODE in(select brncode from employee_office where empsrno in (select empsrno from userid where usrcode='".$_SESSION['tcs_usrcode']."')) AND TARMONT=11 AND delEted='N' AND REQAPPR NOT IN('R') AND APPSTAG>=1 GROUP BY BRNCODE,TARSALES,LSTSALES", "Centra", 'TEST');
                      $head_expense = select_query_json("select brncode,EXPSRNO,ROUND(((SUM(APPVALU)/100000)/LSTSALES*100),2) EXPPER from branch_budget_request where TARMONT=11 AND delEted='N' AND REQAPPR NOT IN('R') AND APPSTAG>=1 and brncode in(select brncode from employee_office where empsrno in (select empsrno from userid where usrcode='".$_SESSION['tcs_usrcode']."')) GROUP BY BRNCODE,EXPSRNO,TARSALES,LSTSALES", "Centra", 'TEST');
                      
                    }
                    $head_expense_key=array();
                    foreach ($head_expense as $key => $value) 
                    {
                      $head_expense_key[$value['BRNCODE']][$value['EXPSRNO']]=$value;
                    }
                    
                    $arr_estimate=array();
                    foreach ($estimated_expense as $key => $value) 
                    {
                      $arr_estimate[$value['BRNCODE']]=$value;
                    }
                    $arr_brn=array();
                    foreach($sql_search_test as $key => $value)
                    {
                      $temp=count($arr_brn[$value['BRNCODE']]);
                      $arr_brn[$value['BRNCODE']][$temp]=$value;
                    }
                    $arr_brn_overall=array();
                    foreach($back_select as $key => $value)
                    {
                      $temp=count($arr_brn_overall[$value['BRNCODE']]);
                      $arr_brn_overall[$value['BRNCODE']][$temp]=$value;
                    }
                    ?>
                    <div class="row panel-body">
                      <center><span class="label label-primary" style="font-size: 20px">Month : <?$month = date('F',strtotime('+1 month'));echo($month);?> - <?echo date('y');?></span></center>
                        <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>                      
                    </div>
                    <div class="col-md-12">
                            <!-- START TABS -->                                
                        <div class="panel panel-default tabs">                            
                            <ul class="nav nav-tabs" role="tablist">
                              <?$h=0;foreach ($arr_brn as $key => $value) {?>
                                <?if($h==0){?>
                                  <li class="active"><a role="tab" href="#tab_<?=$key?>" data-toggle="tab"><?=$value[0]['BRANCH'];?></a></li>
                                <?$h++;}else{?>
                                <li ><a role="tab" href="#tab_<?=$key?>" data-toggle="tab"><?=$value[0]['BRANCH'];?></a></li>
                              <?}} ?>
                            </ul>                            
                            <div class="panel-body tab-content">
                              <?$h=0;foreach ($arr_brn as $key_brn => $value) {?>
                                <?if($h==0){?>
                                <div class="tab-pane active" id="tab_<?=$key_brn?>">
                                <form class="form-horizontal" role="form" id="frm_budget_approve_<?=$key_brn?>" name="frm_budget_approve_<?=$key_brn?>" action="#" method="post" enctype="multipart/form-data">
                                <?$h++;}else{?>
                                  <div class="tab-pane" id="tab_<?=$key_brn?>">
                                  <form class="form-horizontal" role="form" id="frm_budget_approve_<?=$key_brn?>" name="frm_budget_approve_<?=$key_brn?>" action="#" method="post" enctype="multipart/form-data">
                                <?}?>
                                
                                  <div class="row">
                                    <center><span class="label label-warning" style="font-size: 20px"><?=$value[0]['BRNNAME'];?></span></center><br>
                                  </div>
                                  <!-- <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div> -->
                                  <!-- /////////////////////// -->
                                  <?if(count($arr_estimate)>0 && count($sql_search_test)>0){?>
                                   <div class="row">
                                      <div class="input-group " style="margin: 10px;vertical-align: middle;float: right;">
                                            <button class="btn btn-success" type="button" style="margin-left: 10px; float: right;" onclick="nsubmit('frm_budget_approve_<?=$key_brn?>');"><span class="fa fa-file-text"></span> Submit</button>
                                      </div>
                                      <div class="col-md-5" style="float: left;left:5%;">
                                        <table class="table table-bordered">
                                            <tbody> 
                                                <tr>
                                                    <td style="background: #f1f5f9;width: 50%;">LAST YEAR SALES <?$month = date('M',strtotime('+1 month'));echo($month);?> - <?echo date('y')-1;?> (in Lakhs) </td>
                                                    <td style="min-width: 50%;"><span id="txt_last_year_sale_<?=$key_brn?>" name="txt_last_year_sale_<?=$key_brn?>"> <?=$arr_estimate[$key_brn]['LSTSALES']?> </span></td>
                                                    <input type="hidden" id="lst_year_val_<?=$key_brn?>" name="lst_year_val_<?=$key_brn?>" value="<?=$arr_estimate[$key_brn]['LSTSALES']?>"/>
                                                </tr>
                                                <tr>
                                                    <td style="background: #f1f5f9">RUN TARGET ( Lakhs)</td>
                                                    <td ><span id="tar_val" ><?$val=0.1;$val=$val*$arr_estimate[$key_brn]['LSTSALES'];$val=$val+$arr_estimate[$key_brn]['LSTSALES'];echo(round($val,2));?></span></td>
                                                </tr>
                                                <tr>
                                                    <td style="background: #f1f5f9">TARGETED SALES (<span id="tar_val" ><?$val=$arr_estimate[$key_brn]['TARSALES']/100;$val=$val*$arr_estimate[$key_brn]['LSTSALES'];$val=$val+$arr_estimate[$key_brn]['LSTSALES'];echo(round($val,2));?></span> Lakhs)</td>
                                                    <td ><input type="number" disabled name="txt_tar_sal_<?=$key_brn?>" id="txt_tar_sal_<?=$key_brn?>" style="width: 100%;box-sizing: border-box;" onblur="calculate('a');" value="<?=$arr_estimate[$key_brn]['TARSALES']?>"/></td>
                                                </tr>
                                                <tr>
                                                    <td style="background: #f1f5f9">ESTIMATED EXP %</td>
                                                    <td><span class="blinking" ><span id="txt_estimated_val_<?=$key_brn?>" name="txt_estimated_val_<?=$key_brn?>"> <?=$arr_estimate[$key_brn]['ESTPER']?> </span><span> %</span></span></td>
                                                    <input type="hidden" name="txt_cur_exp_<?=$key_brn?>" id="txt_cur_exp_<?=$key_brn?>" value="<?=$arr_estimate[$key_brn]['ESTPER']?>"/>
                                                    <input type="hidden" name="new_cur_expense" id="new_cur_expense_<?=$key_brn?>" value="<?=$arr_estimate[$key_brn][0]['ESTPER']?>"/>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <span class="label blinking" style="font-size: 15px;color:white;">Note : Only approved expenses are Taken under calculation</span>
                                      </div>
                                   </div>

                                   <?}else{?>
                                        <center><h3 style="color: rgba(28, 175, 154, 1);">NO APPROVAL WAITING</h3></center>
                                    <?}?>
                                  <!-- ///////////////////////////// -->
                                  <?
                                  $arr_exp=array();
                                  foreach ($value as $key => $value1)
                                  { 
                                    $temp=count($arr_exp[$value1['EXPSRNO']]);
                                    $arr_exp[$value1['EXPSRNO']][$temp]=$value1;
                                  }
                                 // echo("<pre>");
                                 // print_r(count($arr_exp));
                                  //echo("</pre>");
                                  ?>
                                  <!-- 11111111111111111111111111111111111 -->
                                   <div class="panel-group accordion" style="padding: 10px 20px;">
                                    <?$flag_acc=0;foreach ($arr_exp as $key_exp => $value_exp) {
                                       $expname = select_query_json("select distinct(expname) from department_asset WHERE expsrno='".$value_exp[0]['EXPSRNO']."'", "Centra", 'TCS');
                                      ?>
                                      <!-- <input type="hidden" name="taryear" id="enTyear[<?=$value_exp[0]['EXPSRNO']?>]" value="<?=$value_exp[0]['ENTYEAR']?>"/> -->
                                      <input type="hidden" name="taryear[<?=$value_exp[0]['EXPSRNO']?>]" id="taryear[<?=$value_exp[0]['EXPSRNO']?>]" value="<?=$value_exp[0]['TARYEAR']?>"/>
                                      <input type="hidden" name="tarmont[<?=$value_exp[0]['EXPSRNO']?>]" id="tarmont[<?=$value_exp[0]['EXPSRNO']?>]" value="<?=$value_exp[0]['TARMONT']?>"/>
                                      <input type="hidden" name="entyear[<?=$value_exp[0]['EXPSRNO']?>]" id="entyear[<?=$value_exp[0]['EXPSRNO']?>]" value="<?=$value_exp[0]['ENTYEAR']?>"/>
                                      <!-- <input type="hidden" name="entnumb" id="entyear[<?=$value_exp[0]['EXPSRNO']?>]" value="<?=$value_exp[0]['ENTYEAR']?>"/> -->
                                       <div class="panel panel-primary">
                                          <div class="panel-heading ui-draggable-handle">
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <h2 class="panel-title">
                                                   <a href="#acc_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO']?>"> 
                                                      <?=$expname[0]['EXPNAME'];?>
                                                   </a>
                                                </h2>
                                               </div>
                                               <div class="col-md-4" style="line-height: 30px;">
                                                <label id="txt_dep_expense_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO'];?>" name="txt_dep_expense_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO'];?>" style="font-size: 20px; ;font-weight: bolder; color: rgba(169, 68, 66, 1);height: 100%;"><?=$head_expense_key[$key_brn][$value_exp[0]['EXPSRNO']]['EXPPER'];?></label><span style="color: rgba(169, 68, 66, 1);height: 100%;"> %</span> 
                                                <input type="hidden" class="old_department_expense_<?=$key_brn;?>" name="dep_expense_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO'];?>" id="dep_expense_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO'];?>" value="<?=$head_expense_key[$key_brn][$value_exp[0]['EXPSRNO']]['EXPPER'];?>"/>
                                                 <input type="hidden" class="new_department_expense_<?=$key_brn;?>" name="new_dep_expense[<?=$value_exp[0]['EXPSRNO'];?>]" id="new_dep_expense_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO']?>" value="<?=$head_expense_key[$key_brn][$value_exp[0]['EXPSRNO']]['EXPPER'];?>"/> 
                                               </div>
                                               <div class="col-md-4" style="vertical-align: middle;">
                                                 <SELECT style="float: right;width: 50%;background: #ff96006e;" class="form-control check_<?=$key_brn;?>" tabindex="1" name="chk_cnfrm[<?=$value_exp[0]['EXPSRNO'];?>][]" id="chk_cnfrm_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO'];?>" onchange="notify('acc_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO']?>','chk_cnfrm_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO'];?>');">
                                                   <option value="N" >None</option>
                                                   <option style="font-size: 15px;" value="A">Approve</option>
                                                   <option style="font-size: 15px;" value="R">Reject</option>
                                                 </SELECT>
                                               </div>
                                             </div>
                                          </div><!--PANEL HEADING -->
                                          <?if($flag_acc==0){?>
                                            <div class="panel-body panel-body-open" id="acc_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO']?>" style="display: block; border: 1px solid rgba(246, 183, 60, 1);border-radius: 10px;padding:20px 20px;">
                                          <?$flag_acc++;}else{?>
                                            <div class="panel-body" id="acc_<?=$key_brn;?>_<?=$value_exp[0]['EXPSRNO']?>" style="display: none; border: 1px solid rgba(246, 183, 60, 1);border-radius: 10px;padding:20px 20px;">
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
                                                      <?$ki_size=count($value_exp);
                                                    for($k=0;$k<$ki_size;$k++){
                                     $exp_part = select_query_json("select distinct(ptdesc) from non_purchase_target where depcode='".$value_exp[$k]['DEPCODE']."' and brncode='".$value_exp[$k]['BRNCODE']."' and ptnumb='".$value_exp[$k]['PTNUMB']."'order by ptnumb", "Centra", 'TCS');?>
                                                      <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                        <td class="center" style='text-align:center;'>
                                                          <?=$k+1;?>
                                                        </td>
                                                        <td class="center" style='text-align:center;'>
                                                          <? echo $value_exp[$k]['PTNUMB']; ?> 
                                                          <input type="hidden" name="expsrno[]" id="expsrno" value="<?=$value_exp[$k]['EXPSRNO'];?>"/>
                                                          <input type="hidden" name="ptnumb[]" id="ptnumb" value="<?=$value_exp[$k]['PTNUMB']; ?>"/>
                                                          <input type="hidden" name="depcode[]" id="depcode" value="<?=$value_exp[$k]['DEPCODE']?>"/>
                                                          <input type="hidden" name="appstag[]" id="appstag" value="<?=$value_exp[$k]['APPSTAG']?>" />
                                                          <input type="hidden" class="number" name="reqvalue[<?=$value_exp[$k]['EXPSRNO'];?>][<? echo $value_exp[$k]['PTNUMB']; ?>][]" value="<?echo $value_exp[$k]['REQVALU'];?>"/>
                                                           <input type="hidden" name="entnumb[]" id="entnumb" value="<?=$value_exp[$k]['ENTNUMB']?>"/>
                                                           <input type="hidden" name="depexpp[]" id="depexpp" value="<?=$value_exp[$k]['DEPEXPP']?>"/>
                                                           <input type="hidden" name="curexpp[]" id="curexpp" value="<?=$value_exp[$k]['CUREXPP']?>"/>
                                                        </td>
                                                        <td class="center" style='text-align:center;'>
                                                          <? echo $exp_part[0]['PTDESC']; ?> 
                                                        </td>
                                                        <td class="center" style='text-align:center;'>
                                                          <? echo $value_exp[$k]['BUDVALU']; ?>
                                                        </td>
                                                        <td class="center" style='text-align:center;'>
                                                          <? echo $value_exp[$k]['REQVALU']; ?>
                                                        </td>
                                                       
                                                        <td class="center" style='text-align:center;'>
                                                          <input style="width: 100%;height: 100%;" type="number" maxlength="8" class="Number expense_<?=$key_brn;?>_<?=$value_exp[$k]['EXPSRNO'];?>" name="appvalue[<?=$value_exp[$k]['EXPSRNO'];?>][<? echo $value_exp[$k]['PTNUMB']; ?>][]" value="<?echo $value_exp[$k]['APPVALU'];?>" onkeyup="expense_calculate('expense_<?=$key_brn;?>_<?=$value_exp[$k]['EXPSRNO'];?>');"/>
                                                        </td>
                                                         <td class="center" style='text-align:center;'>
                                                          <? echo $value_exp[$k]['APPRESN']; ?>
                                                        </td>
                                                        <td class="center" style='text-align:center;'>
                                                           <input style="width: 100%;height: 100%;text-transform: uppercase;
"  value="<? echo $value_exp[$k]['APPRESN']; ?>" type="text" id="txt_reason" maxlength="100"  name="txt_reason[]" />
                                                        </td>
                                                      </tr>
                                                    <?}?>
                                                    </tbody>
                                                </table>
                                            </div><!-- panel expense-->
                                       </div><!-- PASNEL PRIMARY-->
                                    <?}?>

                                   </div>
                                  <!-- 11111111111111111111111111111111111 -->
                                  </form> 
                                </div>
                              <?} ?>
                            </div>
                           </div>    
                        <!-- END TABS -->  
                    </div>   
            </div>
            <div>
              <?
            $head_list = select_query_json("select hea.empsrno,hea.empcode,emp.empname,emp.descode,hea.brnhdsr from trandata.approval_branch_head@tcscentr hea,trandata.employee_office@tcscentr emp,trandata.designation@tcscentr des where emp.empsrno=hea.empsrno and emp.descode=des.descode and hea.brncode='".$_SESSION['tcs_brncode']."' and hea.deleted='N' and hea.aprvalu>0 and ((emp.empsrno in (188,19256,125,1682,452,21344,43400,20118,83815)) or (emp.descode in (92,189) and emp.brncode not in (888) ) ) group by hea.empsrno,hea.empcode,emp.empname,emp.descode,hea.brnhdsr order by hea.brnhdsr" ,"Centra","TCS");
            $arr_head=array();
            foreach($head_list as $key => $value)
            {
              if($arr_head[$value['EMPSRNO']]=='')
              {
                $arr_head[$value['EMPSRNO']]=$value;
              }
            }
            $arr_head_sort=array();
            $flag_head=0;
            foreach($arr_head as $key => $value)
            {
              $arr_head_sort[$value['BRNHDSR']]=$value;
            }
            ksort($arr_head_sort);
            $head_list=array();
            foreach($arr_head_sort as $key => $value)
            {
              if($_SESSION['tcs_empsrno']==$value['EMPSRNO'])
              {
                $flag_head=1;
              }
              if($flag_head==1)
              {
                $head_list[$value['BRNHDSR']]=$value;
              }
              
            }
            // echo("<pre>");;
            // print_r($arr_head_sort);
            ?>
           <table class="table table-bordered" style="margin:10px;width: 400px;">
             <thead style="background: #f5f5f5">
               <th>Approval Flow</th>
             </thead>
             <tbody style="background: white;">
              <?foreach ($head_list as $key => $value) {?>
               <tr>
                <?if($value['EMPSRNO']==$_SESSION['tcs_empsrno']){$bgr='#89ad4d';}else{$bgr='';}?>
                <td style="background: <?=$bgr;?>">
                  <?=$value['EMPCODE'].' - '.$value['EMPNAME'];?>
                </td>
              </tr>
              <?} ?> 
             </tbody>
           </table>
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
    $('#load_page').fadeOut('fast');
    function notify(body_id,slt_id)
    {
      var val=$('#'+slt_id).val();
      var arr=slt_id.split("_");
      console.log(arr);
      
      if(val=='R')
      {
        //reset(arr[2],arr[3]);
        expense_calculate("expense_"+arr[2]+"_"+arr[3]);  
        $('#'+body_id).css('border','2px solid rgba(224, 75, 74, 1)');
        $('#'+slt_id).css("background","rgba(171, 33, 60, 0.43)");
        return;
      }
      if(val=='A')
      { expense_calculate("expense_"+arr[2]+"_"+arr[3]);
        $('#'+body_id).css('border','2px solid rgba(149, 183, 93, 1)');
        $('#'+slt_id).css("background","rgba(112, 168, 0, 0.37)");
        return;
      }
      if( val=='N')
      { //reset(arr[2],arr[3]);
        expense_calculate("expense_"+arr[2]+"_"+arr[3]);       
        $('#'+body_id).css('border','2px solid rgba(246, 183, 60, 1)');
        $('#'+slt_id).css("background","#ff96006e");
        return;
      }
    }
    function reset(brn,exp){
      var value=$('#dep_expense_'+brn+"_"+exp).val();
      var old_cur_exp=Number($('#txt_cur_exp_'+brn).val());
      $("#txt_dep_expense_"+brn+"_"+exp).html(value);
      $("#new_dep_expense_"+brn+"_"+exp).val(value);
      $("#txt_estimated_val_"+brn).html(old_cur_exp);
      $('#new_cur_expense_'+brn).val(old_cur_exp); 
    }
    function expense_calculate(clsname){
      var cur_app_val=0;
      var cls_exp=clsname.split('_');
      var cls_brn=cls_exp[1];
      var cls_exp=cls_exp[2];
      console.log(cls_brn+" - "+cls_exp);
      if($("#chk_cnfrm_"+cls_brn+"_"+cls_exp).val()!='A')
      {
        check_expense(cls_brn);
       // return;
      }
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
        var brn=clsname.split('_');
        brn=brn[1];
        var dep_exp=Number($("#dep_"+clsname).val());
        var last_year=Number($('#lst_year_val_'+brn).val())*100000;
        var cur_exp=Number($('#txt_cur_exp_'+brn).val());
        var old_tot_brn_dep=0;
        $('.old_department_expense_'+brn).each(function(){
          console.log("old =>"+$(this).val());
          old_tot_brn_dep=old_tot_brn_dep+Number($(this).val());
        });
        percent(old_tot_brn_dep,last_year,cur_app_val,cur_exp,clsname);
        console.log(cur_app_val);
      }
    }

    function check_expense(brn){
      var check_sum=0;
      var cur_exp=Number($('#txt_cur_exp_'+brn).val());
      var last_year=Number($('#lst_year_val_'+brn).val())*100000;
      $('.check_'+brn).each(function(){
        console.log($(this).attr('id'));
        var cls_exp=($(this).attr('id')).split('_');
        var cls_brn=cls_exp[2];
        var cls_exp=cls_exp[3];
        if($("#chk_cnfrm_"+cls_brn+"_"+cls_exp).val()=='A'){
          console.log($("#new_dep_expense_"+cls_brn+"_"+cls_exp).val());
          var expense=0;
          $('.expense_'+cls_brn+"_"+cls_exp).each(function(){
            expense=expense+$(this).val();
          });
          //check_sum=check_sum+expense;
          check_sum=check_sum+Number($("#new_dep_expense_"+cls_brn+"_"+cls_exp).val());
        }
      });
      //check_sum=Number((Number(check_sum)/last_year)*100).toFixed(2);
      var old_tot_brn_dep=0;
      $('.old_department_expense_'+brn).each(function(){
        console.log("old =>"+$(this).val());
        old_tot_brn_dep=old_tot_brn_dep+Number($(this).val());
      });
      console.log("cur => "+cur_exp);
      console.log("old tot => "+old_tot_brn_dep);
      console.log("check sum =>"+check_sum);
      var fin_cur_exp=Number(cur_exp)-Number(old_tot_brn_dep)+Number(check_sum);
       console.log("fin =>"+fin_cur_exp);
      $("#txt_estimated_val_"+brn).html(fin_cur_exp.toFixed(2));
      $('#new_cur_expense_'+brn).val(fin_cur_exp.toFixed(2)); 
    }
    function percent(old_tot_brn_dep,last,req,cur,clsname)
    {
      // viki5
      //console.log(old_tot_brn_dep+" - "+last+" - "+req+" - "+cur+" - "+clsname);
      var brn=clsname.split('_');
      brn=brn[1];
      var val=(Number(req)/Number(last))*100;
      $("#txt_dep_"+clsname).html((val).toFixed(2));
      $("#new_dep_"+clsname).val((val).toFixed(2));
      
     console.log("#new_dep_expense_"+brn);

      var new_tot_brn_dep=0;
      $('.new_department_expense_'+brn).each(function(){
       // console.log("new =>"+$(this).val());
        new_tot_brn_dep=new_tot_brn_dep+Number($(this).val());
      });
      //console.log("old_tot_brn_dep "+old_tot_brn_dep);
      //console.log("new_tot_brn_dep "+new_tot_brn_dep);
      //$("#txt_estimated_val_"+brn).html((cur-Number(old_tot_brn_dep)+Number(new_tot_brn_dep)).toFixed(2));
      $('#new_cur_expense_'+brn).val((cur-Number(old_tot_brn_dep)+Number(new_tot_brn_dep)).toFixed(2));
      check_expense(brn);
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
    function nsubmit(formname)
    {
      console.log(formname);
      if(confirm("Are Sure to Commit !"))
        {
          $('#load_page').fadeIn('slow');
          var form_data = new FormData(document.getElementById(formname));
          form_data.append("action","approve_user");
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
              //window.location.reload();
              $('#load_page').fadeOut('fast');
            }
          });
        }
    }
    </script>
<!-- END SCRIPTS -->
</body>
</html>
