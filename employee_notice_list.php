<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Request List :: Approval Desk :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

<style type="text/css">

    .modal {
    display: none; /* Hidden by default */
    position:FIXED; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: auto; /* Full width */
    height: auto; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content1 {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
    height :250px;
    overflow-x: scroll;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

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
    .tab-content {
        border-left: 2px solid #ddd;
        border-right: 2px solid #ddd;
        padding: 10px;
    } 
    .panel .nav-tabs > .active > a{
        background-color: #034f84 !important;
        color:#fff !important;
    }
    
    .nav-tabs, .nav-tabs.nav {
        padding: 0px 0px;
        padding-top: 10px;
    }
    .nav-tabs > li > a {
    background: #c3d7e4 !important;
    line-height: 1.2 !important;
    font-size: 10px !important;
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
<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data">
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> process_requirement_view.php*/ ?>
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
                <li class="active">Employee Notice List</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Employee Notice List</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>

                        <!-- ///////////////// -->

                        <div class="col-md-12">                        
                            <!-- START JUSTIFIED TABS -->
                            <div class="panel panel-default tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab9" data-toggle="tab" aria-expanded="false"><b>YOUR NOTICE</b></a></li>
                                    <li class=""><a href="#tab8" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li>
                                    
                                    <div class="panel-body" style="padding-top: 10PX; padding-bottom: 10PX;  border-top: 2px solid #ddd;border-left: 2px solid #ddd;
                                             border-right: 2px solid #ddd;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                        <label class="col-md-3 control-label" id='remarks'>AUTHORIZED BY</label>
                                                    <div class="col-md-8 col-xs-12">
                                                         <select class="form-control custom-select chosn" autofocus tabindex='4' required name='auth_by' id='auth_by' onChange="" data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
                                                        <!-- <option value="choose AUTHORIZER" >CHOOSE THE AUTHORIZER</option> -->
                                                        <option value='20118'>MD</option>
                                                        <option value='43400'>PS MADAM</option>
                                                        <option value='21344'>SK SIR</option>
                                                        <option value='452'>ADMIN GM</option>
                                                    </select>
                                                    
                                                    </div>
                                                    <div class="col-md-1 col-xs-12">
                                                        <div class="checkbox">
                                                            <label><input type="checkbox" value="1" tabindex='5' id="all" name="all"/> All</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </ul>
                                <div class="panel-body tab-content">
                                    <div class="tab-pane" id="tab8">
                                       <div class="panel-body">

                                        <table  class="table datatable table-striped"">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center;width: 100px;'>NOTICE NO.</th>
                                                    <th class="center" style='text-align:center'>EMPLOYEE</th>
                                                    <th class="center" style='text-align:center'>REMARKS</th>
                                                    <th class="center" style='text-align:center'>NOTICE</th>
                                                    <th class="center" style='text-align:center'>AUTHORIZED BY</th>
                                                    <th class="center" style='text-align:center'>REPLY</th>
                                                    <th class="center" style='text-align:center'>REPLY DATE</th>
                                                    <th class="center" style='text-align:center'>ACTION</th>
                                                    <th class="center" style='text-align:center'>PRINT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  $sql_search = select_query_json("Select UI.USRCODE,to_char(emnt.edtdate,'dd/MM/yyyy HH:mi:ss AM') edtdate1,(select empname from employee_office where empsrno=emnt.autsrno) assignuser, emnt.*, emp.empname,emp.empcode From employee_notice_detail emnt, employee_office emp,USERID UI where emnt.EMPSRNO = emp.empsrno AND UI.USRCODE=EMNT.ADDUSER AND EMNT.DELETED='N' AND EMNT.ADDUSER='".$_SESSION['tcs_usrcode']."'  ORDER BY NOTNUMB DESC", "Centra", 'TEST');
                                            $ki = 0;
                                            for($k=0;$k<sizeof($sql_search);$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:center;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center;width: 100px;'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['NOTYEAR'].'-'.$sql_search[$k]['NOTNUMB']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for top core-->
                                                        <? echo $sql_search[$k]['EMPCODE'].' - '.$sql_search[$k]['EMPNAME']; ?> 
                                                    </td>
                                                    <td class="center" style='text-align:left; '><!-- for priority-->
                                                        <? echo $sql_search[$k]['REMARKS']; ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for  editor details-->
                                                         <?  $num=explode('-',$sql_search[$k]['NOTNAME']);
															$num=intval($num[1]);
																if($num>10){  $bg_clr_class='label-danger';}
																if($num>5 && $num<=10){  $bg_clr_class='label-warning'; }
																if($num>0 && $num<=5){  $bg_clr_class='label-success'; }
															
														?>
														<div   style=" text-align:center;line-height: 35px; padding-left: 10px;"><span class="label <?=$bg_clr_class;?> label-form"><b> <?  echo $sql_search[$k]['NOTNAME'];?></b></span></div>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['ASSIGNUSER']; ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['EMP_REMARKS']; ?>
                                                    <td class="center" style='text-align:left'>
                                                        <? echo $sql_search[$k]['EDTDATE1']; ?>
                                                    </td>

                                                    <td class="center" style='text-align:center' id="<? echo $sql_search[$k]['NOTYEAR'];?>-<?echo $sql_search[$k]['NOTNUMB'];?>">
                                                        <?if($sql_search[$k]['EMP_STATUS']=='Y'){


                                                            if($sql_search[$k]['NOTSTAT']!=''){?>
                                                               
                                                                <?  if($sql_search[$k]['NOTSTAT']=='A'){?>
                                                                    <div   style=" text-align:center;line-height: 35px; padding-left: 10px;"><span class="label label-success label-form"><b>APPROVED</b></span></div>
                                                                    <?}if($sql_search[$k]['NOTSTAT']=='R'){?>
                                                                     <div   style=" text-align:center;line-height: 35px; padding-left: 10px;"><span class="label label-danger label-form"><b>DENIED</b></span></div>
                                                                    <?}?>
                                                            <?}else{?>
                                                                <a onclick="approve('<? echo $sql_search[$k]['NOTYEAR'];?>','<?echo $sql_search[$k]['NOTNUMB'];?>','1');" class="btn btn-success btn-sm"><span class="fa fa-thumbs-up"></span></a>
                                                                <a onclick="approve('<? echo $sql_search[$k]['NOTYEAR'];?>','<?echo $sql_search[$k]['NOTNUMB'];?>','2');" class="btn btn-danger btn-sm"><span class="fa fa-thumbs-down"></span></a>
                                                            <?}}?>
                                                    </td>
                                                    <td class="center" style='text-align:center'>
                                                        <a onclick="printpage('<? echo $sql_search[$k]['NOTYEAR'];?>','<?echo $sql_search[$k]['NOTNUMB'];?>');" class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
                                                       
                                                    </td>
                                                    
                                                </tr>
                                                <? 
                                            } ?>
                                            </tbody>
                                        </table>
                                     </div>
                                    </div>
                                    <div class="tab-pane active" id="tab9">
                                     
                                            <div class="panel-body">

                                        <table  class="table datatable table-striped"">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center;width: 100px;'>NOTICE NO.</th>
                                                    <th class="center" style='text-align:center'>EMPLOYEE</th>
                                                    <th class="center" style='text-align:center'>REMARKS</th>
                                                    <th class="center" style='text-align:center'>NOTICE</th>
                                                    <th class="center" style='text-align:center'>AUTHOURIZED BY</th>
													<th class="center" style='text-align:center'>REPLY</th>
													<th class="center" style='text-align:center'>REPLY DATE</th>
                                                    <th class="center" style='text-align:center'>PRINT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?$sql_search = select_query_json("Select (select empname from employee_office where empsrno=emnt.autsrno) assignuser,to_char(emnt.edtdate,'dd/MM/yyyy HH:mi:ss AM') edtdate1, emnt.*, emp.empname,emp.empcode From employee_notice_detail emnt, employee_office emp where emnt.EMPSRNO = emp.empsrno AND EMNT.DELETED='N' and emnt.empsrno='".$_SESSION['tcs_empsrno']."' order by notnumb desc", "Centra", 'TEST');  
									                                                           
                                            $ki = 0;
                                            for($k=0;$k<sizeof($sql_search);$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?><?  $num=explode('-',$sql_search[$k]['NOTNAME']);
															$num=intval($num[1]);
																if($num>10){ $bg_clr_class='label-danger';}
																if($num>5 && $num<=10){ $bg_clr_class='label-warning'; }
																if($num>0 && $num<=5){ $bg_clr_class='label-success'; }?>
                                                    <tr class="odd gradeX">
                                                    <td class="center" style='text-align:center;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center;width: 100px;'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['NOTYEAR'].'-'.$sql_search[$k]['NOTNUMB']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for top core-->
                                                        <? echo $sql_search[$k]['EMPCODE'].' - '.$sql_search[$k]['EMPNAME']; ?> 
                                                    </td>
                                                    <td class="center" style='text-align:left;width:600px;'><!-- for priority-->
                                                        <? echo $sql_search[$k]['REMARKS']; ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for  editor details-->
                                                        
														<div   style=" text-align:center;line-height: 35px; padding-left: 10px;"><span class="label <?=$bg_clr_class;?> label-form"><b> <?  echo $sql_search[$k]['NOTNAME'];?></b></span></div>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['assignuser']; ?>
                                                    </td>
													<td class="center" style='text-align:left'><!-- for attachment count-->
                                                        <? if($sql_search[$k]['EMP_STATUS']=='Y')
                                                            {echo $sql_search[$k]['EMP_REMARKS']; ?>
                                                         <?  if($sql_search[$k]['NOTSTAT']=='A'){?>
                                                            <div   style=" text-align:left;line-height: 35px; padding-left: 10px;"><span class="label label-success label-form"><b>APPROVED</b></span></div>
                                                            <?}if($sql_search[$k]['NOTSTAT']=='R'){?>
                                                             <div   style=" text-align:left;line-height: 35px; padding-left: 10px;"><span class="label label-danger label-form"><b>DENIED</b></span></div>
                                                            <?}if($sql_search[$k]['NOTSTAT']==''){?>
                                                            <div   style=" text-align:left;line-height: 35px; padding-left: 10px;"><span class="label label-warning label-form"><b>WAITING FOR REPLY</b></span></div>
                                                            <?}}?>
                                                    </td>
													<td class="center" style='text-align:left'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['EDTDATE1']; ?>
                                                    </td>
                                                    <td class="center" style='text-align:center; width:100px;'><!-- for USER DETAIL-->
														<?if($sql_search[$k]['EMP_STATUS']!='Y'){?>
                                                        <a type="hidden" onclick="showreply('<? echo $sql_search[$k]['NOTYEAR'];?>','<?echo $sql_search[$k]['NOTNUMB'];?>')" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil"></span></a><?}?>
                                                        <a onclick="printpage('<? echo $sql_search[$k]['NOTYEAR'];?>','<?echo $sql_search[$k]['NOTNUMB'];?>');" class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
                                                        <? //echo $sql_search[$k]['EMPNAME'];echo $sql_search[$k]['EMPCODE']; ?>
                                                    </td>
                                                    
                                                </tr>
                                                <? 
                                            } ?>
                                            </tbody>
                                        </table>
                                     </div>
                                      <!-- /////////////////////////////////// -->
                                    </div>                       
                                </div>
                            </div>                                         
                            <!-- END JUSTIFIED TABS -->
                        </div>
                        
                        <!-- //////////////reply modal -->
                        <style type="text/css">
                            .modal-content {
                               
                                    background-color: #fefefe;
                                    margin: auto;
                                    
                                    border: 1px solid #888;
                                     
                                    
                                     overflow-x: scroll; 
                               
                                 
                            }
                            .modal-content1 {
                                background-color: #fefefe;
                                margin: auto;
                                padding: 20px;
                                border: 1px solid #888;
                                width: 40%;
                                height: 250px;
                                overflow-x: scroll;
                            }

                        </style>

                        <div class="page-title">
                        <div id="myModal1" class="modal">
                          <!-- Modal content -->
                          <span class="close">&times;</span>
                          <div id="modal_data" class="modal-content1">
                                    <textarea id="message" maxlength=250 name="message" malength=250 type="text" tabindex="3" class="form-control" style="text-transform:uppercase; height:100px; padding-right: 5px;" multiple placeholder="TCS Messages.."></textarea>
                                    <center><input type="button" class="btn btn-success" value="SEND" onclick="reply()"/></center>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
</form>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>

    <!-- Collect Document -->
    <div id="myModal11" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>APPROVAL FINAL FINISH</div>
                <div class="modal-body" id="modal-body1"></div>
            </div>
        </div>
    </div>

    <div id="myModal12" class="modal fade">
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
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>

    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>

    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <!-- Select2 -->
    <script src="../dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="js/bootbox.js"></script>
    <script type="text/javascript">

        ///////////
        function alert1(msg){
           bootbox.alert({
                            title: " Success !",
                            message: msg,
                            buttons: {
                                ok: {
                                    label: '<i class="fa fa-times"></i> OK'
                                }
                            },
                            callback: function (result) {
                                //console.log(result);
                            }
                        });
            
        }

        function alert2(msg){
           bootbox.confirm({
                            title: "Confirm",
                            message: msg,
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> No'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Yes'
                                }
                            },
                            callback: function (result) {
                                //console.log(result);
                                if(result)
                                 {//   console.log(1);
                                //     return 1;
                                return true;
                                }
                                else{
                                    console.log(2);
                                    return 2;
                                }
                                
                            }
                        });
            
        }
        /////////////

        var modal = document.getElementById('myModal1');
         var span = document.getElementsByClassName("close")[0];
		var gnotyear=0;
		var gnotnumb=0;

        function showreply(notyear,notnumb){
            modal.style.display = "block";
			gnotyear=notyear;
			gnotnumb=notnumb;
        }
		function reply()
		{	var vurl = "viki/notice_entry.php";
		//alert(gnotyear);
		//alert(gnotnumb);
			var remarks=$('#message').val();
			if(remarks.trim()!='')
			{
				$.ajax({
					type: "POST",
					url: vurl,
					data:{
						'notyear':gnotyear,
						'notnumb':gnotnumb,
						'action':'reply',
						'remarks':remarks
					},
					dataType:'html',
					success: function(data1) {
					   //$('#notice').val(data1);
					   alert1("UPDATED SUCCESSFULLY");
					   modal.style.display = "none";
					    location.reload();

					},
					error: function(response, status, error)
					{       alert(error);
							//alert(response);
							//alert(status);
					}
				});
			}
			else{
				alert("Response required");
				
			}
		}

        span.onclick = function() {
            modal.style.display = "none";
              
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
               
            }
        }

    function approve(notyear,notnumb,ch){
        console.log(ch);
        var msg='';
        if(ch=='1'){msg="APPROVE";}
        else{msg="DENY";}
        bootbox.confirm({
                        title: "Confirm",
                        message: "Are You Sure to "+msg+" "+notyear+"-"+notnumb+" !",
                        buttons: {
                            cancel: {
                                label: '<i class="fa fa-times"></i> No'
                            },
                            confirm: {
                                label: '<i class="fa fa-check"></i> Yes'
                            }
                        },
                        callback: function (result) {
                                if(result)
                                {
                                    var vurl = "viki/notice_entry.php";
                                     $.ajax({
                                            type: "POST",
                                            url: vurl,
                                            data:{
                                                'notyear':notyear,
                                                'notnumb':notnumb,
                                                'action':'approve',
                                                'val':ch
                                            },
                                            dataType:'html',
                                            success: function(data1) {
                                               if(ch=='1')
                                                {   //alert("APPROVED");
                                                    $('#'+notyear+'-'+notnumb).html("<div style=' text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-success label-form'><b>APPROVED</b></span></div>");
                                                }
                                               else
                                                {   //alert("REJECTED");
                                                    $('#'+notyear+'-'+notnumb).html("<div style=' text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-danger label-form'><b>DENIED</b></span></div>");
                                                }
                                                

                                            },
                                            error: function(response, status, error)
                                            {       alert(error);
                                                    //alert(response);
                                                    //alert(status);
                                            }
                                        });
                                    
                                }
                        }
        });

    }

    function PrintDiv(dataurl) {
        var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
    }

    $(function() {
        var showTotalChar = 200, showChar = "Show (+)", hideChar = "Hide (-)";
        $('.show_moreless').each(function() {
            var content = $(this).text();
            if (content.length > showTotalChar) {
                var con = content.substr(0, showTotalChar);
                var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                var txt= '<b>'+con +  '</b><span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="javascript:void(0)" class="showmoretxt">' + showChar + '</a></span>';
                $(this).html(txt);
            }
        });

        $(".showmoretxt").click(function() {
            if ($(this).hasClass("sample")) {
                $(this).removeClass("sample");
                $(this).text(showChar);
            } else {
                $(this).addClass("sample");
                $(this).text(hideChar);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });

    $(document).ready(function() {
        $("#load_page").fadeOut("slow");
        $(".finish_confirm").click( function() {
        });
    });

    $(document).keypress(function(e) {
        if (e.keyCode == 27) {
            $("#myModal1").fadeOut(500);
        }
    });

    $('#datepicker-example3').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example4')
    });

    $('#datepicker-example4').Zebra_DatePicker({
      direction: [1, '<?=date("d-M-Y")?>'], // ['<?=date("d-M-Y")?>', 0], // 0,
      format: 'd-M-Y'
    });

    function printpage(notyear,notnum)
    {
        var opt;
       
        var auth_by=$('#auth_by').val();
       if($("#all").prop('checked')==true)
       {opt=4;
       }
       else
       {
        opt=1;
       }
        var dataurl="print_page.php?notyear="+notyear+"&notnumb="+notnum+"&all="+opt+"&authby="+auth_by;
        //alert(dataurl);
        var popupWin = window.open(dataurl, '_blank', 'width=1024, height=700');

    }

    function call_confirm(ivalue, reqid, year, rsrid, creid, typeid, aprnumb)
    {
        $('#load_page').show();
        var send_url = "final_finish.php?aprnumb="+aprnumb+"&reqid="+reqid+"&year="+year+"&rsrid="+rsrid+"&creid="+creid+"&typeid="+typeid;
        $.ajax({
        url:send_url,
        type: "POST",
        success:function(data){
                $("#myModal1").modal('show');
                $('#load_page').hide();
                document.getElementById('modal-body1').innerHTML=data;
                $('#load_page').hide();
            }
        });
    }

    function cmnt_mail(aprnumb)
    {
        var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
        $.ajax({
        url:sendurl,
        success:function(data){
            $("#myModal2").modal('show');
            $('#modal-body2').html(data);
            $('#txtmailcnt').val("");
            }
        });
    }

    function cmt_usr()
    {
        $('#cmtusr').css("display", "block");
        $('.select2').select2();
        $('#mailusr').focus();
        //$("#mailusr").select2("open");
        $('#mailusr').select2({
        placeholder: 'Enter EC No/Name to Select an mail user',
        allowClear: true,
        dropdownAutoWidth: true,
        minimumInputLength: 3,
        maximumSelectionLength: 3,
        ajax: {
          url: 'ajax/ajax_general_temp.php?action=MAILUSER',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }
    </script>
<!-- END SCRIPTS -->
</body>
</html>
