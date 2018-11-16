<?
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect_1.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

if($_REQUEST['action'] == "edit"){ ?>
    <script>window.location="home.php";</script>
<?php exit();
}


if($_REQUEST['rsrid'] == '') {
    $rqsrno = 1;
} else {
    $rqsrno = $_REQUEST['rsrid'];
}
/*$_REQUEST['reqid'] =  352;
$_REQUEST['year']  = '2017-18';
$_REQUEST['creid'] = 1;
$_REQUEST['typeid']= 2;*/

$cur_mon = strtoupper(date('m'));
$cur_mon = 3;

 
$sql_reqid = select_query_json("select * from APPROVAL_REQUEST where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '".$rqsrno."' and ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N'   order by ARQCODE, ARQSRNO, ATYCODE", "Centra", 'TCS'); 

/*$sql_lock = select_query_json("select APPRVAL-USEDVAL as LOCKVAL,EXPSRNO from trandata.approval_budget_planner@tcscentr where aprnumb in '".$sql_reqid[0]['APRNUMB']."' and APPRVAL>usedval  and APPMNTH=".$cur_mon."","Centra","TCS");*/

$sql_lock = select_query_json("select APPRVAL-nvl(USEDVAL,0) as LOCKVAL,EXPSRNO from trandata.approval_budget_planner@tcscentr
 where aprnumb in '".$sql_reqid[0]['APRNUMB']."'   and APPMNTH=".$cur_mon." having  APPRVAL-nvl(USEDVAL,0)>0 group by apprval,usedval,expsrno","Centra","TCS");



$sql_rptmode = select_query_json("select distinct RPTMODE from department_asset where DEPCODE = '".$sql_reqid[0]['DEPCODE']."'","Centra","TCS");
                     
?>
<!DOCTYPE html>

<html lang="en">
<head>        
<!-- META SECTION -->
<title>Budget Planner Entry :: Approval Desk :: <?php echo $site_title; ?></title>             
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- CSS INCLUDE -->        
<link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
<link href="css/jquery-customselect.css" rel="stylesheet" />
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<link href="../approval_desk/css/monthpicker.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="../bootstrap/css/jquery-ui-1.10.3.custom.min.css" />
<!-- multiple file upload -->
<link href="css/jquery.filer.css" rel="stylesheet">
<!-- EOF CSS INCLUDE -->
<style type="text/css">
    .form-horizontal .control-label { padding-top: 0px !important; }
</style>
</head>
<body>
    <div id="load_page" style='display:block;padding:12% 40%;'></div>

    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-body" id="modal-body1"></div>
            </div>
        </div>
    </div>
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
                <li><a href="request_list.php">Approval Desk </a></li>
                <li class="active">Budget Planner Entry</li>
            </ul>
            <!-- END BREADCRUMB -->
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
				<?php
					/*$sql_reqid = select_query_json("select aprnumb,depcode,tarnumb,PURHEAD,REQSTBY,RESPUSR,subcore,brncode from approval_request where aprnumb like '%ADMIN / INFO TECH 4008108 / 08-03-2018 / 8108 / 03:37 PM%' and arqsrno=1","Centra",'TEST');*/
					
					?>
            
                <div class="row">
                    <div class="col-md-12">
                        
                        <form class="form-horizontal" role="form" id='frm_request_entry_1' name='frm_request_entry_1' action='' method='post' enctype="multipart/form-data">
                        <input type="hidden" class="form-control" name='function' id='function' tabindex="1" value='request_entry_1' />
						 <input type="hidden" class="form-control" name='txt_aprnumb' id='txt_aprnumb'   value='<?php echo $sql_reqid[0]['APRNUMB'];  ?>' />
                         <input type="hidden" name="slt_subcore" id="slt_subcore" value="<?=$sql_reqid[0]['SUBCORE'];?>">
                         <input type="hidden" name="slt_branch" id="slt_branch" value="<?=$sql_reqid[0]['BRNCODE'];?>">
                         <input type='hidden' class="form-control hidn_balance" placeholder="Request Value"   name='txtrequest_value' id='txtrequest_value'  value="0">
                         <input type='hidden' class="form-control"       id='ttl_lock' name='ttl_lock'  value="<?=$sql_lock[0]['LOCKVAL']?>">
                         <input type='hidden' name='txt_rptmode' id='txt_rptmode' value='<?=$sql_rptmode[0]['RPTMODE']?>'>
                         <input type='hidden' name='slt_brnch_0' id='slt_brnch_0' value='<?=$sql_reqid[0]['BRNCODE']?>'>
                         
                        <div class="panel panel-default">
                            <div id="result"></div> <!-- Display the Process Status -->
                            <? $view = 0; if( $sql_reqid[0]['ATYCODE'] == 1 or $sql_reqid[0]['ATYCODE'] == 6 or $sql_reqid[0]['ATYCODE'] == 7 ) { $view = 1; } ?>

                            <div class="panel-heading">
								<div class="col-md-12">
                                <h3 class="panel-title"><strong>Approval No 	- <span class="highlight_redtitle"><?=$sql_reqid[0]['APRNUMB']?></span>
                                </strong></h3>
								</div>
								<div style="clear:both"></div>
								 <div class="col-md-4">
								<h3 class="panel-title"><strong>Target No 	- <span class="highlight_redtitle"><?php      echo $sql_reqid[0]['TARNUMB']." - ".$sql_reqid[0]['TARDESC'];
														?></span>
                               </strong></h3>
							    </div>
								<div class="col-md-4">
								<?php 
									$expHead = select_query_json("select distinct expsrno,EXPNAME from department_asset where deleted='N' AND expsrno in (select distinct expsrno from approval_budget_planner where APRNUMB like '".$sql_reqid[0]['APRNUMB']."')", "Centra", 'TCS');
								?>
								<h3 class="panel-title"><strong>Expense Head   - <span class="highlight_redtitle"><?php      echo $expHead[0]['EXPNAME'];
														?></span>
                               </strong></h3>
                               <input type='hidden' name='slt_core_department' id='slt_core_department' value='<?=$expHead[0]['EXPSRNO']?>'>
                               <input type='hidden' name='slt_department_asset' id='slt_department_asset' value='<?=$sql_reqid[0]['DEPCODE']?>'>
                               <input type='hidden' name='slt_targetno' id='slt_targetno' value='<?=$sql_reqid[0]['TARNUMB']?>'>

							   </div>
							  <div class="col-md-4">
								&nbsp;
							  </div>
							</div>
                            <div class="panel-body">
							    <div class="col-md-6">
                                            <!-- <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Details </strong></h3>
											</div> -->
                                            <div class="panel-body">
                                                <!-- Work Initiate Person -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Per Head <span style="color:red">*</span></label>
                                                    <div class="col-md-9 col-xs-12">       
														<select  tabindex='3' required name='txt_phphead' id='txt_phphead' data-toggle="tooltip" data-placement="top" data-original-title="Core Department" class="form-control">
                                                        <?  $sql_project = select_query_json("SELECT purh.PHDCODE,purh.PHDNAME,purhs.nseccode FROM PURHEAD purh,pur_head_section purhs WHERE purh.phdcode=purhs.phdcode and purhs.nseccode>0 and purhs.nseccode='".$sql_reqid[0]['DEPCODE']."' and purh.DELETED='N' ORDER BY purh.PHDNAME", "Centra", 'TCS');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                            <option value='<?=$sql_project[$project_i]['PHDCODE']?>'><?=$sql_project[$project_i]['PHDNAME']?></option>
                                                        <? } ?>
                                                        </select>
													</div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Work Initiate Person -->

                                                <!-- Responsible Person -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Per Group <span style="color:red">*</span></label>
                                                    <div class="col-md-9 col-xs-12">
													
														<select class="form-control"  tabindex='3' required name='txt_phpgroup' id='txt_phpgroup'>
                                                        <?  $sql_project = select_query_json("SELECT PGRCODE,PGRNAME FROM PURGROUP WHERE DELETED='N' ORDER BY PGRCODE", "Centra", 'TCS');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                            <option value='<?=$sql_project[$project_i]['PGRCODE']?>'><?=$sql_project[$project_i]['PGRNAME']?></option>
                                                        <? } ?>
                                                        </select>
													</div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Approval Subject -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Subject <span style="color:red">*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                        
                                                        <select    class="form-control custom-select chosn" onChange="getapproval_listings(this.value)" onblur="call_days()"  tabindex='6' required name='slt_approval_listings' id='slt_approval_listings' data-toggle="tooltip" data-placement="top" data-original-title="Approval Subject">
                                                        <option value=''>Choose Approval Subject</option>
                                                        <?   
                                                $sql_approval_type_mode = select_query_json("select * from approval_master 
                                                    where ATYCODE in (1, 6, 7) and DELETED = 'N' and apmcode not in (83, 657, 624) 
                                                    order by APMNAME Asc", "Centra", 'TCS');
 
                                                            for($approval_type_mode_i = 0; $approval_type_mode_i < count($sql_approval_type_mode); $approval_type_mode_i++) { ?>
                                                                <option value='<?=$sql_approval_type_mode[$approval_type_mode_i]['APMCODE']?>' <? if($sql_reqid[0]['APMCODE'] == $sql_approval_type_mode[$approval_type_mode_i]['APMCODE']) { ?> selected <? } ?>><?=$sql_approval_type_mode[$approval_type_mode_i]['APMNAME']?></option>
                                                        <? } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                              

                                               <div class="tags_clear"></div>

                                               <!-- Budget Mode -->
                                                    <div class="form-group" id='id_budgetmode' >
                                                        <label class="col-md-3 control-label">Budget Mode <span style="color:red">*</span> </label>
                                                        <div class="col-md-9 col-xs-12">        
                                                            <select class="form-control custom-select chosn" tabindex='12' name='slt_budgetmode' id='slt_budgetmode' data-toggle="tooltip" data-placement="top" title="Budget Mode">
                                                                <?  $sql_project = select_query_json("select * from APPROVAL_BUDGET_MODE where DELETED = 'N' order by BUDNAME", "Centra", 'TCS');
                                                                    for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                                        <option value='<?=$sql_project[$project_i]['BUDCODE']?>' <? if($sql_reqid[0]['BUDCODE'] == $sql_project[$project_i]['BUDCODE']) { ?> selected <? } ?>><?=$sql_project[$project_i]['BUDNAME']?></option>
                                                                <? } ?>
                                                                </select>
                                                             
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                    <!-- Budget Mode -->

                                                <!-- Implementation Due Date -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Implementation Due Date <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">    
                                                        <input type="text" tabindex='22' name="impldue_date" id="datepicker_example3" class="form-control" required readonly placeholder='Implementation Due Date' <?=$rdonly;?> autocomplete='off' value='<?echo strtoupper(date("d-M-Y"));?>' style='text-transform:uppercase; ' maxlength='11' title='Implementation Due Date'>
                                                         
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Usage Section <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">    
                                                        <select class="form-control custom-select chosn" tabindex='12' name='slt_usesec' id='slt_usesec' data-toggle="tooltip" data-placement="top" title="Usage Section">
                                                                <?  $sql_ese = select_query_json("select esecode,trim(substr(esename,4))esename from empsection where DELETED = 'N' order by esename", "Centra", 'TCS');
                                                                    foreach ($sql_ese as $ese) {?>
                                                                    <option value="<?=$ese['ESECODE']?>"><?=$ese['ESENAME']?></option>
                                                                <?}?>
                                                                </select>
                                                         
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Implementation Due Date -->
                                            </div>
                                        </div>
                            <div class="col-md-6">
                                        
                                        <div class="tags_clear"></div>
                                        <div class="form-group">                                        
                                            <label class="col-md-3 control-label" style="text-align: left;">Details <span style='color:red'>*</span> : </label>
                                            <div class="tags_clear height10px"></div>
                                            <div class="col-md-12">
                                                <textarea class="form-control"  tabindex='23' rows="10" placeholder="Details" required maxlength='50' name='txtdetails' id='txtdetails' data-toggle="tooltip" data-placement="top" title="Details" style='text-transform:uppercase' onKeyPress="return isQuotes(event)"> </textarea>
                                                <span style='color:#FF0000; font-size:10px;'>NOTE : MAXIMUM 50 CHARACTERS ALLOWED..</span>
                                                 
                                            </div>
                                        </div>
                                
                                 </div>
										
                                <div class="tags_clear"></div>


                        <!-- Supplier Quotation -->
                        <div id='id_supplier' style="padding-left: 20px; text-align: center;">
                        <div class="parts3 fair_border">
                        
                            <!-- Supplier Quotation -->

                             
                             
                            </div>
                            <div class="panel-footer">
                                <a href='approved_approvals.php' class='btn btn-warning pull-right'><i class="fa fa-refresh"></i> Back</a>
                            </div>
                        </div>
						
						<div class="panel-footer">
                                <button type="reset" tabindex="24" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Clear Form" style="padding: 6px 12px;"><i class="fa fa-times"></i> Clear Form</button>
                                <button  onclick=" return submitForm()"   type="submit" name="sbmt_request" id="sbmt_request" tabindex="25" value="submit" class="btn btn-success pull-right" data-toggle="tooltip" data-placement="top" title="Submit" style="padding: 6px 12px;"><i class="fa fa-save"></i> Submit</button>
						 </div>
						
                        </form>
                        
                    </div>
                </div>                    
                
            </div>
            <!-- END PAGE CONTENT WRAPPER -->                                                
        </div>            
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->
    
    <? include "lib/app_footer.php"; ?>
    
