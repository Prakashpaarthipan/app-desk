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
  <!-- START CONTENT FRAME BODY -->
                <div class="content-frame-body" style="margin-left:0px">
                
                    <div class="list_view push-up-12" id='id_monitor_board'>

                      <? $sql_ord_confirm = select_query_json("select mas.ZNECODE,mas.ADDUSER,mas.ZNCSRNO,sum(mas.ZNEDAYS) ZNEDAYS,mas.ZNENAME from order_tracking_master mas where  (mas.EMPSRNO='".$_SESSION['tcs_empsrno']."' or mas.ALTSRNO='".$_SESSION['tcs_empsrno']."') and mas.ZNEMODE='R' and mas.DELETED='N' group by(mas.ZNECODE,mas.ADDUSER,mas.ZNCSRNO,mas.ZNENAME) order by mas.ZNCSRNO asc", "Centra", "TEST");
                         $seltotal=select_query_json("select COUNT(*) totcount from order_tracking_detail where ZNECODE>0 and ZNESTAT='N' and PORNUMB like '%".$_REQUEST['selectval']."%' and DELETED='N'", "Centra", "TEST");
                          $tme = 0; $ttl_prscnt = '';
                          foreach ($sql_ord_confirm as $key => $ord_confirm_value) { $tme++; 
                           
                              $sql=select_query_json("select (select count(ZNESTAT) from order_tracking_detail where ZNECODE='".$ord_confirm_value['ZNECODE']."' and ZNESTAT='N' and PORNUMB like '%".$_REQUEST['selectval']."%' and DELETED='N') tot, (select sum(PORVAL) from order_tracking_detail where ZNECODE='".$ord_confirm_value['ZNECODE']."' and ZNESTAT='N' and DELETED='N') sum, SUPCODE,PORNUMB,PORQTY,PORYEAR,ZNECODE,ZNPSRNO,REMARKS,EMPSRNO,to_char(POREDDT,'dd-MM-yyyy') POREDDT,PROSTAT, to_char(PORDATE,'dd-MM-yyyy') PORDATE,to_char(ADDDATE,'dd-MM-yyyy') ADDDATE1, to_char(POREDDT,'dd-MM-yyyy HH:mi:ss AM') POREDDT,PORVAL from order_tracking_detail where ZNECODE='".$ord_confirm_value['ZNECODE']."'  and PORNUMB like '%".$_REQUEST['selectval']."%' and ZNESTAT='N' and DELETED='N' order by ADDDATE asc", "Centra", "TEST");
                            $perc=round(($sql[0]['TOT']/$seltotal[0]['TOTCOUNT'])*100);
                            $totalval=number_format(($sql[0]['SUM']/100000),2);
                            if($sql[0]['TOT']>0){
                            ?>
       <div class="nonadv_list" style="height:auto !important">
                                <h3 class="text-center" style="color:#000;font-size: 16px;margin-top:0px;padding-top:5px;">
                                    <span class="pull-left" style="color:#000;border:1px solid #000;font-size: 16px;border-radius:30px;padding:5px;padding:bottom:0px;min-width:50px;margin-left:5px"> <?=$perc.' %'?></span> 
                                    <?=$ord_confirm_value['ZNENAME']?> 
                                    <span class="pull-right" style="color:#000;border:1px solid #000;font-size: 16px;border-radius:30px;padding:5px;padding:bottom:0px;min-width:50px;margin-right:5px"> <?php if($sql[0]['TOT']>0){ echo $sql[0]['TOT'];}else{echo '0';}?></span><br><br>
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
                        foreach($sql as $key1 => $sql_val) {
                          $background='';
                          $t++;
                          
                          $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$sql_val['SUPCODE']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
                          

                          if (($t % 2) == 1){
                           // $background='#bcddff';
                          }
                          else{
                         //  $background='#e6f2ff';
                          }
                         if($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno']){
                          $status=1;
                         }else{
                          $status=0;
                         }
                        ?>
                    
                     <div class='task-item hover-box' id='task_item<?=$tme.$t?>'>
                     <div style="height:auto;cursor:pointer" id="order<?=$tme.$t?>" onclick="openprocess('<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>','<?=$status?>')">
                          <div class="row" style="border:1px solid #ccc;margin:0;font-size:12px !important;max-width:100%;cursor:pointer">
                                    <div class="col-md-12"  style="padding-top:5px;padding-left:0;padding-right:0;height:auto;max-width:100%">
                                        <div class="form-group">
                                         <!--<div class="col-md-1">
                                             <span id='dropdown<?=$tme.$t?>' style="font=size:12px;font-weight:bold;color:#ffebcc;border:1px solid #000;cursor:pointer !important;padding:2px 5px;background-color:#29a329"  onclick="selecttoggle('<?=$tme.$t?>')"> + </span>
                                          </div><!-- duration -->
                                          
                                          <div class="col-md-12" style="font-size: 14px; padding-top: 8px; padding-right: 2px;font-weight:bold;margin-right:5px; color: #002eff;">
                                              Order No : <?=$sql_val['PORYEAR']?> / <?=$sql_val['PORNUMB']?> [ <?=$sql_val['PORDATE']?> ]
                                          </div>
                                           <div style="clear: both;"></div>
                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              <?php 
                                               // echo $sql_val['ADDDATE1'];
                                                 $addeddate=date_create($sql_val['ADDDATE1']);
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
                                              Supplier : <?=$sql_val['SUPCODE']." - ".$supname[0]['SUPNAME'].", ".$supname[0]['CTYNAME']?>
                                          </div><!-- rate -->
                                          <div style="clear: both;"></div>                                         
                                         
                                          <div class="col-md-6" style="padding: 5px 10px; padding-left: 10px;font-weight:bold;color: #FF0000;">
                                              Qty : <?=$sql_val['PORQTY']?>
                                          </div><!-- CGST -->
                                          <div class="col-md-6" style="padding:0; text-align: right; padding-right: 10px; padding: 5px 10px; padding-left: 2px;font-weight:bold;color: #FF0000;">
                                              Val : <?=number_format(($sql_val['PORVAL']/100000),2);?> L
                                          </div><!-- SGST -->
                                          <div style="clear: both;"></div>
											<div class="col-md-12" style="padding: 5px 10px;">
                                              <?php                                                
                                                 $addeddate=date_create($sql_val['ADDDATE1']);
												 $proc=date('d-m-Y',strtotime($sql_val['ADDDATE1']. '+'.$ord_confirm_value['ZNEDAYS'].' day'));?>
                                                  <span class="pull-left blink" style='color:#002eff;font-weight:bold;'>Process Due Date: <?=$proc?></span>
												 											  
                                          </div>
                                          <div style="clear: both;"></div>
										  <div class="col-md-12" style="padding: 5px 10px;">
                                              <?php                                                
                                                 $addeddate=date_create($sql_val['ADDDATE1']);?>
                                                  
												  <span class="pull-left" style='color:#002eff;font-weight:bold;'>Order Due Date: <?=$sql_val['POREDDT']?></span>			

                          <span class="pull-right" style='color:#002eff;font-weight:bold;' onclick="getemployee(event,'<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>')">@</span>									  
                                          </div>
                                         
										  <div style="clear: both;"></div>

                                          <? $empname=select_query_json("select EMPNAME,EMPCODE from employee_office where empsrno='".$sql_val['EMPSRNO']."'","Centra","TEST"); ?>

                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              <span class="pull-right" style='color:#FF0000'>Responsible Person: <?=$empname[0]['EMPCODE']?> - <?=$empname[0]['EMPNAME']?></span>
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
						  if($sql_val['PROSTAT']==1 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){
							  $blink1='blink';							  
						  }else if($sql_val['PROSTAT']==2 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){
							   $blink2='blink';	
						  }
						  else if($sql_val['PROSTAT']==3 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){
							   $blink3='blink';	
						  }?>
						 <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process1<?=$tme.$t?>' onclick="updateprocess(event,'process1<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>','<?=$status?>')"  <?if($sql_val['PROSTAT']==1 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?> <?if($sql_val['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value="1"/> <label class="<?=$blink1?> label label-danger" style="margin: 2px;">UPDATE</label>
						  <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process2<?=$tme.$t?>' onclick="updateprocess(event,'process2<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>','<?=$status?>')" <?if($sql_val['PROSTAT']==2 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?>  <?if($sql_val['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value='2'/> <label class="<?=$blink2?> label label-warning" style="margin: 2px;">PROCESS</label>
						 <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process3<?=$tme.$t?>' onclick="updateprocess(event,'process3<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>','<?=$status?>')" <?if($sql_val['PROSTAT']==3 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?> <?if($sql_val['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value='3'/>  <label class="<?=$blink3?> label label-success" style="margin: 2px;">COMPLETED</label>
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

                  <div class="content-frame-body" style="margin-left:0px">
                
                    <div class="list_view push-up-12" id='id_monitor_board'>

                      <?



                       $sql_ord_confirm = select_query_json("select mas.ZNECODE,mas.ADDUSER,mas.ZNCSRNO,sum(mas.ZNEDAYS) ZNEDAYS,mas.ZNENAME from order_tracking_master mas where  (mas.EMPSRNO='".$_SESSION['tcs_empsrno']."' or mas.ALTSRNO='".$_SESSION['tcs_empsrno']."') and mas.ZNEMODE='R' and mas.DELETED='N' group by(mas.ZNECODE,mas.ADDUSER,mas.ZNCSRNO,mas.ZNENAME) order by mas.ZNCSRNO asc", "Centra", "TEST");
                         $seltotal=select_query_json("select COUNT(*) totcount from order_tracking_detail where ZNECODE>0 and ZNESTAT='N' and SECCODE ='".$_REQUEST['selectval']."' and DELETED='N'", "Centra", "TEST");
                          $tme = 0; $ttl_prscnt = '';
                          foreach ($sql_ord_confirm as $key => $ord_confirm_value) { $tme++; 
                           
                              $sql=select_query_json("select (select count(ZNESTAT) from order_tracking_detail where ZNESTAT='N' and ZNECODE='".$ord_confirm_value['ZNECODE']."' and SECCODE ='".$_REQUEST['selectval']."' and DELETED='N') tot, (select sum(PORVAL) from order_tracking_detail where ZNESTAT='N' and SECCODE ='".$_REQUEST['selectval']."' and DELETED='N') sum, SUPCODE,PORNUMB,PORQTY,PORYEAR,ZNECODE,ZNPSRNO,REMARKS,EMPSRNO,to_char(POREDDT,'dd-MM-yyyy') POREDDT,PROSTAT, to_char(PORDATE,'dd-MM-yyyy') PORDATE,to_char(ADDDATE,'dd-MM-yyyy') ADDDATE1, to_char(POREDDT,'dd-MM-yyyy HH:mi:ss AM') POREDDT,PORVAL from order_tracking_detail where ZNECODE='".$ord_confirm_value['ZNECODE']."' and SECCODE ='".$_REQUEST['selectval']."' and ZNESTAT='N' and DELETED='N'   order by ADDDATE asc", "Centra", "TEST");
                            $perc=round(($sql[0]['TOT']/$seltotal[0]['TOTCOUNT'])*100);
                            $totalval=number_format(($sql[0]['SUM']/100000),2);
                            if($sql[0]['TOT']>0){
                            ?>
                             <div class="nonadv_list" style="height:auto !important">
                                <h3 class="text-center" style="color:#000;font-size: 16px;margin-top:0px;padding-top:5px;">
                                    <span class="pull-left" style="color:#000;border:1px solid #000;font-size: 16px;border-radius:30px;padding:5px;padding:bottom:0px;min-width:50px;margin-left:5px"> <?=$perc.' %'?></span> 
                                    <?=$ord_confirm_value['ZNENAME']?> 
                                    <span class="pull-right" style="color:#000;border:1px solid #000;font-size: 16px;border-radius:30px;padding:5px;padding:bottom:0px;min-width:50px;margin-right:5px"> <?php if($sql[0]['TOT']>0){ echo $sql[0]['TOT'];}else{echo '0';}?></span><br><br>
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
                        foreach($sql as $key1 => $sql_val) {
                          $background='';
                          $t++;
                          
                          $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$sql_val['SUPCODE']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
                          

                          if (($t % 2) == 1){
                           // $background='#bcddff';
                          }
                          else{
                         //  $background='#e6f2ff';
                          }
                         if($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno']){
                          $status=1;
                         }else{
                          $status=0;
                         }
                        ?>
                    
                     <div class='task-item hover-box' id='task_item<?=$tme.$t?>'>
                     <div style="height:auto;cursor:pointer" id="order<?=$tme.$t?>" onclick="openprocess('<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>','<?=$status?>')">
                          <div class="row" style="border:1px solid #ccc;margin:0;font-size:12px !important;max-width:100%;cursor:pointer">
                                    <div class="col-md-12"  style="padding-top:5px;padding-left:0;padding-right:0;height:auto;max-width:100%">
                                        <div class="form-group">
                                         <!--<div class="col-md-1">
                                             <span id='dropdown<?=$tme.$t?>' style="font=size:12px;font-weight:bold;color:#ffebcc;border:1px solid #000;cursor:pointer !important;padding:2px 5px;background-color:#29a329"  onclick="selecttoggle('<?=$tme.$t?>')"> + </span>
                                          </div><!-- duration -->
                                          
                                          <div class="col-md-12" style="font-size: 14px; padding-top: 8px; padding-right: 2px;font-weight:bold;margin-right:5px; color: #002eff;">
                                              Order No : <?=$sql_val['PORYEAR']?> / <?=$sql_val['PORNUMB']?> [ <?=$sql_val['PORDATE']?> ]
                                          </div>
                                           <div style="clear: both;"></div>
                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              <?php 
                                               // echo $sql_val['ADDDATE1'];
                                                 $addeddate=date_create($sql_val['ADDDATE1']);
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
                                              Supplier : <?=$sql_val['SUPCODE']." - ".$supname[0]['SUPNAME'].", ".$supname[0]['CTYNAME']?>
                                          </div><!-- rate -->
                                          <div style="clear: both;"></div>                                         
                                         
                                          <div class="col-md-6" style="padding: 5px 10px; padding-left: 10px;font-weight:bold;color: #FF0000;">
                                              Qty : <?=$sql_val['PORQTY']?>
                                          </div><!-- CGST -->
                                          <div class="col-md-6" style="padding:0; text-align: right; padding-right: 10px; padding: 5px 10px; padding-left: 2px;font-weight:bold;color: #FF0000;">
                                              Val : <?=number_format(($sql_val['PORVAL']/100000),2);?> L
                                          </div><!-- SGST -->
                                          <div style="clear: both;"></div>
											<div class="col-md-12" style="padding: 5px 10px;">
                                              <?php                                                
                                                 $addeddate=date_create($sql_val['ADDDATE1']);
												 $proc=date('d-m-Y',strtotime($sql_val['ADDDATE1']. '+'.$ord_confirm_value['ZNEDAYS'].' day'));?>
                                                  <span class="pull-left blink" style='color:#002eff;font-weight:bold;'>Process Due Date: <?=$proc?></span>
												 											  
                                          </div>
                                          <div style="clear: both;"></div>
										  <div class="col-md-12" style="padding: 5px 10px;">
                                              <?php                                                
                                                 $addeddate=date_create($sql_val['ADDDATE1']);?>
                                                  
												  <span class="pull-left" style='color:#002eff;font-weight:bold;'>Order Due Date: <?=$sql_val['POREDDT']?></span>			

                          <span class="pull-right" style='color:#002eff;font-weight:bold;' onclick="getemployee(event,'<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>')">@</span>									  
                                          </div>
                                         
										  <div style="clear: both;"></div>

                                          <? $empname=select_query_json("select EMPNAME,EMPCODE from employee_office where empsrno='".$sql_val['EMPSRNO']."'","Centra","TEST"); ?>

                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              <span class="pull-right" style='color:#FF0000'>Responsible Person: <?=$empname[0]['EMPCODE']?> - <?=$empname[0]['EMPNAME']?></span>
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
						  if($sql_val['PROSTAT']==1 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){
							  $blink1='blink';							  
						  }else if($sql_val['PROSTAT']==2 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){
							   $blink2='blink';	
						  }
						  else if($sql_val['PROSTAT']==3 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){
							   $blink3='blink';	
						  }?>
						 <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process1<?=$tme.$t?>' onclick="updateprocess(event,'process1<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>','<?=$status?>')"  <?if($sql_val['PROSTAT']==1 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?> <?if($sql_val['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value="1"/> <label class="<?=$blink1?> label label-danger" style="margin: 2px;">UPDATE</label>
						  <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process2<?=$tme.$t?>' onclick="updateprocess(event,'process2<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>','<?=$status?>')" <?if($sql_val['PROSTAT']==2 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?>  <?if($sql_val['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value='2'/> <label class="<?=$blink2?> label label-warning" style="margin: 2px;">PROCESS</label>
						 <input type='radio' class='processstat<?=$tme.$t?>' name='process<?=$tme.$t?>' id='process3<?=$tme.$t?>' onclick="updateprocess(event,'process3<?=$tme.$t?>','<?=$tme.$t?>','<?=$ord_confirm_value["ZNECODE"]?>','<?=$sql_val["ZNPSRNO"]?>','<?=$sql_val["PORYEAR"]?>','<?=$sql_val["PORNUMB"]?>','<?=$sql_val['PORDATE']?>','<?=$sql_val["SUPCODE"]?>','<?=$sql_val['PORQTY']?>','<?=$sql_val['PORVAL']?>','<?=$sql_val['PROSTAT']?>','<?=$sql_val['EMPSRNO']?>','<?=$status?>')" <?if($sql_val['PROSTAT']==3 and ($sql_val['EMPSRNO']==$_SESSION['tcs_empsrno'])){?> checked  <?}?> <?if($sql_val['EMPSRNO']!=$_SESSION['tcs_empsrno']){?> disabled <?}?> value='3'/>  <label class="<?=$blink3?> label label-success" style="margin: 2px;">COMPLETED</label>
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