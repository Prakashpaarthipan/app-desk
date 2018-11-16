<?
session_start();
error_reporting(0);
header('X-UA-Compatible: IE=edge');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

$menu_name = 'APPROVAL DESK';
$inner_submenu = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc", "Centra", 'TCS');
if($_SESSION['tcs_empsrno'] != '') {
    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' order by MNUCODE Asc", "Centra", 'TCS');
} else {
    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and SUPCODE = '".$_SESSION['tcs_userid']."' order by MNUCODE Asc", "Centra", 'TCS');
}
if($inner_menuaccess[0]['VEWVALU'] == 'N' or $inner_menuaccess[0]['MNUCODE'] == 'VEWVALU') { ?>
    <script>alert("You dont have access to view this"); 
	window.location='home.php';</script>
<? exit();
}
/*if(!isset($_SERVER['HTTP_REFERER'])){?>
    <!-- redirect them to your desired location -->
	<script>alert("You dont have access to view this"); 
	window.location='home.php';</script>
<?exit; }*/
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
	

if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_request'] != '')
{
   /* switch($txt_process_type) {
        case 1 : // ORIGINAL APPROVAL NEED
            // Update into approval_request Table for Original Print Need
            $tbl_approval_request = "approval_request";
            $field_approval_request = array();
            $field_approval_request['APPRMRK']  = "";
            $where_approval_request = " arqsrno = 1 and aprnumb like '".$txt_aprnumb."' ";
            // print_r($field_approval_request);
            $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
            // Update into approval_request Table for Original Print Need
            break;

        case 2 : // APPROVAL AUTO FORWARD
            /* Update into approval_request Table for APPROVAL AUTO FORWARD
            $tbl_approval_request = "approval_request";
            $field_approval_request = array();
            $field_approval_request['APPRMRK']  = "";
            $where_approval_request = " arqsrno = 1 and aprnumb like '".$txt_aprnumb."' ";
            // print_r($field_approval_request);
            // $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
            // Update into approval_request Table for APPROVAL AUTO FORWARD */
          /*  break;

        case 3 : // PROJECT ID CHANGE, AFTER APPROVAL
            $sql_apst = select_query_json("select * from approval_request where appstat = 'A' and aprnumb like '".$txt_aprnumb."'", "Centra", 'TCS');
            if(count($sql_apst) > 0) {
                // Update into approval_request Table for PROJECT ID CHANGE, AFTER APPROVAL
                $tbl_approval_request = "approval_request";
                $field_approval_request = array();
                $field_approval_request['APRCODE']  = $slt_project;
                $where_approval_request = " appstat = 'A' and aprnumb like '".$txt_aprnumb."' ";
                // print_r($field_approval_request);
                $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
                // Update into approval_request Table for PROJECT ID CHANGE, AFTER APPROVAL
            } else { ?>
                <script>window.location='admin_process.php?action=add&msg=This approval is not yet approved.';</script>
                <?php exit();
            }
            break;
        default:
            break;
    }

    // exit;
    if($update_approval_request == 1) { ?>
        <script>window.location='admin_process.php?status=success';</script>
        <?php exit();
    } else { ?>
        <script>window.location='admin_process.php?action=add&status=failure';</script>
        <?php exit();
    }
    */
}

