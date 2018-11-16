<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');


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
.modal-content {
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
<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="saveprocessentry.php" method="post" enctype="multipart/form-data">
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
                <li class="active">Process List</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Process List</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>

                        <!-- ///////////////// -->

                        <div class="col-md-12">                        
                            <!-- START JUSTIFIED TABS -->
                            <div class="panel panel-default tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="false"><b>YOUR NOTICE</b></a></li>
                                    <li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li>
                                    <li class=""><a href="#tab3" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li>                                  

                                </ul>
                                    

                                </ul>
                                <div class="panel-body tab-content">
                                    <div class="tab-pane active" id="tab1">
                                       <div class="panel-body">
                                       	<div class="col-md-12">
                                    <div class="col-md-6">                                                                     
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">Process</label>
                                        <div class="col-md-9 col-xs-12">                                                                                            
                                            <select class="form-control select" required="required" id='processname' name='processname' 
                                            onchange="getfields(this.value);getlang(this.value)">
                                                <?php
                                                 $sql_reqid = select_query_json("select * from SUPMAIL_PROCESS", "Centra", 'TEST');
                                              
                                                 ?> 
                                                <option value="">Select Process</option>
                                               <?for($i=0;$i<count($sql_reqid);$i++){ ?>
                                               
                                                    <option value="<?=$sql_reqid[0]['PRCSNO']?>"><?=$sql_reqid[0]['PRCDSC']?></option><?}?>
                                            </select>
                                          
                                        </div>
                                    </div>
                                    <span id="fields"></span>
                                                       
                                
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">Language</label>
                                        <div class="col-md-9 col-xs-12" id="language">                                                   
                                            <select class="form-control select" required="required" name='language' onchange="getimage1(this.value)">
                                                <option>Select Language</option>
                                                <option value="1">Tamil</option>
                                                <option value="2">English</option>
                                                <option value="3">Hindi</option>
                                                <option value="4">Telugu</option>
                                                <option value="5">Malayalam</option>
                                            </select>
                                        </div>
                                    </div>
                                    <span id="image"></span>
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">Comments</label>
                                        <div class="col-md-9 col-xs-12">                                            
                                            <textarea class="form-control" name='comments' id="comments" rows="5" required="required" onkeyup='getcomments()'></textarea>
                                           
                                        </div>
                                    </div>
                                    <div class="form-group" style="padding:10px">
                                        
                                        <div class="col-md-12 col-xs-12">                                            
                                            <center><button class="btn btn-success" name="submit" value="submit">Submit</button></center>
                                           
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6" id="printablearea" style="display:none">

        <div class="row">
            <div class='col-md-12' style="padding:50px 50px">
            <div class="row">
                <div class="text-center" style="height:auto">
                <center><img src="" alt="logo" style="text-align:center;" /></center>
                </div>
            </div>  

        
        <div class="row" style="height:25px">
            <p class='pull-right' style="float:right">DATE : <?php echo strtoupper(date('d-M-Y'));?></p>
                
        </div>
        <div class="row" style="height:100px">
                <div>
                    <p>Dear Business Associates</p>
                    <p>Greeting from The Chennai Silks</p>

                </div>
        </div>


                <h3 style="text-align:center;margin-top:10px" id='process_title'><u></u></h3>
            <div class="row" style="height:auto;padding:10px">
                <div id='image_lang'>
                    <img src="" />

                </div>
            </div>
            <div class="row" id=fields_row>
                <table style="width:100%" class="contact">
                      
                      <tr>
                        <td align="left" width='30%'>Pjv No<span class="pull-right">:</span></td>
                        <td align="left" width='70%'></td>                     
                      </tr>
                       <tr>
                        <td align="left" width='30%'>Bill No<span class="pull-right">:</span></td>
                        <td align="left" width='70%'></td>                     
                      </tr>
                       <tr>
                        <td align="left" width='30%'>Bill Date<span class="pull-right">:</span></td>
                        <td align="left" width='70%'></td>                     
                      </tr>
                       <tr>
                        <td align="left" width='30%'>Shortage Quantity<span class="pull-right">:</span></td>
                        <td align="left" width='70%'></td>                     
                      </tr>
                       <tr>
                        <td align="left" width='30%'>Shortage Value<span class="pull-right">:</span></td>
                        <td align="left" width='70%'></td>                     
                      </tr>
                      
                </table>
            </div>
              

        <div class="row" style="margin-top:50px">
            <div style="height:auto;padding:5px 0px;">
                        <p>Thanks & Regards</p>
                        <p>Management</p>
                        <p>The Chennai Silkks</p>

                </div>
        </div>
        
        <div class="row">
        <div class="col-md-12" style="border:2px solid #000">
            <h5 style="font-weight:bold"><u>SUGGESTION & COMMENTS</u></h5>
            <div id="processcomments"></div>
        </div>
    </div>
        </div>
        <!-- <div class="panel-footer">
            <center >
                <input class="btn btn-success no-print" id='btnPrint' style="text-align: center" type="button" onclick="window.print();" value="PRINT">
            </center>
        </div> -->
   
</div>
    </div>
                                     </div>
                                    </div>
                                      </div>
                                    <div class="tab-pane" id="tab2">
                                      <!-- ///////////////////////// -->
                                            <div class="panel-body">

                                       
                                     </div>
                                      <!-- /////////////////////////////////// -->
                                    </div>  
                                    <div class="tab-pane" id="tab3">
                                      <!-- ///////////////////////// -->
                                            <div class="panel-body">

                                       
                                     </div>
                                      <!-- /////////////////////////////////// -->
                                    </div>                        
                              
                            </div>                                         
                            <!-- END JUSTIFIED TABS -->
                        </div>
                        
                        <!-- //////////////reply modal -->

                        <div class="page-title">
                        <div id="myModal" class="modal">
                          <!-- Modal content -->
                          <span class="close">&times;</span>
                          <div id="modal_data" class="modal-content">
                                

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
    <script type="text/javascript">
    	
    	function getcomments(){
    		var val=$('#comments').val();
    		$('#processcomments').html(val);
    	}
    	
    	function getfields(id){

    		$.ajax({
					type: "POST",
					url: 'getfields.php',
					data:{
						'id':id
					},	
					dataType:'html',				
					success: function(response) {	
									
					   $('#fields').html(response);
					   var val=$('#processname').val();					   
					   getfields_row(id);
					   getprocessname(id);

					},
					error: function(response, status, error)
					{       alert(error);
							//alert(response);
							//alert(status);
					}
				});




    	}
    	function getprocessname(id){

    		$.ajax({
					type: "POST",
					url: 'getprocessname.php',
					data:{
						'id':id
					},	
					dataType:'html',				
					success: function(response) {	
					console.log(response)					
					   $('#process_title').html(response);			  

					},
					error: function(response, status, error)
					{       alert(error);
							//alert(response);
							//alert(status);
					}
				});




    	}
    	function getfields_row(id){

    		$.ajax({
					type: "POST",
					url: 'getfieldsrow.php',
					data:{
						'id':id
					},	
					dataType:'html',				
					success: function(response) {						
					   $('#fields_row').html(response);			  

					},
					error: function(response, status, error)
					{       alert(error);
							//alert(response);
							//alert(status);
					}
				});




    	}
    	function getlang(id){

    		$.ajax({
					type: "POST",
					url: 'getlanguage.php',
					data:{
						'id':id
					},	
					dataType:'html',				
					success: function(response) {
						
					   $('#language').html(response);
					},
					error: function(response, status, error)
					{       alert(error);
							//alert(response);
							//alert(status);
					}
				});




    	}
    	function getimage(id){

    		$.ajax({
					type: "POST",
					url: 'getimage.php',
					data:{
						'id':id
					},	
					dataType:'html',				
					success: function(response) {	

					   $('#image').html(response);
					   $('#image_lang').html(response);
					},
					error: function(response, status, error)
					{       alert(error);
							//alert(response);
							//alert(status);
					}
				});




    	}
    </script>
    <script>
    $('#datepicker-example3').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example4')
    });

    $('#datepicker-example4').Zebra_DatePicker({
      direction: [1, '<?=date("d-M-Y")?>'], // ['<?=date("d-M-Y")?>', 0], // 0,
      format: 'd-M-Y'
    });

   </script>
<!-- END SCRIPTS -->
</body>
</html>