<!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>                
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
    <link rel="stylesheet" href="../bootstrap/css/default.css" type="text/css">
    <script src="../bootstrap/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="../approval_desk/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script src="../approval_desk/js/monthpicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <!--<script src="../approval_desk/js/form-validation.js"></script>-->
    <script type="text/javascript" src="../bootstrap/js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="../bootstrap/js/core.js"></script>
    <script src="js/jquery.filer.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/jquery-customselect.js"></script>
 <script type="text/javascript">
    
    $('#datepicker_example3').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });
	 
 
 
	var slt_branch= '<?=$sql_reqid[0]['BRNCODE']?>';
	var slt_approval_listings= '<?=$sql_reqid[0]['APMCODE']?>';
	var deptid= '<?=$sql_reqid[0]['DEPCODE']?>';
	var slt_submission= '<?=$sql_reqid[0]['ATCCODE']?>';
	var target_no='<?=$sql_reqid[0]['TARNUMB']?>';
	var core_deptid='';
	var expensehead='<?php echo $expHead[0]['EXPSRNO']; ?>';
    //var expensehead=8;
 
	
	
	var strURL="ajax/ajax_dynamic_option.php?action=add_edit&expensehead="+expensehead+"&slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings+"&view=budget";
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data1) {
                    if(data1 == 0) {
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Dynamic Approval Listing loading failed. Kindly try again!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                        $('#load_page').hide();
                    } else {

                        // alert(data1);
                        // $.getScript("chart/js/plugin/sample_order_script.js");
                        $("#id_supplier").html(data1);
                        change_readonly();
                        $('#hid_default_lock').val(0);
                        if ( $( "#default_lock" ).length ) {
                            $("#sbmt_request").prop("disabled", true);
                        }

                        var id = 1;
                        $('#fle_supquot_'+id+'_1').filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});
                        $('#txt_prdcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details1.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       depcode: <?php echo $sql_reqid[0]['DEPCODE'];?>,
                                       slt_targetno: <?php echo $sql_reqid[0]['TARNUMB'];?>,
                                       action: 'product'
                                    },
                                    success: function( data ) {
                                        // alert("###"+data+"###");
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

                        $('#txt_subprdcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details1.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       product: $('#txt_prdcode_'+id).val(),
                                       depcode: <?php echo $sql_reqid[0]['DEPCODE'];?>,
                                       slt_targetno: <?php echo $sql_reqid[0]['TARNUMB'];?>,
                                       action: 'sub_product'
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

                        $('#txt_prdspec_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details1.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       //slt_targetno: <?php echo $sql_reqid[0]['TARNUMB'];?>,
                                       action: 'product_specification'
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

                        $('#txt_sltsupcode_'+id+'_1').autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details1.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       slt_core_department: $('#slt_core_department').val(),
                                       slt_targetno: $('#slt_targetno').val(),
                                       action: 'supplier_withcity'
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

                        $('#txt_suppliercode').autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/get_supplier_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       slt_core_department: $('#slt_core_department').val(),
                                       action: 'supplier_details'
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

                        $('#txt_staffcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_employee_details.php',
                                    dataType: "json",
                                    data: {
                                       slt_emp: request.term,
                                       brncode: $('#slt_brnch_0').val(),
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


                        /* if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { // 
                            calculate_sum();
                            $(".ttlsumrequired").attr('required', true);
                        } else {
                            $(".ttlsumrequired").attr('required', false);
                        } */
                        
                        if(slt_submission == 7)
                        {
                            $('#ttl_lock').val(10000000000000);
                        }
                        var ttl_lock = $('#ttl_lock').val();
                        if(ttl_lock != '') {
                            if(ttl_lock == 10000000000000) {
                                $('#budgt_vlu').html('');
                            } else {
                                $('#budgt_vlu').html(' - Budget Value - '+ttl_lock);
                            }
                        }
                        $('#load_page').hide();
                    }
                }
            });
			
			
			function get_prddet()
			{
				var depcode = "<?php echo $sql_reqid[0]['DEPCODE'];?>";
				var slt_targetno = "<?php echo $sql_reqid[0]['TARNUMB'];?>";
				$.ajax({
					url:"ajax/ajax_budget.php?action=sub_prd&depcode="+depcode+"&slt_targetno="+slt_targetno,
					success:function(data)
					{
						$("#myModal1").modal('show');
						$('#modal-body1').html(data);
					}
				});
			}
			
			function find_taxvalue(opt1, opt2) {
				$('#load_page').show();
				var txt_regdis = document.getElementById('txt_prddisc_'+opt1+'_'+opt2).value;
				var txt_prdrate = (document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value);
				if(txt_regdis != "" && txt_regdis != 0)
				{
				var txt_dis = parseFloat(txt_prdrate)/100*parseFloat(txt_regdis);   
				txt_prdrate = parseFloat(txt_prdrate) - parseFloat(txt_dis);
				}
				var txt_prdsgst = document.getElementById('txt_prdsgst_per_'+opt1+'_'+opt2).value;
				var txt_prdcgst = document.getElementById('txt_prdcgst_per_'+opt1+'_'+opt2).value;
				var txt_prdigst = document.getElementById('txt_prdigst_per_'+opt1+'_'+opt2).value;
				//prdcst = Math.round(prdcst).toFixed(2);
				document.getElementById('txt_prdsgst_'+opt1+'_'+opt2).value = roundTo(((txt_prdsgst / 100) * txt_prdrate),4);
				document.getElementById('txt_prdcgst_'+opt1+'_'+opt2).value = roundTo(((txt_prdcgst / 100) * txt_prdrate),4);
				document.getElementById('txt_prdigst_'+opt1+'_'+opt2).value = roundTo(((txt_prdigst / 100) * txt_prdrate),4);
				
				// document.getElementById('txt_hidprdsgst_'+opt1+'_'+opt2).value = ((txt_prdsgst / 100) * txt_prdrate);
				// document.getElementById('txt_hidprdcgst_'+opt1+'_'+opt2).value = ((txt_prdcgst / 100) * txt_prdrate);
				// document.getElementById('txt_hidprdigst_'+opt1+'_'+opt2).value = ((txt_prdigst / 100) * txt_prdrate);
				$('#load_page').hide();
			}

			 function roundTo(n, digits) {
				if (digits === undefined) {
					digits = 0;
				}

				var multiplicator = Math.pow(10, digits);
				n = parseFloat((n * multiplicator).toFixed(11));
				return (Math.round(n) / multiplicator).toFixed(4);
			}
			function calculatenetamount(opt1, opt2){
				$('#load_page').show();
				find_taxvalue(opt1, opt2);
				var txt_prdqty = document.getElementById('txt_prdqty_'+opt1).value;
				if(txt_prdqty==''){
					txt_prdqty = 0;
				}
				if(txt_prdqty == 0) {
					document.getElementById('txt_prdqty_'+opt1).value = 1;
					calculatenetamount(opt1, opt2);
				}

				var txt_prdrate = document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value;
				if(txt_prdrate==''){
					txt_prdrate = 0;
				}
				var txt_prdsgst = document.getElementById('txt_prdsgst_'+opt1+'_'+opt2).value;
				if(txt_prdsgst==''){
					txt_prdsgst = 0;
				}
				var txt_prdcgst = document.getElementById('txt_prdcgst_'+opt1+'_'+opt2).value;
				if(txt_prdcgst==''){
					txt_prdcgst = 0;
				}
				var txt_prdigst = document.getElementById('txt_prdigst_'+opt1+'_'+opt2).value;
				if(txt_prdigst==''){
					txt_prdigst = 0;
				}
				var txt_prddisc = document.getElementById('txt_prddisc_'+opt1+'_'+opt2).value;
				if(txt_prddisc==''){
					txt_prddisc = 0;
				}

				var txt_spldisc = document.getElementById('txt_spldisc_'+opt1+'_'+opt2).value;
				if(txt_spldisc==''){
					txt_spldisc = 0;
				}
				var txt_pieceless = document.getElementById('txt_pieceless_'+opt1+'_'+opt2).value;
				if(txt_pieceless==''){
					txt_pieceless = 0;
				}

				var txt_ad_duration = document.getElementById('txt_ad_duration_'+opt1).value;
				if(txt_ad_duration==''){
					txt_ad_duration = 0;
				}

				var txt_size_length = document.getElementById('txt_size_length_'+opt1).value;
				if(txt_size_length==''){
					txt_size_length = 0;
				}

				var txt_size_width = document.getElementById('txt_size_width_'+opt1).value;
				if(txt_size_width==''){
					txt_size_width = 0;
				}
				
				 
				var ttl_lock = $("#ttl_lock").val();
				var rptmode = $("#txt_rptmode").val();
				var slt_subcore = $("#slt_subcore").val();
				var pcless = 0;
				var spldis = 0;
				var prdqty = 0;
				var prdcst = 0;
				var tot_prddisc = 0; 
				 
                 if(rptmode == 1 || rptmode == 2 || rptmode == 3 || rptmode == 4) { // Non ADVT Exp.
					prdqty = txt_prdqty;
					tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty))/100 * parseFloat(txt_prddisc) ;
					tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty); 
					 prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
				} else if(rptmode == 5 || rptmode == 6) { // ADVT Exp. Ad Flex Exp.
					prdqty = parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width);
					tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width))/100 * parseFloat(txt_prddisc) ;
					tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty); 
					 if(txt_size_length != ""  && txt_size_width != "" ){
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
					}else{
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
					}
				} else if(rptmode == 7) { // ADVT Exp. Ad Play Duration Exp.
					prdqty = parseFloat(txt_prdqty) * parseFloat(txt_ad_duration);
					tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration))/100 * parseFloat(txt_prddisc) ;
					tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty); 
					 if(txt_ad_duration != "")
					{
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);  
					}else{
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);    
					}   
					
				}
				// console.log("@@"+prdqty+"@@"+pcless+"@@"+spldis+"@@"+prdcst+"@@");
				prdcst = Math.round(prdcst).toFixed(2);
				document.getElementById('id_prdnetamount_'+opt1+'_'+opt2).innerHTML = parseFloat(prdcst);
				document.getElementById('hid_prdnetamount_'+opt1+'_'+opt2).value = parseFloat(prdcst);

				if($('#txt_sltsupplier_'+opt1+'_'+opt2).is(":checked")) {

					//console.log("**"+txt_prdrate+"**"+prdcst+"**");
					txt_prdsgst = roundTo(txt_prdsgst,4);
					txt_prdcgst = roundTo(txt_prdcgst,4);
					txt_prdigst = roundTo(txt_prdigst,4);
					$('#id_sltrate_'+opt1).html(txt_prdrate);
					$('#id_sltsgst_'+opt1).html(txt_prdsgst);
					$('#id_sltcgst_'+opt1).html(txt_prdcgst);
					$('#id_sltigst_'+opt1).html(txt_prdigst);
					/*$('#id_sltslds_'+opt1).html(txt_spldisc);
					$('#id_sltpcls_'+opt1).html(txt_pieceless);*/ 
					$('#id_sltdisc_'+opt1).html(txt_prddisc);
					//alert(prdcst);
					$('#id_sltamnt_'+opt1).html(prdcst);

					var requestedvalue=0;
					var y = $('.parts3 .part3').length + 1;
					for(var j=1;j<=y;j++){
						var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
						for(var i=0;i<x.length;i++){
							if(x[i].checked){
								var z = i+1;
								if(document.getElementById('hid_prdnetamount_'+j+'_'+z).value==''){
									document.getElementById('hid_prdnetamount_'+j+'_'+z).value=0;
								}
								requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value)
							}
						}   
					}

					if(parseInt(requestedvalue) <= parseInt(ttl_lock) && parseInt(ttl_lock) > 0) {
						document.getElementById('txtrequest_value').value = requestedvalue;
						document.getElementById('txt_brnvalue_0').value = requestedvalue;
						document.getElementById('hidrequest_value').value = requestedvalue;
						$('.hidn_balance').val(requestedvalue);
						// New Calculation - 22-09-2017 // GA 

						if(document.getElementById('npobudget'))
						{
							document.getElementById('mnt_yr_amt_<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>').value = requestedvalue;
							calculate_sum();
						}

						var requestedvalue=0;
						var y = $('.parts3 .part3').length + 1;
						for(var j=1;j<=y;j++){
							var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
							for(var i=0;i<x.length;i++){
								if(x[i].checked){
									var z = i+1;
									requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value);
									// alert("***"+requestedvalue+"***"+j+"***"+z+"****");
								}
							}   
						}
						document.getElementById('txtrequest_value').value = requestedvalue;
						document.getElementById('txt_brnvalue_0').value = requestedvalue;
						document.getElementById('hidrequest_value').value = requestedvalue;
						
						/*
						
						if(document.getElementById('npobudget'))
						{
							document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value = requestedvalue;
							calculate_sum();
						}
						
						*/

						for(jvi = 1; jvi <= 10; jvi++) {
							// alert("***"+'#hid_prdnetamount_'+opt1+'_'+jvi+"***");
							// console.log("***"+'#hid_prdnetamount_'+opt1+'_'+jvi+"***"+opt1+"***"+opt2+"***"+ttlcnt+"***"+jvi+"***");
							$('#hid_prdnetamount_'+opt1+'_'+jvi).attr('class', 'form-control');
						}
						$('#hid_prdnetamount_'+opt1+'_'+opt2).attr('class', 'form-control ttlcalc');

						var requestedvalue = totcalc('ttlcalc');
						// console.log("###"+requestedvalue+"###");
						document.getElementById('txtrequest_value').value = requestedvalue;
						document.getElementById('txt_brnvalue_0').value = requestedvalue;
						document.getElementById('hidrequest_value').value = requestedvalue;
						
						
						/*if(document.getElementById('npobudget'))
						{
							document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value = requestedvalue;
							calculate_sum();
						}*/
						
						$('.hidn_balance').val(requestedvalue);
						/* console.log(document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value); */
					} else {
						var ALERT_TITLE = "Message";
						var ALERTMSG = "Maximum "+ttl_lock+" value only allowed here..";
						createCustomAlert(ALERTMSG, ALERT_TITLE);
						document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value = 0;
						calculatenetamount(opt1, opt2);
					}
				}
				$('#load_page').hide();
			}

            function isQuotes(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if (charCode == 39 || charCode == 34) {
            return false;
        }
        return true;
    }

			function totcalc(clsname){
				var dirq = 0;
				var list = document.getElementsByClassName(clsname);
				var values = [];
				if(list.length > 0) {
					for(var i = 0; i < list.length; ++i) {
						values.push(parseFloat(list[i].value));
					}
					dirq = values.reduce(function(previousValue, currentValue, index, array){
						return previousValue + currentValue;
					});
					// alert(clsname+"+++++++"+dirq);
				} else {
					dirq = 0;
				}
				return dirq;
			}

			function calculateqtyamount(gid){
				var x = document.getElementsByName('txt_sltsupcode['+gid+'][]');
				for(var i=1;i<=x.length;i++){
					calculatenetamount(gid,i);
				}
			}
			
			// validate the product textbox
			function validate_prdempty(iv) {
				$('#load_page').show();
				var prdcode = $("#txt_prdcode_"+iv).val();
				var strURL="ajax/ajax_validate.php?action=product&validate_code="+prdcode;
				$.ajax({
					type: "POST",
					url: strURL,
					success: function(data1) {
						if(data1 == 0) {
							var ALERT_TITLE = "Message";
							var ALERTMSG = "No Product Available. Kindly Contact Admin Master Team!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							$("#txt_prdcode_"+iv).val('');
							// $("#txt_prdcode_"+iv).focus();
						} else if(data1 == 2) {
							var ALERT_TITLE = "Message";
							var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							$("#txt_prdcode_"+iv).val('');
						}
					}
				});
				// fix_tax(iv);
				$('#load_page').hide();
			}
			// validate the product textbox
			
		function getrequestvalue(opt1, opt2){
			$('#load_page').show();
			calculatenetamount(opt1, opt2);

			var requestedvalue=0;
			var y = $('.parts3 .part3').length + 1;
			for(var j=1;j<=y;j++){
				var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
				for(var i=0;i<x.length;i++){
					if(x[i].checked){
						var z = i+1;
						requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value);
                    // alert("***"+requestedvalue+"***"+j+"***"+z+"****");
					}
				}   
			}
			document.getElementById('txtrequest_value').value = requestedvalue;
			document.getElementById('txt_brnvalue_0').value = requestedvalue;
			document.getElementById('hidrequest_value').value = requestedvalue;
			$('#load_page').hide();
		}	

    function getrequestvalues(iv, jv, ttlcnt){
        $('#load_page').show();
        calculatenetamount(iv, jv);

        for(jvi = 1; jvi <= ttlcnt; jvi++) {
            // alert("***"+'#hid_prdnetamount_'+iv+'_'+jvi+"***");
            // console.log("***"+'#hid_prdnetamount_'+iv+'_'+jvi+"***"+iv+"***"+jv+"***"+ttlcnt+"***"+jvi+"***");
            $('#hid_prdnetamount_'+iv+'_'+jvi).attr('class', 'form-control');
        }
        $('#hid_prdnetamount_'+iv+'_'+jv).attr('class', 'form-control ttlcalc');

        var requestedvalue = totcalc('ttlcalc');
        // console.log("###"+requestedvalue+"###");
        document.getElementById('txtrequest_value').value = requestedvalue;
        document.getElementById('txt_brnvalue_0').value = requestedvalue;
        document.getElementById('hidrequest_value').value = requestedvalue;
        $('.hidn_balance').val(requestedvalue);
        $('#load_page').hide();
    }

    // validate the product textbox
    function validate_prdempty(iv) {
        $('#load_page').show();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=product&validate_code="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Product Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdcode_"+iv).val('');
                    // $("#txt_prdcode_"+iv).focus();
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdcode_"+iv).val('');
                }
            }
        });
        // fix_tax(iv);
        $('#load_page').hide();
    }
    // validate the product textbox

    // validate the sub product textbox
    function validate_subprdempty(iv) {
        $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=sub_product&validate_code="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // alert("***"+data1+"***");
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Sub Product Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_subprdcode_"+iv).val('');
                    // $("#txt_subprdcode_"+iv).focus(); 
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_subprdcode_"+iv).val('');
                }
            }
        });
        find_unitcode(iv);
        // fix_tax(iv);
        $('#load_page').hide();
    }
    // validate the sub product textbox

    // find the unit code from sub product textbox
    function find_unitcode(iv) {
        $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=find_unitcode&validate_code="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Unit code Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_unitname_"+iv).val('');
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_unitname_"+iv).val('');
                } else {
                    var prd = data1.split(" - ");
                    $("#txt_unitname_"+iv).val(prd[1]);
                    $("#txt_unitcode_"+iv).val(prd[0]);
                }
            }
        });
        // find_hsncode(iv);
        $('#load_page').hide();
    }
    // find the unit code from sub product textbox

    // find the HSN Code based on the chosen product & sub product based
    /* function find_hsncode(iv) {
        var prdcode = $("#txt_prdcode_"+iv).val();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var strURL="ajax/ajax_validate_1.php?action=find_hsncode&prdcode="+prdcode+"&sub_prdcode="+sub_prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == 0) {
                }
            }
        });
    } */
    // find the HSN Code based on the chosen product & sub product based

    // validate the product specifiction textbox 
    function validate_prdspcempty(iv) {
        /* var spc_prdcode = $("#txt_prdspec_"+iv).val();
        var strURL="ajax/ajax_validate_1.php?action=prod_spec&validate_code="+spc_prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Product Specifiction Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdspec_"+iv).val('');
                    // $("#txt_prdspec_"+iv).focus(); 
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdcode_"+iv).val('');
                }
            }
        }); */
    }
    // validate the product specifiction textbox

    // validate the supplier textbox
    function validate_supprdempty(iv, jv) {
        $('#load_page').show();
        var slt_core_department = $("#slt_core_department").val();
        var sup_prdcode = $("#txt_sltsupcode_"+iv+"_"+jv).val();
        var slt_brncode = $("#slt_brnch_0").val();
        var strURL="ajax/ajax_validate.php?action=supplier&validate_code="+sup_prdcode+"&slt_core_department="+slt_core_department+"&brncode="+slt_brncode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                var data = data1.split("~");
                if(data[0] == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Supplier Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_sltsupcode_"+iv+"_"+jv).val('');
                    // $("#txt_sltsupcode_"+iv+"_"+jv).focus(); 
                } 
                document.getElementById('state'+iv+"_"+jv).value = data[1];
                fix_tax(iv, jv);
            }
        });
        $('#load_page').hide();
    }
    // validate the supplier textbox

    // assign tax based on the chosen product / sub product
    function fix_tax(iv, jv) {
        $('#load_page').show();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var ostate = $('#state'+iv+'_'+jv).val();
        var strURL="ajax/ajax_validate.php?action=fix_tax&prdcode="+prdcode+"&sub_prdcode="+sub_prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == '') {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Tax details Available. Kindly Contact MIS team to fix the HSN CODE!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                } else {
                    var reslt = data1.split("-");
                    if(ostate == 1)
                    {
                        $('#txt_prdsgst_per_'+iv+'_'+jv).val(reslt[0]);
                        $('#txt_prdcgst_per_'+iv+'_'+jv).val(reslt[1]);
                        $('#txt_prdigst_per_'+iv+'_'+jv).val('');
                    }else{
                        $('#txt_prdsgst_per_'+iv+'_'+jv).val('');
                        $('#txt_prdcgst_per_'+iv+'_'+jv).val('');
                        $('#txt_prdigst_per_'+iv+'_'+jv).val(reslt[2]);
                    }   
                }
                $('#load_page').hide();
            }
        });
    }
    // assign tax based on the chosen product / sub product

    function call_product_innergrid(gridid) {
        $('#load_page').show();
        // $("#addbtn").click(function () {
            // alert("CAME");
            if( ($('.parts3 .part3').length+1) > 99) {
                alert("Maximum 100 Products allowed.");
            } else {
                var slt_subcore = $('#slt_subcore').val();
                if(slt_subcore == 41) {
                    var rdnly = "";
                } else {
                    var rdnly = "readonly";
                }
                $('[data-toggle="tooltip"]').tooltip();
                var id = ($('.parts3 .part3').length + 2).toString();
                $('#partint3').val(id);
                $('.parts3').append('<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
                                        '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div class="fg-line">&nbsp;'+id+'</div>'+
                                        '</div>'+
                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<div style="width: 49%; float: left;"><input type="text" name="txt_prdcode[]" id="txt_prdcode_'+id+'" required="required" maxlength="100" placeholder="Product" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Product" class="form-control supquot find_prdcode" onBlur="validate_prdempty('+id+')" style=" text-transform: uppercase; padding: 0px;height: 25px;"></div><div style="">'+
                                            '</div>'+

                                            '<div style="width: 49%; float: left;margin-left: 2px;">'+
                                                '<input type="text" name="txt_subprdcode[]" id="txt_subprdcode_'+id+'" maxlength="100" placeholder="Sub Product" data-toggle="tooltip" data-placement="top" title="Sub Product" onKeyPress="enable_product();" class="form-control supquot find_subprdcode" onBlur="validate_subprdempty('+id+')" style=" text-transform: uppercase;height: 25px;">'+

                                                '<input type="hidden" readonly="readonly" name="txt_unitname[]" id="txt_unitname_'+id+'" required="required" maxlength="3" placeholder="Unit" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Unit" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                                '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_unitcode[]" id="txt_unitcode_'+id+'" required="required" maxlength="3" placeholder="Unit Code" data-toggle="tooltip" data-placement="top" title="Unit Code" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both; height: 1px;"></div>'+

                                            '<div>'+
                                                '<input type="text" name="txt_prdspec[]" id="txt_prdspec_'+id+'" required="required" maxlength="100" placeholder="Product Specification" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Product Specification" class="form-control supquot find_prdspec" onBlur="validate_prdspcempty('+id+')" style=" text-transform: uppercase;height: 25px;">'+
                                            '</div><div style="clear: both;"></div>'+
                                            '<div>'+
                                            '<input type="file" name="fle_prdimage[]" id="fle_prdimage_'+id+'" data-toggle="tooltip" onchange="ValidateSingleInput(this);" accept="image/jpg,image/jpeg,image/png,image/jpg" class="form-control supquot" data-placement="left" data-toggle="tooltip" data-placement="top" title="Product Image" placeholder="Product Image" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : ONLY JPG, PNG IMAGES ALLOWED.</span>'+
                                            
                                        '</div>'+
                                        '<div style="clear: both;"></div>'+
                                        '</div>'+

                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<div style="width: 49%; float: left;"><input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_ad_duration[]" id="txt_ad_duration_'+id+'" onblur="calculateqtyamount('+id+')" maxlength="3" placeholder="Ad. Duration" data-toggle="tooltip" data-placement="top" title="Ad. Duration" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div>'+
                                            '<div style="width: 49%; float: left; margin-left: 2px;">'+
                                                '<input type="text" name="txt_print_location[]" id="txt_print_location_'+id+'" maxlength="25" placeholder="Ad. Print Location" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Ad. Print Location" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both;"></div>'+

                                            '<div style="width: 49%; float: left;">'+
                                                '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_length[]" id="txt_size_length_'+id+'" onblur="calculateqtyamount('+id+')" maxlength="7" placeholder="Size Length" data-toggle="tooltip" data-placement="top" title="Size Length" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div>'+
                                            '<div style="width: 49%; float: left; margin-left: 2px;">'+
                                                '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_width[]" id="txt_size_width_'+id+'" onblur="calculateqtyamount('+id+')" maxlength="7" placeholder="Size width" data-toggle="tooltip" data-placement="top" title="Size width" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both;"></div><input type="hidden" readonly="readonly" name="slt_usage_section[]" id="slt_usage_section_'+id+'" required="required" maxlength="3" placeholder="Usage Section" data-toggle="tooltip" data-placement="top" title="Usage Section" onKeyPress="enable_product();" class="form-control supquot custom-select chosn" style=" text-transform: uppercase;height: 25px;" >'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="enable_product(); return numwodot(event)" name="txt_prdqty[]" id="txt_prdqty_'+id+'" required="required" maxlength="6" placeholder="Qty" onblur="calculateqtyamount('+id+')" data-toggle="tooltip" data-placement="top" title="Qty" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px;" id="id_sltrate_'+id+'">'+
                                            ' -'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div style="float: left; width: 50%; text-align: right;">SGST : </div><div style="float: left; width: 50%;" id="id_sltsgst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">CGST : </div><div style="float: left; width: 50%;" id="id_sltcgst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;IGST : </div><div style="float: left; width: 50%;" id="id_sltigst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                        '</div>'+
                                        // discount hide 
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                        /*  '<div style="float: left; width: 50%; text-align: right;">SPL.DIS. : </div><div style="float: left; width: 50%;" id="id_sltslds_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">PCELES. : </div><div style="float: left; width: 50%;" id="id_sltpcls_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+*/
                                            '<div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;DISC.% : </div><div style="float: left; width: 50%;" id="id_sltdisc_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px;" id="id_sltamnt_'+id+'">'+
                                            ' -'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row" style="margin-right: -5px; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">'+
                                        '<div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 0px; border-top-left-radius:5px;"></div>'+
                                        '<!-- Quotation -->'+
                                        '<div class="col-sm-10 colheight" style="padding: 0px; border-top-left-radius:5px;">'+
                                            '<div class="fair_border" style="padding-left: 0px;">'+
                                                '<div class="row" style="margin-right: -10px; background-color: #666666; color:#FFFFFF; display: flex; font-weight: bold;">'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">#</div>'+
                                                    '<div class="col-sm-3 colheight" style="padding: 0px;">Supplier Details</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Delivery Duration</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Per Piece Rate / Adv. Amount</div>'+
                                                    // '<div class="col-sm-1 colheight" style="padding: 0px;">Rate</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Tax Val.</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Discount % </div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Total Amount</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Quotation PDF</div>'+
                                                    '<div class="col-sm-2 colheight" style="padding: 0px;">Remarks</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<!-- Quotation -->'+
                                        '</div>'+
                                        '<div class="col-sm-1 colheight" style="padding: 0px; border: 1px solid #FFFFFF !important; background-color: #FFFFFF; border-top-left-radius:5px;"></div>'+
                                    '</div> '+

                                    '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
                                        '<div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>'+
                                        '<div class="col-sm-10 colheight" style="padding-left: 0px;">'+
                                            '<!-- Quotation -->'+
                                            '<div class="parts3_'+id+' fair_border">'+
                                                '<div class="row" style="margin-right: -10px; display: flex;">'+
                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<div class="fg-line">'+
                                                            '<input type="hidden" name="partint3_'+id+'" id="partint3_'+id+'" value="1"><input type="hidden" name="txt_prdsgst_per['+id+'][]" id="txt_prdsgst_per_'+id+'_1" value=""><input type="hidden" name="txt_prdcgst_per['+id+'][]" id="txt_prdcgst_per_'+id+'_1" value=""><input type="hidden" name="txt_prdigst_per['+id+'][]" id="txt_prdigst_per_'+id+'_1" value="">'+
                                                            '<button class="btn btn-success btn-add3" id="addbtn_'+id+'" type="button" title="Add Suppliers" onclick="call_innergrid('+id+')" style="margin-right: 4px;padding:2px;"><span class="glyphicon glyphicon-plus"></span></button>'+
                                                            '<button id="removebtn_'+id+'" style="padding:2px;" class="btn btn-remove btn-danger" type="button" title="Delete Suppliers" onclick="call_innergrid_remove('+id+')"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;<input type="radio" checked="checked" name="txt_sltsupplier['+id+'][]" id="txt_sltsupplier_'+id+'_1" value="1" onclick="getrequestvalue('+id+', 1)" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;1'+
                                                        '</div>'+
                                                    '</div>'+

                                                    '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" name="txt_sltsupcode['+id+'][]" id="txt_sltsupcode_'+id+'_1" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" onKeyPress="enable_product();" data-placement="top" title="Supplier" class="form-control supquot find_supcode" onBlur="validate_supprdempty('+id+', 1)" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" name="state['+id+'][]" id="state'+id+'_1" value="">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_delivery_duration['+id+'][]" id="txt_delivery_duration_'+id+'_1" required="required" maxlength="4" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdrate['+id+'][]" id="txt_prdrate_'+id+'_1" onblur="calculatenetamount('+id+',1)" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        'Adv.Amount Val.:'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount['+id+'][]" id="txt_advance_amount_'+id+'_1" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdsgst['+id+'][]" id="txt_prdsgst_'+id+'_1" onblur="calculatenetamount('+id+',1)" required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdcgst['+id+'][]" id="txt_prdcgst_'+id+'_1" onblur="calculatenetamount('+id+',1)" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdigst['+id+'][]" id="txt_prdigst_'+id+'_1" onblur="calculatenetamount('+id+',1)" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;"> '+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_spldisc['+id+'][]" id="txt_spldisc_'+id+'_1" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" onblur="calculatenetamount('+id+',1)" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_pieceless['+id+'][]" id="txt_pieceless_'+id+'_1" required="required" maxlength="5" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" onblur="calculatenetamount('+id+',1)" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prddisc['+id+'][]" id="txt_prddisc_'+id+'_1" onblur="calculatenetamount('+id+',1)" required="required" maxlength="10" placeholder="Discount % " data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="hid_prdnetamount['+id+'][]" id="hid_prdnetamount_'+id+'_1" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_'+id+'_1">0</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="file" name="fle_supquot['+id+'][]" id="fle_supquot_'+id+'_1" onchange="ValidateSingleInput(this);" accept=".pdf" data-toggle="tooltip" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>'+
                                                    '</div>'+

                                                    '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
                                                        '<textarea onKeyPress="enable_product();" name="suprmrk['+id+'][]" id="suprmrk_'+id+'_1" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('+id+',1)" class="form-control" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<!-- Quotation -->'+

                                        '</div>'+
                                        '<div class="col-sm-1 colheight" style=" border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>'+
                                    '</div>'+
                                    '</div><script>$("#fle_supquot_'+id+'_1").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
                                    );
            }

            $('#txt_prdcode_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           depcode: $('#slt_department_asset').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'product'
                        },
                        success: function( data ) {
                            // alert("###"+data+"###");
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

            $('#txt_subprdcode_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           product: $('#txt_prdcode_'+id).val(),
                           depcode: $('#slt_department_asset').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'sub_product'
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

            $('#txt_prdspec_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'product_specification'
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

            $('#txt_sltsupcode_'+id+'_1').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_core_department: $('#slt_core_department').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'supplier_withcity'
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
        // });
        $('#load_page').hide();
    }
	
	function numwodot(evt)
    {
       if ((evt.which < 48 || evt.which > 57)) {
            evt.preventDefault();
        }
    }
	
	
	
    function call_product_innergrid_remove(gridid) {
        // $("#removebtn").click(function () {
           if ($('.parts3 .part3').length == 0) {
              alert("No more row to remove.");
           }
           var id = ($('.parts3 .part3').length - 1).toString();
           $('#partint3').val(id);
           $(".parts3 .part3:last").remove();
        // });
    }

    function call_innergrid(gridid) {
        $('#load_page').show();
        // alert("**"+gridid);
        // $("#addbtn_"+gridid).click(function () {
            // alert("!!"+gridid);
            if( ($('.parts3_'+gridid+' .part3_'+gridid).length+1) > 99) {
                alert("Maximum 100 Suppliers allowed.");
            } else {
                $('[data-toggle="tooltip"]').tooltip();
                var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length + 2).toString();
                $('#partint3_'+gridid).val(gid);
                // alert("@@"+gid);
                $('.parts3_'+gridid).append('<div class="row part3_'+gridid+'" style="margin-right: -10px; display: flex;">'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div class="fg-line"><input type="hidden" name="txt_prdsgst_per['+gridid+'][]" id="txt_prdsgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdcgst_per['+gridid+'][]" id="txt_prdcgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdigst_per['+gridid+'][]" id="txt_prdigst_per_'+gridid+'_'+gid+'" value="">'+
                                                '<input type="radio" onclick="getrequestvalue('+gridid+', '+gid+')" name="txt_sltsupplier['+gridid+'][]" id="txt_sltsupplier_'+gridid+'_'+gid+'" value="'+gid+'" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;'+gid+''+
                                            '</div>'+
                                        '</div>'+

                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" name="txt_sltsupcode['+gridid+'][]" id="txt_sltsupcode_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" data-placement="top" title="Supplier" class="form-control supquot find_supcode" onBlur="validate_supprdempty('+gridid+', '+gid+')" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" name="state['+gridid+'][]" id="state'+gridid+'_'+gid+'" value="">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" name="txt_delivery_duration['+gridid+'][]" id="txt_delivery_duration_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdrate['+gridid+'][]" id="txt_prdrate_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            'Adv.Amount Val.:'+
                                            '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount['+gridid+'][]" id="txt_advance_amount_'+gridid+'_'+gid+'" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" readonly="true" name="txt_prdsgst['+gridid+'][]" id="txt_prdsgst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')"  required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" readonly="true" name="txt_prdcgst['+gridid+'][]" id="txt_prdcgst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" readonly="true" name="txt_prdigst['+gridid+'][]" id="txt_prdigst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+


                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="txt_spldisc['+gridid+'][]" id="txt_spldisc_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="txt_pieceless['+gridid+'][]" id="txt_pieceless_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="6" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prddisc['+gridid+'][]" id="txt_prddisc_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="10" placeholder="Discount %" data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="hid_prdnetamount['+gridid+'][]" id="hid_prdnetamount_'+gridid+'_'+gid+'" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_'+gridid+'_'+gid+'">0</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="file" name="fle_supquot['+gridid+'][]" id="fle_supquot_'+gridid+'_'+gid+'" onchange="ValidateSingleInput(this);" accept=".pdf" data-toggle="tooltip" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>'+
                                        '</div>'+

                                        '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
                                            '<textarea onKeyPress="enable_product();" name="txt_suprmrk['+gridid+'][]" id="txt_suprmrk_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('+gridid+','+gid+')" class="form-control supquot" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>'+
                                        '</div>'+
                                    '</div><script>$("#fle_supquot_'+gridid+'_'+gid+'").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
                                    );
            }
        // });

        $('#txt_sltsupcode_'+gridid+'_'+gid).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url : 'ajax/ajax_product_details1.php',
                    dataType: "json",
                    data: {
                       name_startsWith: request.term,
                       slt_core_department: $('#slt_core_department').val(),
                       slt_targetno: $('#slt_targetno').val(),
                       action: 'supplier_withcity'
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
        $('#load_page').hide();
    }

    function call_innergrid_remove(gridid) {
        // alert("**"+gridid);
        // $("#removebtn_"+gridid).click(function () {
            // alert("!!"+gridid);
            if ($('.parts3_'+gridid+' .part3_'+gridid).length == 0) {
                // alert("!!"+gridid);
                alert("No more row to remove.");
            }
            var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length - 1).toString();
            // alert(gridid+"@@"+gid);
            $('#partint3_'+gridid).val(gid);
            $('.parts3_'+gridid+' .part3_'+gridid+':last').remove();
        // });
    }
	
	 function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if (charCode > 31 && charCode != 39 && charCode != 34 && charCode != 46 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
	
	
	function submitForm(){
		var formElement = document.getElementById("frm_request_entry_1");
		var formData = new FormData(formElement);
		$.ajax({
				type: "POST",
				url: "ajax/ajax_budget.php?action=budget_submit",
				data: formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,   // tell jQuery not to set contentType
				success: function(results) {
						alert(results);
				}//success close
		});	
         return false;     
	}
     

     function change_readonly() {
        var slt_subtopcore = document.getElementById('slt_subcore').value;
        if(slt_subtopcore == '41') {
            $(".ad_category").attr("readonly", false); 
        } else {
            $(".ad_category").attr("readonly", true); 
            $(".ad_category").val(""); 
        }
    }

     function apply_supplier(iv) {
        var sltsupcode = $('#txt_sltsupcode_1_'+iv).val();
        var state = $('#state1_'+iv).val();
        var cnt = ($('#partint3').val() + 2);
        
        if($("#chk_apply_supplier_1_"+iv).is(':checked')) {
            if(sltsupcode != '') {
                for(var ii = 2; ii <= cnt; ii++) {
                    if($('#txt_sltsupcode_'+ii+'_'+iv).val() == '') { // if empty means update this
                        $('#txt_sltsupcode_'+ii+'_'+iv).val(sltsupcode);
                        $('#state'+ii+'_'+iv).val(state);
                    }
                }
            } else {
                for(var ii = 2; ii <= cnt; ii++) {
                    if($('#txt_sltsupcode_'+ii+'_'+iv).val() != '') {
                        $('#txt_sltsupcode_'+ii+'_'+iv).val('');
                        $('#state'+ii+'_'+iv).val('');
                    }
                }
            }
        } else {
            for(var ii = 2; ii <= cnt; ii++) {
                if($('#txt_sltsupcode_'+ii+'_'+iv).val() != '') {
                    $('#txt_sltsupcode_'+ii+'_'+iv).val('');
                    $('#state'+ii+'_'+iv).val('');
                }
            }
        }
    }
	
    
			
			function enable_product() {
			}
			
			
			  /******************** Change Default Alert Box ***********************/
				var ALERT_BUTTON_TEXT = "OK";
				/* if(document.getElementById) {
					window.alert = function(txt) {
						var ALERT_TITLE = "GA Title";

						var tga = document.getElementById("id_ga").value;
						createCustomAlert(tga, ALERT_TITLE);
					}
				} */

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

				function ful(){
					//alert('Alert this pages');
				}
			
	
	
	
		
	
   
    </script>
   

<!-- END SCRIPTS -->         
</body>
</html>