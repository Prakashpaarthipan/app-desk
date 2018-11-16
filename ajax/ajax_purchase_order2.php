<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(E_ALL);
//print_r($_REQUEST);
if($_REQUEST['action']=='update'){
	echo "entered";
	print_r($_REQUEST);
	if(count($_FILES['upload']['name'])>0)
      { 

      	$time=strtotime('now');
        for($i=0;$i<count($_FILES['upload']['name']);$i++){
        	$count=$i++;
        	echo $_FILES['upload']['name'][$i];

        $q=$_REQUEST['pornumb'].'_'.$_REQUEST['znecode'].'_'.$_REQUEST['znepcode'].'_'.$count.'_'.$time.$_FILES['upload']['name'][$i];
        
        $tmp_name = $_FILES["upload"]["tmp_name"][$i];
        
      
        
        $a1local_file = "../uploads/purchase_order/".$q;
        move_uploaded_file($tmp_name, $a1local_file);
        	}
       
      }
    $currentdate = strtoupper(date('d-M-Y h:i:s A'));
    $count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
    
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNECODE']=$_REQUEST['znecode'];
    $g_fld['ZNEPCDE']=$_REQUEST['znepcode'];
    $g_fld['ZNEDAYS']=$_REQUEST['znecode']+1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_SESSION['tcs_empsrno'];
    $g_fld['REMARKS']=$_REQUEST['remarks'];
    $g_fld['ADDUSER']=$_SESSION['tcs_empsrno'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    
   echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
    
    $up_table="ORDER_TRACKING_DETAIL";
    $up_fld=array();
    $up_fld['EDTUSER']=$_SESSION['tcs_empsrno'];
    $up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $up_fld['REMARKS']=$_REQUEST['remarks'];;
    $up_fld['ZNESTAT']='N';
   echo $where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".$_REQUEST['znecode']."' AND ZNEPCDE='".$_REQUEST['znepcode']."'";
     
    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);

}
if($_REQUEST['action']=='revert'){
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	print_r($_REQUEST);
	
    $count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
    
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNECODE']=$_REQUEST['znecode'];
    $g_fld['ZNEPCDE']=$_REQUEST['znepcode'];
    $g_fld['ZNEDAYS']=$_REQUEST['znecode']+1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_SESSION['tcs_empsrno'];
    $g_fld['REMARKS']=$_REQUEST['remarks'];
    $g_fld['ADDUSER']=$_SESSION['tcs_empsrno'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    
   echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
    
    	$up_table="ORDER_TRACKING_DETAIL";
		$up_fld=array();
		$up_fld['EDTUSER']=$_SESSION['tcs_empsrno'];
		$up_fld['REMARKS']=$_REQUEST['remarks'];
		$up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$up_fld['ZNEFIND']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$up_fld['ZNESTAT']='R';
		$where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".$_REQUEST['znecode']."' AND ZNEPCDE='".$_REQUEST['znepcode']."'";
	    print_r($up_fld);
	    print_r($where_appplan);
	    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);

	   echo	$sel=select_query_json("SELECT ZNECODE,ZNEPCDE,SUPCODE from order_tracking_detail where ((select MAX(ZNECODE||'.'||ZNEPCDE) From order_tracking_detail WHERE (ZNECODE||'.'||ZNEPCDE) < ( SELECT Max(ZNECODE||'.'||ZNEPCDE) FROM order_tracking_detail))>=('".$_REQUEST['znecode']."'||'.'||'".$_REQUEST['znepcode']."')) and SUPCODE='".$_REQUEST['supcode']."' and ZNESTAT='F' and PORNUMB='".$_REQUEST['pornumb']."' order by ZNECODE desc,ZNEPCDE desc", "Centra", 'TEST');
	   
        $up_table="ORDER_TRACKING_DETAIL";
		$up1_fld=array();
		$up1_fld['ADDUSER']=$_SESSION['tcs_empsrno'];
		$up1_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$up1_fld['EDTUSER']="";
		$up1_fld['EDTDATE']="";
		$up1_fld['ZNESTAT']='N';
		$where_appplan1="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".($sel[0]['ZNECODE'])."' AND ZNEPCDE='".$sel[0]['ZNEPCDE']."'";
	    print_r($up1_fld);
	    print_r($where_appplan1);
	   echo $insert_appplan1 = update_test_dbquery($up1_fld, $up_table, $where_appplan1);

}
if($_REQUEST['action']=='finish'){
 if($_FILES['upload']['name'] != '')
      { 
        
        ///----------updating the index to attachment to local
       echo $q=$_FILES['upload']['name'];
        
        $tmp_name = $_FILES["upload"]["tmp_name"];       
        
      
        // echo "\n".$name."\n";
        $a1local_file = "../uploads/purchase_order/".$q;
        move_uploaded_file($tmp_name, $a1local_file);

       
    	}

		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		$count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
	  	print_r($_REQUEST);
		$g_table="ORDER_TRACKING_HISTORY";
		$g_fld=array();
		$g_fld['PORYEAR']=$_REQUEST['poryear'];
		$g_fld['PORNUMB']=$_REQUEST['pornumb'];
		$g_fld['ZNECODE']=$_REQUEST['znecode'];
		$g_fld['ZNEPCDE']=$_REQUEST['znepcode'];
		$g_fld['ZNEDAYS']=$_REQUEST['znecode']+1;
		$g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
		$g_fld['EMPSRNO']=$_SESSION['tcs_empsrno'];
		$g_fld['REMARKS']=$_REQUEST['remarks'];
		$g_fld['ADDUSER']=$_SESSION['tcs_empsrno'];
		$g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		print_r($g_fld);
		echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
 		
		$up_table="ORDER_TRACKING_DETAIL";
		$up_fld=array();
		$up_fld['EDTUSER']=$_SESSION['tcs_empsrno'];
		$up_fld['REMARKS']=$_REQUEST['remarks'];
		$up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$up_fld['ZNEFIND']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$up_fld['ZNESTAT']='F';
		$where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".$_REQUEST['znecode']."' AND ZNEPCDE='".$_REQUEST['znepcode']."'";
	    print_r($up_fld);
	    print_r($where_appplan);
	    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);

	   	$sel=select_query_json("SELECT ZNECODE,ZNEPCDE,SUPCODE from order_tracking_detail where ((select MAX(ZNECODE||'.'||ZNEPCDE) From order_tracking_detail WHERE (ZNECODE||'.'||ZNEPCDE) < ( SELECT Max(ZNECODE||'.'||ZNEPCDE) FROM order_tracking_detail))>('".$_REQUEST['znecode']."'||'.'||'".$_REQUEST['znepcode']."')) and SUPCODE='".$_REQUEST['supcode']."' and ZNESTAT!='F' and PORNUMB='".$_REQUEST['pornumb']."'", "Centra", 'TEST');
	   	print_r($sel);
        $up_table="ORDER_TRACKING_DETAIL";
		$up1_fld=array();
		$up1_fld['ADDUSER']=$_SESSION['tcs_empsrno'];
		$up1_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$up1_fld['EDTUSER']="";
		$up1_fld['EDTDATE']="";
		
		$up1_fld['ZNESTAT']='N';
		$where_appplan1="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".($sel[0]['ZNECODE'])."' AND ZNEPCDE='".$sel[0]['ZNEPCDE']."'";
	    print_r($up1_fld);
	    print_r($where_appplan1);
	    echo $insert_appplan1 = update_test_dbquery($up1_fld, $up_table, $where_appplan1);


}

