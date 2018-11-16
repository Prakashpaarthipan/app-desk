<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('/lib/config.php');
include_once('/lib/function_connect.php');
include_once('/general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

//$sql_emprights = select_query_json("select * from tlu_employee_rights where deleted = 'N' and empsrno = '".$_SESSION['tcs_empsrno']."'", "Centra", "TCS");
$_SESSION['tlu_emp_prhdcod'] = 1;
if(count($sql_emprights) > 0) {
    $_SESSION['tlu_emp_prhdcod'] = $sql_emprights[0]['PRSCODE'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Purchase Order Track Board :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">
<style type="text/css">
  .red_clr { font-size: 16px; }
  .blue_clr { font-size: 16px; }

    .comment-frame {
        background-color: #f0eeee;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.23);
        box-shadow: 0 1px 2px rgba(0,0,0,.23);
        margin: 4px 4px 12px 0;
    }
    .table>thead>tr>th{
      background:#003e7d !important ;
      color:#000 !important;
    }
     .table>thead>tr>th{
      
      background:#003e7d !important ;
      color:#000 !important;
   
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
    .content-frame-body{
      overflow-x : scroll;
      padding-right:50px;
    }
    .list_view{
      min-width:7000px;
    }
    .nonadv_list{
      width:350px;
      margin:5px 10px;
      float:left;
    }
    .tasks{
       height: 600px;
      /* overflow-y: scroll;*/
       padding: 2px 5px;
       border:none !important;

     }
     .tasks .task-item{
      border-left:none !important;
    cursor:default !important;
     }
     .nonadv_list{
       border:solid 1px #3e4095;
       border-radius:2px;
       padding-top:5px;
       box-shadow: 2px 5px 20px #8788a5;
     }
     .nonadv_list h3{
       border-bottom:solid 1px #3e4095;
       padding-bottom:25px;
       color:#ed3237
     }
     .nonadv_list .task-item{
       box-shadow: 0px 2px 10px #8788a5;
       margin-right: 10px;
     }
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }

    item.task-primary {
        border-left-color: #1b1e24;
    }

    .page-container .page-content .content-frame {
        background: #f5f5f5 url(../../images/3/bg1.jfif) center center no-repeat !important;
    }

    #gallery {
      margin-left: auto;
      margin-right: auto;
    }
   div::-webkit-scrollbar {
        background: #fff;
        width: 2px !important;
       /*height: 15px; */
        
    }

    div::-webkit-scrollbar-thumb {
        background: #003e7d;
    }
    /*table th{
      font-size:12px !important;
    }
    table td{
      font-size:10px !important;
    }*/
    .task-item p{
      text-align:center !important;
    }
    .blinking{
      animation:blinkingText 1.2s infinite;
    }
    @keyframes blinkingText{
      0%{   color: #000;  }
      49%{  color: transparent; }
      50%{  color: transparent; }
      99%{  color:transparent;  }
      100%{ color: #000;  }
    }
   /* .breadcrumb{
      background-color:#bcddff !important;
      color:#000 !important;
    }
    .breadcrumb li a{
      color:#000 !important;
    }
    .breadcrumb li{
      color:#000 !important;
    }
    .breadcrumb>li+li:before{
      color:#000 !important;
    }*/
    .dropdown-main{
      max-height:150px !important;
      overflow-y:auto !important;
    }
</style>
<!-- END META SECTION -->
<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<link rel="stylesheet" type="text/css" href="css/social-buttons.css"/>
<style>

#main_wrapper{
    background: url('images/ap1.png') #FFFFFF;
}

#main_content{
    background-color: #000000;
    height: 5em;
    width: 5em;
}
.view_grid{
  margin: 0 13%;
}
@media only screen and (max-width: 980px) {
  .view_grid{
    margin: 0;
  }
}
.modal-backdrop{   z-index: auto !important; }
.nonadv_list{padding-top:0px !important;}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
 <link rel="stylesheet" href="css/jquery.dropdown.css">
<!-- EOF CSS INCLUDE -->
</head>
<body>
    <div id="load_page" style='display:block;padding:12% 40%;'></div>
    <!-- START PAGE CONTAINER -->
    <div class="page-container page-navigation-toggled page-container-wide page-navigation-top-fixed">

        <!-- START PAGE SIDEBAR -->
        <div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide mCS_no_scrollbar mCS_disabled">
            <!-- START X-NAVIGATION -->
            <? include 'lib/app_left_panel.php';?>
            <!-- END X-NAVIGATION -->
        </div>
        <!-- END PAGE SIDEBAR -->

        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
            <? include "lib/app_header.php"; ?>
            <!-- END X-NAVIGATION VERTICAL -->

            <!-- START BREADCRUMB -->
            <ul class="breadcrumb" >
                <li><a href="home.php">Home</a></li>
                <li class="active">Purchase Order Track</li>
                 <li> 
                  
                  <select name='select_sec' id='select_sec'  onchange="showboard(this.value)">
                  
                    <option value='0'>CHOOSE THE SECTION</option>
                    <option value='all'>ALL</option>
                    <? 
                    $sql = select_query_json("select sec.seccode,sec.secname from order_tracking_detail tdet,section sec 
                                              where tdet.seccode=sec.seccode and tdet.deleted='N' group by sec.seccode,sec.secname,sec.secsrno 
                                              order by sec.secsrno,sec.seccode,sec.secname", "Centra", "TEST");
                    foreach($sql as $key=>$secval){
                   ?>
                    <option value='<?=$secval['SECCODE']?>'><?=$secval['SECNAME']?></option>
          <?}?>                    

                </select>  </li>
                <li class="xn-search">
                  
                        <input type="text" name="ord_search" id="ord_search" placeholder="Search Order No."/>
            
            </li>
            </ul>
            <!-- END BREADCRUMB -->
             <span class="pull-right red_clr" id="valueinlacs" style="font-size: 12px; padding-top: 8px; padding-left: 2px;padding-right:10px"> </span>
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">

        <span id="selection_board">
                <!-- START CONTENT FRAME BODY -->
                <div class="content-frame-body" style="margin-left:0px;">



                    <div class="list_view push-up-12" id='id_monitor_board'>

                      <? $sql=select_query_json("select (select COUNT(*) from order_tracking_detail where ZNECODE>0 and ZNESTAT='N' and DELETED='N') totcount,(select count(ZNESTAT) from order_tracking_detail where ZNESTAT='N'  and DELETED='N') tot, ord.PROSTAT,ord.SUPCODE,ord.PORNUMB,ord.PORQTY,ord.PORYEAR,ord.ZNECODE,ord.ZNPSRNO,ord.REMARKS,ord.EMPSRNO, to_char(ord.PORDATE,'dd-MM-yyyy') PORDATE,to_char(ord.POREDDT,'dd-MM-yyyy') POREDDT,to_char(ord.ADDDATE,'dd-MM-yyyy') ADDDATE1, to_char(ord.POREDDT,'dd-MM-yyyy HH:mi:ss AM') POREDDT,ord.PORVAL,sup.SUPNAME,city.CTYNAME,emp.empname,emp.empcode from supplier sup,city city,order_tracking_detail ord,employee_office emp where  ord.ZNESTAT='N' and emp.empsrno=ord.empsrno and sup.SUPCODE=ord.supcode and sup.CTYCODE=city.CTYCODE  and ord.DELETED='N' order by ord.ADDDATE asc", "Centra", "TEST");
                       $sql_process=array();
                       foreach($sql as $key => $process)
                      {
                          $sql_process[$process['ZNECODE']][] = $process;
                      }
                     
                       $sql_ord_confirm = select_query_json("select mas.ZNECODE,mas.ADDUSER,mas.ZNCSRNO,sum(mas.ZNEDAYS) ZNEDAYS,mas.ZNENAME from order_tracking_master mas where mas.ZNEMODE='R' and mas.DELETED='N' group by(mas.ZNECODE,mas.ADDUSER,mas.ZNCSRNO,mas.ZNENAME) order by mas.ZNCSRNO asc", "Centra", "TEST");
                       //  $seltotal=select_query_json("select COUNT(*) totcount from order_tracking_detail where ZNECODE>0 and ZNESTAT='N' and DELETED='N'", "Centra", "TEST");
                          $tme = 0; $ttl_prscnt = '';$perc='';
                          foreach ($sql_ord_confirm as $key => $ord_confirm_value) { $tme++; 
                            $count=0;$sum=0;$subcount=0;
                            for($i=0;$i<count($sql_process[$ord_confirm_value['ZNECODE']]);$i++){
                              
                              $sum+=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORVAL'];
                            }
                           $count= $sql_process[$ord_confirm_value['ZNECODE']][0]['TOTCOUNT'];
                            $subcount=count($sql_process[$ord_confirm_value['ZNECODE']]);
                             
                           $perc=round((count($sql_process[$ord_confirm_value['ZNECODE']])/$count)*100);
                           $totalval=number_format(($sum/100000),2);
                            ?>
                            <div class="nonadv_list" style="height:auto !important">
                                <h3 class="text-center" style="color:#000;font-size: 16px;margin-top:0px;padding-top:5px;">
                                    <span class="pull-left" style="color:#000;border:1px solid #000;font-size: 16px;border-radius:30px;padding:5px;padding:bottom:0px;min-width:50px;margin-left:5px"> <?=$perc.' %'?></span> 
                                    <?=$ord_confirm_value['ZNENAME']?> 
                                    <span class="pull-right" style="color:#000;border:1px solid #000;font-size: 16px;border-radius:30px;padding:5px;padding:bottom:0px;min-width:50px;margin-right:5px"> <?php if($subcount>0){ echo $subcount;}else{echo '0';}?></span><br><br>
                                   <span class="pull-left" style="font-size: 14px; padding-top: 8px; padding-left: 2px;font-weight:bold;margin-left:5px; color: #FF0000;"> Due Days : <?=$ord_confirm_value['ZNEDAYS'].' days'?> </span>
                                   <span class="pull-right" style="font-size: 14px; padding-top: 8px; padding-right: 2px;font-weight:bold;margin-right:5px; color: #002eff;"> Total Value : <?=$totalval.' L'?></span>
                                </h3>

                              
                                 <? /* <div id="list_head" style="margin:0;color:#000;font-size:12px !important;">
                                      <div class="row" style="margin:0">
                                        <div class="col-md-12" style="padding:5px 0px;text-align:center">                                       
                                        
                                          <div class="col-md-3">
                                              <label >Order</label>
                                          </div><!-- duration -->
                                          <div class="col-md-5">
                                              <label>Supplier</label>
                                          </div><!-- rate -->
                                          
                                          
                                          <div class="col-md-2">
                                              <label >Qty</label>
                                          </div><!-- CGST -->
                                          <div class="col-md-2">
                                              <label >Val</label>
                                          </div><!-- SGST -->
                                       </div>
                                      
                                      </div> 

                                    </div> */ ?> 

                <div class='tasks table' style="overflow-y:auto;margin:0;padding:5px">
                   <?php 
                        $t=0;
                                      for($i=0;$i<count($sql_process[$ord_confirm_value['ZNECODE']]);$i++){
                                            
                                            $sum+=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORVAL'];
                                            $t=$t+1;
                                       if($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']==$_SESSION['tcs_empsrno']){
                                        $status=1;
                                       }else{
                                        $status=0;
                                       }
                        ?>
                    
                     <div class='task-item hover-box' id='task_item<?=$tme.$t?>'>
                     <div style="height:auto;cursor:pointer" id="order<?=$tme.$t?>" onclick="openprocess('<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["ZNPSRNO"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORYEAR"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORNUMB"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORDATE']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["SUPCODE"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORQTY']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORVAL']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']?>','<?=$status?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['SUPNAME']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['CTYNAME']?>')">
                          <div class="row" style="border:1px solid #ccc;margin:0;font-size:12px !important;max-width:100%;cursor:pointer">
                                    <div class="col-md-12"  style="padding-top:5px;padding-left:0;padding-right:0;height:auto;max-width:100%">
                                        <div class="form-group">
                                         <!--<div class="col-md-1">
                                             <span id='dropdown<?=$tme.$t?>' style="font=size:12px;font-weight:bold;color:#ffebcc;border:1px solid #000;cursor:pointer !important;padding:2px 5px;background-color:#29a329"  onclick="selecttoggle('<?=$tme.$t?>')"> + </span>
                                          </div><!-- duration -->
                                          
                                          <div class="col-md-12" style="font-size: 14px; padding-top: 8px; padding-right: 2px;font-weight:bold;margin-right:5px; color: #002eff;">
                                              Order No : <?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORYEAR']?> / <?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORNUMB']?> [ <?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORDATE']?> ]
                                          </div>
                                           <div style="clear: both;"></div>
                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              <?php 
                                               // echo $sql_val['ADDDATE1'];
                                                $addeddate=date_create($sql_process[$ord_confirm_value['ZNECODE']][$i]['ADDDATE1']);
                                                          $currentdate = strtoupper(date('d-m-Y'));                                      
                                                           $curdate=date_create($currentdate);
                                                           $diff=date_diff($addeddate,$curdate);
                                                           //$v= $diff->d;
                                                           $v= $diff->format("%a");
                                                 $percent=round(0.3*$ord_confirm_value['ZNEDAYS']);
                                              // echo $v;
                                                 // echo $duedate=strtoupper(date('d-m-Y h:i:s A'),strtotime('+'.$ord_confirm_value['ZNEDAYS'].' days',$adddate));
                                                 // $due=date_create($duedate);
                                                 // $diff=date_diff($curdate,$due);
                                                
                                              if($v < $ord_confirm_value['ZNEDAYS']){
                                                  if(($ord_confirm_value['ZNEDAYS']-$v)<=$percent){
                                                    $class='warning';
                                                  }else{
                                                    $class='success';
                                                  }
                                                ?>
                                                  <Label  class='label label-<?=$class?>'>Due Days Expires in : <?= ($ord_confirm_value['ZNEDAYS']-$v) ?> days </label>
                                              <? } else { ?>
                                                  <label  class='label label-danger'>Due Days Expired : <?=$v-$ord_confirm_value['ZNEDAYS']?> days before </label>
                                              <? } 

                                              ?>
                                               
                                          </div>
                                          <div style="clear: both;"></div>
                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              Supplier : <?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['SUPCODE']." - ".$sql_process[$ord_confirm_value['ZNECODE']][$i]['SUPNAME'].", ".$sql_process[$ord_confirm_value['ZNECODE']][$i]['CTYNAME']?>
                                          </div><!-- rate -->
                                          <div style="clear: both;"></div>                                         
                                         
                                          <div class="col-md-6" style="padding: 5px 10px; padding-left: 10px;font-weight:bold;color: #FF0000;">
                                              Qty : <?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORQTY']?>
                                          </div><!-- CGST -->
                                          <div class="col-md-6" style="padding:0; text-align: right; padding-right: 10px; padding: 5px 10px; padding-left: 2px;font-weight:bold;color: #FF0000;">
                                              Val : <?=number_format(($sql_process[$ord_confirm_value['ZNECODE']][$i]['PORVAL']/100000),2);?> L
                                          </div><!-- SGST -->
                                          <div style="clear: both;"></div>
                      <div class="col-md-12" style="padding: 5px 10px;">
                                              <?php                                                
                                                  $addeddate=date_create($sql_process[$ord_confirm_value['ZNECODE']][$i]['ADDDATE1']);
                                   $proc=date('d-m-Y',strtotime($sql_process[$ord_confirm_value['ZNECODE']][$i]['ADDDATE1']. '+'.$ord_confirm_value['ZNEDAYS'].' day'));?>
                                                  <span class="pull-left blink" style='color:#002eff;font-weight:bold;'>Process Due Date: <?=$proc?></span>
                                                
                                          </div>
                                          <div style="clear: both;"></div>
                      <div class="col-md-12" style="padding: 5px 10px;">
                                                                                              
                          <span class="pull-left" style='color:#002eff;font-weight:bold;'>Order Due Date: <?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['POREDDT']?></span>      

                          <span class="pull-right" style='color:#002eff;font-weight:bold;' onclick="getemployee(event,'<?=$tme.$t?>','<?=$ord_confirm_value['ZNECODE']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["ZNPSRNO"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORYEAR"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORNUMB"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORDATE']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["SUPCODE"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORQTY']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORVAL']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['SUPNAME']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['CTYNAME']?>')">@</span>                    
                                          </div>
                                         
                      <div style="clear: both;"></div>


                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              <span class="pull-right" style='color:#FF0000'>Responsible Person: <?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPCODE']?> - <?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPNAME']?></span>
                                          </div>
                                          <div style="clear: both;"></div>
                                       </div>
                                      
                                    </div>
                  
                        </div> 
            <div style="padding-bottom:5px; background-color: #d8d8d8; min-height: 45px;">
              <div class="row" style="margin:0;padding:0;padding-top:5px">
             
              <div class="col-md-12" style="text-align:center">
              <? 
              $blink1='';$blink2='';$blink3='';
              if($sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']==1 and ($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']==$_SESSION['tcs_empsrno'])){
                $blink1='blink';                
              }else if($sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']==2 and ($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']==$_SESSION['tcs_empsrno'])){
                 $blink2='blink'; 
              }
              else if($sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']==3 and ($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']==$_SESSION['tcs_empsrno'])){
                 $blink3='blink'; 
              }?>
             <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process1<?=$tme.$t?>' onclick="updateprocess(event,'process1<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value['ZNECODE']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["ZNPSRNO"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORYEAR"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORNUMB"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORDATE']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["SUPCODE"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORQTY']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORVAL']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']?>','<?=$status?>')"  <?if($sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']==1 and ($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?> <?if($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value="1"/> <label class="<?=$blink1?> label label-danger" style="margin: 2px;">UPDATE</label>
              <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process2<?=$tme.$t?>' onclick="updateprocess(event,'process2<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["ZNPSRNO"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORYEAR"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORNUMB"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORDATE']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["SUPCODE"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORQTY']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORVAL']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']?>','<?=$status?>')"  <?if($sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']==2 and ($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?> <?if($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value='2'/> <label class="<?=$blink2?> label label-warning" style="margin: 2px;">PROCESS</label>
             <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process3<?=$tme.$t?>' onclick="updateprocess(event,'process1<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value['ZNECODE']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["ZNPSRNO"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORYEAR"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["PORNUMB"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORDATE']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]["SUPCODE"]?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORQTY']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PORVAL']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']?>','<?=$sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']?>','<?=$status?>')"  <?if($sql_process[$ord_confirm_value['ZNECODE']][$i]['PROSTAT']==3 and ($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?> <?if($sql_process[$ord_confirm_value['ZNECODE']][$i]['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value='3'/>  <label class="<?=$blink3?> label label-success" style="margin: 2px;">COMPLETED</label>
              </div>
              
              
              </div></div>
              
              </div>
                      
                  </div><?}?>
                    
                </div>
                
                </div>
                               
                           
                      <? } ?>
                  </div>
              </div>
      </span>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->
<script>

   function blinkFont(id)
 {
   document.getElementById(id).style.display="none"
   setTimeout("setblinkFont(id)",1000)
 }

 function setblinkFont(id)
 {
   document.getElementById(id).style.display="block"
   setTimeout("blinkFont()",1000)
 }


    // To find and fix the height dynamically for Footer scroll bar 
    var window_hght = (window.innerHeight);
    // alert("height:"+window_hght);
    $('.content-frame-body').height(window_hght); // This is the class to fix the window height
    // To find and fix the height dynamically for Footer scroll bar 
 
function selecttoggle(v){
  
  if($('#dropsec'+v).css('display')=='none')
  {
    $('#dropdown'+v).css('background-color','#cc3300');
    $('#dropdown'+v).html('-');
//$('#orderhistory'+v).css('display','none');

  }else{
    $('#dropdown'+v).css('background-color','#29a329');
    $('#dropdown'+v).html('+');
  }
    
  $('#dropsec'+v).toggle();
  
  
}
function showboard(val){
  //alert(val);
console.log(val);
  $('#load_page').show();
  $.ajax({
                url:"ajax/ajax_load_purchase_order.php",
                type: "POST",
                data: {selectval:val,
                  action:'section_load'},
                
                dataType:"html",
                success:function(data){
                 console.log(data);
                 $('#selection_board').html(data);
                 $('#load_page').hide();
                  blink(); 
                  hover_box();
                   //location.reload();
                   //window.location.reload();
                }
            });
  
}

function submitform(frm_name,id,sta,znepcde){
  //alert(id);
  
  var val=$('#znedays'+id).val();
  
  //alert(val);
  if(val.trim('')!=""){
  $('#load_page').show();
   var form_data = new FormData(document.getElementById(frm_name));
  form_data.append('action','finish');
  form_data.append('znepcde',znepcde);
  form_data.append('id', id);
  form_data.append('sta', sta);

            $.ajax({
                url:"ajax/ajax_purchase_order.php",
                type: "POST",
                data: form_data,
                processData: false,
                contentType: false,
                async:true,
                dataType:"html",
                success:function(data){
                location.reload();
            
//$('#load_page').hide();
                   console.log(data);
                   
                   //window.location.reload();
                }
                
            });
}
else{
  alert("Please Enter Required Days");
  return false;
}
}
function submitform1(frm_name,id,sta,znepcde){
  //alert(id);
  console.log(id);
  console.log($('#orderstatus'+id+1).val());
  //$('#load_page').show();
  var flag=0; var count=0;
  var length=$('#count'+id).val();
if(sta!=1){
 for(var i=1;i<sta;i++){
  var value=$('#orderstatus'+id+i).val();
  console.log(value);
     if(value=='N' || value=='T'){
      count=count+1;
      
     }

}
 
}


console.log(count);
if(count==0){  
  $('#load_page').show();
   var form_data = new FormData(document.getElementById(frm_name));
  form_data.append('action','finish');
  form_data.append('znepcde',znepcde);
  form_data.append('id', id);
form_data.append('sta', sta);
            $.ajax({
                url:"ajax/ajax_purchase_order.php",
                type: "POST",
                data: form_data,
                processData: false,
                contentType: false,
                async:true,
                dataType:"html",
                success:function(data){                
            
                  //$('#load_page').hide();
                  // window.location.href('purchase_order_track.php');
                   // console.log(data);
                    location.reload();
                   //window.location.reload();
                }
                
            });

        }else{
          alert("please complete the previous stages first!");
          return false;
        }
}
$(document).ajaxStop(function(){
    window.location.reload();
});
function updateform(frm_name,act,id,sta,znepcde){
  $('#load_page').show();
var form_data = new FormData(document.getElementById(frm_name));
form_data.append('action', act);
form_data.append('znepcde', znepcde);
form_data.append('id', id);
form_data.append('sta', sta);
console.log(form_data);
            $.ajax({
                url:"ajax/ajax_purchase_order.php",
                type: "POST",
                data: form_data,
                processData: false,
                contentType: false,
                async:true,
                dataType:"html",
                success:function(data){
                
                    console.log("entered");
//                  $('#load_page').hide();
                    console.log(data);
                   location.reload();
                   //window.location.reload();
                }
            });
}

function openprocess(id,znecode,znepcde,poryear,pornumb,pordate,supcode,porqty,porval,prostat,empsrno,status,supname,ctyname){
$('#load_page').show();
  var flag=1;
//  var isChecked1 = $('#process1'+id).prop('checked');
//  var isChecked2 = $('#process2'+id).prop('checked');
//  var isChecked3 = $('#process3'+id).prop('checked');
// if(status==1){
//  if(isChecked1){
//  var proval=$('#process1'+id).val();
//  flag=1;
//  //alert(proval);
//  }
//  else if(isChecked2){
//  var proval=$('#process2'+id).val();
//  flag=1;
//  //alert(proval);
//  }
//  else if(isChecked3){
//  var proval=$('#process3'+id).val();
//  flag=1;
//  //alert(proval);
//  }
// }else{
//   flag=1;
// }
  if(flag==1){
  $.ajax({
                url:"ajax/ajax_purchase_order_process.php",
                type: "POST",
                data: {id:id,
                  action:'showprocess',
                  znecode:znecode,
                  znepcde:znepcde,
                  poryear:poryear,
                  pordate:pordate,
                  pornumb:pornumb,
                  supcode:supcode,
                  porqty:porqty,
                  porval:porval,
                  prostat:prostat,
                  empsrno:empsrno,
                  supname:supname,
                  ctyname:ctyname},
                
                dataType:"html",
                success:function(data){
                   $('#load_page').hide();
                 console.log(data);
                  $('#modal-bodyshowProcess').html(data);
                  $('#modal-bodyshowProcess').modal();
                
                   
                   //location.reload();
                   //window.location.reload();
                }
            });
  }
}
function updateprocess(e,process,id,znecode,znepcde,poryear,pornumb,pordate,supcode,porqty,porval,prostat,empsrno,status){
 // alert('hi');
  e.stopPropagation();
$('#load_page').show();
  var flag=1;var proval='';

  proval=$('#'+process).val();
 console.log(proval);
  if(flag==1){
  $.ajax({
                url:"ajax/ajax_purchase_order.php",
                type: "POST",
                data: {id:id,
                  action:'updateprocess',
                  znecode:znecode,
                  znepcde:znepcde,
                  poryear:poryear,
                  pordate:pordate,
                  pornumb:pornumb,
                  supcode:supcode,
                  porqty:porqty,
                  porval:porval,
                  prostat:proval,
                  empsrno:empsrno},
                
                dataType:"html",
                success:function(data){
                $('#load_page').hide();
                 console.log(data);
               
                 blink();
                
                   
                 location.reload();
                   //window.location.reload();
                }
            });
  }
}
function getemployee(event,id,znecode,znepcde,poryear,pornumb,pordate,supcode,porqty,porval,prostat,empsrno,supname,ctyname){
  $(".chosn").customselect();
event.stopPropagation();
$.ajax({
                url:"ajax/ajax_purchase_order_process.php",
                type: "POST",
                data: {
                  action:'showemployee',
                   branch:'888',
                   id:id,                  
                    znecode:znecode,
                    znepcde:znepcde,
                    poryear:poryear,
                    pordate:pordate,
                    pornumb:pornumb,
                    supcode:supcode,
                    porqty:porqty,
                    porval:porval,
                    prostat:prostat,
                    empsrno:empsrno,
                    supname:supname,
                    ctyname:ctyname   },
                
                dataType:"html",
                success:function(data){
                 // $('#load_page').hide();
                  $.getScript("js/jquery-customselect.js");

                 console.log(data);

                  $('#myModal_showEmployee').html(data);
                  $('#myModal_showEmployee').modal();
                 
                  $('#modal-bodyshowProcess').hide();
                    $("#expslist").removeClass("custom-select");
                   $("#expslist").removeClass("chosn");
                 $('.multiselect').dropdown({
  // options here
});
                  
                   
                   //location.reload();
                   //window.location.reload();
                }
            });


}
function sendmail(frm_name){

var form_data = new FormData(document.getElementById(frm_name));
//alert(form_data);
form_data.append('action','sendmail');
$.ajax({
                url:"ajax/ajax_purchase_order.php",
                type: "POST",
                data: form_data,  
                processData: false,
                contentType: false,
                async:true,              
                dataType:"html",
                success:function(data){

                 console.log(data);
                 alert('Notification Sent Successfully');
                 //$('#load_page').hide();
                   
                  // location.reload();
                   //window.location.reload();
                }
            });


}
</script>
    <? include "lib/app_footer.php"; ?>

    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>
<div class="modal fade" id="modal-bodyshowProcess" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  
</div>
    <!-- Show Modal process -->
    <div id="myModal_showProcess" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
               
                <div class="modal-body" id="modal-bodyshowProcess"></div>
            </div>
        </div>
    </div>

    <!-- Show History -->
    <div id="myModal_showHistory" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
               
                <div class="modal-body" id="modal-bodyshowHistory" style="padding: 0px 0px 10px 0px;font-size:12px!important"></div>
            </div>
        </div>
    </div>
    <!-- Show History -->
 <!-- Show Emploee numbers-->
    <div id="myModal_showEmployee" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       
    </div>
  
    <!-- Show History -->
    <!-- Show Modal Windows -->

    <!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
    <script type="text/javascript" src="js/plugins/moment.min.js"></script>
    <script type="text/javascript" src="js/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>
    <script type="text/javascript" src="js/task.js"></script>

    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>

    <link href="css/ekko-lightbox.css" rel="stylesheet">
    <!-- yea, yea, not a cdn, i know -->
    <script src="js/ekko-lightbox-min.js"></script>
    <link href="css/lightgallery.css" rel="stylesheet">
    <script src="js/picturefill.min.js"></script>
    <script src="js/lightgallery.js"></script>
    <script src="js/lg-fullscreen.js"></script>
    <script src="js/lg-thumbnail.js"></script>
    <script src="js/lg-video.js"></script>
    <script src="js/lg-autoplay.js"></script>
    <script src="js/lg-zoom.js"></script>
    <script src="js/lg-hash.js"></script>
    <script src="js/lg-pager.js"></script>
    
    <link rel="stylesheet" href="css/flavor-lightbox.css">
    <script src="js/jquery.flavor.js"></script>
    <script src="js/script.js"></script>
    <script type="text/javascript" src="js/jquery-customselect.js"></script>
   
 
  <script src="js/jquery.dropdown.js"></script>
    <script type="text/javascript">
        $(document).ready(function ($) {
    
   $('.multiselect').dropdown({
  // options here
});
    $('.blink').each(function() {
    var elem = $(this);
    setInterval(function() {
        if (elem.css('visibility') == 'hidden') {
            elem.css('visibility', 'visible');
        } else {
            elem.css('visibility', 'hidden');
        }    
    }, 500);
});
    
    $(".reqdays").keydown(function (e) {
   //alert(e);
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    
          $('#ord_search').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                url:"ajax/ajax_load_purchase_order.php",
                type: "POST",
                data: {selectval:request.term,
                      action:'order_search'},
                
                dataType:"html",
                success:function(data){
                 console.log(data);
                 $('#selection_board').html(data);
                 $('#load_page').hide();
         blink();
                 hover_box();
                   // location.reload();
                   //window.location.reload();
                }
            });
                },
                autoFocus: true,
                minLength: 4
            });

          // $("#ord_search").keyup(function(){
          //   var val=$('#ord_search').val();

          //     $.ajax({
          //       url:"ajax/ajax_load_purchase_order.php",
          //       type: "POST",
          //       data: {selectval:val,
          //             action:'order_search'},
                
          //       dataType:"html",
          //       success:function(data){
          //        console.log(data);
          //        $('#selection_board').html(data);
          //        $('#load_page').hide();
                   
          //          // location.reload();
          //          //window.location.reload();
          //       }
          //   });

          // });               
               
           // blinkFont();
       //$('#valueinlacs').html('Value in Lacs');
          
            $('#txt_empcode').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        type: 'post',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == '0') {
                                $('#txt_empcode').val('');
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
            $("#load_page").fadeOut("slow");

            // Search the Box
            $('#txt_search').keyup(function(){
                $.each($('#id_monitor_board').find('div'), function(){
                    //alert('ga');
                    if($(this).text().toLowerCase().indexOf($('#txt_search').val()) == -1){
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
            // Search the Box
       

        $('.ui-sortable').sortable({
            items: '> :not(.nodragorsort)'
        });

        $('#datepicker-alterdate').Zebra_DatePicker({
            format: 'd-m-Y',
            direction: true
        });
 });
 
 
 function blink(){
   $('.blink').each(function() {
    var elem = $(this);
    setInterval(function() {
        if (elem.css('visibility') == 'hidden') {
            elem.css('visibility', 'visible');
        } else {
            elem.css('visibility', 'hidden');
        }    
    }, 500);
});
 }
       
        
       
        function showHistory(supcode,poryear,pornumb,pordate,id) {

          $('#orderhistory'+id).toggle();
          // console.log(supcode);
          //   $('#load_page').show();
          //   var sendurl = "ajax/ajax_purchase_order_history1.php?action=showHistory&pornumb="+pornumb+"&poryear="+poryear+"&supcode="+supcode+"&pordate="+pordate;
          //   $.ajax({
          //       url:sendurl,
          //       success:function(data){
          //         console.log("enter");
          //         console.log(data);
          //           $("#myModal_showHistory").modal('show');
          //           $('#load_page').hide();
          //           document.getElementById('modal-bodyshowHistory').innerHTML = data;
          //           $('#load_page').hide();
          //           // $('.lightgallery').lightGallery();

          //       }
          //   });
        }

        
    function hover_box(){
  
       $('.hover-box').mouseover(function (){
       
        $(this).css('box-shadow','0 0 7px black');
       });
       $('.hover-box').mouseout(function (){
        $(this).css('box-shadow','none');
       });
       $('.hover-box-def').mouseover(function (){
        $(this).css('box-shadow','0 0 10px black');
       });
       $('.hover-box-def').mouseout(function (){
        $(this).css('box-shadow','0 0 7px black');
       });
     }
     hover_box();
   
   function selectedfile(e,id){
alert('hi');     
 var input = document.getElementById('upload'+id);
alert(input);
  
    for(var i=0;i<input.files.length;i++){
      var count=i+1;
    console.log(input.files[i].name);
    $('#files'+id).append('<p style="margin-bottom:0;font-size:10px">'+count+'.' +input.files[i].name+'</p>');
  }  
}
function setremarks(val,id){

    $('#remarks'+id).val(val);
  
  
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
 

    </script>
    <!-- END TEMPLATE -->
<!-- END SCRIPTS -->
</body>
</html>
