<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(E_ALL);
if($_REQUEST['action']=='order_search'){
?>
 <div class="content-frame-body" style="margin-left:0px;">



                    <div class="list_view push-up-12" id='id_monitor_board'>

                      <? 
           
                    
              
                      $sql=select_query_json("select (select COUNT(*) from order_tracking_detail where ZNECODE>0 and PORNUMB like '%".$_REQUEST['selectval']."%' and ZNESTAT='N' and DELETED='N') totcount,(select count(ZNESTAT) from order_tracking_detail where ZNESTAT='N' and PORNUMB like '%".$_REQUEST['selectval']."%' and DELETED='N') tot, ord.PROSTAT,ord.SUPCODE,ord.PORNUMB,ord.PORQTY,ord.PORYEAR,ord.ZNECODE,ord.ZNPSRNO,ord.REMARKS,ord.EMPSRNO, to_char(ord.PORDATE,'dd-MM-yyyy') PORDATE,to_char(ord.POREDDT,'dd-MM-yyyy') POREDDT,to_char(ord.ADDDATE,'dd-MM-yyyy') ADDDATE1, to_char(ord.POREDDT,'dd-MM-yyyy HH:mi:ss AM') POREDDT,ord.PORVAL,sup.SUPNAME,city.CTYNAME,emp.empname,emp.empcode from supplier sup,city city,order_tracking_detail ord,employee_office emp where  ord.ZNESTAT='N' and emp.empsrno=ord.empsrno and sup.SUPCODE=ord.supcode and sup.CTYCODE=city.CTYCODE  and ord.DELETED='N' and ord.PORNUMB like '%".$_REQUEST['selectval']."%' order by ord.ADDDATE asc", "Centra", "TEST");
                   
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
               if(count($sql_process[$ord_confirm_value['ZNECODE']])>0){
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
                               
                           
               <? }} ?>
                  </div>
              </div>
              <?}
                if($_REQUEST['action']=='section_load'){?>

                  <div class="content-frame-body" style="margin-left:0px;">



                    <div class="list_view push-up-12" id='id_monitor_board'>

                      <? 
           
                      if($_REQUEST['selectval']=='all'){
                        $sql=select_query_json("select (select COUNT(*) from order_tracking_detail where ZNECODE>0 and ZNESTAT='N' and DELETED='N') totcount,(select count(ZNESTAT) from order_tracking_detail where ZNESTAT='N'  and DELETED='N') tot, ord.PROSTAT,ord.SUPCODE,ord.PORNUMB,ord.PORQTY,ord.PORYEAR,ord.ZNECODE,ord.ZNPSRNO,ord.REMARKS,ord.EMPSRNO, to_char(ord.PORDATE,'dd-MM-yyyy') PORDATE,to_char(ord.POREDDT,'dd-MM-yyyy') POREDDT,to_char(ord.ADDDATE,'dd-MM-yyyy') ADDDATE1, to_char(ord.POREDDT,'dd-MM-yyyy HH:mi:ss AM') POREDDT,ord.PORVAL,sup.SUPNAME,city.CTYNAME,emp.empname,emp.empcode from supplier sup,city city,order_tracking_detail ord,employee_office emp where  ord.ZNESTAT='N' and emp.empsrno=ord.empsrno and sup.SUPCODE=ord.supcode and sup.CTYCODE=city.CTYCODE  and ord.DELETED='N' order by ord.ADDDATE asc", "Centra", "TEST");
                      }
                      else{
              
                      $sql=select_query_json("select (select COUNT(*) from order_tracking_detail where ZNECODE>0 and SECCODE ='".$_REQUEST['selectval']."' and ZNESTAT='N' and DELETED='N') totcount,(select count(ZNESTAT) from order_tracking_detail where ZNESTAT='N' and SECCODE ='".$_REQUEST['selectval']."' and DELETED='N') tot, ord.PROSTAT,ord.SUPCODE,ord.PORNUMB,ord.PORQTY,ord.PORYEAR,ord.ZNECODE,ord.ZNPSRNO,ord.REMARKS,ord.EMPSRNO, to_char(ord.PORDATE,'dd-MM-yyyy') PORDATE,to_char(ord.POREDDT,'dd-MM-yyyy') POREDDT,to_char(ord.ADDDATE,'dd-MM-yyyy') ADDDATE1, to_char(ord.POREDDT,'dd-MM-yyyy HH:mi:ss AM') POREDDT,ord.PORVAL,sup.SUPNAME,city.CTYNAME,emp.empname,emp.empcode from supplier sup,city city,order_tracking_detail ord,employee_office emp where  ord.ZNESTAT='N' and emp.empsrno=ord.empsrno and sup.SUPCODE=ord.supcode and sup.CTYCODE=city.CTYCODE  and ord.DELETED='N' and ord.SECCODE ='".$_REQUEST['selectval']."' order by ord.ADDDATE asc", "Centra", "TEST");
                    }
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
               if(count($sql_process[$ord_confirm_value['ZNECODE']])>0){
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
                               
                           
               <? }} ?>
                  </div>
              </div>
        
        <?}?>