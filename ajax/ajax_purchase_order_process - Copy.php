<?php 
session_start();
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');


if($_REQUEST['action'] == 'showprocess') { 
$id=$_REQUEST['id'];
if($_REQUEST['znecode']==1){
	$sta=0;



?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
	 <form class="form-horizontal" name='purchase_form<?=$id?>'  id= 'purchase_form<?=$id?>' method='post' action=''>
      <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title" id="exampleModalLabel">Order : <?=$_REQUEST['poryear']?> / <?=$_REQUEST['pornumb']?></h3>
       
      </div>
      <div class="modal-body">
		

                             
                                                                      
                                    
                                    <div class="row">
                                        
                                        <div class="col-md-12">
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Required Days</label>
                                                <div class="col-md-6">                                            
                                                    <div class="input-group">                                                        
                                                        <input type="text" name='znedays<?=$id.$sta?>' id='znedays<?=$id?>' class="reqdays form-control"/>
                                                    </div>                                            
                                                    <span class="help-block">Enter Required Days</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">                                        
                                                <label class="col-md-3 control-label">Remarks</label>
                                                <div class="col-md-6 col-xs-12">
                                                   <textarea class="form-control" rows="5" cols="20" name="remarks"></textarea>          
                                                    <span class="help-block">Enter The Remarks</span>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">File</label>
                                                <div class="col-md-6">                                                                                                                                        
                                                    <input type="file" class="fileinput btn-primary" name="upload<?=$id.$sta?>[]" id="upload<?=$id?>  onchange="selectedfile(event,'<?=$id?>')" multiple accept="image/jpg,image/jpeg,image/png,.pdf""  title="Browse file"/>
                                                    <span class="help-block">Select The Attachment</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                      
                                        
                                    </div>
								  
                                
                           
							<input type='hidden' name='poryear' value='<?=$_REQUEST['poryear']?>'/>
                         <input type='hidden' name='pornumb' value='<?=$_REQUEST['pornumb']?>'/>
                          <input type='hidden' name='ZNCSRNO' value='<?=$_REQUEST['znecode']?>'/>
                           <input type='hidden' name='znepcode' value='<?=$_REQUEST['znepcde']?>'/>
                           
                           <input type='hidden' name='supcode' value='<?=$_REQUEST['supcode']?>'/>
                           
      </div>
      
        <div class="modal-footer">
		
		 <div class="form-group">
								    
								   <div class="col-md-12">  
                                  <button class="btn btn-default">Submit</button> &nbsp; &nbsp;                                    
                                  <button class="btn btn-primary">Finish</button> 
								  </div>
                                   </div>
		
		</div>
      
	   </form>
    </div>
  </div>
	
                   
		
<? }else{
	
	
	
	
	?>
	
	<div class="modal-dialog" role="document">
    <div class="modal-content">
	 <form class="form-horizontal" name='purchase_form<?=$id?>'  id= 'purchase_form<?=$id?>' method='post' action=''>
      <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title" id="exampleModalLabel">Order : <?=$_REQUEST['poryear']?> / <?=$_REQUEST['pornumb']?></h3>
       
      </div>
      <div class="modal-body">
		
									<div class="row">
                                        
                                        <div class="col-md-12">
                                            
                                           
                                                <label class="col-md-3">Process</label>
                                           
                                            
                                                                                
                                                <label class="col-md-3">Remarks</label>
                                         
                                            
                                            
                                           
                                                <label class="col-md-3">File</label>
                                                
                                            
											
                                                <label class="col-md-3">Action</label>
                                                
                                           
                                            
                                        </div>
                                      
                                        
                                    </div>
                             
                                                                      
                                    
                                    <div class="row">
									
									<?
									$sta=0;
   $selopt=select_query_json("select ord.ZNECODE,ord.ZNEPCDE,ord.ZNEPNME,tra.ZNECODE,tra.ZNESTAT from order_tracking_master ord,order_tracking_detail tra where tra.ZNECODE='".$_REQUEST['znecode']."' and tra.PORNUMB='".$_REQUEST['pornumb']."' and tra.PORYEAR='".$_REQUEST['poryear']."' and tra.SUPCODE='".$_REQUEST['supcode']."' and  ord.ZNECODE=tra.ZNECODE and tra.ZNPSRNO=ord.ZNEPCDE and ord.ZNEMODE='R' and ord.DELETED='N' and tra.DELETED='N' and tra.ZNECODE=ord.ZNECODE and (tra.ZNESTAT='N' or tra.ZNESTAT='F' or tra.ZNESTAT='T' or tra.ZNESTAT='R') order by tra.ZNPSRNO asc", "Centra", "TEST");
   foreach($selopt as $key=>$seloptval){$sta=$sta+1; 
   $selimg=select_query_json("select IMGLOCA from order_tracking_history tra where tra.ZNCSRNO='".$_REQUEST['znecode']."' and tra.ZNPSRNO='".$sta."' and tra.PORNUMB='".$_REQUEST['pornumb']."' and tra.PORYEAR='".$_REQUEST['poryear']."'  and tra.DELETED='N' ", "Centra", "TEST");
                                          	//echo "select MAX(tra.IMGLOCA) IMGLOCA from order_tracking_history tra where tra.ZNCSRNO='".$ord_confirm_value['ZNECODE']."' and tra.ZNPSRNO='".$ord_confirm_value['ZNPSRNO']."' and tra.PORNUMB='".$sql_val['PORNUMB']."' and tra.PORYEAR='".$sql_val['PORYEAR']."'  and tra.DELETED='N' ";

									?>
                                        
                                        <div class="col-md-12">
                                            
                                         
                                                
                                                                                                      
                                            <label class="col-md-3"><?=$seloptval['ZNEPNME']?></label>
                                                   
                                            
                                                                                  
                                               
                                                <div class="col-md-3">
                                                   <textarea class="form-control" rows="5" cols="20" name="remarks"></textarea>          
                                                    
                                                </div>
                                           
                                            
                                            
                                            
                                               
                                                <div class="col-md-3">                                                                                                                                        
                                                    <input type="file" class="fileinput btn-primary" name="upload<?=$id.$sta?>[]" id="upload<?=$id?>  onchange="selectedfile(event,'<?=$id?>')" multiple accept="image/jpg,image/jpeg,image/png,.pdf""  title="Browse file"/>
                                                   
                                                </div>
                                            
                                               
                                                <div class="col-md-3">                                                                                                                                        
                                                   <button class="btn btn-default">Submit</button> &nbsp; &nbsp;                                    
												   <button class="btn btn-primary">Finish</button> 
                                                </div>
                                           
                                            
                                        </div>
   <?}?>
                                        
                                    </div>
								  
                                
                           
							<input type='hidden' name='poryear' value='<?=$_REQUEST['poryear']?>'/>
                         <input type='hidden' name='pornumb' value='<?=$_REQUEST['pornumb']?>'/>
                          <input type='hidden' name='ZNCSRNO' value='<?=$_REQUEST['znecode']?>'/>
                           <input type='hidden' name='znepcode' value='<?=$_REQUEST['znepcde']?>'/>
                           
                           <input type='hidden' name='supcode' value='<?=$_REQUEST['supcode']?>'/>
                           
      </div>
      
        <div class="modal-footer">
		
		 <div class="form-group">
								    
								   <div class="col-md-12">  
                                  <button class="btn btn-default">Revert</button> &nbsp; &nbsp;                                    
                                  <button class="btn btn-primary">PDF</button> &nbsp; &nbsp;                                    
                                  <button class="btn btn-primary">History</button> 
								  </div>
                                   </div>
		
		</div>
      
	   </form>
    </div>
  </div>
	
	
<? } } ?>