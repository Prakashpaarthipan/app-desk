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
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
}

switch ($_SESSION['tcs_descode']) {
	case 132: // HOD
		$usr_allstatus = "H";
		$connectWith = "#sksir_tasks,#kssir_tasks,#gm_seniorgm_tasks,#hodtasks,#it_tasks";
		break;
	case 189: // DGM
	case 92: // BM
		$usr_allstatus = "B";
		break;
	case 165: // SR.BM
	case 19: // GM
		$usr_allstatus = "G";
		$connectWith = "#sksir_tasks,#kssir_tasks,#gm_seniorgm_tasks,hodtasks,#it_tasks";
		break;
	case 78: 
	case 195: // MD SK Sir
	case 194: // MD SK Sir
		$usr_allstatus = "MD2";
		$connectWith = "#sksir_tasks,#kssir_tasks,#gm_seniorgm_tasks,#hodtasks,#it_tasks";
		break;
	case 9:  
	case 193:  // MD KS Sir
		$usr_allstatus = "MD1";
		$connectWith = "#sksir_tasks,#kssir_tasks,#gm_seniorgm_tasks,#hodtasks,#it_tasks";
		break;
	default: // PM
		$usr_allstatus = "P";
		break;
}

if($_SESSION['tcs_esecode']==20){
	$usr_allstatus = "IT";	
	$connectWith = "#hodtasks,#gm_seniorgm_tasks";
}
if($inner_menuaccess[0]['VEWVALU'] == 'Y') { // Menu Permission is allowed ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <!-- META SECTION -->
    <title>Paperless Approval :: Approval Desk :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <!-- Select2 -->
    <link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">
	<link href="../bootstrap/css/autocomplete_jquery-ui.css" rel="stylesheet" type="text/css" />

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
		item.task-primary {
    border-left-color: #1b1e24;
   }
   
	.page-container .page-content .content-frame {
		//background: #f5f5f5 url(../../images/3/bg1.jfif) center center no-repeat !important;
		//background: #f5f5f5 url(../../images/bg.jpg) center center no-repeat !important;
	}
	
	#hodtasks,#gm_seniorgm_tasks,#sksir_tasks,#kssir_tasks,#it_tasks{
		border: 1px solid #fff;
		height: 700px;
		width: 100%;
		overflow-y: scroll;
		overflow-x: hidden
	}
	.flow-title{
		border: 1px solid #fff;
		margin-bottom: 0px;
		text-align: center;
		border-bottom: 0px;
		height: 30px;
		padding: 7px;
		background: #fff;
		font-size: 13px;	
	}
	.tasks .task-item{
		margin: 4px 3px;
		width: 97% !important;
	}
	/* width */
	::-webkit-scrollbar {
		width: 5px;
	}
	/* Track */
	::-webkit-scrollbar-track {
		background: #ecc772; 
	}
	/* Handle */
	::-webkit-scrollbar-thumb {
		/*background: #888; */
	}
	/* Handle on hover */
	::-webkit-scrollbar-thumb:hover {
		/*background: #555; */
		
	}
	
	@media (min-width: 768px){
		.modal-dialog {
			width: 80% !important;
		}
	}
	
	.tasks .task-item .task-footer{
		font-size: 9px !important;
		color: #000 !important;
	}
	
	.clr_both{
		clear:both;	
	}
	
	.ui-front {
		z-index: 9999 !important;
	}
	
	.loader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		opacity: 0.4;
		// background: url('images/cassette.jpg') 50% 50% no-repeat rgb(249,249,249);
		background: url('../images/preloader.gif') 50% 50% no-repeat rgb(249,249,249);
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
	<div id='pageloader' class="loader" style="display:none;"></div>
        <!-- START PAGE CONTAINER -->
        <div class="page-container page-navigation-toggled">
            
            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <!-- START X-NAVIGATION -->
                <? include 'lib/app_left_panel.php'; ?>
                <!-- END X-NAVIGATION -->
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->
            
            <!-- PAGE CONTENT -->
            <div class="page-content">
                <!-- START X-NAVIGATION VERTICAL -->
                <? include "lib/app_header.php"; ?>
                <!-- END X-NAVIGATION VERTICAL -->
                                   
                
                <!-- START BREADCRUMB -->
                <ul class="breadcrumb push-down-0">
                    <li><a href="purchase_po_dashboard.php">Dashboard</a></li>
                    <li class="active">Operation Approval</li>
                </ul>
                <!-- END BREADCRUMB -->                                                
                                
                <!-- START CONTENT FRAME -->
                <div class="content-frame">     
                    <!-- START CONTENT FRAME TOP -->
                    <div class="content-frame-top">                        
                        <div class="page-title">                    
                            <h2><span class="fa fa-arrow-circle-o-left"></span> Operation Approval</h2>
                        </div>                                                
                        <div class="pull-right">
                            <button class="btn btn-default content-frame-left-toggle"><span class="fa fa-bars"></span></button>
                        </div>                                
                        <!--<div class="pull-right" style="width: 100px; margin-right: 5px;">
                            <select class="form-control select">
                                <option>All</option>                                
                                <option>Work</option>
                                <option>Home</option>
                                <option>Friends</option>
                                <option>Closed</option>
                            </select>
                        </div>-->
                        
                    </div>                    
                   
                    <!-- END CONTENT FRAME TOP -->
                    
                    <!-- START CONTENT FRAME BODY -->
                    <div class="content-frame-body" style="margin-left:0px;">
                                                
                        <div class="row push-up-10">
						<div class="col-md-1">&nbsp;</div>
                            <div class="col-md-2" style="background: #e4ecdd; !important;margin: 0px 8px;padding: 5px;">
                                
                                <h3 class="flow-title">HOD</h3>
                                
                                <div class="tasks" id="hodtasks">
									<?php 
										
										$selectWaitCntSource = select_query_json("SELECT PO.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE FROM PAPERLESS_APP_PO po WHERE PO.ADDUSER='".$_SESSION['tcs_usrcode']."'", "Centra", 'TEST');
										if($usr_allstatus=="H" || count($selectWaitCntSource)>0){//check GM condition open
										
										$selectEmpSource = select_query_json("SELECT EMPCODE FROM EMPLOYEE_OFFICE WHERE EMPSRNO='".$_SESSION['tcs_empsrno']."'","Centra","TCS");
										//hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."'
										$selectWaitSource = select_query_json("SELECT PO.PAPYEAR,PO.PAPNUMB,PO.PORYEAR,PO.PORNUMB,PO.PAPSRNO,PO.PAPCODE,PO.GRPSRNO,PO.SUPCODE,PO.NEW_SUPCODE,PO.PRDCODE,PO.NEW_PRDCODE,PO.PORPRAT,PO.NEW_PORPRAT,PO.PORSALR,PO.NEW_PORSALR,PO.PORDISC,PO.NEW_PORDISC,PO.PORSPDC,PO.NEW_PORSPDC,PO.PORPCLS,PO.NEW_PORPCLS,HR.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr WHERE 
										po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB and hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."' and hr.HSTAT='N' and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
										$cardContainer = '';
										$i=0;
										//and po.PAPSRNO=hr.PAPSRNO 
										foreach($selectWaitSource as $key => $podata){$i++;
											$int_emp_chk = select_query_json("SELECT * FROM PAPERLESS_APP_HIERARCHY WHERE PAPYEAR='".$podata['PAPYEAR']."' And PAPNUMB='".$podata['PAPNUMB']."' And PAPCODE='".$podata['PAPCODE']."' And FLWSRNO=9","Centra","TEST");
											//echo count($int_emp_chk);
											$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
										<div class="task-item task-primary <?php if(count($int_emp_chk)>1){?> internal <?php }else{ ?> hod-normal <?php } ?>" id="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>">
										<div class="task-text">
										<?php } 
										if($i==0){ 
										?>
										<?php if(count($int_emp_chk)>1){
											$int_emp_chk1 = select_query_json("SELECT * FROM PAPERLESS_APP_HIERARCHY WHERE PAPYEAR='".$podata['PAPYEAR']."' And PAPNUMB='".$podata['PAPNUMB']."' And PAPCODE='".$podata['PAPCODE']."' And HSTAT='V' And HRSRN='".($podata['HRSRN']-1)."'","Centra","TEST");
										/* echo "SELECT * FROM PAPERLESS_APP_HIERARCHY WHERE PAPYEAR='".$podata['PAPYEAR']."' And PAPNUMB='".$podata['PAPNUMB']."' And PAPSRNO='".$podata['PAPSRNO']."' And PAPCODE='".$podata['PAPCODE']."' And HSTAT='V' And HRSRN='".($podata['HRSRN']-1)."'"; */?>
										
										
										<input type="hidden"  id="intverify_empcode" value="<?php echo $int_emp_chk1[0]['EMPCODE'];?>"/>
										<?php } ?>
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											<input type="hidden"  id="hrsrn" value="<?php echo $podata['HRSRN'];  ?>"/>
											<input type="hidden"  id="empcode" value="<?php echo $podata['EMPCODE'];  ?>"/>
											<input type="hidden"  id="flwsrno" value="<?php echo $podata['FLWSRNO'];  ?>"/>
											<input type="hidden" id="classid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden" id="requestid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden"  id="apphstat" value="1"/>
											
											<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
												<?php 
												
											}		
											
											
													
													  if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div> <span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
											<?php
												$list2 = $selectWaitSource[$key+1]['PAPYEAR']."~".$selectWaitSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" class="Remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>" name="remarks" id="remarks" value="" />
												</div>
											</div>
											<?php } ?>
											
											
											<?php if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
													<div class="clr_both"></div>
													Approve History
													<div id="approveHistroy-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>"></div>
													<?php
														$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."' and hr.EMPCODE=emp.empcode and hr.hstat in ('F','V') ORDER BY hr.HRSRN desc","Centra",'TEST');
														foreach($ApprovalQry as $appdata){ ?>
														<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span> <?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
														<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
														<?php } ?>  
														<div class="clr_both"></div>&nbsp;
														<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														
															<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
															<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
														</div>
													</div> 
											<?php } ?>		
												
												<?php if($cardContainer!= $list2){ ?>
												
													</div>
												<?php } 
												}
											}
											
											$selectWaitCntSource = select_query_json("SELECT  PO.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE,emp.descode FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB  and emp.EMPCODE=hr.EMPCODE and hr.HSTAT='N' and emp.descode='132' and PO.ADDUSER='".$_SESSION['tcs_usrcode']."'  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$cardContainer= '';
											foreach($selectWaitCntSource as $key => $podata){$i++;
												
												
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
										<div class="task-item task-primary approvalwaiting">
										<div class="task-text">
											<?php } 
											if($i==0){ 
											?>
										
										
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											
											
											<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
												<?php 
												
											}		
												 
													 if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
											<?php
												$list2 = $selectWaitCntSource[$key+1]['PAPYEAR']."~".$selectWaitCntSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												<!--</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" name="remarks" id="remarks" value="" />
												</div>-->
											</div>
											<?php } ?>
											
											
											<?php 
											
											
											
											if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
													<div class="clr_both"></div>
													Approve History
													<div id="approveHistroy-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>"></div>
													<?php
														$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."' and hr.EMPCODE=emp.empcode and hr.hstat in ('F','V') ORDER BY hr.HRSRN desc","Centra",'TEST');
														foreach($ApprovalQry as $appdata){ ?>
														<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span> <?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
														<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
														<?php } ?>  
														<div class="clr_both"></div>&nbsp;
														<!--<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														
															<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
															<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
														</div>-->
													</div> 
											<?php } ?>		
												
												<?php
													if($cardContainer!= $list2){
												?>
													</div>
													<?php } 
											}
									?>
									                            
								</div>
							</div>
                            <div class="col-md-2" style="background: #e4ecdd; !important;margin: 0px 8px;padding: 5px;">
                                <h3 class="flow-title">Operation GM / Senior GM</h3>
                                <div class="tasks" id="gm_seniorgm_tasks">
									<?php 
										
										if($usr_allstatus=="G"){//check GM condition open
										
											$selectEmpSource = select_query_json("SELECT EMPCODE FROM EMPLOYEE_OFFICE WHERE EMPSRNO='".$_SESSION['tcs_empsrno']."'","Centra","TCS");
											//hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."'
											$selectWaitSource = select_query_json("SELECT PO.*,HR.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr WHERE 
											po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB and hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."' and hr.HSTAT='N'  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$i=0;
											$cardContainer = '';
											foreach($selectWaitSource as $key => $podata){$i++;
												$int_emp_chk_gm = select_query_json("SELECT * FROM PAPERLESS_APP_HIERARCHY WHERE PAPYEAR='".$podata['PAPYEAR']."' And PAPNUMB='".$podata['PAPNUMB']."'  And PAPCODE='".$podata['PAPCODE']."' And FLWSRNO=10","Centra","TEST");
											//echo count($int_emp_chk_gm);
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
												if($cardContainer=='' || $cardContainer!= $list){
													$i=0;
													$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
										<div class="task-item task-primary <?php if(count($int_emp_chk_gm)>1){?>internal<?php } ?>" id="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>">
										<div class="task-text">
										<?php } 
										
										
										if($i==0){ 
										
										 if(count($int_emp_chk_gm)>1){
											//echo "Here";
										$int_emp_chk_gm1 = select_query_json("SELECT * FROM PAPERLESS_APP_HIERARCHY WHERE PAPYEAR='".$podata['PAPYEAR']."' And PAPNUMB='".$podata['PAPNUMB']."' And PAPCODE='".$podata['PAPCODE']."' And HSTAT='V' And HRSRN='".($podata['HRSRN']-1)."'","Centra","TEST");?>
										<input type="hidden"  id="intverify_empcode" value="<?php echo $int_emp_chk_gm1[0]['EMPCODE'];?>"/>
										<?php } ?>
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											<input type="hidden"  id="hrsrn" value="<?php echo $podata['HRSRN'];  ?>"/>
											<input type="hidden"  id="empcode" value="<?php echo $podata['EMPCODE'];  ?>"/>
											<input type="hidden"  id="flwsrno" value="<?php echo $podata['FLWSRNO'];  ?>"/>
											<input type="hidden" id="classid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden" id="requestid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden"  id="apphstat" value="2"/>
											
											
											
												<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
											
											<?php 
										 }
											
											
													  if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
											<?php
												$list2 = $selectWaitSource[$key+1]['PAPYEAR']."~".$selectWaitSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" class="Remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>" name="remarks" id="remarks" value="" />
												</div>
											</div>
											<?php } ?>
											
											
											<?php if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
												<div class="clr_both"></div>
													Approve History
													
													<?php
													$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."'  and hr.EMPCODE=emp.empcode and  hr.hstat in ('F','V') ORDER BY hr.HRSRN DESC","Centra",'TEST');
													foreach($ApprovalQry as $appdata){ ?>
													<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span><?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
													<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
												<?php } ?> 
												<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
													<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
													<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
												</div>												
											</div> 
											<?php } ?>		
												
												<?php if($cardContainer!= $list2){ ?>
												
													</div>
												<?php } 
												}
											}
									
										$selectWaitCntSource = select_query_json("SELECT PO.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB and emp.EMPCODE=hr.EMPCODE and hr.HSTAT='N' and emp.descode in (165,19)  and PO.ADDUSER='".$_SESSION['tcs_usrcode']."'  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$cardContainer= '';
											foreach($selectWaitCntSource as $key => $podata){$i++;
												
												
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
										<div class="task-item task-primary approvalwaiting">
										<div class="task-text">
											<?php } 
											if($i==0){ 
											?>
										
										
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											
											
											<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
												<?php 
												
											}		
												 
													 if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
											<?php
												$list2 = $selectWaitCntSource[$key+1]['PAPYEAR']."~".$selectWaitCntSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												<!--</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" name="remarks" id="remarks" value="" />
												</div>-->
											</div>
											<?php } ?>
											
											
											<?php 
											
											
											
											if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
													<div class="clr_both"></div>
													Approve History
													<div id="approveHistroy-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>"></div>
													<?php
														$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."' and hr.EMPCODE=emp.empcode and hr.hstat in ('F','V') ORDER BY hr.HRSRN desc","Centra",'TEST');
														foreach($ApprovalQry as $appdata){ ?>
														<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span> <?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
														<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
														<?php } ?>  
														<div class="clr_both"></div>&nbsp;
														<!--<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														
															<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
															<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
														</div>-->
													</div> 
											<?php } ?>		
												
												<?php
													if($cardContainer!= $list2){
												?>
													</div>
													<?php } 
											}
									?>
									                            
								</div>
							</div>
                            <div class="col-md-2" style="background: #e4ecdd; !important;margin: 0px 8px;padding: 5px;">
                                <h3 class="flow-title">SK Sir</h3>
                                <div class="tasks" id="sksir_tasks">
                                     <?php 
										if($usr_allstatus=="MD2"){//check GM condition open
											$selectEmpSource = select_query_json("SELECT EMPCODE FROM EMPLOYEE_OFFICE WHERE EMPSRNO='".$_SESSION['tcs_empsrno']."'","Centra","TCS");
											//hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."'
											$selectWaitSource = select_query_json("SELECT PO.*,HR.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr WHERE 
											po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB and hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."' and hr.HSTAT='N'  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$cardContainer = '';
											$i=0;
											foreach($selectWaitSource as $key => $podata){$i++;
												
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
										<div class="task-item task-primary" id="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>">
										<div class="task-text">
										<?php }

										if($i==0){ 
										?>										
										
										
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											<input type="hidden"  id="hrsrn" value="<?php echo $podata['HRSRN'];  ?>"/>
											<input type="hidden"  id="empcode" value="<?php echo $podata['EMPCODE'];  ?>"/>
											<input type="hidden"  id="flwsrno" value="<?php echo $podata['FLWSRNO'];  ?>"/>
											<input type="hidden" id="classid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden" id="requestid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden"  id="apphstat" value="3"/>
										<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
										
										<?php } ?>
											
											
											
												<?php 
													  if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  
														  if($podata['NEW_SUPCODE']!=''){
															echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
														  }

														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
												<?php
												$list2 = $selectWaitSource[$key+1]['PAPYEAR']."~".$selectWaitSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" class="Remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>" name="remarks" id="remarks" value="" />
												</div>
											</div>
											<?php } ?>
											<?php if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
												<div class="clr_both"></div>
													Approve History
													<?php
													$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."'  and hr.EMPCODE=emp.empcode and  hr.hstat in ('V','F') ORDER BY hr.HRSRN DESC","Centra",'TEST');
													foreach($ApprovalQry as $appdata){ ?>
													<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span><?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
													<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
												<?php } ?> 
													<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
														<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
													</div>													
											</div>                                    
										
										<?php } ?>		
												
												<?php if($cardContainer!= $list2){ ?>
												
													</div>
												<?php } 
												}
											}
										
										
									
										$selectWaitCntSource = select_query_json("SELECT PO.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE,emp.descode FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB and emp.EMPCODE=hr.EMPCODE and hr.HSTAT='N' and PO.ADDUSER='".$_SESSION['tcs_usrcode']."' and emp.descode in (78,195,194)  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$cardContainer= '';
											foreach($selectWaitCntSource as $key => $podata){$i++;
												
												
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
										<div class="task-item task-primary approvalwaiting">
										<div class="task-text">
											<?php } 
											if($i==0){ 
											?>
										
										
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											
											
											<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
												<?php 
												
											}		
												 
													 if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
											<?php
												$list2 = $selectWaitCntSource[$key+1]['PAPYEAR']."~".$selectWaitCntSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												<!--</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" name="remarks" id="remarks" value="" />
												</div>-->
											</div>
											<?php } ?>
											
											
											<?php 
											
											
											
											if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
													<div class="clr_both"></div>
													Approve History
													<div id="approveHistroy-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>"></div>
													<?php
														$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."' and hr.EMPCODE=emp.empcode and hr.hstat in ('F','V') ORDER BY hr.HRSRN desc","Centra",'TEST');
														foreach($ApprovalQry as $appdata){ ?>
														<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span> <?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
														<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
														<?php } ?>  
														<div class="clr_both"></div>&nbsp;
														<!--<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														
															<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
															<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
														</div>-->
													</div> 
											<?php } ?>		
												
												<?php
													if($cardContainer!= $list2){
												?>
													</div>
													<?php } 
											}
									?>
									                            
								</div>
							</div>
							
							<div class="col-md-2" style="background: #e4ecdd; !important;margin: 0px 8px;padding: 5px;">
                                <h3 class="flow-title">KS Sir</h3>
                                <div class="tasks" id="kssir_tasks">
									<?php 
										
										if($usr_allstatus=="MD1"){//check GM condition open
										
											$selectEmpSource = select_query_json("SELECT EMPCODE FROM EMPLOYEE_OFFICE WHERE EMPSRNO='".$_SESSION['tcs_empsrno']."'","Centra","TCS");
											//hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."'
											$selectWaitSource = select_query_json("SELECT PO.*,HR.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr WHERE 
											po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB  and hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."' and hr.HSTAT='N'  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$cardContainer = '';
											$i=0;
											foreach($selectWaitSource as $key=>$podata){$i++;
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
									<div class="task-item task-primary" id="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>">
										<div class="task-text">
										<?php } 
										
										if($i==0){ 
										?>
										
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											<input type="hidden"  id="hrsrn" value="<?php echo $podata['HRSRN'];  ?>"/>
											<input type="hidden"  id="empcode" value="<?php echo $podata['EMPCODE'];  ?>"/>
											<input type="hidden"  id="flwsrno" value="<?php echo $podata['FLWSRNO'];  ?>"/>
											<input type="hidden" id="classid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden" id="requestid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden"  id="apphstat" value="4"/>
											
											
												<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
												<?php 
												
											}	 
													 if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
											<?php
												$list2 = $selectWaitSource[$key+1]['PAPYEAR']."~".$selectWaitSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" class="Remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>" name="remarks" id="remarks" value="" />
												</div>
											</div>
											<?php } ?>
											<?php if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
												<div class="clr_both"></div>
													Approve History
													<?php
													$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."'  and hr.EMPCODE=emp.empcode and  hr.hstat in ('V','F') ORDER BY hr.HRSRN DESC","Centra",'TEST');
													foreach($ApprovalQry as $appdata){ ?>
													<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span><?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
													<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
												<?php } ?>
													<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
														<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
													</div>													
											</div>                                       
										<?php } ?>		
												
												<?php if($cardContainer!= $list2){ ?>
												
													</div>
												<?php } 
												}
											}
										
										$selectWaitCntSource = select_query_json("SELECT PO.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE,emp.descode FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB and emp.EMPCODE=hr.EMPCODE and hr.HSTAT='N' and  emp.descode in (9,193) and PO.ADDUSER='".$_SESSION['tcs_usrcode']."'  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$cardContainer= '';
											foreach($selectWaitCntSource as $key => $podata){$i++;
												
												
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
										<div class="task-item task-primary approvalwaiting">
										<div class="task-text">
											<?php } 
											if($i==0){ 
											?>
										
										
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											
											
											<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
												<?php 
												
											}		
												 
													 if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
											<?php
												$list2 = $selectWaitCntSource[$key+1]['PAPYEAR']."~".$selectWaitCntSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												<!--</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" name="remarks" id="remarks" value="" />
												</div>-->
											</div>
											<?php } ?>
											
											
											<?php if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
													<div class="clr_both"></div>
													Approve History
													<div id="approveHistroy-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>"></div>
													<?php
														$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."' and hr.EMPCODE=emp.empcode and hr.hstat in ('F','V') ORDER BY hr.HRSRN desc","Centra",'TEST');
														foreach($ApprovalQry as $appdata){ ?>
														<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span> <?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
														<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
														<?php } ?>  
														<div class="clr_both"></div>&nbsp;
														<!--<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														
															<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
															<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
														</div>-->
													</div> 
											<?php } ?>		
												
												<?php
													if($cardContainer!= $list2){
												?>
													</div>
													<?php } 
											}
									?>
									                            
								</div>
							</div>
                           <!-- <div class="col-md-2" style="background: #f5f5f5 url(../../images/bg.jpg) center center no-repeat !important;margin: 0px 8px;padding: 5px;">-->
						   <div class="col-md-2" style="background: #e4ecdd; !important;margin: 0px 8px;padding: 5px;">
						   
						   
                                <h3 class="flow-title">Execution</h3>
                                <div class="tasks" id="it_tasks">
                                    <?php 
										
										if($usr_allstatus=="IT"){//check IT condition open
											
											$selectEmpSource = select_query_json("SELECT EMPCODE FROM EMPLOYEE_OFFICE WHERE EMPSRNO='".$_SESSION['tcs_empsrno']."'","Centra","TCS");
											
											$selectWaitSource = select_query_json("SELECT PO.*,HR.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr WHERE 
											po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB and hr.EMPCODE='".$selectEmpSource[0]['EMPCODE']."' and hr.HSTAT='N'  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$cardContainer='';
											$i=0;
											foreach($selectWaitSource as $key => $podata){$i++;
												
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
									<div class="task-item task-primary" id="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>">
										<div class="task-text">
										<?php } 
										
										if($i==0){ 
									
										?>
										
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											<input type="hidden"  id="hrsrn" value="<?php echo $podata['HRSRN'];  ?>"/>
											<input type="hidden"  id="empcode" value="<?php echo $podata['EMPCODE'];  ?>"/>
											<input type="hidden"  id="flwsrno" value="<?php echo $podata['FLWSRNO'];  ?>"/>
											<input type="hidden" id="classid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden" id="requestid" value="<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'] ?>"/>
											<input type="hidden"  id="apphstat" value="5"/>
											
											
											
											<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
												<?php 
												
											}	
												
													  if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												$list2 = $selectWaitSource[$key+1]['PAPYEAR']."~".$selectWaitSource[$key+1]['PAPNUMB'];

												if($cardContainer!= $list2){ ?>
											
												</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" class="Remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>" name="remarks" id="remarks" value="" />
												</div>
											</div>
											<?php } ?>
									
											<?php if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<?php
													$selectEmpSource = select_query_json("SELECT EMPCODE FROM EMPLOYEE_OFFICE WHERE EMPCODE='".$_SESSION['tcs_empsrno']."'","Centra","TCS");
													
												?>
												<div class="pull-left"> Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
												<div class="clr_both"></div>
													Approve History
													<?php
													$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."' and hr.EMPCODE=emp.empcode and  hr.hstat in ('V','F') ORDER BY hr.HRSRN DESC","Centra",'TEST');
													foreach($ApprovalQry as $appdata){ ?>
													<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span> <?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
													<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
												<?php } ?>
													<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														<a class="glyphicon glyphicon-ok-sign" style="color:green; font-size:15px;" onclick="ok_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
													
														<!--<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
														<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>-->
													</div>
											</div>                                         
										<?php } ?>		
												
												<?php if($cardContainer!= $list2){ ?>
												
													</div>
												<?php } 
												}
											}
										
										
										$selectWaitCntSource = select_query_json("SELECT PO.*,to_Char(po.adddate,'dd-MM-yyyy HH:MI:SS AM') AS ADD_DATE,hr.EMPCODE FROM PAPERLESS_APP_PO po,PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE po.PAPYEAR=hr.PAPYEAR and po.PAPNUMB=hr.PAPNUMB and emp.EMPCODE=hr.EMPCODE and hr.HSTAT='N' and PO.ADDUSER='".$_SESSION['tcs_usrcode']."' and emp.empcode in (5077)  and po.deleted='N' ORDER BY PO.Papyear,PO.papnumb,PO.papsrno", "Centra", 'TEST');
											$cardContainer= '';
											foreach($selectWaitCntSource as $key => $podata){$i++;
												
												
												$list = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											if($cardContainer=='' || $cardContainer!= $list){
												$i=0;
												$cardContainer = $podata['PAPYEAR']."~".$podata['PAPNUMB'];
											?>
										<div class="task-item task-primary approvalwaiting">
										<div class="task-text">
											<?php } 
											if($i==0){ 
											?>
										
										
											<input type="hidden"  id="papyear" value="<?php echo $podata['PAPYEAR']; ?>"/>
											<input type="hidden"  id="papnumb" value="<?php echo $podata['PAPNUMB'];  ?>"/>
											<input type="hidden"  id="papsrno" value="<?php echo $podata['PAPSRNO'];  ?>"/>
											<input type="hidden"  id="papcode" value="<?php echo $podata['PAPCODE'];  ?>"/>
											
											
											<h2 style="font-size: 16px;font-weight: bold;">PO NO : <span style="color:blue;"><?php echo $podata['PORYEAR']; ?> - <?php echo $podata['PORNUMB']; ?></span></h2>
												<?php 
												
											}		
												 
													 if($podata['GRPSRNO']==0){
															echo '<div><span style="color:red;">Supplier code</span> </br>Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div>';
													  }else{
														  if($i==0){ 
															  if($podata['NEW_SUPCODE']!=''){
																echo '<div><span style="color:red;">Supplier code</span> </br> &nbsp;&nbsp;Existing :'.$podata['SUPCODE'].", Proposed:".$podata['NEW_SUPCODE'].'</div></br>';
															  }
														  }
														  
														  if($podata['NEW_PRDCODE']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Product code</span> </br>&nbsp;&nbsp;Existing :'.$podata['PRDCODE'].", Proposed:".$podata['NEW_PRDCODE'].'</div></br>'; 
														  }
														  if($podata['NEW_PORPRAT']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Purchase rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPRAT'].", Proposed:".$podata['NEW_PORPRAT'].'</div></br>'; 
														  }
														  if($podata['NEW_PORSALR']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Sale rate</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSALR'].", Proposed:".$podata['NEW_PORSALR'].'</div></br>'; 
														  }
														  if($podata['NEW_PORDISC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Regular Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORDISC'].", Proposed:".$podata['NEW_PORDISC'].'</div></br>';  
														  }
														  if($podata['NEW_PORSPDC']!=''){
															echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Special Disc</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORSPDC'].", Proposed:".$podata['NEW_PORSPDC'].'</div></br>';  
														  }
														  if($podata['NEW_PORPCLS']!=''){
															 echo '<div> G['.$podata['GRPSRNO'].'] <span style="color:red;">Pieceless</span> </br>&nbsp;&nbsp;Existing :'.$podata['PORPCLS'].", Proposed:".$podata['NEW_PORPCLS'].'</div>'; 
														  }
													  }
												?>
											<?php
												$list2 = $selectWaitCntSource[$key+1]['PAPYEAR']."~".$selectWaitCntSource[$key+1]['PAPNUMB'];


											if($cardContainer!= $list2){ ?>
											
												<!--</br>
												<div id="remarks-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE']; ?>">
													Comment: <input type="text" name="remarks" id="remarks" value="" />
												</div>-->
											</div>
											<?php } ?>
											
											
											<?php 
											
											
											
											if($cardContainer!= $list2){ ?>
											<div class="task-footer">
												<div class="pull-left">Created <span class="fa fa-clock-o"></span> <?php echo $podata['ADD_DATE']; ?></div>
													<div class="clr_both"></div>
													Approve History
													<div id="approveHistroy-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>"></div>
													<?php
														$ApprovalQry = select_query_json("SELECT hr.*,emp.*,to_Char(hr.APPDATE,'dd-MM-yyyy HH:MI:SS AM') AS APP_DATE,emp.descode FROM PAPERLESS_APP_HIERARCHY hr,EMPLOYEE_OFFICE emp WHERE hr.PAPYEAR='".$podata['PAPYEAR']."' and hr.PAPNUMB='".$podata['PAPNUMB']."' and hr.EMPCODE=emp.empcode and hr.hstat in ('F','V') ORDER BY hr.HRSRN desc","Centra",'TEST');
														foreach($ApprovalQry as $appdata){ ?>
														<div class="pull-left" style="width:100%;"><span class="fa fa-clock-o"></span> <?php echo $appdata['EMPCODE']." - ".$appdata['EMPNAME']." ".$appdata['APP_DATE'];  ?> <?php echo $podata['APP_DATE']; ?></div>
														<div style="width:100%;"><?php echo $appdata['REMARKS'];?></div>
														<?php } ?>  
														<div class="clr_both"></div>&nbsp;
														<!--<div class="pull-right" id="editoption-<?php echo $podata['PAPYEAR']."~".$podata['PAPNUMB']."~".$podata['PAPCODE'];?>">
														
															<a data-toggle="modal" onclick="showPoEdit('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')" data-target="#modal-default" style="color:red; font-size:15px;" class="glyphicon glyphicon-edit"></a>
															<a class="glyphicon glyphicon-remove-circle" style="color:red; font-size:15px;" onclick="reject_approval('<?php echo $podata['PAPYEAR']; ?>','<?php echo $podata['PAPNUMB']; ?>','<?php echo $podata['PAPSRNO']; ?>','<?php echo $podata['PAPCODE']; ?>')"></a>
														</div>-->
													</div> 
											<?php } ?>		
												
												<?php
													if($cardContainer!= $list2){
												?>
													</div>
													<?php } 
											}
									?>
									                            
								</div>
							</div>
							<div class="col-md-1">&nbsp;</div>
                        </div>                        
                                                
                    </div>
                    <!-- END CONTENT FRAME BODY -->
                    
                </div>
                <!-- END CONTENT FRAME -->

            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

         <? include "lib/app_footer.php"; ?>
		<div class="modal fade" id="modal-default" style="display: none;">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span></button>
				<h4 class="modal-title">HOD Operation Approval</h4>
			  </div>
			  <div class="modal-body" id="po-edit-container" style="overflow:hidden;">
				
			   </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save</button>-->
			  </div>
			</div>
			<!-- /.modal-content -->
		  </div>
		  <!-- /.modal-dialog -->
		</div>
                    
        
    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
		 <script src="../bootstrap/js/autocomplete_jquery-ui.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        
        <script type="text/javascript" src="js/plugins/moment.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script> 
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/settings.js"></script>
        
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>
        <script type="text/javascript">
		
			function reject_approval(papyear,papnumb,papsrno,papcode) 
			{
				
				
				
				var alertStatus = confirm("Are you sure to Reject this Approval");
				
				if(alertStatus){
				
				
						//alert("mohan");
						$('.loader').show(); // show the loading message.
						$.ajax({
							method:'POST',
							url:"ajax/ajax_supplier_details.php?action=reject_approval&papyear="+papyear+"&papnumb="+papnumb+"&papsrno="+papsrno+"&papcode="+papcode,
							success:function(data){
									$('.loader').hide(); // hide the loading message.
									//alert(data);
								//if(data == 1) 
								//{
									//var ALERT_TITLE = "Message";
									var ALERTMSG = "Rejected Successfully..!!";
									//createCustomAlert(ALERTMSG, ALERT_TITLE);
									alert(ALERTMSG);
									document.getElementById(papyear+'~'+papnumb+'~'+papsrno+'~'+papcode).style.display = 'none';
								//} else {					
									//var ALERT_TITLE = "Message";
									//var ALERTMSG = "Failed to Reject.. Kindly try again!!";
									//createCustomAlert(ALERTMSG, ALERT_TITLE);
									//alert(ALERTMSG);
									//document.getElementById(papyear+'~'+papnumb+'~'+papsrno+'~'+papcode).style.display = 'none';
								//}
								//$(".loader").fadeOut("fast");
							}
						});

				}
			}
			
			function ok_approval(papyear,papnumb,papsrno,papcode) 
			{
				var alertStatus = confirm("Are you sure to Confirm your Approval Request");
				
				if(alertStatus){
				
				
						//alert("mohan");
						$('.loader').show(); // show the loading message.
						$.ajax({
							method:'POST',
							url:"ajax/ajax_supplier_details.php?action=ok_approval&papyear="+papyear+"&papnumb="+papnumb+"&papsrno="+papsrno+"&papcode="+papcode,
							success:function(data){
									$('.loader').hide(); // hide the loading message.
									//alert(data);
								//if(data == 1) 
								//{
									var ALERT_TITLE = "Message";
									var ALERTMSG = "Request Successfully Approved..!!";
									createCustomAlert(ALERTMSG, ALERT_TITLE);
									document.getElementById(papyear+'~'+papnumb+'~'+papcode).style.display = 'none';
								//} else {					
									//var ALERT_TITLE = "Message";
									//var ALERTMSG = "Failed to Reject.. Kindly try again!!";
									//createCustomAlert(ALERTMSG, ALERT_TITLE);
									//alert(ALERTMSG);
									//document.getElementById(papyear+'~'+papnumb+'~'+papsrno+'~'+papcode).style.display = 'none';
								//}
								//$(".loader").fadeOut("fast");
							}
						});

				}
				
			}
			
			
			
			
			function showPoEdit(papyear,papnumb,papsrno,papcode){
				$('.loader').show(); // show the loading message.
				$.ajax({
					method:'POST',
					url:"ajax/ajax_supplier_details.php?action=po_edit_approval&papyear="+papyear+"&papnumb="+papnumb+"&papsrno="+papsrno+"&papcode="+papcode,
					success:function(data){
						$('.loader').hide(); // hide the loading message.
						document.getElementById('po-edit-container').innerHTML = data;
						$('#txt_newsupcode').autocomplete({
							source: function( request, response ) {
								$.ajax({
									url : 'ajax/ajax_supplier_details.php',
									dataType: "json",
									data: {
									   name_startsWith: request.term,
									   action: 'supplier'
									},
									success: function( data ) {
										
										response( $.map( data, function( item ) {
											var spl = item.split(" - ");
										
										//alert(spl[0]+" - "+spl[1]+" - "+spl[2]);
										if(spl[2]==null || spl[2]==""){
											spl[2] = 'Nil';
										}
										$('#gst-label').html(spl[2])
											return {
												label: spl[0]+" - "+spl[1],
												value: spl[0]+" - "+spl[1]
											}
										}));
									}
								});
							},
							autoFocus: true,
							minLength: 0
						});
						
						$('#new_prdcode_1').autocomplete({
							source: function( request, response ) {
								$.ajax({
									url : 'ajax/ajax_supplier_details.php',
									dataType: "json",
									data: {
									   name_startsWith: request.term,
									   action: 'product'
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
						
						$('#new_prdcode_2').autocomplete({
							source: function( request, response ) {
								$.ajax({
									url : 'ajax/ajax_supplier_details.php',
									dataType: "json",
									data: {
									   name_startsWith: request.term,
									   action: 'product'
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
						
						
						$('#new_prdcode_3').autocomplete({
							source: function( request, response ) {
								$.ajax({
									url : 'ajax/ajax_supplier_details.php',
									dataType: "json",
									data: {
									   name_startsWith: request.term,
									   action: 'product'
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
						
						
						$('#new_prdcode_4').autocomplete({
							source: function( request, response ) {
								$.ajax({
									url : 'ajax/ajax_supplier_details.php',
									dataType: "json",
									data: {
									   name_startsWith: request.term,
									   action: 'product'
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
						
						
						$('#new_prdcode_5').autocomplete({
							source: function( request, response ) {
								$.ajax({
									url : 'ajax/ajax_supplier_details.php',
									dataType: "json",
									data: {
									   name_startsWith: request.term,
									   action: 'product'
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
		
						
					}
				});
			}
		
		
			$(function(){
				var tasks = function(){
				//,#sksir_tasks,#kssir_tasks,#it_tasks
				
				$("#hodtasks,#gm_seniorgm_tasks,#sksir_tasks,#kssir_tasks,#it_tasks").sortable({
						items: "> .task-item",
						connectWith: "<?php echo $connectWith; ?>",
						handle: ".task-text",
						update : function () { 
							//console.log("sort");
						},
						receive: function(event, ui) {
							console.log("drag");
							
							
							if(ui.item.hasClass("approvalwaiting")){
								ui.sender.sortable("cancel");
								return false;
							}
							if(ui.item.find("#classid").val()=='approvalwaiting'){
								ui.sender.sortable("cancel");
							}
							if(this.id == "hodtasks"){
								
								var hstat='F';
								if(ui.item.hasClass("internal")){
								
									if(ui.item.find("#intverify_empcode").val()!=1602){
										ui.sender.sortable("cancel");
										return false; 
									 }else{
										var hstat='V'; 
									}
								}
								
								if(ui.item.find("#remarks").val()==''){
									ui.sender.sortable("cancel");
									alert("Please enter Remarks");
									return false;
								}
								var apphstat =1;
								if(apphstat<ui.item.find("#apphstat").val()){
									var hstat='V';
								}

								if(ui.item.find("#classid").val()!='approvalwaiting'){
									ui.item.find("#classid").val("approvalwaiting");
									$.ajax({
										url : 'ajax/ajax_supplier_details.php',
										data: {
										   action: 'hod_tasks',
										   papyear:  ui.item.find("#papyear").val(),
										   papnumb:  ui.item.find("#papnumb").val(),
										   papsrno:  ui.item.find("#papsrno").val(),
										   papcode:  ui.item.find("#papcode").val(),
										   hrsrn:  ui.item.find("#hrsrn").val(),
										   flwsrno:  ui.item.find("#flwsrno").val(),
										   remarks:  ui.item.find("#remarks").val(),
										    apphstat:  ui.item.find("#apphstat").val(),
										   hstat:  hstat
										},
										success: function( data ) {
											
											document.getElementById('editoption-'+ui.item.find("#requestid").val()).style.display = 'none';
										},
										error: function (textStatus, errorThrown) {
											//DO NOTHINIG
										}
									});
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).style.display = 'none';
									document.getElementById('remarks-'+ui.item.find("#requestid").val()).innerHTML = 'Comment : '+ui.item.find("#remarks").val();
									
									
								}
								
								//ui.item.find(".task-footer").append('<div class="pull-right"><span class="fa fa-play"></span> 00:00</div>');
							}
							
							if(this.id == "gm_seniorgm_tasks"){
								
								if(ui.item.find("#classid").val()=='approvalwaiting'){
									ui.sender.sortable("cancel");
								}
								
								
								var hstat='F';
								
								if(ui.item.hasClass("internal")){
									 if(ui.item.find("#intverify_empcode").val()!=2444){
										ui.sender.sortable("cancel");
										return false; 
									 }else{
										var hstat='V'; 
									}
								}
								
								if(ui.item.find("#remarks").val()==''){
									ui.sender.sortable("cancel");
									alert("Please enter Remarks");
									return false;
								}
								
								var apphstat =2;
								
								if(apphstat<ui.item.find("#apphstat").val()){
									var hstat='V';
								}
								
								if(ui.item.find("#classid").val()!='approvalwaiting'){
									ui.item.find("#classid").val("approvalwaiting");
									$.ajax({
										url : 'ajax/ajax_supplier_details.php',
										data: {
										   action: 'gm_seniorgm',
										   papyear:  ui.item.find("#papyear").val(),
										   papnumb:  ui.item.find("#papnumb").val(),
										   papsrno:  ui.item.find("#papsrno").val(),
										   papcode:  ui.item.find("#papcode").val(),
										   hrsrn:  ui.item.find("#hrsrn").val(),
										   flwsrno:  ui.item.find("#flwsrno").val(),
										   remarks:  ui.item.find("#remarks").val(),
										    apphstat:  ui.item.find("#apphstat").val(),
										   hstat:  hstat
										},
										success: function( data ) {
											document.getElementById('editoption-'+ui.item.find("#requestid").val()).style.display = 'none';
										},
										error: function (textStatus, errorThrown) {
											//DO NOTHINIG
										}
									});
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).style.display = 'none';
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).readOnly = true;
									//$('.Remarks-'+ui.item.find("#requestid").val()).attr('readonly', true);
									document.getElementById('remarks-'+ui.item.find("#requestid").val()).innerHTML = 'Comment : '+ui.item.find("#remarks").val();
									
								
								}
								//ui.item.find(".task-footer").append('<div class="pull-right"><span class="fa fa-play"></span> 00:00</div>');
							}
							
							
							if(this.id == "sksir_tasks"){
								if(ui.item.hasClass("hod-normal")){
									ui.sender.sortable("cancel");
									return false;
								}
								var hstat='F';
								
								if(ui.item.hasClass("internal")){
									if(ui.item.find("#intverify_empcode").val()!=3){
										ui.sender.sortable("cancel");
										return false; 
									}else{
										var hstat='V'; 
									}
								}
								
								if(ui.item.find("#remarks").val()==''){
									ui.sender.sortable("cancel");
									alert("Please enter Remarks");
									return false;
								}
								
								var apphstat =3;
								if(apphstat<ui.item.find("#apphstat").val()){
									var hstat='V';
								} 
								if(ui.item.find("#classid").val()!='approvalwaiting'){
									ui.item.find("#classid").val("approvalwaiting");
									$.ajax({
										url : 'ajax/ajax_supplier_details.php',
										data: {
										   action: 'md_sk',
										   papyear:  ui.item.find("#papyear").val(),
										   papnumb:  ui.item.find("#papnumb").val(),
										   papsrno:  ui.item.find("#papsrno").val(),
										   papcode:  ui.item.find("#papcode").val(),
										   hrsrn:  ui.item.find("#hrsrn").val(),
										   flwsrno:  ui.item.find("#flwsrno").val(),
										   remarks:  ui.item.find("#remarks").val(),
										    apphstat:  ui.item.find("#apphstat").val(),
										   hstat:  hstat
										},
										success: function( data ) {
											document.getElementById('editoption-'+ui.item.find("#requestid").val()).style.display = 'none';
										},
										error: function (textStatus, errorThrown) {
											//DO NOTHINIG
										}
									});
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).style.display = 'none';
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).readOnly = true;
									//$('.Remarks-'+ui.item.find("#requestid").val()).attr('readonly', true);
									document.getElementById('remarks-'+ui.item.find("#requestid").val()).innerHTML = 'Comment : '+ui.item.find("#remarks").val();
								}
								//ui.item.addClass("task-complete").find(".task-footer > .pull-right").remove();
							}
							
							
							if(this.id == "kssir_tasks"){
								if(ui.item.hasClass("hod-normal")){
									ui.sender.sortable("cancel");
									return false;
								}
								var hstat='F';
								if(ui.item.hasClass("internal")){
									if(ui.item.find("#intverify_empcode").val()!=1){
										ui.sender.sortable("cancel");
										return false; 
									}else{
										var hstat='V'; 
									}
								}
								
								
								if(ui.item.find("#remarks").val()==''){
									ui.sender.sortable("cancel");
									alert("Please enter Remarks");
									return false;
								}
								
								var apphstat =4;
								if(apphstat<ui.item.find("#apphstat").val()){
									var hstat='V';
								}

								if(ui.item.find("#classid").val()!='approvalwaiting'){
									ui.item.find("#classid").val("approvalwaiting");
										$.ajax({
										url : 'ajax/ajax_supplier_details.php',
										data: {
										   action: 'md_ks',
										   papyear:  ui.item.find("#papyear").val(),
										   papnumb:  ui.item.find("#papnumb").val(),
										   papsrno:  ui.item.find("#papsrno").val(),
										   papcode:  ui.item.find("#papcode").val(),
										   hrsrn:  ui.item.find("#hrsrn").val(),
										   flwsrno:  ui.item.find("#flwsrno").val(),
										   remarks:  ui.item.find("#remarks").val(),
										   apphstat:  ui.item.find("#apphstat").val(),
										   hstat:  hstat
										},
										success: function( data ) {
											document.getElementById('editoption-'+ui.item.find("#requestid").val()).style.display = 'none';
										},
										error: function (textStatus, errorThrown) {
											//DO NOTHINIG
										}
									});
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).style.display = 'none';
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).readOnly = true;
									//$('.Remarks-'+ui.item.find("#requestid").val()).attr('readonly', true);
									document.getElementById('remarks-'+ui.item.find("#requestid").val()).innerHTML = 'Comment : '+ui.item.find("#remarks").val();
								}
								//ui.item.addClass("task-complete").find(".task-footer > .pull-right").remove();
							}
							
							if(this.id == "it_tasks"){
								
								if(ui.item.hasClass("hod-normal")){
									ui.sender.sortable("cancel");
									return false;
								}
								
								var hstat='F';
								if(ui.item.hasClass("internal")){
								
									if(ui.item.find("#intverify_empcode").val()!=5077){
										ui.sender.sortable("cancel");
										return false; 
									}else{
										var hstat='V'; 
									}
								
								}
								
								
								
								if(ui.item.find("#remarks").val()==''){
									ui.sender.sortable("cancel");
									alert("Please enter Remarks");
									return false;
								}
								
								var apphstat =5;
								if(apphstat<ui.item.find("#apphstat").val()){
									var hstat='V';
								} 
								
								
								
								if(ui.item.find("#classid").val()!='approvalwaiting'){
									ui.item.find("#classid").val("approvalwaiting");
									$.ajax({
										url : 'ajax/ajax_supplier_details.php',
										data: {
										   action: 'it_execute',
										   papyear:  ui.item.find("#papyear").val(),
										   papnumb:  ui.item.find("#papnumb").val(),
										   papsrno:  ui.item.find("#papsrno").val(),
										   papcode:  ui.item.find("#papcode").val(),
										   hrsrn:  ui.item.find("#hrsrn").val(),
										   flwsrno:  ui.item.find("#flwsrno").val(),
										   remarks:  ui.item.find("#remarks").val(),
										   apphstat:  ui.item.find("#apphstat").val(),
										   hstat:  hstat
										},
										success: function( data ) {
											document.getElementById('editoption-'+ui.item.find("#requestid").val()).style.display = 'none';
										},
										error: function (textStatus, errorThrown) {
											//DO NOTHINIG
										}
									});
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).style.display = 'none';
									//document.getElementById('remarks-'+ui.item.find("#requestid").val()).readOnly = true;
									//$('.Remarks-'+ui.item.find("#requestid").val()).attr('readonly', true);
									document.getElementById('remarks-'+ui.item.find("#requestid").val()).innerHTML = 'Comment : '+ui.item.find("#remarks").val();
								}
								
								
								
								
								
								//ui.item.addClass("task-complete").find(".task-footer > .pull-right").remove();
							}
											
							page_content_onresize();
						}
						
					}).disableSelection();
					
				}();
				
			});
			
		function validateDate(valId,valDate){
			var EffectiveDate = valDate;
			var Today = new Date();
			if(new Date(EffectiveDate) < Today.setDate(Today.getDate() - 1))
			{
				$('#'+valId).val(null);
				$('#'+valId).focus();
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Due Date not less than Today date!!";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				return false;
			}
		}
		
		function validatePrate(valPrate,hidPrate,i){
			var percentage = parseFloat(hidPrate*5/100,0);
			var MinRate = parseFloat(hidPrate)-parseFloat(percentage);
			var MaxRate = parseFloat(hidPrate)+parseFloat(percentage);
			if(parseFloat(MinRate)>parseFloat(valPrate)){
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Purchase rate not less than Previous rate";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				//$('#new_pur_rate_'+i).val(MinRate);
				$('#new_pur_rate_'+i).val('');
				return false;
			}
			if(parseFloat(MaxRate)<parseFloat(valPrate)){
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Purchase rate not greater than Previous rate";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				//$('#new_pur_rate_'+i).val(MaxRate);
				$('#new_pur_rate_'+i).val('');
				return false;
			}
		}
		
		function validateSrate(valPrate,hidPrate,i){
			var percentage = parseFloat(hidPrate*5/100,0);
			var MinRate = parseFloat(hidPrate)-parseFloat(percentage);
			var MaxRate = parseFloat(hidPrate)+parseFloat(percentage);
			if(parseFloat(MinRate)>parseFloat(valPrate)){
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Sale rate not less than Previous rate";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				//$('#new_pur_rate_'+i).val(MinRate);
				$('#new_sale_rate_'+i).val('');
				return false;
			}
			if(parseFloat(MaxRate)<parseFloat(valPrate)){
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Salre rate not greater than Previous rate";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				//$('#new_pur_rate_'+i).val(MaxRate);
				$('#new_sale_rate_'+i).val('');
				return false;
			}
		}
		
		function validatePieceless(valPieceless,hidPrate,i){
			var percentage = parseFloat(hidPrate*25/100,0);
			//console.log(percentage);
			if(parseFloat(percentage)<parseFloat(valPieceless)){
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Pieceless not greater than Percentage Purrate";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				$('#new_porpcls_'+i).val(parseInt(percentage));
				return false;
			}
		}	
			
		function validSupplier(supVal){
				var spl = supVal.split(" - ");
				if(spl[1]==undefined){
						$('#txt_newsupcode').val('');
				}
		}
		
		function validPrdcode(prdId,prdVal){
				var spl = prdVal.split(" - ");
				if(spl[1]==undefined){
						$('#'+prdId).val('');
				}
		}
		
		function ValidateForm(){
			
			var emptystatus = true;
			var supcodestatus = false;
			
			if($('#txt_newsupcode').val()!=''){
				emptystatus = false;
				supcodestatus = true;
				//$("#submit_action").val(1);
				//$("#frm_po_detail").submit();
				//return true;
				var formElement = document.getElementById("frm_po_detail");
				var formData = new FormData(formElement);
				$.ajax({
					type: "POST",
					data: formData,
					url:  "ajax/ajax_supplier_details.php?action=po_edit_update",
					contentType: false,
					processData: false,
					success: function(data){
						if(data == 1) {
							//$("#submit_action").val(1);
							//$( "#frm_po_detail" ).submit();
							//return true;
							var ALERT_TITLE = "Message";
							var ALERTMSG = "PO entry updated success!!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							return false;
						}else if(data==0){
							var ALERT_TITLE = "Message";
							var ALERTMSG = "Insert Failed. Kindly try again!!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							return false;
						}else{
							$('#modal-default').modal('toggle');
							var ALERT_TITLE = "Message";
							var ALERTMSG = "PO entry updated success!!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							return false;
							
						}
						
						
					},
					error: function(){}
				});
				
				window.location.href="http://www.tcsportal.com/approval-desk/purchase_po_appboard.php";
				
			}
		  
			if($('#txt_newsupcode').val()!='' || $('#new_prdcode_1').val()!='' || $('#new_pur_rate_1').val()!='' || $('#new_sale_rate_1').val()!='' || $('#new_reg_discount_1').val()!='' || $('#new_spl_discount_1').val()!='' || $('#new_porpcls_1').val()!=''  ){
				
				if(supcodestatus==false){
				emptystatus = false;
				//$("#submit_action").val(1);
				//$( "#frm_po_detail" ).submit();
				//return true;
				var formElement = document.getElementById("frm_po_detail");
				var formData = new FormData(formElement);
				$.ajax({
					type: "POST",
					data: formData,
					url:  "ajax/ajax_supplier_details.php?action=po_edit_update",
					contentType: false,
					processData: false,
					success: function(data){
						if(data == 1) {
							//$("#submit_action").val(1);
							//$( "#frm_po_detail" ).submit();
							//return true;
							var ALERT_TITLE = "Message";
							var ALERTMSG = "PO entry updated success!!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							return false;
						}else if(data==0){
							var ALERT_TITLE = "Message";
							var ALERTMSG = "Insert Failed. Kindly try again!!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							return false;
						}else{
							$('#modal-default').modal('toggle');
							var ALERT_TITLE = "Message";
							var ALERTMSG = "PO entry updated success!!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							return false;
							
						}
					
						
					},
					error: function(){}
				});
				}
				window.location.href="http://www.tcsportal.com/approval-desk/purchase_po_appboard.php";
			}
	 
		
			if(emptystatus==true){
			var ALERT_TITLE = "Message";
			var ALERTMSG = "Please fill any one proposed fields!!!";
			createCustomAlert(ALERTMSG, ALERT_TITLE);
			return false;
		   }
	  }
	  
	  
	  		/******************** Change Default Alert Box ***********************/
		var ALERT_BUTTON_TEXT = "OK";
		
			function createCustomAlert(txt, title) {
			d = document;

			if(d.getElementById("modalContainer")) return;

			mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
			mObj.id = "modalContainer";
			mObj.style.height = d.documentElement.scrollHeight + "px";
			
			alertObj = mObj.appendChild(d.createElement("div"));
			alertObj.id = "alertBox";
			if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
			alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
			alertObj.style.visiblity="visible";

			h1 = alertObj.appendChild(d.createElement("h1"));
			h1.appendChild(d.createTextNode(title));

			msg = alertObj.appendChild(d.createElement("p"));
			//msg.appendChild(d.createTextNode(txt));
			msg.innerHTML = txt;
			
			btn = alertObj.appendChild(d.createElement("a"));
			btn.id = "closeBtn";
			btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
			btn.href = "#";
			btn.focus();
			btn.onclick = function() { removeCustomAlert();return false; }
			alertObj.style.display = "block";
		}

		function removeCustomAlert() {
			document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
		}
		
			
		</script>
        <!-- END TEMPLATE -->
		<script type="text/javascript">
			$('. ui-sortable').sortable({
				items: '> :not(.nodragorsort)'
			})
			
		</div>
    <!-- END SCRIPTS -->         
    </body>
</html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>