if($_REQUEST['action']=='selectprocess'){
?>
 <div class="row" style="padding-bottom:10px">
                                        <div class="col-sm-6">
                                            <div class="row form-group">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Setup&nbsp;</label>
                                                </div>
                                                <div class="col-lg-8 col-md-8 ">
                                                    <p><input type="checkbox" id="check_all" name="process[]" />  Select All</p>
															<?
															$sql=select_query_json("select distinct(ZNECODE) FROM order_tracking_section where SECCODE='".$_REQUEST['sectionno']."'", "Centra", 'TEST');
															foreach($sql as $key=>$selprocess){
															$selpro=select_query_json("select distinct(ZNENAME) FROM order_tracking_master where ZNECODE='".$selprocess['ZNECODE']."' and ZNEMODE='R' and DELETED='N'", "Centra", 'TEST');?>
															
																<p><input type="checkbox" class="process" id="process" name="process[]" />  <?=$selpro[0]['ZNENAME']?></p>
															
															<?}?>
                                                </div>
                                             </div>
											
											
                                            <div class="row form-group" id="process_section" style="display:none">

                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Process Name&nbsp;<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                   
													 <div id="add_ledger" class="form-group" >
															<input type="hidden" name="add_ledger" id="add_ledger_row" value="1">
															<div class="form-group input-group">
															<div style="width:100%">
																
																<div style="width:100%;">
																	<input type="text" name = 'txt_value[]' class="form-control" id='txt_values'   placeholder= "PROCESS NAME"  data-toggle ="tooltip" title ="values"  onkeypress="javascript:return isNumber(event)" autocomplete="off" maxlength = "7">
																</div>
															</div>

															<span class="input-group-btn"><button id="add_ledger_button" type="button" onclick="subject_addnew4()" class="btn btn-success btn-add" data-toggle="" title ="Add More">+</button>
															  </span>

															</div>
														</div>


                                                </div>
                                            </div>
                                           <div class="form-group trbg" style='min-height:40px; padding-top:10px;padding-bottom:10px'>
											<div class="col-lg-6 col-md-6" style=' text-align:center; padding-right:10px;'>
												<!-- <div id="error_message" class="ajax_response" style="float:left;text-align:center">sda</div></br>
												<div id="success_message" class="ajax_response" style="float:left;text-align:center">asd</div>--> 
											  <input type="button" class="btn btn-success" name="btn_submit" id="btn_submit" value="Submit" onclick="return validate()"/>
											  <!--<input type ="submit" class ="btn btn-success" name="submit" id="submit" value ="Submit" data-toggle="tooltip" title ="submit" />-->
												<button type="button" tabindex='3' class="btn btn-info" data-toggle="tooltip" data-placement="top" title="addnew" onclick="addnewrow()"> Add New</button>
											</div>
										<div class='clear clear_both'>&nbsp;</div>
										
										</div>
										
										</div>
										
							
						
										
										
								</div>	
							<script>	
							$(document).ready(function(){
								$("#check_all").click(function(){
										//alert("just for check");
										if(this.checked){
											$('.process').each(function(){
												this.checked = true;
											})
										}else{
											$('.process').each(function(){
												this.checked = false;
											})
										}
									});
								});
						</script>
<?}	
if($_REQUEST['action']=='selectprocess1') {	

	?>
 <div class='row' style="padding:10px">
 	<div class="col-md-12">
 		<div class="col-md-2">
 			<h4> Section Name </h4> 
 		</div> 

 		<div class="col-md-10">
 			<h4> Process List </h4> 
 		</div>

 	</div>	</div>
 <div class='row' style="padding:10px">
 	<div class="col-md-12">
 		<div class="col-md-2">
 			<label> Setup </label> 
 		</div> 

 		<div class="col-md-10">
 			<p><input type="checkbox" id="process" name="process[]" checked />  Select All</p>
 			<?
 			$sql=select_query_json("select distinct(ZNECODE) FROM order_tracking_section where SECCODE='".$_REQUEST['sectionno']."'", "Centra", 'TEST');
 			foreach($sql as $key=>$selprocess){
 			$selpro=select_query_json("select distinct(ZNENAME) FROM order_tracking_master where ZNECODE='".$selprocess['ZNECODE']."' and ZNEMODE='R' and DELETED='N'", "Centra", 'TEST');?>
 			
 				<p><input type="checkbox" name="process[]" checked/>  <?=$selpro[0]['ZNENAME']?></p>
 			
 			<?}?>
 			
 		</div>

 	</div>


 </div>
<div class="row">     
                     <div id="add_ledger" class="form-group" style="display:none">
													<input type="hidden" name="add_ledger" id="add_ledger_row" value="1">
													<div class="form-group input-group">
													<div style="width:100%">
														<div style="width:50%;float:left">
															<input type="text" name = 'txt_value[]' class="form-control" id='txt_values'   placeholder= "VALUES"  data-toggle ="tooltip" autocomplete="off" >
														</div>
														<span class="input-group-btn" style="width:10%;float:right"><button id="add_ledger_button" type="button" onclick="subject_addnew4()" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More">+</button>
													   </span>
													</div>
													
													

													</div>
					 </div>
                    
                    
                  </div>

<div class='row' style="padding:10px">
	<div class="col-md-1">
	 <button class="btn btn-success btn-sm"> Submit </button>
</div>
<!-- <div class="col-md-1">
	 <button class="btn btn-primary btn-sm" id="addnew" onclick="addnewrow()"> Add New </button> 
</div> -->
</div>


<? }  
?>
