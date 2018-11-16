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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">

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
                                    <li class=""><a href="#tab1" data-toggle="tab" aria-expanded="false"><b>NEW PROCESS</b></a></li>
                                    <li class="active"><a href="#tab2" data-toggle="tab" aria-expanded="false"><b>VIEW PROCESS</b></a></li>
                                    <li class=""><a href="#tab3" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li>                                  

                                </ul>
                                <div class="panel-body tab-content">
                                    <!-- //////////////////// -->
                                  
                                      <!-- ///////////////////////// -->
                                    <div class="tab-pane active" id="tab2">
                                      <!-- ///////////////////////// -->
                                         
                                       <div class="panel-body">

                                        <table  class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.NO</th>
                                                    <th class="center" style='text-align:center'>PROCESS NO.</th>
                                                    <th class="center" style='text-align:center'>PROCESS NAME</th>
                                                    <th class="center" style='text-align:center'>LANGUAGE</th>
                                                    <th class="center" style='text-align:center'>COMMENT</th>
                                                    <!-- <th class="center" style='text-align:center'>AUTHOURIZED BY</th>
                                                    <th class="center" style='text-align:center'>REPLY</th>
                                                    <th class="center" style='text-align:center'>REPLY DATE</th>
                                                    <th class="center" style='text-align:center'>ACTION</th> -->
                                                    <th class="center" style='text-align:center'>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  $sql_search = select_query_json("select * from SUPMAIL_PROCESS_ENTRY where DELETED='N' order by TEMPNO desc", "Centra", 'TEST');
                                            if($sql_search){
                                            $ki = 0;
                                            for($k=0;$k<sizeof($sql_search);$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:center;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['TEMPYR'].' _ '.$sql_search[$k]['TEMPNO']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for top core-->
                                                        <? 
                                                        $pro_name = select_query_json("select * from SUPMAIL_PROCESS where PRCSNO='".$sql_search[$k]['PRCSNO']."' and PRCSYR='".$sql_search[$k]['PRCSYR']."'", "Centra", 'TEST');

                                                        echo $pro_name[0]['PRCDSC']; ?> 
                                                    </td>
                                                    <td class="center" style='text-align:center; '><!-- for priority-->
                                                        <?
                                                        	$lang_name = select_query_json("select * from SUPMAIL_PROCESS_LANGUAGE where PRCSNO='".$sql_search[$k]['PRCSNO']."' and LANGCOD='".$sql_search[$k]['LANGCOD']."'", "Centra", 'TEST'); 
                                                        echo $lang_name[0]['LANGNAM']; ?>
                                                    </td>
                                                    
                                                    <td class="center" style='text-align:center'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['TEMPCMNT']; ?>
                                                    </td>
                                                   
                                                    <td class="center" style='text-align:center'><!-- for USER DETAIL-->
                                                        <a onclick="printpage('<? echo $sql_search[$k]['TEMPNO'];?>','<?echo $sql_search[$k]['TEMPYR'];?>');" data-toggle="tooltip" title="print" class="btn btn-warning btn-sm"><span class="fa fa-print"></span></a>
                                                        <a onclick="delprocess('<? echo $sql_search[$k]['TEMPNO'];?>','<?echo $sql_search[$k]['TEMPYR'];?>');" data-toggle="tooltip" title="delete" class="btn btn-danger btn-sm"><span class="fa fa-trash-o"></span></a>
                                                       
                                                    </td>
                                                    
                                                </tr>
                                                <? 
                                            }}else{?><td colspan="6" align="center"><label class="label label-danger">NO RECORDS FOUND</label></td><?} ?>
                                            </tbody>
                                        </table>
                                    
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
    

    <!-- Collect Document -->
    <div class='clear'></div>


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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
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
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
    <script type="text/javascript">
    	function printpage(tempno,tempyr){
    		 var dataurl="report_print_page.php?tempno="+tempno+"&tempyr="+tempyr;
        //alert(dataurl);
        	 window.open(dataurl);
    	}
    	function getcomments(){
    		var val=$('#comments').val();
    		$('#processcomments').html(val);
    	}
    	
    	function delprocess(tempno,tempyr){
            
    		$.ajax({
					type: "POST",
					url: 'delprocess.php',
					data:{
						tempno:tempno,
                        tempyr:tempyr
					},	
									
					success: function(response) {	
							location.reload();

					},
					error: function(response, status, error)
					{       alert(error);
							//alert(response);
							//alert(status);
					}
				});




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
    $('#datepicker-example1').Zebra_DatePicker({
      // direction: false, // 1,
      format: 'd-M-Y'
     
    });

    

   </script>
<!-- END SCRIPTS -->
</body>
</html>
