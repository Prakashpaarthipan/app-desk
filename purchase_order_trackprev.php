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
// echo "**".$_SESSION['tlu_emp_prhdcod']."**";
// echo "........".$_SESSION['tcs_tlumenu_prev_prscode'].".......".$_SESSION['tcs_tlumenu_prscode']."........";


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
    	color:#fff !important;
    }
     .table>thead>tr>th{
    	
    	background:#003e7d !important ;
    	color:#fff !important;
   
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
      width:500px;
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
    .breadcrumb{
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
                 	
                 	<select name='select_sec' id='select_sec' style="height:40px" onchange="showboard(this.value)">
                  
                    <option value='0'>CHOOSE THE SECTION</option>
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
                  
                        <input type="text" style="height:40px" name="ord_search" id="ord_search" placeholder="Search Order No."/>
            
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

                      <? $sql_ord_confirm = select_query_json("select mas.ZNECODE,mas.ADDUSER,mas.ZNCSRNO,sum(mas.ZNEDAYS) ZNEDAYS,mas.ZNENAME from order_tracking_master mas where  (mas.EMPSRNO='".$_SESSION['tcs_empsrno']."' or mas.ALTSRNO='".$_SESSION['tcs_empsrno']."') and mas.ZNEMODE='R' and mas.DELETED='N' group by(mas.ZNECODE,mas.ADDUSER,mas.ZNCSRNO,mas.ZNENAME) order by mas.ZNCSRNO asc", "Centra", "TEST");
                         $seltotal=select_query_json("select COUNT(*) totcount from order_tracking_detail where ZNECODE>0 and ZNESTAT='N' and DELETED='N'", "Centra", "TEST");
                          $tme = 0; $ttl_prscnt = '';
                          foreach ($sql_ord_confirm as $key => $ord_confirm_value) { $tme++; 
                            $sql=select_query_json("select (select count(ZNESTAT) from order_tracking_detail where ZNECODE='".$ord_confirm_value['ZNECODE']."' and  ZNESTAT='N'  and DELETED='N') tot, (select sum(PORVAL) from order_tracking_detail where ZNECODE='".$ord_confirm_value['ZNECODE']."' and ZNESTAT='N' and DELETED='N') sum, SUPCODE,PORNUMB,PORQTY,PORYEAR,ZNECODE,ZNPSRNO,REMARKS,EMPSRNO, to_char(PORDATE,'dd-MM-yyyy') PORDATE,to_char(ADDDATE,'dd-MM-yyyy') ADDDATE1, to_char(POREDDT,'dd-MM-yyyy HH:mi:ss AM') POREDDT,PORVAL from order_tracking_detail where ZNECODE='".$ord_confirm_value['ZNECODE']."' and ZNESTAT='N'  and DELETED='N' order by ADDDATE asc", "Centra", "TEST");
                             
                            $perc=round(($sql[0]['TOT']/$seltotal[0]['TOTCOUNT'])*100);
                            $totalval=number_format(($sql[0]['SUM']/100000),2);
                            ?>
                            <div class="nonadv_list">
                             
                                <h3 class="text-center" style="color:#fff;margin-top:0px;padding-top:5px;background-color:#1caf9a"><span class="pull-left" style="color:#fff;background-color:#003e7d;border:1px solid #fff;border-radius:30px;padding:5px;padding-bottom:0px;margin-left:5px"> <?=$perc. ' %'?></span> <?=$ord_confirm_value['ZNENAME']?> <span class="pull-right" style="color:#fff;background-color:#003e7d;border:1px solid #fff;border-radius:30px;padding:5px;padding:bottom:0px;width:50px;margin-right:5px"> <?php if($sql[0]['TOT']>0){ echo $sql[0]['TOT'];}else{echo '0';}?></span><br><br>
                                   <span class="pull-left" style="font-size: 12px; padding-top: 8px; padding-left: 2px;font-weight:bold;margin-left:5px; "> Due Days : <?=$ord_confirm_value['ZNEDAYS'].' days'?> </span>
                                   <span class="pull-right" style="font-size: 14px; padding-top: 8px; padding-right: 2px;font-weight:bold;margin-right:5px; "> Total Value : <?=$totalval.' Lacs'?></span>
                                   
                                </h3>

                              
                                 <div id="list_head" style="background-color:#003e7d;margin:0;color:#fff;font-size:12px !important;">
                                      <div class="row" style="margin:0">
                                        <div class="col-md-12" style="padding:5px 0px;text-align:center">                                       
                                         
                                         <div class="col-md-1">
                                             <!--  <label >Sec</label> -->
                                          </div><!-- duration -->
                                          <div class="col-md-2">
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

                                    </div> 
                <div class='tasks table' style="overflow-y:auto;margin:0;padding:0">
                   <?php 
                        
                        $t=0;
                        foreach($sql as $key1 => $sql_val) {
                          $background='';
                          $t++;
                          
                          $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$sql_val['SUPCODE']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
                          

                          if (($t % 2) == 1){
                            $background='#bcddff';
                          }
                          else{
                           $background='#e6f2ff';
                          }
                         
                        ?>
                     <form name='purchase_form<?=$tme.$t?>'  id= 'purchase_form<?=$tme.$t?>' method='post' action=''> 
                     <div class='task-item' id='task_item<?=$tme.$t?>'>
                     
                          <div class="row" style="background-color:<?=$background?>;border:1px solid #ccc;margin:0;font-size:12px !important;max-width:100%">
                                    <div class="col-md-12"  style="padding-top:5px;padding-left:0;padding-right:0;max-height:100px; height:100px;max-width:100%">
                                        <div class="form-group">
                                         <div class="col-md-1">
                                             <span id='dropdown<?=$tme.$t?>' style="font=size:12px;font-weight:bold;color:#ffebcc;border:1px solid #000;cursor:pointer !important;padding:2px 5px;background-color:#29a329"  onclick="selecttoggle('<?=$tme.$t?>')"> + </span>
                                          </div><!-- duration -->
                                          
                                          <div class="col-md-2">
                                              <p style="text-align:left !important;margin-bottom:0"><?=$sql_val['PORYEAR']?> / <?=$sql_val['PORNUMB']?></p>
                                               <p style="text-align:left !important"><?=$sql_val['PORDATE']?></p>
                                          </div><!-- duration -->
                                          <div class="col-md-5">
                                              <p style="text-align:center !important;margin-bottom:0"><?=$sql_val['SUPCODE']?> - <?=$supname[0]['SUPNAME']?> </p><p><?=$supname[0]['CTYNAME']?></p>
                                          </div><!-- rate -->
                                         
                                         
                                          <div class="col-md-2" style="text-align:center">
                                              <p ><?=$sql_val['PORQTY']?></p>
                                          </div><!-- CGST -->
                                          <div class="col-md-2" style="padding:0">
                                              <p ><?=number_format(($sql_val['PORVAL']/100000),2);?></p>
                                          </div><!-- SGST -->
                                       </div><br><br><br>
                                       <div class='row' style="margin:0;padding:0">
                                        <?php 
                                     // echo $sql_val['ADDDATE1'];
                                       $addeddate=date_create($sql_val['ADDDATE1']);
                                      $currentdate = strtoupper(date('d-m-Y'));                                      
                                       $curdate=date_create($currentdate);
                                       $diff=date_diff($addeddate,$curdate);
                                       //$v= $diff->d;
                                       $v= $diff->format("%a");
                                    // echo $v;
                                       // echo $duedate=strtoupper(date('d-m-Y h:i:s A'),strtotime('+'.$ord_confirm_value['ZNEDAYS'].' days',$adddate));
                                       // $due=date_create($duedate);
                                       // $diff=date_diff($curdate,$due);
                                      
                                      
                                     
                                       if($v < $ord_confirm_value['ZNEDAYS']){?>
                                      <div class="col-md-4" style="margin-left:0"> <Label  class='label label-success'>Due Days Expires in : <?= ($ord_confirm_value['ZNEDAYS']-$v) ?> days </label></div>

                                      <? }
                                      else{?>
                                        <div class="col-md-4" style="margin-left:0"> <label  class='label label-danger'>Due Days Expired : <?=$v-$ord_confirm_value['ZNEDAYS']?> days before </label></div>
                                       
                                      <? }

                                       $empname=select_query_json("select EMPNAME,EMPCODE from employee_office where empsrno='".$sql_val['EMPSRNO']."'","Centra","TEST");                                     
                                      

                                      ?>
                                      <div class="col-md-8" style="padding-right:5px !important"><span class='pull-right' style='margin-right:5px;color:#ff0200'>Responsible Person: <?=$empname[0]['EMPCODE']?> - <?=$empname[0]['EMPNAME']?></span></div>
                                    </div>
                                    </div>
                        </div> 
                        <?if($ord_confirm_value['ZNECODE']=='1'){$sta=0;?>
                         <div class="row" id='dropsec<?=$tme.$t?>' style='display:none;max-width:100%;padding:0;margin:0'>
                              <div class="col-md-12" style="padding:0;">
                                        <table class='table table-hovered table-bordered tabledrop' style='border:1px solid #000;width:100%;table-layout:fixed'>
                                        <thead>
                                                                  <tr style="background-color:#bcddff !important">                                                                     
                                                                    <th style='text-align:center'>REQ. DAYS</th> 
                                                                   <!--  <th style='text-align:center'>ACTUAL DATE</th> --> 
                                                                    <th colspan='2' style='text-align:center'>REMARKS</th>                                                     
                                                                    <th style='text-align:center'>ATTACH FILES</th>
                                                                    <th style='text-align:center'>ACTION</th>  
                                                                 </tr>
                                         </thead>
                                         <tr>
                                        
                                                                           
                                        <td><input type='text' class='reqdays' name='znedays<?=$tme.$t.$sta?>' id='znedays<?=$tme.$t?>' maxlength="2" required style='text-transform: uppercase;width:100%'/> </td>
                                       <!--   <td></td> -->
                                        <td align='center' colspan='2'><textarea rows='2' name='remarks<?=$tme.$t.$sta?>' cols='15' style='width:100%'></textarea> </td>   
                                        <td align='center' id='files<?=$tme.$t?>'><label class="btn btn-primary btn-sm" >
								                Browse&hellip; <input type="file" name='upload<?=$tme.$t.$sta?>[]' onchange="selectedfile(event,'<?=$tme.$t?>')" multiple accept="image/jpg,image/jpeg,image/png,.pdf" id='upload<?=$tme.$t?>' style="display: none;">
								            </label></td>    
                                       <td align='center'><button type="button" class='btn btn-sm btn-primary' onclick='updateform("purchase_form<?=$tme.$t?>","update","<?=$tme.$t?>","<?=$sta?>","<?=$sql_val['ZNPSRNO']?>")' style='cursor:pointer;color:##fff' data-toggle="tooltip" data-placement="top" data-original-title="SUBMIT"><i class='fa fa-paper-plane' ></i></button> <button type="button" class='btn btn-sm btn-success' data-toggle="tooltip" data-placement="top" data-original-title="FINISH" onclick='submitform("purchase_form<?=$tme.$t?>","<?=$tme.$t?>","<?=$sta?>","<?=$sql_val['ZNPSRNO']?>")' style='cursor:pointer;color:#fff'><i class='fa fa-check' ></i></button></td> 
                                              
                                   </table>
                              </div>
                        </div> 

                        <input type='hidden' name='poryear' value='<?=$sql_val['PORYEAR']?>'/>
                         <input type='hidden' name='pornumb' value='<?=$sql_val['PORNUMB']?>'/>
                          <input type='hidden' name='ZNCSRNO' value='<?=$sql_val['ZNECODE']?>'/>
                           <input type='hidden' name='znepcode' value='<?=$sql_val['ZNPSRNO']?>'/>
                           <input type='hidden' name='remarks1' value='<?=$sql_val['REMARKS']?>'/>
                           <input type='hidden' name='supcode' value='<?=$sql_val['SUPCODE']?>'/>
                           <?}else{?>
                             <div class="row" id='dropsec<?=$tme.$t?>' style='display:none;max-width:100%;padding:0;margin:0'>
                              <div class="col-md-12" style="padding:0">
                                        <table class='table table-hovered table-bordered tabledrop' style='border:1px solid #000;width:100%'>
                                        <thead style="background-color:#000">
                                                                   <tr class="darkgrey" style="background-color:#000">              
                                                                   
                                                                    <th style='text-align:center'>PROCESS</th>
                                                                    <th style='text-align:center'>REMARKS</th>
                                                                    <th style='text-align:center'>ATTACH FILES</th>
                                                                    <th style='text-align:center'>ACTION</th>                                                      
                                                                 </tr>
                                         </thead>

                                         <? $sta=0;
                                         $selopt=select_query_json("select ord.ZNECODE,ord.ZNEPCDE,ord.ZNEPNME,tra.ZNECODE,tra.ZNESTAT from order_tracking_master ord,order_tracking_detail tra where tra.ZNECODE='".$ord_confirm_value['ZNECODE']."' and tra.PORNUMB='".$sql_val['PORNUMB']."' and tra.PORYEAR='".$sql_val['PORYEAR']."' and tra.SUPCODE='".$sql_val['SUPCODE']."' and  ord.ZNECODE=tra.ZNECODE and tra.ZNPSRNO=ord.ZNEPCDE and ord.ZNEMODE='R' and ord.DELETED='N' and tra.DELETED='N' and tra.ZNECODE=ord.ZNECODE and (tra.ZNESTAT='N' or tra.ZNESTAT='F' or tra.ZNESTAT='T' or tra.ZNESTAT='R') order by tra.ZNPSRNO asc", "Centra", "TEST");
                                          foreach($selopt as $key=>$seloptval){$sta=$sta+1; 
                                          	$selimg=select_query_json("select IMGLOCA from order_tracking_history tra where tra.ZNCSRNO='".$sql_val['ZNECODE']."' and tra.ZNPSRNO='".$sta."' and tra.PORNUMB='".$sql_val['PORNUMB']."' and tra.PORYEAR='".$sql_val['PORYEAR']."'  and tra.DELETED='N' ", "Centra", "TEST");
                                          	//echo "select MAX(tra.IMGLOCA) IMGLOCA from order_tracking_history tra where tra.ZNCSRNO='".$ord_confirm_value['ZNECODE']."' and tra.ZNPSRNO='".$ord_confirm_value['ZNPSRNO']."' and tra.PORNUMB='".$sql_val['PORNUMB']."' and tra.PORYEAR='".$sql_val['PORYEAR']."'  and tra.DELETED='N' ";

                                          	?>
                                       
                                        <tr>
                                        <td><?=$seloptval["ZNEPNME"]?></td>
                                       <td align='center'><textarea <?if($seloptval["ZNESTAT"]=='F') {?> disabled <?}?> rows='2' name='remarks<?=$tme.$t.$sta?>' onblur="setremarks(this.value,'<?=$tme.$t?>')" cols='15' style='width:100%'></textarea> </td>   
                                       <td align='center' id='files<?=$tme.$t.$sta?>' ><?if($seloptval["ZNESTAT"]=='F') {
                                        foreach($selimg as $key=>$selimg){
                                          $selected=array();
                                          $selected=explode(',',$selimg['IMGLOCA']);
                                       	for($i=0;$i<sizeof($selected)-1;$i++){
                                       	?><p><a href='ftp://ituser:S0ft@369@ftp1.thechennaisilks.com:5022/Order_tracking_detail/<?=$sql_val['PORYEAR']?>_<?=$sql_val['PORNUMB']?>/<?=$selected[$i]?>' target='_blank' type='button' title="view"><?php echo $selected[$i]; ?></a></p><?}}}


                                        else{?><label  class="btn btn-primary btn-sm">
								                Browse&hellip; <input <?if($seloptval["ZNESTAT"]=='F') {?> disabled <?}?> type="file" name='upload<?=$tme.$t.$sta?>[]' onchange="selectedfile(event,'<?=$tme.$t.$sta?>')" multiple accept="image/jpg,image/jpeg,image/png,.pdf" id='upload<?=$tme.$t.$sta?>' style="display: none;">
								            </label><?}?></td>   
                                        <td align='center'><button type="button" <?if($seloptval["ZNESTAT"]=='F') {?> disabled <?}?> class='btn btn-sm btn-primary' onclick='updateform("purchase_form<?=$tme.$t?>","update","<?=$tme.$t?>","<?=$sta?>","<?=$seloptval['ZNEPCDE']?>")' style='cursor:pointer;color:##fff' data-toggle="tooltip" data-placement="top" data-original-title="SUBMIT"><i class='fa fa-paper-plane' ></i></button> <button <?if($seloptval["ZNESTAT"]=='F') {?> disabled <?}?> class='btn btn-sm btn-success' data-toggle="tooltip" data-placement="top" data-original-title="FINISH" onclick='submitform1("purchase_form<?=$tme.$t?>","<?=$tme.$t?>","<?=$sta?>","<?=$seloptval['ZNEPCDE']?>")' style='cursor:pointer;color:#fff'><i class='fa fa-check' ></i></button></td>                     
                                        </tr> 
                                        <input type='hidden' id='orderstatus<?=$tme.$t.$sta?>' value="<?=$seloptval["ZNESTAT"]?>"/>
                                        <input type='hidden' id='count<?=$tme.$t?>' value="<?=$sta?>"/>
                                        <?}?>
                                             
                                   </table>

                                   <center><button class='btn btn-sm btn-warning' type='button' name='updatetrack' onclick='updateform("purchase_form<?=$tme.$t?>","revert","<?=$seloptval["ZNECODE"]?>","<?=$seloptval["ZNEPCDE"]?>","<?=$sta?>")'>REVERT</button>&nbsp;&nbsp;&nbsp;<a href='ftp://ituser:S0ft@369@tcstextile.in/purchase_Order_auto_pdf/purchase_order/po_<?=$sql_val['PORYEAR']?>_<?=$sql_val['PORNUMB']?>.pdf' target='_blank' type='button' title="view"><img style="max-height:20px;max-width:20px" src="images/pdflogo.jpg"/></a>&nbsp;&nbsp;&nbsp;<a type='button' title="history" style="height:40px;width:40px;cursor:pointer !important" onclick="showHistory('<?=$sql_val['SUPCODE']?>','<?=$sql_val['PORYEAR']?>','<?=$sql_val['PORNUMB']?>','<?=$sql_val['PORDATE']?>','<?=$tme.$t?>')"><span class="fa fa-history"></span></a> </center><br>


                              </div>
                              <div class="row" id="orderhistory<?=$tme.$t?>" style="padding:0;margin:0;font-size:10px !important;display:none">
              <div class="col-md-12" style="padding:0;margin:0">
                <div class="row" style="text-align: center; font-weight: bold; background-color: #fcc837; color: #000; line-height: 25px;padding:0;margin:0">
                  <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">#</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Process</div>  
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Process Stage</div>        
          <div class="col-md-3" style="padding:0;border:1px solid #FFF; min-height: 52px;">Start Date / Attachments</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">End Date</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Order Status / Remarks </div>
                  
                </div>
              </div>
              <div class="col-md-12" style="padding:0;margin:0">
                
                <? 
                
                $sql_employee_tlu = select_query_json("select to_char(ZNEADDT,'dd-Mon-yyyy HH:MI:SS AM') ZNEADDT,to_char(ZNEEDDT,'dd-Mon-yyyy HH:MI:SS AM') ZNEEDDT,ZNESTAT,ZNCSRNO,ZNPSRNO,IMGLOCA,REMARKS from order_tracking_history where PORNUMB='".$sql_val['PORNUMB']."' and PORYEAR='".$sql_val['PORYEAR']."' order by ENTSRNO desc","Centra","TEST");
                
                $empi = 0;
                foreach ($sql_employee_tlu as $key => $employee_tlu_value) { $empi++; 
                  
                  $supcode=select_query_json("select distinct(ord.SUPCODE) SUP,sup.SUPNAME,city.CTYNAME,mas.ZNENAME,mas.ZNEPNME from order_tracking_detail ord,order_tracking_master mas,supplier sup,city city where  ord.PORNUMB='".$sql_val['PORNUMB']."' and ord.PORYEAR='".$sql_val['PORYEAR']."' and sup.SUPCODE='".$sql_val['SUPCODE']."' and sup.CTYCODE=city.CTYCODE and mas.ZNECODE='".$employee_tlu_value['ZNCSRNO']."' and mas.ZNEMODE='R' and mas.DELETED='N' and ord.DELETED='N' and mas.ZNEPCDE='".$employee_tlu_value['ZNPSRNO']."'", "Centra", "TEST");
                  
                   // $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$_REQUEST['supcode']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
                   
                   // $prsname=select_query_json("select ZNENAME from order_tracking_master where ZNCSRNO='".$employee_tlu_value['ZNCSRNO']."' and ZNEMODE='R' and DELETED='N'", "Centra", "TEST");
                  
                    
                   ?>
                  <div class="row" style="background-color:#fcc8372e;padding:0;margin:0; line-height: 25px; text-align: center;">
                    <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?= $empi;?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$supcode[0]['ZNENAME']?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$supcode[0]['ZNEPNME']?></div>
              
                      <div class="col-md-3" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$employee_tlu_value['ZNEADDT']?><br>  <? $selected=array(); $selected=explode(',',$employee_tlu_value['IMGLOCA']);
                                        for($i=0;$i<sizeof($selected)-1;$i++){
                                        ?><a href='ftp://ituser:S0ft@369@ftp1.thechennaisilks.com:5022/Order_tracking_detail/<?=$sql_val['PORYEAR']?>_<?=$sql_val['PORNUMB']?>/<?=$selected[$i]?>' target='_blank' type='button' title="view"><?php echo $selected[$i]; ?></a><br><?}?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$employee_tlu_value['ZNEEDDT']?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?php if($employee_tlu_value['ZNESTAT']=='F') { echo "FINISHED";}else if($employee_tlu_value['ZNESTAT']=='R'){ echo "REVERTED";} else if($employee_tlu_value['ZNESTAT']=='U'){ echo "UPDATED";} ?> <p style="color:red"><?=$employee_tlu_value['REMARKS']?>  </p></div>
                    
                      
                           </div>                                        
                    <?  }

                    ?>
                  
                  
                      
              </div></div>
                        </div> 
                       
					                        <input type='hidden' name='poryear' value='<?=$sql_val['PORYEAR']?>'/>
                         <input type='hidden' name='pornumb' value='<?=$sql_val['PORNUMB']?>'/>
                          <input type='hidden' name='ZNCSRNO' value='<?=$sql_val['ZNECODE']?>'/>
                           <input type='hidden' name='znepcode' value='<?=$sql_val['ZNPSRNO']?>'/>
                           <input type='hidden' name='remarks1' id='remarks<?=$tme.$t?>' value='<?=$sql_val['REMARKS']?>'/>
                           <input type='hidden' name='supcode' value='<?=$sql_val['SUPCODE']?>'/>
                           <?}?>
                  </div></form><?}?>
                    
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
//   function blinkFont()
// {
//   document.getElementById("blink").style.display="none"
//   setTimeout("setblinkFont()",1000)
// }

// function setblinkFont()
// {
//   document.getElementById("blink").style.display="block"
//   setTimeout("blinkFont()",1000)
// }
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
                   
                   //location.reload();
                   //window.location.reload();
                }
            });
	
}
function selectedfile(e,id){  
 var input = document.getElementById('upload'+id);

  
    for(var i=0;i<input.files.length;i++){
    	var count=i+1;
    console.log(input.files[i].name);
    $('#files'+id).append('<p style="margin-bottom:0;font-size:10px">'+count+'.' +input.files[i].name+'</p>');
  }  
}
function setremarks(val,id){

    $('#remarks'+id).val(val);
  
  
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
                   // console.log(data);
                   
                   //window.location.reload();
                },
                complete:function(data){
                location.reload();
            
//$('#load_page').hide();
                   // console.log(data);
                   
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



</script>
    <? include "lib/app_footer.php"; ?>

    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>

    <!-- Show Modal Windows -->
    

    <!-- Show History -->
    <div id="myModal_showHistory" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
               
                <div class="modal-body" id="modal-bodyshowHistory" style="padding: 0px 0px 10px 0px;font-size:12px!important"></div>
            </div>
        </div>
    </div>
    <!-- Show History -->

  
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
    <script type="text/javascript">
        $(document).ready(function ($) {

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
           $(".reqdays").keydown(function (e) {
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
        });

        $('.ui-sortable').sortable({
            items: '> :not(.nodragorsort)'
        });

        $('#datepicker-alterdate').Zebra_DatePicker({
            format: 'd-m-Y',
            direction: true
        });

        function startTime(row, col, brncode, entry_year, entry_no, entry_srno, entry_sub_srno){
          $.ajax({
             url : 'ajax/ajax_timer.php?action=start_timer_update',
             type: 'post',
             dataType: "json",
             data: {
               step : row,
               step_end : col,
               id: entry_year+"-"+brncode+"-"+entry_no+"-"+entry_srno+"-"+entry_sub_srno
             },
             complete: function(){
                refresh_monitor_board();
                function pretty_time_string(num) {
                    return ( num < 10 ? "0" : "" ) + num;
                }

                var start = new Date;
                setInterval(function() {
                  var total_seconds = (new Date - start) / 1000;
                  var hours = Math.floor(total_seconds / 3600);
                  total_seconds = total_seconds % 3600;
                  var minutes = Math.floor(total_seconds / 60);
                  total_seconds = total_seconds % 60;
                  var seconds = Math.floor(total_seconds);
                  hours = pretty_time_string(hours);
                  minutes = pretty_time_string(minutes);
                  seconds = pretty_time_string(seconds);
                  var currentTimeString = hours + ":" + minutes + ":" + seconds;
                  if(currentTimeString >= '00:00:5'){
                      // $('#timer_'+row+'_'+col).addClass( "blink_me" );
                      // $('#timer_'+row+'_'+col).css('color' , 'red');
                   }
                  // $('#timer_'+row+'_'+col).val(currentTimeString);
               }, 1000);
             }
           });
         }

        function stopTime(row, col, id_time){
            $.ajax({
               url : 'ajax/ajax_timer.php?action=stop_timer_update',
               type: 'post',
               dataType: "json",
               data: {
                  id: id_time
               },
               complete: function(){
                  //$('#load_page').show();
                  refresh_monitor_board();
                  // $('#timer_'+row+'_'+col).val("currentTimeString");

                  function pretty_time_string(num) {
                      return ( num < 10 ? "0" : "" ) + num;
                  }

                  var start = new Date;
                  setInterval(function() {
                    var total_seconds = (new Date - start) / 1000;
                    var hours = Math.floor(total_seconds / 3600);
                    total_seconds = total_seconds % 3600;
                    var minutes = Math.floor(total_seconds / 60);
                    total_seconds = total_seconds % 60;
                    var seconds = Math.floor(total_seconds);
                    hours = pretty_time_string(hours);
                    minutes = pretty_time_string(minutes);
                    seconds = pretty_time_string(seconds);
                    var currentTimeString = hours + ":" + minutes + ":" + seconds;
                    if(currentTimeString >= '00:00:5'){
                        // $('#timer_'+row+'_'+col).addClass( "blink_me" );
                        // $('#timer_'+row+'_'+col).css('color' , 'red');
                     }
                    // $('#timer_'+row+'_'+col).val(currentTimeString);
                 }, 1000);
               }
            });
        }

        function popup_order_details(brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_show_order_details.php?brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal1").modal('show');
                    $('#load_page').hide();
                    document.getElementById('modal-body1').innerHTML = data;
                    $('#load_page').hide();
                    // $('.lightgallery').lightGallery();
                }
            });
        }

        function assign_user(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=assign_user&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal2").modal('show');
                    $('#load_page').hide();
                    document.getElementById('modal-body2').innerHTML = data;
                    $('#load_page').hide();
                    // $('.lightgallery').lightGallery();

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
                }
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

        function refresh_monitor_board() { 
            $('#load_page').show();
            var strURL="ajax/ajax_monitor_board.php";
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    $.getScript( "js/task.js" );
                    $("#id_monitor_board").html(data);
                    $("#myModal2").modal('hide');
                    $('#load_page').hide();
                }
            });
        }

        function save_monitor_board(step_ends, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var txt_empcode = $('#txt_empcode').val();
            var step_end = $('#txt_nextstep').val();
            var store_id = entry_year+"-"+brncode+"-"+entry_no+"-"+entry_srno+"-"+entry_sub_srno; // 121-2018-19-2-1-1
            var strURL="ajax/ajax_drag_embc.php?action=track_update&id="+store_id+"&txt_empcode="+txt_empcode+"&step_end="+step_end;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    $.getScript( "js/task.js" );
                    refresh_monitor_board();
                    $("#myModal2").modal('hide');
                    $('#load_page').hide();
                }
            });
        }

        function changeDueDate(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno, ex_duedate) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=changeDueDate&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno+"&ex_duedate="+ex_duedate;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $.getScript("js/zebra_datepicker.js");
                    $.getScript("js/core.js");
                    $("#myModal_changeDueDate").modal('show');
                    document.getElementById('modal-body_changeDueDate').innerHTML = data;
                    $('#load_page').hide();
                }
            });
        }

        function savechangedDueDate(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno, ex_duedate) {
            $('#load_page').show();
            var nw_duedate = $('#datepicker-alterdate').val();
            var sendurl = "ajax/ajax_drag_embc.php?action=savechangedDueDate&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno+"&ex_duedate="+ex_duedate+"&nw_duedate="+nw_duedate;
            $.ajax({
                url:sendurl,
                success:function(data){
                    refresh_monitor_board();
                    $("#myModal_changeDueDate").modal('hide');
                    $('#load_page').hide();
                }
            });
        }

        function assignStyleMaster(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=assignStyleMaster&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_assignStyleMaster").modal('show');
                    document.getElementById('modal-body_assignStyleMaster').innerHTML = data;
                    $('#load_page').hide();
                }
            });
        }

        function saveStyleMaster(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            $("#frm_assignStyleMaster_"+brncode+"_"+entry_year+"_"+entry_no+"_"+entry_srno+"_"+entry_sub_srno).on('submit',(function(e) {
              e.preventDefault();
                $.ajax({
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    url: "ajax/ajax_drag_embc.php?action=saveStyleMaster&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno,
                    success: function(data){
                        $('#load_page').hide();
                    },
                    error: function(){}
                });
            }));
            $('#load_page').hide();
        }

        function getOrderImages(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=getOrderImages&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_getOrderImages").modal('show');
                    /* $.getScript("js/ekko-lightbox-min.js");
                    $.getScript("js/picturefill.min.js");
                    $.getScript("js/lightgallery.js");
                    $.getScript("js/lg-fullscreen.js");
                    $.getScript("js/lg-thumbnail.js");
                    $.getScript("js/lg-video.js");
                    $.getScript("js/lg-autoplay.js");
                    $.getScript("js/lg-zoom.js");
                    $.getScript("js/lg-hash.js");
                    $.getScript("js/lg-pager.js");
                    $('.lightgallery').lightGallery(); */

                    /* $.getScript("js/jquery.gallerie.js");
                    $('#gallery').gallerie(); */

                    /* $.getScript("js/lightbox-plus-jquery.js"); */ 

                    $.getScript("js/jquery.flavor.js");
                    $.getScript("js/script.js");
                    document.getElementById('modal-body_getOrderImages').innerHTML = data;
                    $('#load_page').hide();
                }
            });
        }

        
        function changeEmployee(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno, ex_employee) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=changeEmployee&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno+"&ex_employee="+ex_employee;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_changeEmployee").modal('show');
                    document.getElementById('modal-body_changeEmployee').innerHTML = data;
                    $('#load_page').hide();
                }
            });
        }

        function savechangeEmployee(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno, ex_employee) {
            $('#load_page').show();
            var nw_employee = $('#nw_employee').val();
            var sendurl = "ajax/ajax_drag_embc.php?action=savechangeEmployee&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno+"&ex_employee="+ex_employee+"&nw_employee="+nw_employee;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_changeEmployee").modal('hide');
                    refresh_monitor_board();
                    $('#load_page').hide();
                }
            });
        }
    </script>
    <!-- END TEMPLATE -->
<!-- END SCRIPTS -->
</body>
</html>
