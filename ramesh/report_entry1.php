<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');


$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);

$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$cur = strtoupper(date('Y')-1);
$lat = strtoupper(date('Y')-2);
$cur_mon = strtoupper(date('m'));
$lat_mon = strtoupper(date('m'));
$sysip = $_SERVER['REMOTE_ADDR'];
$cur_mn = date("m");

if($_SESSION['auditor_login'] == 1) { ?>
    <script>alert('You dont have rights to access this page.'); window.location="index.php";</script>
<? exit();
}



?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title><?=$title_tag?> Process Entry :: Approval Desk :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->
    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->
    <link href="css/jquery-customselect.css" rel="stylesheet" />
    
    <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
    <!-- multiple file upload -->
    <link href="css/jquery.filer.css" rel="stylesheet">
    <script src="js/angular.js"></script>
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
                <li><a href="request_list.php">Report Request</a></li>
               <!--  <li class="active"><?=$title_tag?> Request Entry</li> -->
            </ul>
            <!-- END BREADCRUMB -->
            <style>
                .input_group{
                    width:100%;
                }
              
            </style>
            <style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }

    body 
    {
        background-color:#FFFFFF; 
        border: solid 1px black ;
        margin: 0px;  /* this affects the margin on the content before sending to printer */
   }
</style>

            <!-- PAGE CONTENT WRAPPER -->
           <div class="page-content-wrap">
                
                    <div class="row">
                        <div class="col-md-12">
                             <!-- START JUSTIFIED TABS -->
                            <div class="panel panel-default tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="false"><b>YOUR NOTICE</b></a></li>
                                    <li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li>
                                    <li class=""><a href="#tab3" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li>                                  

                                </ul>
                                <div class="panel-body tab-content">
                                    <div class="tab-pane" id="tab1" class="active">
                                       <div class="panel-body">

                                <div class="col-md-12">
                                    <div class="col-md-6">                                                                     
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">Process</label>
                                        <div class="col-md-9 col-xs-12">                                                                                            
                                            <select class="form-control select" required="required">
                                                <?
                                               // $sql_reqid = select_query_json("select a.* from SUPMAIL_PROCESS", "Centra", 'TEST'); ?>
                                                <option>Select Process</option>
                                                <?php 
                                                //$selprocess=select_query_json("select a.*,b.* from SUPMAIL_PROCESS a,SUPMAIL_PROCESS_ENTRY b where a.PRCSNO=b.PRCSNO");?>
                                                <?php 
                                                //for($i=0;$i<count($sql_reqid);$i++){?>
                                                    <option value=""></option>
                                            </select>
                                          
                                        </div>
                                    </div>
                                     <div class="form-group" style="padding:10px" id="fields">
                                         

                                     </div>                                
                                
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">Language</label>
                                        <div class="col-md-9 col-xs-12">                                                   
                                            <select class="form-control select" required="required" onchange>
                                                <option>Select Language</option>
                                                <option value="1">Tamil</option>
                                                <option value="2">English</option>
                                                <option value="3">Hindi</option>
                                                <option value="4">Telugu</option>
                                                <option value="5">Malayalam</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">Comments</label>
                                        <div class="col-md-9 col-xs-12">                                            
                                            <textarea class="form-control" name='comments' rows="5" required="required"></textarea>
                                           
                                        </div>
                                    </div>
                                    
                                   
                                    
                                    
                                    <!-- <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">File</label>
                                        <div class="col-md-6 col-xs-12">                                                                                                                                        
                                            <input type="file" class="fileinput btn-primary" name="filename" id="filename" title="Browse file"/>
                                            <span class="help-block">Input type file</span>
                                        </div>
                                    </div> -->
                                    
                                    <!-- <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Checkbox</label>
                                        <div class="col-md-6 col-xs-12">                                                                                                                                        
                                            <label class="check"><input type="checkbox" class="icheckbox" checked="checked"/> Checkbox title</label>
                                            <span class="help-block">Checkbox sample, easy to use</span>
                                        </div>
                                    </div> -->

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


                <h3 style="text-align:center;margin-top:10px"><u>BOOKING BUNDLE SHORTAGE CONFIRMATION</u></h3>
            <div class="row" style="height:auto;padding:10px">
                <div>
                    <img src="" />

                </div>
            </div>
            <div class="row">
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
            <h3><u>SUGGESTION & COMMENTS</u></h3>

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
                                     
                                    </div>  
                                    <div class="tab-pane" id="tab3">
                                    
                                    </div>                      
                                </div>
                            </div>  
                             
                    </div>                    
                    
                </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>
    <script type="text/javascript">
//     function printDiv(divName) {

//      var printContents = document.getElementById(divName).innerHTML;

//      printContents+='<style>@page{size: auto;margin: 0mm;}<style>';

//      var originalContents = document.body.innerHTML;

//      document.body.innerHTML = printContents;

//      window.print();

//      document.body.innerHTML = originalContents;
// }
    </script>

  
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

</body>
</html>
