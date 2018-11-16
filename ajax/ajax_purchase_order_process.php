<?php 
session_start();
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
if($_REQUEST['action']=='showemployee'){  ?>
  <div class="modal-dialog" role="document" style="width:75% !important">
    <div class="modal-content" style="width:100%;max-height:800px;height:auto;overflow-y:auto">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
       <h3 class="modal-title" id="exampleModalLabel">Order : <?=$_REQUEST['poryear']?> / <?=$_REQUEST['pornumb']?> [ <?=$_REQUEST['pordate']?> ] <span style="font-size: 12px; padding-right: 10px;font-weight:bold;margin-right:5px; color: #002eff;" class="pull-right"> Qty: <?=$_REQUEST['porqty']?>  /  Value: <?=number_format(($_REQUEST['porval']/100000),2).' L'?> </span><br> <span style="font-size: 12px; padding-right: 10px;font-weight:bold;margin-right:5px; color: #002eff;" class="pull-right"> Supplier : <?=$_REQUEST['supcode']." - ".$_REQUEST['supname'].", ".$_REQUEST['ctyname']?> </span> </h3>
       
      </div>
        <div class="modal-body">
                  <div class='row'>
                    <form name='sendmail<?=$_REQUEST['znecode']?><?=$_REQUEST['znepcde']?>' id='sendmail<?=$_REQUEST['znecode']?><?=$_REQUEST['znepcde']?>'>
                           <div class="col-md-12"> 
                            
                              <div class="form-group">
                                <label class="col-md-3 control-label">Select Person</label>
                                               
                              <div class="col-md-6 multiselect" style="margin:10px;padding-left:0px;margin-right:0px"> 

                              <select id="expslist" multiple class="form-control" name="empsrno[]"  <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?>>                            
                                 
                                 
                                  <? 
                                  $sel=select_query_json("select (EMPCODE||' - '||EMPNAME) EMP,EMPCODE,EMPSRNO from employee_office where brncode='888'","Centra","TEST");
                                  foreach($sel as $key=> $employee){?>
                                  <option  value="<?=$employee['EMPSRNO']?>"><?=$employee['EMP']?></option>
                                  <?}?>
                               </select>
                                
                              

                            </div>
                              </div></div>
                              <!--  <div class="col-md-12"> 
                               <div class="form-group">                                        
                                                <label class="col-md-3 control-label">Subject</label>
                                                <div class="col-md-6 col-xs-12">
                                                   <input type='text' class="form-control" style="text-transform:uppercase !important" maxlength='50' <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?> name='mailsubject<?=$_REQUEST['znecode']?><?=$_REQUEST['znepcde']?>' id='mailsubject<?=$_REQUEST['znecode']?><?=$_REQUEST['znepcde']?>'>          
                                                   
                                                </div>
                                    </div>
                                      </div> -->
                               <div class="col-md-12"> 
                               <div class="form-group">                                        
                                                <label class="col-md-3 control-label">Remarks</label>
                                                <div class="col-md-6 col-xs-12">
                                                   <textarea class="form-control" rows="5" cols="20" style="text-transform:uppercase !important" maxlength='50' <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?> name='mailremarks<?=$_REQUEST['znecode']?><?=$_REQUEST['znepcde']?>' id='mailremarks<?=$_REQUEST['znecode']?><?=$_REQUEST['znepcde']?>'></textarea>          
                                                   
                                                </div>
                                    </div>
                                      </div>
                                      <div class='col-md-12' style="padding-top:20px"> 
                                    <div class="form-group"> 
                                      
                                 <center> <button class="btn btn-success" type="button" <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?>  onclick="sendmail('sendmail<?=$_REQUEST['znecode']?><?=$_REQUEST['znepcde']?>')">Send</button></center></div></div>


                                <input type="hidden" name='pornumb' value="<?=$_REQUEST['pornumb']?>"/>
                              <input type="hidden" name='pordate' value="<?=$_REQUEST['pordate']?>"/>
                              <input type="hidden" name='poryear' value="<?=$_REQUEST['poryear']?>"/>
                              <input type="hidden" name='porval' value="<?=$_REQUEST['porval']?>"/>
                              <input type="hidden" name='znecode' value="<?=$_REQUEST['znecode']?>"/>
                              <input type="hidden" name='znepcde' value="<?=$_REQUEST['znepcde']?>"/>
                              <input type="hidden" name='supcode' value="<?=$_REQUEST['supcode']?>"/>
                              <input type="hidden" name='prostat' value="<?=$_REQUEST['prostat']?>"/>

                                 
                          </form>
                          </div>
                    </div>
</div>
</div>
</div>




  <?}