if($inner_menuaccess[0]['VEWVALU'] == 'Y') { // Menu Permission is allowed ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <!-- META SECTION -->
    <title>Admin Project Modify :: Approval Desk :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <!-- Select2 -->
    <link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">
    <link href="css/jquery-customselect.css" rel="stylesheet" />
        <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
        <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
		<link href="css/facebook_alert.css" rel="stylesheet" type="text/css">
		
		
        <!-- multiple file upload -->
        <link href="css/jquery.filer.css" rel="stylesheet">

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
		
		.table > thead > tr > th {
		background: #3f444c !important;
		color: #fff !important;
		}
		
	
    </style>
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
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
                    <li class="active">Admin Project Modify</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Admin Project Modify</h3>
                        </div>
		<div class="panel-body">
						<?
						$project_current_user = select_query_json("select PRJTITL from approval_project_master pm , approval_project_head ph  
							where pm.PRMSYER = ph.PRMSYER and pm.PRMSCOD = ph.PRMSCOD and PRJTITL = '4' and ph.DELETED = 'N' and
							ph.PRMSCOD = ".$id."  ORDER BY PM.PRMSCOD", "Centra", "TEST");
						$project_user = select_query_json("select PRJTITL from approval_project_master pm , approval_project_head ph  
							where pm.PRMSYER = ph.PRMSYER and pm.PRMSCOD = ph.PRMSCOD and PRJTITL = '4' and ph.DELETED = 'N' and
							ph.PRMSCOD = ".$id."  ORDER BY PM.PRMSCOD", "Centra", "TEST");
						
					//if(count($project_current_user)== 0){?>
						    <!--<script>alert("Data not Available. Create A New");window.location="index.php";</script> -->
						<?//php exit();
						//}
						
						//else{
							
					?>
		  
					<? $sql_reqid = select_query_json("select * from APPROVAL_TOPCORE where ATCCODE = '".$_REQUEST['reqid']."' order by ATCCODE", "Centra", "TCS");
					
				  ?>
			<form role="form" id='frm_project_modify' name='frm_project_modify' method='post' enctype="multipart/form-data"  >
				<div class="col-lg-12 col-md-12 tooltip-demo" style='border-right: 1px solid #d4d4d4;'>
	
					<? if($_REQUEST['status'] == 'failure') { ?>
					<div class="form-group trbg">
						<div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Failed in updation. Kindly try again!
						</div>
					</div>
					<? } elseif($_REQUEST['status'] == 'success') { ?>
					<div class="form-group trbg">
						<div class="alert alert-success alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Successfully updated. Kindly Verify!
						</div>
					</div>
					<? } elseif($_REQUEST['msg'] != '') { ?>
					<div class="form-group trbg">
						<div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<?=$msg?>
						</div>
					</div>
					<? } ?>
					<div class='clear clear_both'></div>

				</div>

			  <?
				$admin_project = select_query_json("select * from approval_project_master where PRMSYER = '".$c_year."' and PRMSCOD = '".$id."' and DELETED = 'N'", "Centra", 'TEST');
				?>
					<div class="col-md-12 col-md-offset-4" style="padding-bottom:20px">
									<div class="row">
									<tr>
									<td colspan="3" style="width:100%; padding-bottom: 10px; padding-top: 10px; height:20px; text-align:center;" id="id_priority">
									<input type="radio" name="slt_priority" id="slt_priority_1" value="1" <?if ($admin_project[0]['PRIMODE'] == 1){?>checked   <?}else{?>disabled<?}?> onclick="onChangeradio(this)">&nbsp;&nbsp;<span class="badge badge-ap1" title="MD WORK ORDERS - DO RIGHT AWAY - IMPORTANT AND URGENT [ Maximum 1 Days Allowed ]" style="font-size:16px; background-color: #ed1c24 !important; color: #fff200 !important; font-weight:bold;">AP-1</span>&nbsp;&nbsp;&nbsp;
									<input type="radio" name="slt_priority" id="slt_priority_2" value="2"  <?if ($admin_project[0]['PRIMODE'] == 2){?>checked  <?}else{?>disabled<?}?> onclick="onChangeradio(this)" title="GM &amp; DGM WORK ORDERS - PLAN TO DO ASAP - IMPORTANT AND NOT URGENT [ Maximum 2 Days Allowed ]">&nbsp;&nbsp;<span class="badge badge-ap2" title="GM &amp; DGM WORK ORDERS - PLAN TO DO ASAP - IMPORTANT AND NOT URGENT [ Maximum 2 Days Allowed ]" style="font-size:16px; background-color: #00a651 !important; color: #fff200 !important; font-weight:bold;">AP-2</span>&nbsp;&nbsp;&nbsp;
									<input type="radio" name="slt_priority" id="slt_priority_3" value="3"  <?if ($admin_project[0]['PRIMODE'] == 3){?>checked  <?}else{?>disabled<?}?>  onclick="onChangeradio(this)" title="HOD &amp; MANAGER WORK ORDERS - DELEGATE - URGENT AND NOT IMPORTANT [ Maximum 3 Days Allowed ]" >&nbsp;&nbsp;<span class="badge badge-ap3" title="HOD &amp; MANAGER WORK ORDERS - DELEGATE - URGENT AND NOT IMPORTANT [ Maximum 3 Days Allowed ]" style="font-size:16px; background-color: #fff200 !important; color: #000000 !important; font-weight:bold;">AP-3</span>&nbsp;&nbsp;&nbsp;
									<input type="radio" name="slt_priority" id="slt_priority_4" value="4"  <?if ($admin_project[0]['PRIMODE'] == 4){?>checked  <?}else{?>disabled<?}?>  onclick="onChangeradio(this)" title="FUTURE WORK ORDERS - IMPORTANT IN FUTURE BUT NOT URGENT [ Maximum 4 Days Allowed ]">&nbsp;&nbsp;<span class="badge badge-ap4" title="FUTURE WORK ORDERS - IMPORTANT IN FUTURE BUT NOT URGENT [ Maximum 4 Days Allowed ]" style="font-size:16px; background-color: #6e3694 !important; color: #fff200 !important; font-weight:bold;">AP-4</span>&nbsp;&nbsp;&nbsp;
									<input type="radio" name="slt_priority" id="slt_priority_5" value="5"  <?if ($admin_project[0]['PRIMODE'] == 5){?>checked  readOnly disabled <?}else{?>disabled<?}?>  onclick="onChangeradio(this)" title="PENDING / CANCEL / REJECT - NOT IMPORTANT BUT NOT URGENT [ Maximum 5 Days Allowed ]">&nbsp;&nbsp;<span class="badge badge-ap5" title="PENDING / CANCEL / REJECT - NOT IMPORTANT BUT NOT URGENT [ Maximum 5 Days Allowed ]" style="font-size:16px; background-color: #32327b !important; color: #fff200 !important; font-weight:bold;">AP-5</span>&nbsp;&nbsp;&nbsp;
									</td>
							
									</tr>
										
									</div>
								</div>
					
					<!-- Top Core -->
					<div class="row form-group" style="margin-bottom:30px;margin-top:10px">
					<div class="col-sm-6">
						<div class="row form-group" style="height:35px;">
						<label style="margin-left:15px"  id = "projectcode">Project Name:&nbsp; <span style="font-weight:bold;font-size:16px;color:#e04b4a; margin-left:30px;"> <?echo $admin_project[0]['PRJNAME']." - " .$admin_project[0]['PRMSCOD'];?></span></label>
						</div>
						<input type="hidden" name="prjcode" id="pjcode" value='<?=$id?>' />
					</div>
					<!--<div class="col-sm-6">
						<div class="row form-group" style="height:35px;display:none">
						<?//if($admin_project[0]['CAPPUSR'] ==0 ){?>
						<!--<a id="reject-button" type="submit" tabindex='2' class="btn btn-danger pull-right" title="Reject" ><i class="fa fa-ban"></i> Reject</a>-->
						<?//}else{?>
						<!--<a id="reject-button" type="submit" tabindex='2' class="btn btn-danger pull-right" title="Reject"  ><i class="fa fa-ban"></i> Reject</a><?//}?>
						</div>
					</div>-->

					<hr/>
					<hr/>
					</div>
						<div class="col-sm-6">

			<div class="row form-group">
				<div class="col-lg-4 col-md-4">
					<label style='height:27px;'>Branch&nbsp;<span style='color:red'>*</span></label>
				</div>
				<div class="col-lg-8 col-md-8 ">
					
						<input type="text" value="<?echo $admin_project[0]['BRNCODE']." - ".$admin_project[0]['BRNNAME'];?>" class="form-control" disabled style="background:#fff"/>
						<!--<select class="form-control" autofocus required name='txt_branch_type' id='txt_branch_type' data-toggle="tooltip" data-placement="top" title="branch type">
							  <option value="<?// echo $sql_project[$project_i]['BRNNAME'];?>"> <?// echo $sql_project[$project_i]['BRNNAME'];?> </option>
					</select>-->
				</div>
			 </div>
							 <!--
							 <div class="row form-group">
								<div class="col-lg-4 col-md-4">
									<label style='height:27px;'>Top Core<span style='color:red'>*</span></label>
								</div>
								<div class="col-lg-8 col-md-8">
									<input type="text" value="<?//echo $admin_project[0]['TOPCORE'];?>" class="form-control" disabled style="background:#fff"/>
									<!--  <select class="form-control" autofocus required name='txt_top_core' id='txt_top_core' data-toggle="tooltip" data-placement="top" title="choose the top core" >
									 </select> 
								</div>
							</div>
							<div class="row form-group">
								<div class="col-lg-4 col-md-4">
									<label style='height:27px;'>Core<span style='color:red'>*</span></label>
								</div>
								<div class="col-lg-8 col-md-8">
									<input type="text" value="<?//echo $admin_project[0]['SUBCORE']." - " .$admin_project[0]['SUBCRNM'];?>" class="form-control" disabled style="background:#fff"/>
								</div>
						   </div>-->
							

			<div class="row form-group">

				<div class="col-lg-4 col-md-4">
					<label style='height:27px;'>Ledger Code-Name&nbsp;<span style='color:red'>*</span></label>
				</div>
				<div class="col-lg-8 col-md-8">
								<div id="add_ledger" class="form-group">
								<input type="hidden" name="add_ledger" id="add_ledger_row" value="1">

							<?
				  $sql_timer = select_query_json("select * from approval_project_head where PRMSYER = '".$c_year."' and PRMSCOD = '".$id."' and PRJTITL = '4' and DELETED = 'N'", "Centra", "TEST");
				   $led_id =1;
				  foreach($sql_timer as $key => $timer_value) {
							?>	
							
								<div class="form-group input-group">
								<div style="width:100%">
									<div style="width:70%;float:left;">
									 <input type = "text" name ='txt_ledger_name[]' id = "txt_ledger_name<?echo $led_id;?>" class="form-control findHod" value="<?echo $timer_value['TARNUMB']." - ".$timer_value['TARNAME'];?>" readonly >
										 <!--<select class="form-control" autofocus required name='txt_ledger_name[]' id='txt_ledger_name' data-toggle="tooltip" data-placement="top" title="Ledger Name" >
									  </select> -->
									</div>
									<div style="width:30%;float:right">
										<input type="text" name = "txt_values[]" id = "txt_values" class="form-control valold" value="<?echo $timer_value['PRJVALU'];?>" pattern= "^[0–9]$" onkeypress="javascript:return isNumber(event)" <?if($allow == 0 ){?> readonly <?}else{}?> >
									</div>
									
									</div>
									  <span class="input-group-btn"><button id="add_ledger_button<?echo $led_id;?>" type="button"  class="btn btn-danger btn-remove" onclick ="javascript:var lid = document.getElementById('txt_ledger_name<?echo $led_id;?>');ledgerremove(lid)">-</button>
									  </span>
									</div>
									<script>
								function ledgerremove(lid){
									//alert(lid);
								//var sel = document.getElementById("txt_ledger_name<?echo $led_id;?>");
								//var myVar = sel.options[sel.selectedIndex].value;
								var myVar = lid.value;
								//alert(myVar);
								var ledgerid = myVar.split(" - ");
								var ledgervalue = ledgerid [0];
								//alert(ledgervalue);
								if(myVar != " - "){
								if(confirm("Are you sure want to remove : ("+myVar+") from the list?")){
								var p_id = $("#pjcode").val();
								$.ajax({
									type:"post",
									url:"prakash/project_update.php?action=removeledger&ledgerdata="+ledgervalue+"&pid="+p_id+"",
									//data:({id1 : id1},{id2 : id2}),
									cache:false,
									/* above the code is for general approval*/
									dataType: 'text',
									success: function(data, textStatus, jqXHR){
										$("#load_page").show();
										//alert (" Thank You .project is approved");
										alert("Ledger Data has Removed");
										$("#txt_mode_type").val("");
										$("#flowUser").html("");
										
										window.location.reload(true);
										//console.log(data);
										},
									error: function(jqXHR, textStatus, errorThrown) {
										alert('An error occurred... Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information!');
										window.location.reload(true);
										//$("#load_page").fadeOut("fast");
										}
								});	}else{
								 window.location.reload(true);
								}
								}
								else{
									alert("No Values Placed Here! ");
								}
						}
		
									</script>
									<!--<script>
										$( document ).on( 'click', '.btn-remove', function ( event ) {
											 event.preventDefault();
											 
											 $(this).closest( '.form-group' ).remove();
										});
									</script>-->
									
									<?
									$led_id++;}
									?>
								<div class="form-group input-group"id="dynamic" style="display:none;"  >
									<div style="width:100%" >
										<div style="width:70%;float:left; font-weight:bold;">
											<!--<select class="form-control" autofocus  name='txt_ledger_name[]' placeholder= "CHOOSE THE LEDGER NAME" id='txt_ledger_name' data-toggle="tooltip" data-placement="top" title="Ledger Name" >
										</select>-->
										</div>
										
										 <div style="width:30%;float:right;margin-right:5px; font-size:10px;position:center;vertical-align:center;">
											<!--<input type="text" name = 'txt_value[]' class="form-control" id='txt_value' placeholder= "Values" onkeypress="javascript:return isNumber(event)" >-->
											Press + to Add Ledger
										</div>
									</div>
									<span class="input-group-btn" style = "margin-right:35px;" ><button id="add_ledger_button" type="button" onclick="subject_addnew4()" class="btn btn-success btn-add">+</button>
									  </span>
									  
									
								</div>
								<form id= "newtable_form" name = "newtable_form">
									<div id="add_ledger1" class="form-group">
									<input type="hidden" name="add_ledger1" id="add_ledger_row1" value="5">
									</div>
									</form>
								
							
							</div>
							

						</div>
					</div>
					<div class="row form-group">

					<div class="col-lg-4 col-md-4">
						<label style='height:27px;'>Mode&nbsp;<span style='color:red'>*</span></label>
					</div>
					<div class="col-lg-8 col-md-8">
						<!--<input type="text" value="<?//if ($admin_project[0]['BRN_PRJ'] == "B"){ echo 'BRANCH'; } else {echo 'NEW PROJECT';}?>" class="form-control" />-->
					<select class="form-control" autofocus required name='txt_mode_type' id='txt_mode_type' data-toggle="tooltip" data-placement="top" title="project type">
						 <?if($admin_project[0] ['BRN_PRJ'] == "B"){ echo '<option value="B"> BRANCH </option>';
						 }
						 else{ echo '<option value="P"> NEW PROJECT </option>';}?>
					</select>

					</div>
					</div>
					
					<div class="row form-group">
						<div class="col-lg-4 col-md-4"><label style='height:27px;'>Attachments&nbsp;<span style='color:red'>*</span></label></div>
						<div class="col-lg-8 col-md-8">
						
					<div class = "panel panel-body panel-success">
							<? $file_attach = select_query_json("select * from approval_project_attachment where PRMSYER = '".$c_year."' and PRMSCOD = '".$id."' and DELETED = 'N'","Centra","TEST");
							$c_attch = sizeof($file_attach);
							        if($c_attch> 0){            
							for($filecount = 0;$filecount < $c_attch ; $filecount++){
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
					
					<div id = "add_attach" class="form-group" >
					<input type="hidden" name="add_attach1" id="add_attach_row" value="1">
					</div>
					<!-- Flow User -->
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
							<? /*$sortFlow = array();
						$temparray = array();
							$k =array('1657','19256','1118','1986','3','2','1'); // Order the flowuser here
								for($v = 0 ; $v <sizeof($approveUser); $v++)
								{
									for($f = 0 ; $f <sizeof($approveUser); $f++)
								{
									if ($approveUser [$f]['EMPCODE'] == $k [$v]){
										$sortFlow [$v] = $approveUser[$f];
										array_push($temparray,$sortFlow [$v]['FLOWUSR']);
									}
								}
								}
								*/
							for($j = 0; $j<count($approveUser) ; $j++){
								
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
					<!-- END-->
					</div>
					<div class="col-sm-6">
						<div class="row form-group ">
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Project Name&nbsp;<span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-8 col-md-8">

								<input type='text' name='txt_project_name' value='<?echo $admin_project[0]['PRJNAME'];?>' class='form-control' id ="txt_project_name" style='text-transform:uppercase;'<?if($_SESSION['tcs_empsrno']== 21344 ||$_SESSION['tcs_empsrno']== 43400||$_SESSION['tcs_empsrno']== 20118 ){}else{?> readonly<?}?>>
							</div>
						</div>
						<div class="row form-group " >
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Due Date&nbsp;<span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-8 col-md-8">
							
							<input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="txt_due_date" id="datepicker" autocomplete="off"  maxlength="11" tabindex='5' value='<? $date=date_create($admin_project[0]['DUEDATE']);
								echo date_format($date,"d-M-Y");?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
							</div>
							<script>
							
							</script>
							</div>
						<div class="row form-group ">
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Project Owner&nbsp;<span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-8 col-md-8">
							  <div id="add_emp" class="form-group">
								<input type="hidden" name="partint3" id="partint" value="1">
						<?
						  $sql_timer = select_query_json("select * from approval_project_head where PRMSYER = '".$c_year."' and PRMSCOD = '".$id."' and PRJTITL = '1' and DELETED = 'N'", "Centra", "TEST");$ownerid = 1;
						  foreach($sql_timer as $key => $timer_value) { 
						?>
						  <div class="form-group input-group">

							<input type="text" name="txt_project_owner[]" id="txt_project_owner<?echo $ownerid;?>" maxlength="50" value="<?echo $timer_value['EMPCODE']." - ".$timer_value['EMPNAME'];?>" placeholder="THE PROJECT OWNER" title="the project owner" class="form-control find_empcode find_owner" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" readonly>
							<span class="input-group-btn"><button id="add_emp_button<?echo $ownerid;?>" type="button" class="btn btn-danger btn-remove" onclick = "javascript:var myown = document.getElementById('txt_project_owner<?echo $ownerid;?>');ownerremove(myown)">-</button></span>

						  </div>
						  <script>
						  function ownerremove(myown){
							  //var ooo = <?echo $ownerid;?>	;
							  
								//var own = document.getElementById("txt_project_owner"+ooo);
								//var myVar = sel.options[sel.selectedIndex].value;
								
								var prjOwn = $(".find_owner").length;
								//alert(prjOwn);
								if(prjOwn == 1){
									alert("Atleast One Project Owner User is Mandatory!");
								}
								else{
											var myown1 = myown.value;
											//alert(myown1);
											var ownerid = myown1.split(" - ");
											var ownercode = ownerid[0];
											//alert(ownercode);
											if(confirm("Are sure want to remove :(" +myown1+ ") from the list?")){
											var p_id = $("#pjcode").val();
											$("#load_page").fadeIn("fast");
											$.ajax({
												type:"post",
												url:"prakash/project_update.php?action=removeowner&ownerdata="+ownercode+"&pid="+p_id+"",
												//data:({id1 : id1},{id2 : id2}),
												//cache:false,
												/* above the code is for general approval*/
												dataType: 'text',
												success: function(data, textStatus, jqXHR){
													//alert (" Thank You .project is approved");
													alert("Removed");
													window.location.reload(true);
													console.log(data);
													},
												error: function(jqXHR, textStatus, errorThrown) {
													alert('An error occurred... Try again!');
													window.location.reload(true);
													//$("#load_page").fadeOut("fast");
													}
											});	
									  }else{
									  window.location.reload(true);}
								}
							}
						
						  </script>
						 <!-- <script>
							$( document ).on( 'click', '.btn-remove', function ( event ) {
								event.preventDefault();
								$(this).closest( '.form-group' ).remove();
							 });
						  </script>-->
						  <?
						  $ownerid++;
						  }
						  ?>

						   <div class="form-group input-group" style="display:none;font-weight:bold" id = "dynamic_owner" >
							<div style="width:100%" >
										<div style="width:70%;float:left; font-weight:bold;"></div>
										<div style="width:30%;float:right;margin-right:5px; font-size:10px;vertical-align:middle">Press + to Add Project Owner</div>
							<!--   <input type="text" name="txt_project_owner[]" id="txt_project_owner" value="" placeholder="SELECT THE PROJECT OWNER" title="select the project owner" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;">Press + to Add project owner-->
							</div>
							  <span class="input-group-btn" style = "margin-right:35px;"><button id="add_emp_button" type="button" onclick="subject_addnew()" class="btn btn-success btn-add">+</button>
							  </span>
							</div>
						
						</div>
					</div>
				</div>
				<div  class="input-group ">
									<hr class="s"/>
										</div>
				<div class="row form-group ">
					<div class="col-lg-4 col-md-4">
						<label style='height:27px;'>Project Head&nbsp;<span style='color:red'>*</span></label>
					</div>
					<div class="col-lg-8 col-md-8">
						 <div id="add_emp1" class="form-group">
							<input type="hidden" name="partint3" id="partint" value="1">

						   <?
						  $sql_timer = select_query_json("select * from approval_project_head where PRMSYER = '".$c_year."' and PRMSCOD = '".$id."' and PRJTITL = '2' and DELETED = 'N'", "Centra", "TEST");$headid = 1;
						  foreach($sql_timer as $key => $timer_value) {
						?>
							<div class="form-group input-group">

							  <input type="text" name="txt_project_head[]" id="txt_project_head<?echo $headid;?>" value="<?echo $timer_value['EMPCODE']." - ".$timer_value['EMPNAME'];?>" placeholder="THE PROJECT HEAD" title="the project head" class="form-control find_empcode find_head " data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" readonly>
							  <span class="input-group-btn"><button id="add_emp_button<?echo $headid;?>" type="button" class="btn btn-danger btn-remove" onclick="javascript:var head = document.getElementById('txt_project_head<?echo $headid;?>');headremove(head)">-</button></span>
							  </div>
							   <script>
								function headremove(head){
								//var ooo = <?echo $headid;?>	;
							  
								//var head = document.getElementById("txt_project_head<?echo $headid;?>");
								//var myVar = sel.options[sel.selectedIndex].value;
								
								//console.log(head);
								var prjHead = $(".find_head").length;
								
								if(prjHead == 1){
									alert("Atleast One Project Head User is Mandatory!");
								}
								else{
									var myhead = head.value;
									//alert(myhead);
									var headid = myhead.split(" - ");
									var headcode = headid[0];
									//alert(headcode);
									if(confirm("Are you sure want to remove :("+myhead+") from list?")){
									var p_id = $("#pjcode").val();
									$("#load_page").fadeIn("fast");
									$.ajax({
										type:"post",
										url:"prakash/project_update.php?action=removehead&headdata="+headcode+"&pid="+p_id+"",
										//data:({id1 : id1},{id2 : id2}),
										//cache:false,
										/* above the code is for general approval*/
										dataType: 'text',
										success: function(data, textStatus, jqXHR){
											//alert (" Thank You .project is approved");
											alert("Removed");
											window.location.reload(true);
											console.log(data);
											},
										error: function(jqXHR, textStatus, errorThrown) {
											alert('An error occurred... Try again!');
											window.location.reload(true);
											//$("#load_page").fadeOut("fast");
											}
									});	
									}else{
									window.location.reload(true);}
								
								}
							}
								/*$( document ).on( 'click', '.btn-remove', function ( event ) {
									event.preventDefault();
									$(this).closest( '.form-group' ).remove();
								 });*/
								</script>
								<?$headid++;}?>
						<div class="form-group input-group" style="font-weight:bold;display:none;" id = "dynamic_head">
						<div style="width:100%" >
										<div style="width:70%;float:left; font-weight:bold;"></div>
										<div style="width:30%;float:right;margin-right:5px; font-size:10px;position:center;">Press + to Add Project Head</div>
					 
						</div>
						  <span class="input-group-btn" style = "margin-right:35px;"><button id="add_emp_button" type="button" onclick="subject_addnew1()" class="btn btn-success btn-add">+</button>
						  </span>
						</div>
					</div>
				</div>
			</div>
			<div  class="input-group ">
									<hr class="s"/>
										</div>
						<div class="row form-group ">
							<div class="col-lg-4 col-md-4">
								<label style='height:27px;'>Project Members&nbsp;<span style='color:red'>*</span></label>
							</div>
					
							<div class="col-lg-8 col-md-8">

									 <div id="add_emp2" class="form-group">
									<input type="hidden" name="partint3" id="partint" value="1">
									<?
								  $sql_timer = select_query_json("select * from approval_project_head where PRMSYER = '".$c_year."' and PRMSCOD = '".$id."' and PRJTITL = '3' and DELETED = 'N'", "Centra", "TEST");$memberid = 1;
								  foreach($sql_timer as $key => $timer_value) {
									?>

									<div class="form-group input-group" >

									  <input type="text" name="txt_project_member[]" id="txt_project_member<?echo $memberid;?>" value="<?echo $timer_value['EMPCODE']." - ".$timer_value['EMPNAME'];?>" placeholder="THE PROJECT MEMBER" title="the project member" class="form-control find_empcode find_member" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; "readonly>

									  <span class="input-group-btn"><button id="add_emp_button<?echo $memberid;?>" type="button" class="btn btn-danger btn-remove" onclick="javascript:var member = document.getElementById('txt_project_member<?echo $memberid;?>');memberremove(member)">-</button></span>
									  </div>

									  <script>
									  function memberremove(member){
								//var ooo = <?echo $headid;?>	;
							  
								//var member = document.getElementById("txt_project_member<?echo $memberid;?>");
								//var myVar = sel.options[sel.selectedIndex].value;
								
								//console.log(head);
								var prjMem = $(".find_member").length;
								
								if(prjMem == 1){
									alert("Atleast One Project Member User is Mandatory!");
								}
								else{
										var mymember = member.value;
										//alert(mymember);
										var memberid = mymember.split(" - ");
										var membercode = memberid[0];
										//alert(membercode);
										if(confirm("Are sure want to remove : ("+mymember+") from the list?")){
										var p_id = $("#pjcode").val();
										$("#load_page").fadeIn("fast");
										$.ajax({
											type:"post",
											url:"prakash/project_update.php?action=removemember&memberdata="+membercode+"&pid="+p_id+"",
											//data:({id1 : id1},{id2 : id2}),
											//cache:false,
											/* above the code is for general approval*/
											dataType: 'text',
											success: function(data, textStatus, jqXHR){
												//alert (" Thank You .project is approved");
												alert("Removed");
												window.location.reload(true);
												console.log(data);
												},
											error: function(jqXHR, textStatus, errorThrown) {
												alert('An error occurred... Try again!');
												window.location.reload(true);
												//$("#load_page").fadeOut("fast");
												}
										});	}else{
										window.location.reload(true);}
								}
							}
								/*$( document ).on( 'click', '.btn-remove', function ( event ) {
									event.preventDefault();
									$(this).closest( '.form-group' ).remove();
								 });*/
									/*	 $( document ).on( 'click', '.btn-remove', function ( event ) {
											event.preventDefault();
											$(this).closest( '.form-group' ).remove();
										 });*/
										</script>
										<? $memberid++;}?>
									<div class="form-group input-group" style="font-weight:bold;display:none;" id = "dynamic_member" >
									<div style="width:100%" >
										<div style="width:70%;float:left; font-weight:bold;"></div>
										<div style="width:30%;float:right;margin-right:5px; font-size:10px;position:center;">Press + to Add Project Member</div>
										</div>
									  <span class="input-group-btn" style = "margin-right:35px;"><button id="add_emp_button" type="button" onclick="subject_addnew2()" class="btn btn-success btn-add">+</button>
									  </span>

									</div>
								</div>

							</div>

						</div>
						<div  class="input-group ">
									<hr class="s"/>
										</div>
					</div>
				</div>




				 <!--</div> head finish -->	
				
				<div class='clear clear_both'>&nbsp;</div>
				<!-- Top Core -->

				<div class="form-group trbg" style='min-height:40px; padding-top:10px'>
					<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:15px;'>
					<a id="new_table" type="submit" tabindex='1' class="btn btn-info" name = "new_table" style="display:inline-block;background-color:#3c74dc !important;width:80px;border:none"><i class="fa fa-edit" data-toggle="tooltip" title="Edit this project by adding new"></i> Edit</a>
					
					
					<? if($allow ==1 ){?>
					<a id="approve_btn" type="submit" tabindex='2' class="btn btn-success" style="display:inline-block;width:80px" name = "approve"><i class="fa fa-check"></i>Approve</a><?}else{?>
					<a id="update_btn" type="submit1" tabindex='1' class="btn btn-info" style="display:inline-block;width:80px" name = "update"><i class="fa fa-save"></i> Save</a>
					
					<?}?>
					  <!--  <button type="submit" id="approve_btn" tabindex='2' class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Modify"><i class="fa fa-check"></i> Approve</button>
						 <input type="submit" class="btn btn-success" id = "modify_btn"/> -->
						<button type="reset" id="" tabindex='3' style="width:80px"class="btn btn-danger" data-toggle="tooltip" data-placement="top" onclick=" window.location.reload();" title="Reset"><i class="fa fa-times"></i> Reset</button>
							
						
						<a id="reject-button" type="submit3" tabindex='2' style="background-color:orange;border:orange" class="btn btn-danger" title="Reject"  ><i class="fa fa-ban"></i> Reject</a>
						
					</div>
				<div class='clear clear_both'>&nbsp;</div>

			</div>
			</div>

                                </div>
								
                            </form>
							<? //} ?>
                        
					<!-- Command Box Start -->	
              
                    <div class="row">
                        <div class="col-md-12">
                           <div class="panel panel-default panel-toggled">
                                <div class="panel-heading" style="background-color:gray">
                                    <h3 class="panel-title " style="font-weight:bold;color:white">Project History</h3>
                                  		<ul class="panel-controls">
                                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>         
										</ul>
                                </div>
                                <div class="panel-body">                                   
								<div class="col-md-4">
									<h4><? echo $admin_project[0]['PRJNAME']." - " .$admin_project[0]['PRMSCOD'];?></h4>
								</div>
										
								<div class="non-printable " style='clear:both; border-bottom:1px solid #122344 !important; margin-bottom:10px; margin-top: 10px;'></div>
								
								<table id="tbl_history_list" class="table datatable dataTable " style="font-size: 12px;" >
								<thead >
								<tr style="background-color:#3f444c;">
								<th class="center" style="text-align:center">S NO</th>
								<th class="center" style="text-align:center">PROJECT ID</th>
								<th class="center" style="text-align:center">ADD USER</th>
								<th class="center" style="text-align:center">ADD DATE</th>
								<th class="center" style="text-align:center" >TARNUMB</th>
								<th class="center" style="text-align:center" >DELUSER</th>
								<th class="center" style="text-align:center" >DELDATE</th>
								
								<th class="center" style="text-align:center">ACTION</th>
								</tr>
							</thead>
							<tbody>
										
										
							<?$table_history = select_query_json("select to_char(ah.EDTDATE,'dd-MM-yyyy HH:mi:ss AM') EDTDATE,to_char(ah.DELDATE,'dd-MM-yyyy HH:mi:ss AM') DELDATE,ah.* from approval_project_history ah where ah.PRMSCOD = '".$id."' and ah.PRMSYER = '".$c_year."' order by ah.HISSRNO desc","Centra","TEST");
							//var_dump($table_history);
							$c_table_history=sizeof($table_history);
												for($hlist = 0 ; $hlist <$c_table_history  ; $hlist++) {
											?>
												<tr>
												 <td><? echo ($hlist)+1 ?></td>
												<td class="center" style="text-align:center"><? echo $table_history[$hlist]['PRMSCOD'];?></td>
												<?$pname = select_query_json("select emp.EMPCODE,emp.EMPNAME from USERID usr,EMPLOYEE_OFFICE emp where emp.EMPSRNO = usr.EMPSRNO and usr.USRCODE = '".$table_history[$hlist]['EDTUSER']."'","Centra","TCS");?>
												<td class="center" style="text-align:center"><?echo $pname[0]['EMPCODE']." - ".$pname[0]['EMPNAME'];?></td>
												<?$time = strtotime($table_history[$hlist]['EDTDATE']);
												$myFormatForView = date("m/d/y g:i A", $time);?>
												<td class="center" style="text-align:center"><? echo $table_history[$hlist]['EDTDATE'];?></td>
												
												<td class="center" style="text-align:center"><? echo $table_history[$hlist]['TARNUMB'];?></td>
												<?$pname1 = select_query_json("select emp.EMPCODE,emp.EMPNAME from USERID usr,EMPLOYEE_OFFICE emp where emp.EMPSRNO = usr.EMPSRNO and usr.USRCODE = '".$table_history[$hlist]['DELUSER']."'","Centra","TCS");?>
												<td class="center" style="text-align:center"><?echo $pname1[0]['EMPCODE']." - ".$pname1[0]['EMPNAME'];?></td>
												<td class="center" style="text-align:center"><? echo $table_history[$hlist]['DELDATE'];?></td>
												
												<td class="center" style="text-align:left"><? echo $table_history[$hlist]['REMARKS'];?></td>
												</tr>
												
												<? }?>
										
                                       
									
									</tbody>
									</table>
														
								</div>
                            </div>
						</div>
					</div>
					</div>
					</div>
						
					<!-- Comment Box End -->	
						
					</div>
					
				
                    </div>
					
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




       
                        <!-- PRAKASH -->
	
	  <!-- START PLUGINS -->
	
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
	<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
	 <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <!-- END PLUGINS -->

    <!-- THIS PAGE PLUGINS -->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
    <script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
    <!-- END THIS PAGE PLUGINS -->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>
    <!-- END TEMPLATE -->

    <!-- Custom Scripts - Arun Rama Balan.G -->
    <script src="ajax/ajax_staff_change.js"></script>
    <link rel="stylesheet" href="css/default.css" type="text/css">
	
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <? /* <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script> */ ?>
    <script src="js/monthpicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <script src="js/form-validation.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>
    <script src="js/jquery.filer.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/jquery-customselect.js"></script>
	<script type="text/javascript" src="js/jquery_facebook.alert.js"></script>   
   
        <script type="text/javascript">
		
		
		//Project Flow User check
		var User = [];
		function findFlowUser(){
			
			$(' .findHod').each(function(){
				var tar = $(this).val();
				var tarnum = tar.split(" - ");
				User.push(tarnum[0]);
				//$(this).attr("readonly","readonly");
				//$("#txt_mode_type").val("");
				$("#flowUser").html("");
			});
			console.log(User);
			
		}
		function catchFlow(){
			$("#flowUser").html("");
			function removeDuplicateUsingSet(arr){
			let unique_array = Array.from(new Set(arr))
			return unique_array
		}
		var Filteruser = removeDuplicateUsingSet(User);
			Filterusr = JSON.stringify(Filteruser,2);
			console.log(Filterusr+"+++++++++");
			var che = [];
			
			 $.ajax({
					type: "POST",
					
					url:  "prakash/project_update.php?action=checkFlowuser&filter="+Filteruser,
					//url:  "viki/post_test.php",
					contentType: false,
					processData: false,
					dataType:"text",					
					success:function(data)
					 {	
					 $("#flowUser").html(data);
							//console.log(data);
						 console.log("data posted");
						 var string = $("#list").text();
						 //var code = string.split(",");
						 /*console.log(code[0]);
						 console.log(code.length);
							for(var d = 0; d<=code.length ; d++){
								che.push(code[d]);
								
							}
						console.log(code.length);
						console.log(che);*/
					 },
					 error:function(error){
						 console.log("Failed");
					 }
			 });
		}
		
		
		//Radio btn confirm
		function onChangeradio(val){
		jConfirm("Are you sure want to change priority Level?","Confirmation Dialog",function(ev){
			if(ev==true){
				
				$("#load_page").fadeIn("fast");
				var p =$("#pjcode").val();
				$.ajax({
					
					type:"post",
					url:"prakash/project_update.php?action=priorityChange&level="+val.value+"&pid="+p+"",
					dataType: 'html',
					success: function(data, textStatus, jqXHR){
						jAlert (" Priority Level has been changed!");
						$("#load_page").fadeOut("fast");
						//alert("removed");
						console.log(data);
						},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('An error occurred... Try Again!');
						//window.location.reload(true);
						//$("#load_page").fadeOut("fast");
						}
								
					
				});
			}
			else{
				
				val.checked= false;
				window.location.reload(true);
			}
		},'YES','NO');
		}
		
		
		// Validate text box values allow only number
		function isNumber(evt) {
				evt = (evt) ? evt : window.event;
				var iKeyCode = (evt.which) ? evt.which : evt.keyCode
				//if (iKeyCode != 46 && iKeyCode > 31 && charCode != 34 && charCode != 39 && (iKeyCode < 48 || iKeyCode > 57)){
			if (iKeyCode > 31 && iKeyCode != 39 && iKeyCode != 34 && (iKeyCode < 48 || iKeyCode > 57 || iKeyCode == 46)){
				return false;
				}
				return true;
			}    
		
		$(document).ready(function() 
			{
				 $("#load_page").fadeOut("slow");
				 $(".finish_confirm").click( function() {
				 });
				/*
				$("#txt_values").keypress(function (e) { // only works first box
				//if the letter is not digit then display error and don't type anything
				if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				//display error message
				//$("#errmsg").html("Digits Only").show().fadeOut("slow");
				return false;
					}
				   });*/
			});
		$(document).keypress(function(e) 
			{
				if (e.keyCode == 27) {
				$("#myModal1").fadeOut(500);
				}
			}); 
			//remove the ledger
				
			// remove tooltip insteadly
				$('[data-toggle="tooltip"]').tooltip({
					trigger : 'hover'
				}) 
				
				
				//Project Date Update
		$('#datepicker').Zebra_DatePicker({
                                   direction: true,
                                   format: 'd-M-Y',
								  
                                  onSelect: function(date) { 
								 // alert(date);
								  var p = $("#pjcode").val();
								  var d_date = date;
									//$(this).change();
									//alert($(this).context.value);
									//console.log(d_date);
									$.ajax({
										type:"post",
									url:"prakash/project_update.php?action=duedate&project_date="+date+"&pid="+p+"",
									dataType: 'html',
									success: function(data, textStatus, jqXHR){
										//alert (" Thank You .project is approved");
										//alert("removed");
										console.log(data);
										},
									error: function(jqXHR, textStatus, errorThrown) {
										alert('An error occurred... Try Again!');
										//window.location.reload(true);
										//$("#load_page").fadeOut("fast");
										}
								
									});
									
									}
                                 });
								 
								 
								 
								 
			//reject button confirm box
		$("#reject-button").click(function(e)
			{
				e.preventDefault();
				var id = $("#pjcode").val();
				if(confirm("Do you want to reject the project?")){
				$.ajax({
					type:"post",
					url:"prakash/reject_project.php",
					data:({id : id}),
					dataType:'text',
					success:function(data){
						//console.log(data);
						//alert("success! X:" + data);
						//if (data =="hai"){
						alert("project is rejected!");
						window.location.href = "admin_list.php";
						//alert(projectid);
						}
				});		
				//return false;				
				}
				else{
					//alert("project did not rejected yet");
					//return false;
				}
			});
			
			
		
			//approve button confirm box
			
		$("#approve_btn_").click(function(e)
			{
				var formdata = $("#frm_project_modify").serializeArray();
				//alert(formdata.length);
				//var id2 = formdata.length;
				//alert (id2);
				e.preventDefault();		
				var confirm1 = confirm("Do you want to approve the project?");
				if( confirm1 == true){
					var id1 = $("#pjcode").val();
					$.ajax({
					type:"post",
					url:"prakash/editapproval.php",
					data:({id1 : id1},{id2 : id2}),
					cache:false,
					/* above the code is for general approval*/
					dataType: 'text',
					success: function(data){
						//alert (" Thank You .project is approved");
						//alert(data);
						console.log(data);
						}
					});	
					}
					else{
						alert("project did not approval yet");
						//return false;
					}			
			});
			
			
		$("#approve_btn").click(function(e)
			{
				/*var v1=document.getElementsByClassName("valold");
				for( var val_1 =0 ; val_1 < v1.length ; val_1++ )
					{
						//fieldValues6[val_1] = document.getElementsByClassName("newval")[val_1].value;
						v1[val_1].setAttribute("readonly","true");
						//alert(v1.length);
					}
					var txtID = document.getElementById("txt_project_name");
					txtID.readOnly = true;*/
					//<!-------    ---------->
					var confirmapprove = confirm("Do you want to approve this project ?");
				//var a_id = <?echo $admin_project[0]['PRMSCOD'];?>;
				var fid1 = $("#idlist1").val();
				if( confirmapprove == true){
				//if(new_entry = 1){
					//alert("New Data Entry");
					$("#load_page").show();
					return validate1();
					function validate1()
					{
					var error = 0;
					var msg = 'Please enter all the required fields !! \n';
					var fieldno = document.getElementsByName("txt_ledger_name[]");
					var fieldArrayOwner = document.getElementsByName("txt_project_owner[]");
					var fieldArrayHead = document.getElementsByName("txt_project_head[]");
					var fieldArrayMember = document.getElementsByName("txt_project_member[]");
					var count = fieldno.length;
					var fieldArray = [];
					var OwnerCount = [];
					var HeadCount = [];
					var MemberCount = [];
				for(var con =0 ; con < count ; con++ )
							{
								fieldArray[con] = document.getElementsByName("txt_ledger_name[]")[con].value;
							}
				for(var owner =0 ; owner <fieldArrayOwner.length ; owner++ )
							{
								OwnerCount[owner] = document.getElementsByName("txt_project_owner[]")[owner].value;
							}
				for(var head =0 ; head <fieldArrayHead.length ; head++ )
							{
								HeadCount [head] = document.getElementsByName("txt_project_head[]")[head].value;
							}
				for(var member =0 ; member <fieldArrayMember.length ; member++ )
							{
								MemberCount [member] = document.getElementsByName("txt_project_member[]")[member].value;
							}
									
					var fieldArray1 = fieldArray.slice();
					var fieldOwner1 = OwnerCount.slice();
					var fieldHead1 = HeadCount.slice();
					var fieldMember1 = MemberCount.slice();
					var TotalList = fieldOwner1.concat(fieldHead1,fieldMember1);
					var le = true;
						
					$(':input[required]', '#frm_project_modify').each(function(){
						//$(this).css('border','2px solid green');
						if($(this).val() != ''){
							 //+= '\n' + $(this).attr('id') + ' Is A Required Field..';
							//$(this).css('border','2px solid green');
							//alert(msg);
							//$(this).css('border','');
							return le;

						}
						else{
							$(this).css('border','2px solid red');
							le = false;
							$("#load_page").fadeOut("fast");
						}
						//return true;
					});
					if(le){
					//hasDupesInLedger(fieldArray1);	
					function hasDupesInLedger(fieldArray1) 
					{
						  // temporary object 
						  var uniqOb = {};
						  // create object attribute with name=value in array, this will not keep dupes
						  for (var i in fieldArray1)
							uniqOb[fieldArray1[i]] = "";
						  // if object's attributes match array, then no dupes!
						  if (fieldArray1.length == Object.keys(uniqOb).length){
							//alert('Good'); 
							return true;
								
						  }
						  else{
							alert('Ledger Entry Has Duplicates. Please check');
							
							
								  }
					}
				if(hasDupesInLedger(fieldArray1))
					{
						hasDupesInOwner(fieldOwner1);
						//if(hasDupesInLedger()==true){
						function hasDupesInOwner(fieldOwner1)
						{
						  // temporary object 
						  var uniqOb1 = {};
						  // create object attribute with name=value in array, this will not keep dupes
						  for (var i in fieldOwner1)
							uniqOb1[fieldOwner1[i]] = "";
						  // if object's attributes match array, then no dupes! 
						  if (fieldOwner1.length == Object.keys(uniqOb1).length){
							//alert('Good');
							 //return true;
							 hasDupesInHead(fieldHead1);
						  }
						  else{
							  alert('Project Owner List Has Duplicates. Please Check');
							  
						  }	
						}
				
					
				//}
					function hasDupesInHead(fieldHead1) {
					 // temporary object
					  var uniqOb2 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldHead1)
						uniqOb2[fieldHead1[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (fieldHead1.length == Object.keys(uniqOb2).length){
						//alert('Good');
					  //return true;
					  hasDupesInMember(fieldMember1);
					  }
					  else{
					  alert('Project Head List Has Duplicates. Please Check');
					  return false;
					  }
					}
				
					
					
					function hasDupesInMember(fieldMember1) {
					 // temporary object
					  var uniqOb3 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldMember1)
						uniqOb3[fieldMember1[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (fieldMember1.length == Object.keys(uniqOb3).length){
						TotalPerson();
					  //return true;
					  }
					  else{
						alert('Project Member List Has Duplicates. Please Check');
						
					  }
					}
					
				}
				function TotalPerson()
					{
						hasDupesInTotal(TotalList); 
						function hasDupesInTotal(TotalList) {
						 // temporary object
						  var uniqOb4 = {};
						 // create object attribute with name=value in array, this will not keep dupes
						  for (var i in TotalList)
							uniqOb4[TotalList[i]] = "";
						  //if object's attributes match array, then no dupes! 
						  if (TotalList.length == Object.keys(uniqOb4).length){
							
								var result1 = $('#frm_project_modify').serialize();
				//alert(result1);
								var fieldArray3 = [];
								var fieldArray6 = [];
								var ledgerValues = document.getElementsByClassName('newval');
								for( var con1 =0 ; con1 < ledgerValues.length ; con1++ )
									{
										fieldArray6[con1] = document.getElementsByClassName("newval")[con1].value;
									}
								
								var valueOfLedger = document.getElementsByClassName('newledger');
								//var valueOfLedger1 = document.getElementsByClassName('newledger')[0].value;
								//alert(valueOfLedger1);
								for( var con =0 ; con < valueOfLedger.length ; con++ )
									{
										fieldArray3[con] = document.getElementsByClassName("newledger")[con].value;
									}
								//alert("We detect following new items:\n"+fieldArray3.join('\n'));
								//var fieldArray4 = fieldArray3.slice();
								var fieldArray5 = JSON.stringify(fieldArray3);
								var fieldArray7 = JSON.stringify(fieldArray6);
								//alert(fieldArray5);
								
								// New Project Owner //
								var Ownerarray = [];
								var Ownerlist = document.getElementsByClassName('newowner');
								for( var con2 =0 ; con2 < Ownerlist.length ; con2++ )
									{
										Ownerarray[con2] = document.getElementsByClassName("newowner")[con2].value;
									}
								var postOwner = JSON.stringify(Ownerarray);
								
								// New Project Head //
								var Headarray = [];
								Headlist = document.getElementsByClassName('newhead');
								for( var con3 =0 ; con3 < Headlist.length ; con3++ )
									{
										Headarray[con3] = document.getElementsByClassName("newhead")[con3].value;
									}
								var postHead = JSON.stringify(Headarray);
								
								// New Project Member //
								var Memberarray = [];
								Memberlist = document.getElementsByClassName('newmember');
								for( var con4 =0 ; con4 < Memberlist.length ; con4++ )
									{
										Memberarray[con4] = document.getElementsByClassName("newmember")[con4].value;
									}
								var postMember = JSON.stringify(Memberarray);
								
								
								//var result2 = $('#newtable_form').serialize();
								//alert(result2);
								var p_id = $("#pjcode").val();
								var p_name = document.getElementById('txt_project_name').value;
								$.ajax({
									type:"post",
									url:"prakash/project_update.php?action=newentry&pid="+p_id+"&newledgeritems="+fieldArray5+"&newledgervalues="+fieldArray7+"&newowner="+postOwner+"&newhead="+postHead+"&newmember="+postMember+"&idlist1="+fid1,
									//contentType: "application/json; charset=utf-8",
									//data : formData,
										   // postname : local variable
									//data : result2,
									cache:false,
									dataType: 'text',
									success: function(data, textStatus, jqXHR){
										$("#load_page").fadeOut("fast");
										alert (" Thank You! The Project is approved now!");
										//alert(data);
										//window.location.href = "admin_project_list_prakash.php";
										//window.location.reload();
										console.log(data);
										
										},
									error: function(jqXHR, textStatus, errorThrown) {
									alert('An error occurred... Try again!');
									$("#load_page").fadeOut("fast");
									}
								});	
							
							}
						  else{
							  //createCustomAlert(txt, title);
							alert('Repeated Project Persons List is Present . Please Check');
							return false;
							
						  }
						}
																		
						}
						
					return true;
							
						}
						}
				}
				// Edit and Save
				
				if(new_entry=0){
					
					//<!-------    ---------->
				
				e.preventDefault();	
					//alert(new_entry);
					//if(new_entry ==0){
				var confirm1 = confirm("Do you want to approve this project (No Changes)?");
				var a_id = $("#pjcode").val();
				if( confirm1 == true){
					$("#load_page").show();
					$.ajax({
					type:"post",
					
					url:"prakash/project_hierarchy.php",
					data : ({a_id : a_id}),
					dataType: 'text',
					success: function(data){
						//alert (" Thank You .project is approved");
						//alert(data);
						$("#load_page").fadeOut("fast");
						alert("Project is Approved!");
						//window.location.href = "project_list_prakash.php";
						console.log(data);
						
						},
					error: function( jqXhr, textStatus, errorThrown ){
						alert('An error occurred... Try Again!');
						$("#load_page").fadeOut("fast");
					}
					});	
					
					}
					else{
							alert("Project did not approval yet");
							//return false;
						}	
					
					
						//else{
						//	alert('empty');
						//}
				}
			
			});
			
			//Validate the form
			/*----------------Validation START------------------*/
			function validate()
				{
					var error = true;
					var msg = 'Please enter all the required fields !! \n';
					var fieldno = document.getElementsByName("txt_ledger_name[]");
					var fieldArrayOwner = document.getElementsByName("txt_project_owner[]");
					var fieldArrayHead = document.getElementsByName("txt_project_head[]");
					var fieldArrayMember = document.getElementsByName("txt_project_member[]");
					var count = fieldno.length;
					var fieldArray = [];
					var OwnerCount = [];
					var HeadCount = [];
					var MemberCount = [];
				for(var con =0 ; con < count ; con++ )
							{
								fieldArray[con] = document.getElementsByName("txt_ledger_name[]")[con].value;
							}
				for(var owner =0 ; owner <fieldArrayOwner.length ; owner++ )
							{
								OwnerCount[owner] = document.getElementsByName("txt_project_owner[]")[owner].value;
							}
				for(var head =0 ; head <fieldArrayHead.length ; head++ )
							{
								HeadCount [head] = document.getElementsByName("txt_project_head[]")[head].value;
							}
				for(var member =0 ; member <fieldArrayMember.length ; member++ )
							{
								MemberCount [member] = document.getElementsByName("txt_project_member[]")[member].value;
							}
									
					var fieldArray1 = fieldArray.slice();
					var fieldOwner1 = OwnerCount.slice();
					var fieldHead1 = HeadCount.slice();
					var fieldMember1 = MemberCount.slice();
					var TotalList = fieldOwner1.concat(fieldHead1,fieldMember1);
					
						
					$(':input[required]', '#frm_project_modify').each(function(){
						//$(this).css('border','2px solid green');
						if($(this).val() != ''){
							 //+= '\n' + $(this).attr('id') + ' Is A Required Field..';
							//$(this).css('border','2px solid green');
							//alert(msg);
							//$(this).css('border','');
							return error;

						}
						else{
							$(this).css('border','2px solid red');
							error = false;
						}
						//return true;
					});
					if(error){
					//hasDupesInLedger(fieldArray1);	
					function hasDupesInLedger(fieldArray1) 
					{
						  // temporary object 
						  var uniqOb = {};
						  // create object attribute with name=value in array, this will not keep dupes
						  for (var i in fieldArray1)
							uniqOb[fieldArray1[i]] = "";
						  // if object's attributes match array, then no dupes!
						  if (fieldArray1.length == Object.keys(uniqOb).length){
							//alert('Good'); 
							return true;
								
						  }
						  else{
							alert('Ledger Entry Has Duplicates. Please check');
							//$("#txt_ledger_name1").focus(); 
							
								  }
					}
				if(hasDupesInLedger(fieldArray1))
					{
						hasDupesInOwner(fieldOwner1);
						//if(hasDupesInLedger()==true){
						function hasDupesInOwner(fieldOwner1)
						{
						  // temporary object 
						  var uniqOb1 = {};
						  // create object attribute with name=value in array, this will not keep dupes
						  for (var i in fieldOwner1)
							uniqOb1[fieldOwner1[i]] = "";
						  // if object's attributes match array, then no dupes! 
						  if (fieldOwner1.length == Object.keys(uniqOb1).length){
							//alert('Good');
							 //return true;
							 hasDupesInHead(fieldHead1);
						  }
						  else{
							  alert('Project Owner List Has Duplicates. Please Check');
						  }	
						}
				
					
				//}
					function hasDupesInHead(fieldHead1) {
					 // temporary object
					  var uniqOb2 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldHead1)
						uniqOb2[fieldHead1[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (fieldHead1.length == Object.keys(uniqOb2).length){
						//alert('Good');
					  //return true;
					  hasDupesInMember(fieldMember1);
					  }
					  else{
					  alert('Project Head List Has Duplicates. Please Check');
					  return false;
					  }
					}
				
					
					
					function hasDupesInMember(fieldMember1) {
					 // temporary object
					  var uniqOb3 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldMember1)
						uniqOb3[fieldMember1[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (fieldMember1.length == Object.keys(uniqOb3).length){
						TotalPerson();
					  //return true;
					  }
					  else{
						alert('Project Member List Has Duplicates. Please Check');
					  }
					}
					
				}
				function TotalPerson()
					{
						hasDupesInTotal(TotalList); 
						function hasDupesInTotal(TotalList) {
						 // temporary object
						  var uniqOb4 = {};
						 // create object attribute with name=value in array, this will not keep dupes
						  for (var i in TotalList)
							uniqOb4[TotalList[i]] = "";
						  //if object's attributes match array, then no dupes! 
						  if (TotalList.length == Object.keys(uniqOb4).length){
							
						 // return true;
							insertdb();
							}
						  else{
							  //createCustomAlert(txt, title);
							alert('Repeated Project Persons List is Present . Please Check');
							
						  }
						}
						
							
						//alert(JSON.stringify(TotalList));
						//alert('form has been submitted');
						}
					return false;
					
				}
				}
		
			
			/*----------------Validation END------------------*/
			
			// save / update new entery
		var new_entry = 0;
		
				
		$("#update_btn").click(function(e)
		{
				//alert(new_entry);
				if(new_entry == 0)
			{
				var confirm1 = confirm("Do you want to submit this project?");
				if( confirm1 == true){
					$("#load_page").show();
					e.preventDefault();
					//var result = $('#txt_ledger_name1').serialize();
					var result1 = $('#frm_project_modify').serialize();
					//var result = document.getElementById("frm_project_modify");
					//var formElement = document.getElementById("#frm_project_modify");
					//var formData = new FormData(formElement);
					//alert(result1);
					var p_id = $("#pjcode").val();
					var p_name = document.getElementById('txt_project_name').value;
					$.ajax({
						type:"post",
						url:"prakash/project_update.php?action=update&pvalues="+fieldValues1+"&pid="+p_id+"",
						//contentType: "application/json; charset=utf-8",
						//data : formData,
							   // postname : local variable
						data : result1,
						cache:false,
						dataType: 'text',
						success: function(data, textStatus, jQxhr){
							$("#load_page").fadeOut("fast");
							alert ("Project is Updated");
							//alert(data);
							window.location.href = "admin_list.php";
							console.log(data);
							/*$.ajax({
									type:"post",
									// above the code is for general approval uncomment it
									url:"prakash/project_hierarchy.php",
									data : ({a_id : p_id}),
									dataType: 'text',
									success: function(data){
										//alert (" Thank You .project is approved");
										//alert(data);
										alert("Project is Approved!");
										//window.location.href = "project_list_prakash.php";
										console.log(data);
										
										}
									});	
							*/
							
							},
						error: function( jqXhr, textStatus, errorThrown ){
						alert('An error occurred... Try Again!');
						$("#load_page").fadeOut("fast");
					}
					});	
				}
				else{
					alert("project did not approval yet");
						//return false;
					}
			}
			
			else if(new_entry == 1)
			{
				
				//alert("New Data Entry");
				$("#load_page").show();
				return validate1();
				function validate1()
				{
					var error = 0;
					var msg = 'Please enter all the required fields !! \n';
					var fieldno = document.getElementsByName("txt_ledger_name[]");
					var fieldArrayOwner = document.getElementsByName("txt_project_owner[]");
					var fieldArrayHead = document.getElementsByName("txt_project_head[]");
					var fieldArrayMember = document.getElementsByName("txt_project_member[]");
					var count = fieldno.length;
					var fieldArray = [];
					var OwnerCount = [];
					var HeadCount = [];
					var MemberCount = [];
				for(var con =0 ; con < count ; con++ )
							{
								fieldArray[con] = document.getElementsByName("txt_ledger_name[]")[con].value;
							}
				for(var owner =0 ; owner <fieldArrayOwner.length ; owner++ )
							{
								OwnerCount[owner] = document.getElementsByName("txt_project_owner[]")[owner].value;
							}
				for(var head =0 ; head <fieldArrayHead.length ; head++ )
							{
								HeadCount [head] = document.getElementsByName("txt_project_head[]")[head].value;
							}
				for(var member =0 ; member <fieldArrayMember.length ; member++ )
							{
								MemberCount [member] = document.getElementsByName("txt_project_member[]")[member].value;
							}
									
					var fieldArray1 = fieldArray.slice();
					var fieldOwner1 = OwnerCount.slice();
					var fieldHead1 = HeadCount.slice();
					var fieldMember1 = MemberCount.slice();
					var TotalList = fieldOwner1.concat(fieldHead1,fieldMember1);
					var le = true;
						
					$(':input[required]', '#frm_project_modify').each(function(){
						//$(this).css('border','2px solid green');
						if($(this).val() != ''){
							 //+= '\n' + $(this).attr('id') + ' Is A Required Field..';
							//$(this).css('border','2px solid green');
							//alert(msg);
							//$(this).css('border','');
							return le;

						}
						else{
							$(this).css('border','2px solid red');
							le = false;
							$("#load_page").fadeOut("fast");
						}
						//return true;
					});
					if(le){
					//hasDupesInLedger(fieldArray1);	
					function hasDupesInLedger(fieldArray1) 
					{
						  // temporary object 
						  var uniqOb = {};
						  // create object attribute with name=value in array, this will not keep dupes
						  for (var i in fieldArray1)
							uniqOb[fieldArray1[i]] = "";
						  // if object's attributes match array, then no dupes!
						  if (fieldArray1.length == Object.keys(uniqOb).length){
							//alert('Good'); 
							return true;
								
						  }
						  else{
							alert('Ledger Entry Has Duplicates. Please check');
							
							
								  }
					}
				if(hasDupesInLedger(fieldArray1))
					{
						hasDupesInOwner(fieldOwner1);
						//if(hasDupesInLedger()==true){
						function hasDupesInOwner(fieldOwner1)
						{
						  // temporary object 
						  var uniqOb1 = {};
						  // create object attribute with name=value in array, this will not keep dupes
						  for (var i in fieldOwner1)
							uniqOb1[fieldOwner1[i]] = "";
						  // if object's attributes match array, then no dupes! 
						  if (fieldOwner1.length == Object.keys(uniqOb1).length){
							//alert('Good');
							 //return true;
							 hasDupesInHead(fieldHead1);
						  }
						  else{
							  alert('Project Owner List Has Duplicates. Please Check');
							  
						  }	
						}
				
					
				//}
					function hasDupesInHead(fieldHead1) {
					 // temporary object
					  var uniqOb2 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldHead1)
						uniqOb2[fieldHead1[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (fieldHead1.length == Object.keys(uniqOb2).length){
						//alert('Good');
					  //return true;
					  hasDupesInMember(fieldMember1);
					  }
					  else{
					  alert('Project Head List Has Duplicates. Please Check');
					  return false;
					  }
					}
				
					
					
					function hasDupesInMember(fieldMember1) {
					 // temporary object
					  var uniqOb3 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldMember1)
						uniqOb3[fieldMember1[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (fieldMember1.length == Object.keys(uniqOb3).length){
						TotalPerson();
					  //return true;
					  }
					  else{
						alert('Project Member List Has Duplicates. Please Check');
						
					  }
					}
					
				}
				function TotalPerson()
					{
						hasDupesInTotal(TotalList); 
						function hasDupesInTotal(TotalList) {
						 // temporary object
						  var uniqOb4 = {};
						 // create object attribute with name=value in array, this will not keep dupes
						  for (var i in TotalList)
							uniqOb4[TotalList[i]] = "";
						  //if object's attributes match array, then no dupes! 
						  if (TotalList.length == Object.keys(uniqOb4).length){
							
								var result1 = $('#frm_project_modify').serialize();
				//alert(result1);
								var fieldArray3 = [];
								var fieldArray6 = [];
								var ledgerValues = document.getElementsByClassName('newval');
								for( var con1 =0 ; con1 < ledgerValues.length ; con1++ )
									{
										fieldArray6[con1] = document.getElementsByClassName("newval")[con1].value;
									}
								
								var valueOfLedger = document.getElementsByClassName('newledger');
								//var valueOfLedger1 = document.getElementsByClassName('newledger')[0].value;
								//alert(valueOfLedger1);
								for( var con =0 ; con < valueOfLedger.length ; con++ )
									{
										fieldArray3[con] = document.getElementsByClassName("newledger")[con].value;
									}
								//alert("We detect following new items:\n"+fieldArray3.join('\n'));
								//var fieldArray4 = fieldArray3.slice();
								var fieldArray5 = JSON.stringify(fieldArray3);
								var fieldArray7 = JSON.stringify(fieldArray6);
								//alert(fieldArray5);
								
								// New Project Owner //
								var Ownerarray = [];
								var Ownerlist = document.getElementsByClassName('newowner');
								for( var con2 =0 ; con2 < Ownerlist.length ; con2++ )
									{
										Ownerarray[con2] = document.getElementsByClassName("newowner")[con2].value;
									}
								var postOwner = JSON.stringify(Ownerarray);
								
								// New Project Head //
								var Headarray = [];
								Headlist = document.getElementsByClassName('newhead');
								for( var con3 =0 ; con3 < Headlist.length ; con3++ )
									{
										Headarray[con3] = document.getElementsByClassName("newhead")[con3].value;
									}
								var postHead = JSON.stringify(Headarray);
								
								// New Project Member //
								var Memberarray = [];
								Memberlist = document.getElementsByClassName('newmember');
								for( var con4 =0 ; con4 < Memberlist.length ; con4++ )
									{
										Memberarray[con4] = document.getElementsByClassName("newmember")[con4].value;
									}
								var postMember = JSON.stringify(Memberarray);
								
								var fid = $("#idlist1").val();
								//var result2 = $('#newtable_form').serialize();
								//alert(result2);
								var p_id = $("#pjcode").val();
								var p_name = document.getElementById('txt_project_name').value;
								$.ajax({
									type:"post",
									url:"prakash/project_update.php?action=newentry&pid="+p_id+"&newledgeritems="+fieldArray5+"&newledgervalues="+fieldArray7+"&newowner="+postOwner+"&newhead="+postHead+"&newmember="+postMember+"&idlist1="+fid,
									//contentType: "application/json; charset=utf-8",
									//data : formData,
										   // postname : local variable
									//data : result2,
									cache:false,
									dataType: 'text',
									success: function(data, textStatus, jqXHR){
										$("#load_page").fadeOut("fast");
										alert (" Thank You! The Project is updated now!");
										//alert(data);
										//window.location.href = "admin_list.php";
										//window.location.reload();
										console.log(data);
										
										},
									error: function(jqXHR, textStatus, errorThrown) {
									alert('An error occurred... Try again!');
									$("#load_page").fadeOut("fast");
									}
								});	
							
							}
						  else{
							  //createCustomAlert(txt, title);
							alert('Repeated Project Persons List is Present . Please Check');
							return false;
							
						  }
						}
																		
						}
						
					return true;
					
				}
				}
				
				//$("#load_page").show();

				//e.preventDefault();
				//alert(led_inc);
				//var ledgervalByID = $('#txt_ledger_name4').find('.newledger').val();
				//alert(ledgervalByID);
				
			}
			
		});	
		
		
		// Update existing table finised--->
		
				var p_name = document.getElementById('txt_project_name').value;
				var fieldArrayOwner = document.getElementsByName("txt_project_owner[]");
				var fieldArrayValues = document.getElementsByName("txt_values[]");
				var fieldArrayLedger = document.getElementsByName("txt_ledger_name[]");
				var fieldArray = [], txtValues = [], txtLedger = []; 
				for(var con =0 ; con < fieldArrayOwner.length ; con++ )
					{
						fieldArray[con] = document.getElementsByName("txt_project_owner[]")[con].value;
					}
					var fieldArray1 = fieldArray.slice();
					//alert(JSON.stringify(fieldArray1));
					//console.log(JSON.stringify(fieldArray1));
					//alert(fieldArrayValues.length);
					
				for(var values =0 ; values < fieldArrayValues.length ; values++ )
					{
						txtValues[values] = document.getElementsByName("txt_values[]")[values].value;
					}	
				for (var ledger = 0 ; ledger < fieldArrayLedger.length ; ledger++ )
					{
						txtLedger[ledger] = document.getElementsByName("txt_ledger_name[]")[ledger].value;
					}
					

					var fieldValues1 = txtValues.slice();
					//alert(JSON.stringify(fieldValues1));
					//console.log(JSON.stringify(fieldValues1));
					//alert(fieldArrayLedger.length);
					var JSONvalues = JSON.stringify(fieldValues1);
					//var al = fieldValues1+","+p_name;
					//alert(fieldValues1);
					//console.log(JSON.stringify(JSONvalues));
					
					//var objProject ={};
					//objProject.
				//alert(p_name);
		
		// Update New table row 
		var led_inc = <?echo $led_id;?>;
		
		/*
				if(id = 1)
					{
						$("#update_btn").click(function(e)
					{
					alert("new entry event");
				});
				}
			*/	

	/*---------------------------------------------------------------------*/
			$("#new_table").click(function(e)
			{
				e.preventDefault();
				//var node = document.getElementById("dynamic,dynamic_owner,dynamic_head,dynamic_member").style.visibility = "visible";
				$("#dynamic").css("display", "flex");
				$("#dynamic_owner").css("display", "flex");
				$("#dynamic_head").css("display", "flex");
				$("#dynamic_member").css("display", "flex");
				//subject_addnew4();// ledger
				//subject_addnew1(); //head
				//subject_addnew(); //owner
				//subject_addnew2();//member
				$(this).css("display" ,"none");
				//document.getElementById("myText").disabled = true;
				new_entry += 1;
				console.log(new_entry);
				
				var v1=document.getElementsByClassName("valold");
				for( var val_1 =0 ; val_1 < v1.length ; val_1++ )
					{
						//fieldValues6[val_1] = document.getElementsByClassName("newval")[val_1].value;
						v1[val_1].setAttribute("readonly","true");
						//alert(v1.length);
					}
					var v2=document.getElementsByName("slt_priority");
				for( var val_2 =0 ; val_2 < v2.length ; val_2++ )
					{
						//fieldValues6[val_1] = document.getElementsByClassName("newval")[val_1].value;
						v2[val_2].removeAttribute("disabled","false");
						//alert(v1.length);
						
					}
					console.log(v2);
					
					var txtID = document.getElementById("txt_project_name");
					txtID.readOnly = true;
					add_attach1();
				//	$("#update_btn").css("display", "none");
				//$("#approve_btn").css("display", "inline-block");
				
				
				//document.getElementById("dynamic").style.display = "inline-block";
				//document.getElementById("dynamic_owner").style.display = "inline-block";
				//document.getElementById("dynamic_owner").style.display = "inline-block";
				//document.getElementById("dynamic_member").style.display = "inline-block";
				//document.getElementById("spanledger").style.display = "inline-block";
			});
			

          //////Dynamically Load Contents Here /////////
         
				
	   // branch fetching from db

            $.ajax({
                url: 'prakash/branch.php',
                type: 'post',
                //data: {top_core:top_core},
                dataType: 'json',
                success:function(response){
                 var len = response.length;
                 $("#txt_branch_type").append("<option value='' selected hidden>CHOOSE THE BRANCH</option>");
                 for( var i = 0; i<len; i++){
                  var id = response[i]['id'];
                  var name = response[i]['name'];
                  var brn = response[i]['brn'];
                  $("#txt_branch_type").append("<option value='"+id+" , "+name+"'>"+brn+"</option>");
                 }
                }
            });
			
			// ledger fetching from db
             $.ajax({
                url: 'prakash/ledger.php',
                type: 'post',
                //data: {top_core:top_core},
                dataType: 'json',
                success:function(response){
                 var len = response.length;
                 // $("#txt_ledger_code").append("<option value='' selected hidden>CHOOSE THE LEDGER CODE</option>");
                 $("#txt_ledger_name").append("<option value='' selected hidden>CHOOSE THE LEDGER NAME</option>");

                 for( var i = 0; i<len; i++){
                  var id = response[i]['id'];
                  var name = response[i]['name'];
                  // $("#txt_ledger_code").append("<option value='"+id+"'>"+id+"</option>");
                  $("#txt_ledger_name").append("<option value='"+name+"'>"+name+"</option>");

                 }
                }
            });
			 // Project owner list script
			$('#txt_project_owner').autocomplete({
            source: function( request, response ) {
              $.ajax({
                url : 'ajax/ajax_employee_details.php',
                dataType: "json",
                data: {
                   slt_emp: request.term,
                   brncode: 888,
                   action: 'allemp'
                },
                success: function( data ) {
                  response( $.map( data, function( item ) {
                    return {
                      label: item,
                      value: item
						}
					  }));
					}
				  });
				},
				autoFocus: true,
				minLength: 0
			  });

            // Project Head List out Scripts
            $('#txt_project_head').autocomplete({
                  source: function( request, response ) {
                    $.ajax({
                      url : 'ajax/ajax_employee_details.php',
                      dataType: "json",
                      data: {
                         slt_emp: request.term,
                         brncode: 888,
                         action: 'allemp'
                      },
                      success: function( data ) {
                        response( $.map( data, function( item ) {
                          return {
                            label: item,
                            value: item
                          }
                        }));
                      }
                    });
                  },
                  autoFocus: true,
                  minLength: 0
                });


             // Project Member List out scripts
            $('#txt_project_member').autocomplete({
                  source: function( request, response ) {
                    $.ajax({
                      url : 'ajax/ajax_employee_details.php',
                      dataType: "json",
                      data: {
                         slt_emp: request.term,
                         brncode: 888,
                         action: 'allemp'
                      },
                      success: function( data ) {
                        response( $.map( data, function( item ) {
                          return {
                            label: item,
                            value: item
                          }
                        }));
                      }
                    });
                  },
                  autoFocus: true,
                  minLength: 0
                });
													
			
		
			// add multiple owner
			var inc1 = 0;
	function subject_addnew() {
		 
		$('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
		var valueowner = <?echo $ownerid;?> + inc1;
		//var id = (parseInt(value)).toString();
		console.log("Owner"+valueowner);
		inc1+=1;
		console.log(inc1);
		
        //var id1 = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        //var value = $('#partint').val();
        var idowner = (parseInt(valueowner)).toString();
        $('#partint').val(idowner);
        $('#add_emp').append(
          '<div class="form-group input-group">'+

            '<input type="text" name="txt_project_owner[]" id="txt_project_owner'+idowner+'" required placeholder="PROJECT OWNER"  class="form-control find_empcode newowner" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;">'+'<span class="input-group-btn"><button id="add_emp_button" type="button" class="btn btn-danger btn-remove data-toggle="tooltip" title ="remove"">-</button></span>'+
          '</div>');

               $('#txt_project_owner'+idowner).autocomplete({
               source: function( request, response ) {
                 $.ajax({
                   url : 'ajax/ajax_employee_details.php',
                   dataType: "json",
                   data: {
                      slt_emp: request.term,
                      brncode: 888,
                      action: 'allemp'
                   },
                   success: function( data ) {
                     response( $.map( data, function( item ) {
                       return {
                         label: item,
                         value: item
                       }
                     }));
                   }
                 });
               },
			   
			   change: function(event,ui)
				{ if (ui.item == null) 
				{
					
					$("#txt_project_owner"+idowner).val('');
					$("#txt_project_owner"+idowner).focus(); 
				} 
				},
               autoFocus: true,
               minLength: 0
             });
          $( document ).on( 'click', '.btn-remove', function ( event ) {
            	event.preventDefault();
            	$(this).closest( '.form-group' ).remove();
           });
		}

		//add multiple head
		var inc2 = 0;
		function subject_addnew1() {

        $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
        //var id1 = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();
		var valuehead = <?echo $headid;?> + inc2;
		//var id = (parseInt(value)).toString();
		console.log("Head"+valuehead);
		inc2+=1;
		console.log(inc2);
       // var value = $('#partint').val();
        var idhead = (parseInt(valuehead)).toString();
        $('#partint').val(idhead);
        $('#add_emp1').append(
          '<div class="form-group input-group">'+

            '<input type="text" name="txt_project_head[]" id="txt_project_head'+idhead+'" required maxlength="50" placeholder="PROJECT HEAD"  class="form-control find_empcode newhead" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;">'+'<span class="input-group-btn"><button id="add_emp_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip" title ="remove">-</button></span>'+
          '</div>');

               $('#txt_project_head'+idhead).autocomplete({
               source: function( request, response ) {
                 $.ajax({
                   url : 'ajax/ajax_employee_details.php',
                   dataType: "json",
                   data: {
                      slt_emp: request.term,
                      brncode: 888,
                      action: 'allemp'
                   },
                   success: function( data ) {
                     response( $.map( data, function( item ) {
                       return {
                         label: item,
                         value: item
                       }
                     }));
                   }
                 });
               },
			   change: function(event,ui)
				{ if (ui.item == null) 
				{
					
					$("#txt_project_head"+idhead).val('');
					$("#txt_project_head"+idhead).focus(); 
				} 
				},
               autoFocus: true,
               minLength: 0
             });
          $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });
		}
		
			// multiple member generate
			var inc3 = 0;
	function subject_addnew2() {

        $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
        //var id1 = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();
		var valuemember = <?echo $memberid;?> + inc3;
		//var id = (parseInt(value)).toString();
		console.log("Head"+valuemember);
		inc3+=1;
		console.log(inc3);
        //var value = $('#partint').val();
		var idmember = (parseInt(valuemember)).toString();
        $('#partint').val(idmember);
        $('#add_emp2').append(
          '<div class="form-group input-group">'+

            '<input type="text" name="txt_project_member[]" id="txt_project_member'+idmember+'" required maxlength="50" placeholder="PROJECT MEMBER"  class="form-control find_empcode newmember" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;">'+'<span class="input-group-btn"><button id="add_emp_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip" title ="remove">-</button></span>'+
          '</div>');

               $('#txt_project_member'+idmember).autocomplete({
               source: function( request, response ) {
                 $.ajax({
                   url : 'ajax/ajax_employee_details.php',
                   dataType: "json",
                   data: {
                      slt_emp: request.term,
                      brncode: 888,
                      action: 'allemp'
                   },
                   success: function( data ) {
                     response( $.map( data, function( item ) {
                       return {
                         label: item,
                         value: item
                       }
                     }));
                   }
				   //
				   
				   //
                 });
               },
			   change: function(event,ui)
				{ if (ui.item == null) 
				{
					
					$("#txt_project_member"+idmember).val('');
					$("#txt_project_member"+idmember).focus(); 
				} 
				},
               autoFocus: true,
               minLength: 0
             });
          $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });
        }
	
	var inc = 0;
	
	function subject_addnew4() {

	$('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
		/*trigger : 'hover'*/
		var value = <?echo $led_id;?> + inc;
			
       
        //var value = $('#add_ledger_row').val();
		//console.log(value);
        var id = (parseInt(value)).toString();
		
		console.log(id);
		//inc = <?echo $led_id = $led_id+1;?>;
		inc+=1;
		console.log(inc);
		
        $('#add_ledger_row1').val(id);
		/*
		if(document.getElementById('txt_ledger_name'+id)==ledgercode)
		{
			alert('Duplicate value');
		}
		*/
        $('#add_ledger').append(
              '<div class="form-group input-group">'+
				'<div style="width:100%">'+
					'<div style="width:70%;float:left;">'+
						'<select class="form-control newledger findHod" autofocus name="txt_ledger_name[]" id="txt_ledger_name'+id+'" data-toggle="tooltip" data-placement="top" title="ledger Name" onchange="findFlowUser()" onBlur="catchFlow()" ></select>'+
					'</div>'+
					'<div style="width:30%;float:right">'+
						'<input type="text" name = "txt_value[]" id= "txt_values'+id+'"class="form-control newval" pattern="^[0-9]*$" placeholder= "VALUES" data-toggle="tooltip" title ="values"  onkeypress="javascript:return isNumber(event)" autocomplete="off">'+
					'</div>'+
				'</div>'+
				'<span class="input-group-btn"><button id="add_ledger_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip" title ="remove">-</button></span>'+
			  '</div>');
		
                 $.ajax({
					url: 'prakash/ledger.php',
					type: 'post',
					//data: {top_core:top_core},
					dataType: 'json',
					success:function(response){
					 
					 
					 var len = response.length;
					 
					 // $("#txt_ledger_code").append("<option value='' selected hidden>CHOOSE THE LEDGER CODE</option>");
					 $("#txt_ledger_name"+id).append("<option value='' selected hidden>CHOOSE THE LEDGER NAME</option>");

					 for( var i = 0; i<len; i++){
					  var idd = response[i]['id'];
					  var name = response[i]['name'];
					  // $("#txt_ledger_code").append("<option value='"+id+"'>"+id+"</option>");
					  $("#txt_ledger_name"+id).append("<option value='"+name+"'>"+name+"</option>");

					 }
					}
				});
                 $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
				});
				
			
        }	
	
		function add_attach1(){
			$('#add_attach').append('<form name="files" id="files_frm" enctype="multipart/form-data"><div class="row form-group">'+
		'<div class="col-lg-4 col-md-4">'+
			'<label style="height:27px;">Attachments<span style="color:red">*</span></label>'+
			'</div>'+
				'<div class="col-lg-8 col-md-8">'+
					'<div>'+
'<input type="file" placeholder="Document Attachment" tabindex="10" class="form-control  input-group" name="attachments[]"'+'id="attachments" onchange="ValidateSingleInput(this, all);" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf"'+  'data-toggle="tooltip" data-placement="top" title="Document Attachment">'+
					'<span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span>'+
					'</div></div></div></form>');
	
		}
		
		//upload Only PDF file
    function ValidateSingleInput(oInput, file_ext) {
        $('#load_page').show();
		
        if(file_ext == 'pdf') {
            var _validFileExtensions = [".pdf",".PDF"];
			//alert(oInput+" 1\n "+file_ext);
        } else {
            var _validFileExtensions = [".jpg",".jpeg",".png",".gif",".pdf",".JPG",".JPEG",".PNG",".GIF",".PDF"];
			//	alert(oInput+" 2\n "+file_ext);
        }
        if (oInput.type == "file") {
            var sFileName = oInput.value;
			//alert(sFileName+" 3\n "+file_ext);
             if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						
						blnValid = true;
						// Call file upload event
						attachFiles();
                        break;
                    }
                }

                if (!blnValid) {
                    // alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                    // alert("Sorry, Upload Only PDF file Format");
                    //var ALERT_TITLE = "Message";
                    //var ALERTMSG = "Kindly Upload Only PDF file. Other Formats not allowed!!";
                    //createCustomAlert(ALERTMSG, ALERT_TITLE);
					alert("Kindly Upload Only PDF file. Other Formats not allowed!!");
					$("#load_page").fadeOut("slow");
                    oInput.value = "";
                    return false;
                }
            }
			
            $('#load_page').hide();
        }
        return true;
    }
	
		function attachFiles(){
			var formElement = document.getElementById("files_frm");
				var formData = new FormData(formElement);
			//alert(formData);
			//var pno1 = <?echo $admin_project[0]['PRMSCOD'];?>;
			// = <?echo gettype($admin_project[0]['PRJNAME']);?>; 
			
			//
			var pjid =	$("#pjcode").val();
		
			if(confirm("Are you sure want to upload the new files?")){
				
			//var pname = document.getElementById('txt_project_name').value;
			
			//document.getElementById('project_name').value = pname;
				
				$.ajax({
					type:"post",
					data:formData,
					//url:"prakash/project_file_attach.php?pjid="+pjid+"&project_name="+<?echo $admin_project[0]['PRJNAME'];?>,
					url:"prakash/project_file_attach.php?pjid="+pjid,
					contentType: false,
					processData: false,
					dataType:"html",
										
					success:function(response){
						alert("Files uploaded successfully!");
						//window.location.reload(true);
					},
					error:function(jqXHR, textStatus, errorThrown){
						alert("Not yet"+jqXHR.responseText);
					}
				});
			}
			else{
				//window.location.reload(true);
			}
		}
			
	
        </script>
    <!-- END SCRIPTS -->
    </body>
    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>
