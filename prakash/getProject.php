
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
//include_once('general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

if (isset($_REQUEST['pjid'])) {
   
 $id = intval($_REQUEST['pjid']);
 $name = $_REQUEST['pjyr'];
// echo $id; 
//echo $name;

//$projectDeatils = select_query_json("select ","Centra","TEST"); 

 
}
?>

				<?
				$approveUser = select_query_json("select EMPCODE,EMPNAME,EMPSRNO ,APPSTAT from approval_project_hierarchy where PRMSCOD = '".$id."' and deleted = 'N' order by APPSRNO","Centra","TEST");
							$a = array();
							foreach($approveUser as $ap){
								foreach($ap as $key => $value){
									$a[]=$value;
								}
							}
								
									if(in_array($_SESSION['tcs_empsrno'],$a)){
								$allow = 1;
								
									}
									else{
										$allow = 0;
										
									}
				$admin_project = select_query_json("select to_char(am.DUEDATE,'dd-MM-yyyy HH:mi:ss AM') DUEDATE,am.* from approval_project_master am where PRMSYER = '".$name."' and PRMSCOD = '".$id."' and DELETED = 'N'", "Centra", 'TEST');
				?>
				<div class = "row">
                   <div class="col-sm-6">
                        <div class="panel-heading" style="font-size:20px;color:red;font-weight:bold;">
                          <?echo $admin_project[0]['PRJNAME']." - " .$admin_project[0]['PRMSCOD'];?>
                        </div>
						</div>
						<div class="col-sm-6">
						 <div class="row pull-right">						
						<a id="history-button" href = "#" data-toggle="modal" data-target="#historyModal" tabindex='2' class="btn btn-info pull-right" title="History"  <i class="fa fa-history"></i> History</a>
						</div>
						
						
					</div>
				</div>
			<div class="panel-body">	
				<!--<div class="row">			
				<?
				$admin_project1 = select_query_json("select * from approval_project_hierarchy where PRMSCOD = '".$id."' and APPSTAT = 'Y'","Centra","TEST");
				foreach($admin_project1 as $approve)
				{
					
					if($approve['EMPSRNO'] ==188){?>
						<h5> ashok sir approved </h5>
					<?}if($approve['EMPSRNO'] ==61579){?>
					<h5> selva sir approved </h5><?}
					if($approve['EMPSRNO'] == 452){?>
					<h5> kumaran sir approved </h5><?}
					?>
					
				<?}?>
				</div>
				<!--  END -->
				<div class="row">	
						
					
						
					<div class="col-sm-6">
						<div class="row form-group">
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Branch<span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-8 col-md-8 ">
								<input type="text" value="<?echo $admin_project[0]['BRNCODE']." - ".$admin_project[0]['BRNNAME'];?>" class="form-control" readonly />
							</div>
						</div>
						
						<div class="row form-group">
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Ledger Code-Name<span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-8 col-md-8">						
								<? $sql_timer_ledger = select_query_json("select * from approval_project_head where PRMSYER = '".$name."' and PRMSCOD = '".$id."' and PRJTITL = '4' and DELETED = 'N'", "Centra", "TEST");								  
								 foreach($sql_timer_ledger as $key => $timer_value) {
								?>	
							
								<div class="form-group input-group">
								<div style="width:100%">
									<div style="width:70%;float:left;">
									 <input type = "text" name ='txt_ledger_name[]' id = "txt_ledger_name" class="form-control" value="<?echo $timer_value['TARNUMB']." - ".$timer_value['TARNAME'];?>" readonly >
										 
									</div>
									<div style="width:30%;float:right">
										<input type="text" name = "txt_values[]" id = "txt_values" class="form-control" value="<?echo $timer_value['PRJVALU'];?>" pattern= "^[0–9]$" readonly>
									</div>
																	
								</div>
								</div>
								 <?}?>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Mode<span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-8 col-md-8">
								<input type="text" value="<?if ($admin_project[0]['BRN_PRJ'] == "B"){ echo 'BRANCH'; } else {echo 'NEW PROJECT';}?>" class="form-control" readonly />
							</div>
						</div>
						<div class="row form-group">
							<div class="col-lg-4 col-md-4"><label style='height:27px;'>Attachments<span style='color:red'>*</span></label></div>
							<div class="col-lg-8 col-md-8">
							<div class = "panel panel-body panel-success">
									<? $file_attach = select_query_json("select * from approval_project_attachment where PRMSYER = '".$name."' and PRMSCOD = '".$id."' and DELETED = 'N'","Centra","TEST");
											if(sizeof($file_attach)> 0){            
									for($filecount = 0;$filecount < sizeof($file_attach) ; $filecount++){
										$filename = $file_attach[$filecount]['FILENAM'];
										
										$folder_path = "../uploads/admin_project/".$dataurl."/";
										$exp = explode(".", $filename);
							echo '<li class=" input-group  form-control">'.$file_attach[$filecount]['FILENAM'].'
							<span style="pull-left" ><i class="fa fa-file" aria-hidden="true"></i> <a href = "../approval-desk/ftp_image_view_pdf.php?pic='.$filename.'&path=approval_desk/approval_project_mgt/2018-19/" target="_blank" >View </a></span></li></br>';
							
											}}
											else{ echo '<li class=" input-group  form-control"> No Attachments
							<span style="pull-left" ><i class="fa fa-file" aria-hidden="true"></i></span></li>'; }
												?>
							</br>
							</div> 
							</div>

						</div>
						<div>
						<div class = 'col-md-12' id="flowUserhed">
						<div class="row form-group ">
								<div class="col-lg-4 col-md-4">
									<label style='height:27px;'>Approve Flow&nbsp;<span style='color:red'>:</span></label>
								</div>
								<div class="col-lg-8 col-md-8">
							<div class = 'form-gro' style ="font-size:12px;margin-left:5px" id="flowUser">			
							<b>
							<ol>
							<? for($j = 0; $j<count($approveUser) ; $j++){
								echo '<li>'.$approveUser[$j]['EMPNAME']." - ".$approveUser[$j]['EMPCODE'];
								if($approveUser[$j]['APPSTAT'] == 'Y'){
									echo '<span><i class="fa fa-check" style="padding-left:8px;color:green" aria-hidden="true"></i></span>';
								}
								echo '</li>';
							}
							?>
							</ol>
							</b>
							</div>
							</div>
						</div>
						</div>
					</div>
						
					</div>
				<div class="col-sm-6">
					<div class="row form-group " >
										<div class="col-lg-4 col-md-4">
											<label style='height:27px;'>Due Date<span style='color:red'>*</span></label>
										</div>
										<div class="col-lg-8 col-md-8">
										
										<input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="txt_due_date" id="datepicker" autocomplete="off" readonly maxlength="11" tabindex='5' value='<?echo $admin_project[0]['DUEDATE'];?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date" >
										
																		
											
										</div>
									</div>
					<div class="row form-group ">
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Project Owner<span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-8 col-md-8">						  
						<?
						  $sql_timer_owner = select_query_json("select * from approval_project_head where PRMSYER = '".$name."' and PRMSCOD = '".$id."' and PRJTITL = '1' and DELETED = 'N'", "Centra", "TEST");
						  foreach($sql_timer_owner as $key => $timer_value) { 
						?>
						  <div class="form-group ">

							<input type="text" name="txt_project_owner[]" id="txt_project_owner"  value="<?echo $timer_value['EMPCODE']." - ".$timer_value['EMPNAME'];?>" placeholder="THE PROJECT OWNER" title="the project owner" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" readonly >
						  </div>
						  <?}?>
							</div>
					</div>	
					<div class="row form-group ">
					<div class="col-lg-4 col-md-4">
						<label style='height:27px;'>Project Head<span style='color:red'>*</span></label>
					</div>
					<div class="col-lg-8 col-md-8">
						 <?
						  $sql_timer_head = select_query_json("select * from approval_project_head where PRMSYER = '".$name."' and PRMSCOD = '".$id."' and PRJTITL = '2' and DELETED = 'N'", "Centra", "TEST");
						  foreach($sql_timer_head as $key => $timer_value) {
						?>
							<div class="form-group ">
							  <input type="text" name="txt_project_head[]" id="txt_project_head" value="<?echo $timer_value['EMPCODE']." - ".$timer_value['EMPNAME'];?>" placeholder="THE PROJECT HEAD" title="the project head" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" readonly>
							</div>
						  <?}?>
					</div>	
					</div>
					
					<div class="row form-group ">
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Project Members<span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-8 col-md-8">
									<?
								  $sql_timer_member = select_query_json("select * from approval_project_head where PRMSYER = '".$name."' and PRMSCOD = '".$id."' and PRJTITL = '3' and DELETED = 'N'", "Centra", "TEST");
								  foreach($sql_timer_member as $key => $timer_value) {
									?>
									<div class="form-group " >
									  <input type="text" name="txt_project_member[]" id="txt_project_member" value="<?echo $timer_value['EMPCODE']." - ".$timer_value['EMPNAME'];?>" placeholder="THE PROJECT MEMBER" title="the project member" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; "readonly>
									</div>
								  <?}?>
							</div>	  
					</div>
				</div> <!-- Col 2 -->
				</div><!-- main -->
					
			</div><!-- Body tag end -->
			<style>
		.modal-footer {
			background: #fff !important;
		</style>
		<div class="modal" id="historyModal" tabindex="-2" role="dialog" aria-labelledby="largeModalHead" aria-hidden="true" style="display: none;">
					<div class="modal-dialog modal-lg " style="width:100%;">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title" id="largeModalHead">Overview</h4>
							</div>
							<div class="modal-body">
							   <div>
								 <div class="non-printable" style='clear:both; border-bottom:1px solid #000; margin-bottom:10px; margin-top: 10px;'></div>
								<table id="tbl_history_list" class="table datatable " style="font-size: 12px;" >
								<thead >
									<style>
									.table > thead > tr > th {
									background: #3f444c !important;
									color: #fff !important;
									}
									</style>
								<tr >
								<th class="center" style="text-align:center">S NO</th>
								<!--<th class="center" style="text-align:center">PROJECT ID</th>-->
								<th class="center" style="text-align:center">ADD USER</th>
								<th class="center" style="text-align:center">ADD DATE</th>
								<!--<th class="center" style="text-align:center" >TARNUMB</th>-->
								<th class="center" style="text-align:center" >DELUSER</th>
								<th class="center" style="text-align:center" >DELDATE</th>
								
								<th class="center" style="text-align:center">ACTION</th>
								</tr>
								</thead>
								<tbody>
								<?$table_history = select_query_json("select to_char(ah.EDTDATE,'dd-MM-yyyy HH:mi:ss AM') EDTDATE,ah.* from approval_project_history ah where PRMSCOD = '".$id."' and PRMSYER = '".$name."' order by HISSRNO desc","Centra","TEST");
								//var_dump($table_history);
								for($hlist = 0 ; $hlist < sizeof($table_history) ; $hlist++) {
								?>
								<tr>
								 <td><? echo ($hlist)+1 ?></td>
								<!--<td class="center" style="text-align:center"><? echo $table_history[$hlist]['PRMSCOD'];?></td>-->
								<?$pname = select_query_json("select EMPCODE,EMPNAME from employee_office where EMPSRNO = '".$table_history[$hlist]['EDTUSER']."'","Centra","TCS");?>
								<td class="center" style="text-align:center"><?echo $pname[0]['EMPCODE']." - ".$pname[0]['EMPNAME']; ?></td>
								<?$time = strtotime($table_history[$hlist]['EDTDATE']);
								$myFormatForView = date("m/d/y g:i A", $time);?>
								<td class="center" style="text-align:center"><? echo $table_history[$hlist]['EDTDATE'];?></td>
								
								<!--<td class="center" style="text-align:center"><? echo $table_history[$hlist]['TARNUMB'];?></td>-->
								<td class="center" style="text-align:center"><? echo $table_history[$hlist]['DELUSER'];?></td>
								<td class="center" style="text-align:center"><? echo $table_history[$hlist]['DELDATE'];?></td>
								
								<td class="center" style="text-align:left"><? echo $table_history[$hlist]['REMARKS'];?></td>
								</tr>
												
								<? }?>
										
                                       
								</tbody>
								</table>
							 	
								
								
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
										<!--<button type="button" class="btn btn-primary">Save changes</button>	-->					
							</div>
						</div>
					</div>
		</div>

				