else if($_REQUEST['action'] == 'showprocess') { 
$id=$_REQUEST['id'];
if($_REQUEST['znecode']==1){
	$sta=0;



?>

  <div class="modal-dialog" role="document" style="width:75% !important">
    <div class="modal-content" style="width:100%;max-height:800px;height:auto;overflow-y:auto">
	 <form class="form-horizontal" name='purchase_form<?=$id?>'  id= 'purchase_form<?=$id?>' method='post' action=''>
      <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
       <h3 class="modal-title" id="exampleModalLabel">Order : <?=$_REQUEST['poryear']?> / <?=$_REQUEST['pornumb']?> [ <?=$_REQUEST['pordate']?> ] <span style="font-size: 12px; padding-right: 10px;font-weight:bold;margin-right:5px; color: #002eff;" class="pull-right"> Qty: <?=$_REQUEST['porqty']?>  /  Value: <?=number_format(($_REQUEST['porval']/100000),2).' L'?> </span><br><span style="font-size: 12px; padding-right: 10px;font-weight:bold;margin-right:5px; color: #002eff;" class="pull-right"> Supplier : <?=$_REQUEST['supcode']." - ".$_REQUEST['supname'].", ".$_REQUEST['ctyname']?> </span>  </h3>
       
      </div>
      <div class="modal-body">
		

                             
                                                                      
                                    
                                    <div class="row" style="margin:0 !important">
                                        
                                        <div class="col-md-12">
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Required Days</label>
                                                <div class="col-md-6">                                            
                                                    <div class="input-group">                                                        
                                                        <input type="text" name='znedays<?=$id.$sta?>' id='znedays<?=$id?>' <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?> maxlength='2' class="form-control" onkeypress="return isNumber(event)"/>
                                                    </div>                                            
                                                    <span class="help-block">Enter Required Days </span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">                                        
                                                <label class="col-md-3 control-label">Remarks</label>
                                                <div class="col-md-6 col-xs-12">
                                                   <textarea class="form-control" rows="5" cols="20" style="text-transform:uppercase !important" maxlength='50' <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?> name='remarks<?=$id.$sta?>' id='remarks<?=$id.$sta?>'></textarea>          
                                                    <span class="help-block">Enter The Remarks (Max 50 characters)</span>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">File</label>
                                                <div class="col-md-6">                                                                                                                                        
                                                    <input type="file" class="fileinput btn-primary" <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?> name="upload<?=$id.$sta?>[]" id="upload<?=$id?>  onchange="selectedfile(event,'<?=$id?>')" multiple accept="image/jpg,image/jpeg,image/png,.pdf"  title="Browse file"/>
                                                    <span class="help-block">Select The Attachment</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                      
                                        
                                    </div>
								  
                                
                           <input type='hidden' name='prostat' value='<?=$_REQUEST['prostat']?>'/>
							<input type='hidden' name='poryear' value='<?=$_REQUEST['poryear']?>'/>
                         <input type='hidden' name='pornumb' value='<?=$_REQUEST['pornumb']?>'/>
                          <input type='hidden' name='ZNCSRNO' value='<?=$_REQUEST['znecode']?>'/>
                           <input type='hidden' name='znepcode' value='<?=$_REQUEST['znepcde']?>'/>
                           
                           <input type='hidden' name='supcode' value='<?=$_REQUEST['supcode']?>'/>
						   <hr>
						   <div class="form-group">
								    
								   <div class="col-md-12">  
                             <center>  <button type="button" class='btn btn-sm btn-primary' <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?> onclick='updateform("purchase_form<?=$id?>","update","<?=$id?>","<?=$sta?>","<?=$_REQUEST['znepcde']?>")' style='cursor:pointer;color:##fff' data-toggle="tooltip" data-placement="top" data-original-title="SUBMIT"><i class='fa fa-paper-plane' >&nbsp; SUBMIT</i></button> <button type="button" <?if($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno']) {?> disabled <?}?> class='btn btn-sm btn-success' data-toggle="tooltip" data-placement="top" data-original-title="FINISH" onclick='submitform("purchase_form<?=$id?>","<?=$id?>","<?=$sta?>","<?=$_REQUEST['znepcde']?>")' style='cursor:pointer;color:#fff'><i class='fa fa-check' >&nbsp; FINISH </i></button></center> 
                                              
								  </div>
                                   </div>
                           
      </div>
      
       
      
	   </form>
	   <div class="modal-body">
  <?
                    $sql_employee_tlu = select_query_json("select to_char(ZNEADDT,'dd-Mon-yyyy HH:MI:SS AM') ZNEADDT,to_char(ZNEEDDT,'dd-Mon-yyyy HH:MI:SS AM') ZNEEDDT,ZNESTAT,ZNCSRNO,ZNPSRNO,IMGLOCA,REMARKS,EMPSRNO,NOTEMP,NOTMSG from order_tracking_history where PORNUMB='".$_REQUEST['pornumb']."' and PORYEAR='".$_REQUEST['poryear']."' order by ENTSRNO desc","Centra","TEST");
                
        if($sql_employee_tlu){
        
        
        ?>
                                   <div class="row" id="orderhistory<?=$id?>" style="padding:0;margin:0;font-size:10px !important;">
              <div class="col-md-12" style="padding:0;margin:0">
                <div class="row" style="text-align: center; font-weight: bold; background-color: #fcc837; color: #000; line-height: 25px;padding:0;margin:0">
                  <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">#</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Process</div>  
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Process Stage</div>        
          <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">Start Date</div>
          <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">End Date</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Attachments</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Remarks</div>
          <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">Order Status</div>
                  
                </div>
              </div>
              <div class="col-md-12" style="padding:0;margin:0">
                
                <? 
                
               
                $empi = 0;
                foreach ($sql_employee_tlu as $key => $employee_tlu_value) { $empi++; 
                  $supcode=select_query_json("select distinct(ord.SUPCODE) SUP,sup.SUPNAME,city.CTYNAME,mas.ZNENAME,mas.ZNEPNME,ord.PROSTAT from order_tracking_detail ord,order_tracking_master mas,supplier sup,city city where  ord.PORNUMB='".$_REQUEST['pornumb']."' and ord.PORYEAR='".$_REQUEST['poryear']."' and sup.SUPCODE='".$_REQUEST['supcode']."' and sup.CTYCODE=city.CTYCODE and mas.ZNECODE='".$employee_tlu_value['ZNCSRNO']."' and mas.ZNEMODE='R' and ord.ZNESTAT='N' and mas.DELETED='N' and ord.DELETED='N' and mas.ZNEPCDE='".$employee_tlu_value['ZNPSRNO']."'", "Centra", "TEST");
                  
                   // $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$_REQUEST['supcode']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
                   
                   // $prsname=select_query_json("select ZNENAME from order_tracking_master where ZNCSRNO='".$employee_tlu_value['ZNCSRNO']."' and ZNEMODE='R' and DELETED='N'", "Centra", "TEST");
                   $employee=array();
                  $empname=select_query_json("select EMPCODE,EMPNAME from employee_office where EMPSRNO IN (".$employee_tlu_value['NOTEMP'].")", "Centra", "TEST");

                  foreach($empname as $key=>$value){
                    $employee[]=$value['EMPCODE'].' - '.$value['EMPNAME'];
                  }
                   $emp=implode(',',$employee); 
                   ?>
                  <div class="row" style="background-color:#fcc8372e;padding:0;margin:0; line-height: 25px; text-align: center;">
                    <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?= $empi;?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$supcode[0]['ZNENAME']?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$supcode[0]['ZNEPNME']?></div>
              
                      <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$employee_tlu_value['ZNEADDT']?></div> 
                    <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$employee_tlu_value['ZNEEDDT']?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><? $selected=array(); $selected=explode(',',$employee_tlu_value['IMGLOCA']);
                                        for($i=0;$i<sizeof($selected)-1;$i++){
                                        ?><a href='ftp://ituser:S0ft@369@ftp1.thechennaisilks.com:5022/Order_tracking_detail/<?=$_REQUEST['poryear']?>_<?=$_REQUEST['pornumb']?>/<?=$selected[$i]?>' target='_blank' type='button' title="view"><?php echo $selected[$i]; ?></a><br><?}?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?if($employee_tlu_value['ZNESTAT']=='M'){

                      ?><?=strtoupper($employee_tlu_value['NOTMSG']).' <span style="color:red"> to</span> '.$emp?> <?}else{?> <?=strtoupper($employee_tlu_value['REMARKS'])?><?}?> </div>
                    
                      <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?php if($employee_tlu_value['ZNESTAT']=='F') { echo "FINISHED";}else if($employee_tlu_value['ZNESTAT']=='R'){ echo "REVERTED";} else if($employee_tlu_value['ZNESTAT']=='U'){ echo "UPDATED";}else if($employee_tlu_value['ZNESTAT']=='M'){
                        $status='';
                          if($supcode[0]['PROSTAT']==1)
                              $status='UPDATE';
                          else  if($supcode[0]['PROSTAT']==2)
                              $status='PROCESS';
                          else  if($supcode[0]['PROSTAT']==3)
                              $status='COMPLETED';

                            echo $status;

                      } ?> </div>
                           </div>                                        
                    <?  }

                    ?>
                  
                  
                      
              </div>
        </div>    <?}?> 
 
 
 
 
 
 
 
 </div>
    </div>
  </div>
	
                   
		
<? }
else{
	
	
	
	
	?>
	
	<div class="modal-dialog" role="document" style="width:75% !important;height:90% !important">
    <div class="modal-content" style="width:100%;max-height:100%;height:auto;overflow-y:auto">
	 <form class="form-horizontal" name='purchase_form<?=$id?>'  id= 'purchase_form<?=$id?>' method='post' action=''>
      <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title" id="exampleModalLabel">Order : <?=$_REQUEST['poryear']?> / <?=$_REQUEST['pornumb']?> [ <?=$_REQUEST['pordate']?> ] <span style="font-size: 12px; padding-right: 10px;font-weight:bold;margin-right:5px; color: #002eff;" class="pull-right"> Qty: <?=$_REQUEST['porqty']?>  /  Value: <?=number_format(($_REQUEST['porval']/100000),2).' L'?> </span><br><span style="font-size: 12px; padding-right: 10px;font-weight:bold;margin-right:5px; color: #002eff;" class="pull-right"> Supplier : <?=$_REQUEST['supcode']." - ".$_REQUEST['supname'].", ".$_REQUEST['ctyname']?> </span>  </h3>
       
      </div>
      <div class="modal-body">
		
									<div class="row" style="margin:0 !important">
                                        
                                        <div class="col-md-12">
                                            
                                           
                                              <table class='table table-hovered table-bordered tabledrop' style='border:1px solid #000;width:100%;font-size:12px !important'>
                                        <thead style="background-color:#000">
                                                                   <tr class="darkgrey" style="background-color:#000">              
                                                                   
                                                                    <th style='text-align:center;color:#FFF !important'>PROCESS</th>
                                                                    <th style='text-align:center;color:#FFF !important'>REMARKS</th>
                                                                    <th style='text-align:center;color:#FFF !important'>ATTACH FILES</th>
                                                                    <th style='text-align:center;color:#FFF !important'>ACTION</th>                                                      
                                                                 </tr>
                                         </thead>
                                           <?
									$sta=0;
   $selopt=select_query_json("select ord.ZNECODE,ord.ZNEPCDE,ord.ZNEPNME,tra.ZNECODE,tra.ZNESTAT from order_tracking_master ord,order_tracking_detail tra where tra.ZNECODE='".$_REQUEST['znecode']."' and tra.PORNUMB='".$_REQUEST['pornumb']."' and tra.PORYEAR='".$_REQUEST['poryear']."' and tra.SUPCODE='".$_REQUEST['supcode']."' and  ord.ZNECODE=tra.ZNECODE and tra.ZNPSRNO=ord.ZNEPCDE and ord.ZNEMODE='R' and ord.DELETED='N' and tra.DELETED='N' and tra.ZNECODE=ord.ZNECODE and (tra.ZNESTAT='N' or tra.ZNESTAT='F' or tra.ZNESTAT='T' or tra.ZNESTAT='R') order by tra.ZNPSRNO asc", "Centra", "TEST");
   foreach($selopt as $key=>$seloptval){$sta=$sta+1; 
   $selimg=select_query_json("select IMGLOCA from order_tracking_history tra where tra.ZNCSRNO='".$_REQUEST['znecode']."' and tra.ZNPSRNO='".$sta."' and tra.PORNUMB='".$_REQUEST['pornumb']."' and tra.PORYEAR='".$_REQUEST['poryear']."'  and tra.DELETED='N' ", "Centra", "TEST");
                                          	//echo "select MAX(tra.IMGLOCA) IMGLOCA from order_tracking_history tra where tra.ZNCSRNO='".$ord_confirm_value['ZNECODE']."' and tra.ZNPSRNO='".$ord_confirm_value['ZNPSRNO']."' and tra.PORNUMB='".$sql_val['PORNUMB']."' and tra.PORYEAR='".$sql_val['PORYEAR']."'  and tra.DELETED='N' ";

									?>
                                             <tr>
                                        <td width='20%'><?=$seloptval["ZNEPNME"]?></td>
                                       <td align='center' width='30%'><textarea <?if($seloptval["ZNESTAT"]=='F' || ($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno'])) {?> disabled <?}?> rows='3' name='remarks<?=$id.$sta?>' id='remarks<?=$id.$sta?>' style="text-transform:uppercase !important;width:100%"  maxlength='50' onblur="setremarks(this.value,'<?=$id?>')" cols='15'></textarea><span class="help-block pull-left">Max 50 characters</span> </td>   
                                       <td align='center' width='30%' id='files<?=$id.$sta?>' ><?if($seloptval["ZNESTAT"]=='F' || ($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno'])) {
                                        foreach($selimg as $key=>$selimg){
                                          $selected=array();
                                          $selected=explode(',',$selimg['IMGLOCA']);
                                       	for($i=0;$i<sizeof($selected)-1;$i++){
                                       	?><p style="padding:2px;margin:2px"><a href='ftp://ituser:S0ft@369@ftp1.thechennaisilks.com:5022/Order_tracking_detail/<?=$_REQUEST['poryear']?>_<?=$_REQUEST['pornumb']?>/<?=$selected[$i]?>' target='_blank' type='button' title="view"><?php echo $selected[$i]; ?></a></p><?}}}


                                        else{?><label  class="btn btn-primary btn-sm">
								               <input  type="file" name='upload<?=$id.$sta?>[]' multiple accept="image/jpg,image/jpeg,image/png,.pdf" id='upload<?=$id.$sta?>'">
								            </label><?}?></td>   
                                        <td align='center' width='20%'><button type="button" <?if($seloptval["ZNESTAT"]=='F' || ($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno'])) {?> disabled <?}?> class='btn btn-sm btn-primary' onclick='updateform("purchase_form<?=$id?>","update","<?=$id?>","<?=$sta?>","<?=$seloptval['ZNEPCDE']?>")' style='cursor:pointer;color:##fff' data-toggle="tooltip" data-placement="top" data-original-title="SUBMIT"><i class='fa fa-paper-plane' ></i></button> <button <?if($seloptval["ZNESTAT"]=='F' || ($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno'])) {?> disabled <?}?> class='btn btn-sm btn-success' data-toggle="tooltip" data-placement="top" data-original-title="FINISH" onclick='submitform1("purchase_form<?=$id?>","<?=$id?>","<?=$sta?>","<?=$seloptval['ZNEPCDE']?>")' style='cursor:pointer;color:#fff'><i class='fa fa-check' ></i></button></td>                     
                                        </tr> 
                                        <input type='hidden' id='orderstatus<?=$id.$sta?>' value="<?=$seloptval["ZNESTAT"]?>"/>
                                        <input type='hidden' id='count<?=$id?>' value="<?=$sta?>"/>
                                        <?}?>
                                             
                                   </table>
                                        </div>
                                      
                                        
                                    </div>
                             <input type='hidden' name='prostat' value='<?=$_REQUEST['prostat']?>'/>
									           <input type='hidden' name='poryear' value='<?=$_REQUEST['poryear']?>'/>
                             <input type='hidden' name='pornumb' value='<?=$_REQUEST['pornumb']?>'/>
                            <input type='hidden' name='ZNCSRNO' value='<?=$_REQUEST['znecode']?>'/>
                            <input type='hidden' name='znepcode' value='<?=$_REQUEST['znepcde']?>'/>
                           
                           <input type='hidden' name='supcode' value='<?=$_REQUEST['supcode']?>'/>
                                      
								<div class="form-group">
								    
								   <div class="col-md-12">  
								      <center><button class='btn btn-sm btn-warning' type='button' <?if($seloptval["ZNESTAT"]=='F' || ($_SESSION['tcs_empsrno']!=$_REQUEST['empsrno'])) {?> disabled <?}?> name='updatetrack' onclick='updateform("purchase_form<?=$id?>","revert","<?=$_REQUEST["znecode"]?>","<?=$_REQUEST["znepcde"]?>","<?=$sta?>")'>REVERT</button>&nbsp;&nbsp;&nbsp;<a href='ftp://ituser:S0ft@369@tcstextile.in/purchase_Order_auto_pdf/purchase_order/po_<?=$_REQUEST['poryear']?>_<?=$_REQUEST['pornumb']?>.pdf' target='_blank' type='button' title="view"><img style="max-height:20px;max-width:20px" src="images/pdflogo.jpg"/></a> </center><br>

                                  
								  </div>
                                   </div>
								   <?
								    $sql_employee_tlu = select_query_json("select to_char(ZNEADDT,'dd-Mon-yyyy HH:MI:SS AM') ZNEADDT,to_char(ZNEEDDT,'dd-Mon-yyyy HH:MI:SS AM') ZNEEDDT,ZNESTAT,ZNCSRNO,ZNPSRNO,IMGLOCA,REMARKS,EMPSRNO,NOTEMP,NOTMSG from order_tracking_history where PORNUMB='".$_REQUEST['pornumb']."' and PORYEAR='".$_REQUEST['poryear']."' order by ENTSRNO desc","Centra","TEST");
                
				if($sql_employee_tlu){
				
				
				?>
                                   <div class="row" id="orderhistory<?=$id?>" style="padding:0;margin:0;font-size:10px !important;">
              <div class="col-md-12" style="padding:0;margin:0">
                <div class="row" style="text-align: center; font-weight: bold; background-color: #fcc837; color: #000; line-height: 25px;padding:0;margin:0">
                  <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">#</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Process</div>  
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Process Stage</div>        
          <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">Start Date</div>
          <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">End Date</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Attachments</div>
          <div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Remarks</div>
          <div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">Order Status</div>
                  
                </div>
              </div>
              <div class="col-md-12" style="padding:0;margin:0">
                
                <? 
                
               
                $empi = 0;
                foreach ($sql_employee_tlu as $key => $employee_tlu_value) { $empi++; 

                  $supcode=select_query_json("select distinct(ord.SUPCODE) SUP,sup.SUPNAME,city.CTYNAME,mas.ZNENAME,mas.ZNEPNME,ord.PROSTAT from order_tracking_detail ord,order_tracking_master mas,supplier sup,city city where  ord.PORNUMB='".$_REQUEST['pornumb']."' and ord.PORYEAR='".$_REQUEST['poryear']."' and sup.SUPCODE='".$_REQUEST['supcode']."' and sup.CTYCODE=city.CTYCODE and mas.ZNECODE='".$employee_tlu_value['ZNCSRNO']."' and mas.ZNEMODE='R' and ord.ZNESTAT='N'  and mas.DELETED='N' and ord.DELETED='N' and mas.ZNEPCDE='".$employee_tlu_value['ZNPSRNO']."'", "Centra", "TEST");
                  
                   // $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$_REQUEST['supcode']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
                   
                   // $prsname=select_query_json("select ZNENAME from order_tracking_master where ZNCSRNO='".$employee_tlu_value['ZNCSRNO']."' and ZNEMODE='R' and DELETED='N'", "Centra", "TEST");
                   $employee=array();
                  $empname=select_query_json("select EMPCODE,EMPNAME from employee_office where EMPSRNO IN (".$employee_tlu_value['NOTEMP'].")", "Centra", "TEST");

                  foreach($empname as $key=>$value){
                    $employee[]=$value['EMPCODE'].' - '.$value['EMPNAME'];
                  }
                   $emp=implode(',',$employee); 
                   ?>
                  <div class="row" style="background-color:#fcc8372e;padding:0;margin:0; line-height: 25px; text-align: center;">
                    <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?= $empi;?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$supcode[0]['ZNENAME']?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$supcode[0]['ZNEPNME']?></div>
              
                      <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$employee_tlu_value['ZNEADDT']?></div> 
                    <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$employee_tlu_value['ZNEEDDT']?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><? $selected=array(); $selected=explode(',',$employee_tlu_value['IMGLOCA']);
                                        for($i=0;$i<sizeof($selected)-1;$i++){
                                        ?><a href='ftp://ituser:S0ft@369@ftp1.thechennaisilks.com:5022/Order_tracking_detail/<?=$_REQUEST['poryear']?>_<?=$_REQUEST['pornumb']?>/<?=$selected[$i]?>' target='_blank' type='button' title="view"><?php echo $selected[$i]; ?></a><br><?}?></div>
                    <div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?if($employee_tlu_value['ZNESTAT']=='M'){

                      ?><?=strtoupper($employee_tlu_value['NOTMSG']).' <span style="color:red"> to</span> '.$emp?> <?}else{?> <?=strtoupper($employee_tlu_value['REMARKS'])?><?}?> </div>
                    
                      <div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?php if($employee_tlu_value['ZNESTAT']=='F') { echo "FINISHED";}else if($employee_tlu_value['ZNESTAT']=='R'){ echo "REVERTED";} else if($employee_tlu_value['ZNESTAT']=='U'){ echo "UPDATED";}else if($employee_tlu_value['ZNESTAT']=='M'){
                        $status='';
                          if($supcode[0]['PROSTAT']==1)
                              $status='UPDATE';
                          else  if($supcode[0]['PROSTAT']==2)
                              $status='PROCESS';
                          else  if($supcode[0]['PROSTAT']==3)
                              $status='COMPLETED';

                          echo $status;

                      } ?> </div>
                           </div>                                        
                    <?  }

                    ?>
                  
                  
                      
              </div>
				</div>    <?}?>  
                            </div>
								  
                                
                           
							
                           

      
      
	   </form>
	 
    </div>
  </div>
	
	
<? }
}

?>

 