<?
session_start();
error_reporting(0);
header('X-UA-Compatible: IE=edge');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="../index.php";</script>
<?php exit();

}

$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);

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
if($inner_menuaccess[0]['VEWVALU'] == 'Y' and $_SESSION['tcs_empsrno'] == 92631) { // Menu Permission is allowed ?>
<!DOCTYPE html>
<html lang="en">
    <head>        
        <!-- META SECTION -->
        <title>TEST </title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="../favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="../css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->                                     
    </head>
    <style type="text/css">
        .nav-tabs > li > a{
        border: 0px;
        background-color:#BABABA !important;
        -moz-border-radius: 3px 3px 0px 0px;
        -webkit-border-radius: 3px 3px 0px 0px !important;
        border-radius: 3px 3px 0px 0px;
    }
    
    .nav-tabs > li > a:hover{
    
    color: #fff !important;
    cursor: pointer;
    background-color: #a7a9ad !important;
    border: 0px;
    border-top: 2px solid #1b1e24 !important;
    -moz-border-radius: 3px 3px 0px 0px;
    -webkit-border-radius: 3px 3px 0px 0px;
    border-radius: 3px 3px 0px 0px;
    }
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus {
    border: 0px;
    border-top: 2px solid #1b1e24;
    background: #3f444c !important;
    -moz-border-radius: 3px 3px 0px 0px;
    -webkit-border-radius: 3px 3px 0px 0px;
    border-radius: 3px 3px 0px 0px;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    color: #fff !important;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}

    </style>
       <!-- CSS INCLUDE -->
    <?  $theme_view = "../css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->
    </head>
    <body>
     
         <div id="load_page" style='display:none;padding:12% 40%;'></div> 
        <!-- START PAGE CONTAINER -->
        <div class="page-container page-navigation-top-fixed">

            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <? include '../lib/app_left_panel.php'; ?>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->

            <!-- PAGE CONTENT -->
            <div class="page-content">
            
                <!-- START X-NAVIGATION VERTICAL -->
                <? include "../lib/app_header.php"; ?>
                <!-- END X-NAVIGATION VERTICAL -->

                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="home.php">Dashboard</a></li>
                    <li class="active">Vue JS</li>
                </ul>
                <!-- END BREADCRUMB -->              
                
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <p>Use search to find contacts. You can search by: name, address, phone. Or use the advanced search.</p>
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="fa fa-search"></span>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Who are you looking for?"/>
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-primary">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-success btn-block"><span class="fa fa-plus"></span> Add new contact</button>
                                            </div>
                                        </div>
                                    </form>                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
               
               
                    <ul class="nav nav-tabs">
                      <li class="" ><a href="#tab_1" data-toggle= "tab" title ="profiles" >Profiles</a></li>
                      <li class=""><a href="#tab_2" data-toggle="tab"  title ="attendance">Attendance</a></li>
                      <li class="active"><a href="#tab_3" data-toggle="tab"  title ="special">Special</a></li>
                      
                    </ul>
                <div class="tab-content">
                  <div class="tab-pane " id="tab_1">
                  <div style ="padding-top:10px; background: #3f444c;"></div>
                  <div class="page-content-wrap">
                    <div class="panel panel-default" id="profile_form" >
                        <div class="panel-heading">
                        <h3 class="panel-title">Profiles</h3>
                        </div>
                        <div class="panel-body">                      
                        <div class="row form-group "  >
                                        <div class=" col-xs-2 col-md-offset-3">
                                            <label>NO:<span style='color:red'>*</span></label>
                                        </div>
                                        <div class="col-md-3" >

                                            <input type='text' name='txt_profile' id='txt_profile' value='' data-toggle="tooltip" v-model='txt_profile' required value='{{txt_profile }}' class='form-control' maxlength="100" style='text-transform:uppercase;' @keyup.enter="loaddata('load',$event)" autocomplete="off" >

                                        </div>
                                         <button type="button" name="submit" class="btn btn-primary" @click="loaddata">Submit</button>
                        </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
               
                      <div class="panel panel-info">
                        <div class="panel-heading">
                          <h3 class="panel-title">{{ empname }}</h3>
                        </div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-3 col-lg-3 " align="center"> 
                                <img alt="User Pic" v-bind:src="img" class="img-responsive" style="width:200px; height:200px; border:1px solid #a0a0a0; text-align:center; border:0px;" >
                                 </div>
                            
                            <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
                              <dl>
                                <dt>DEPARTMENT:</dt>
                                <dd>Administrator</dd>
                                <dt>HIRE DATE</dt>
                                <dd>11/12/2013</dd>
                                <dt>DATE OF BIRTH</dt>
                                   <dd>11/12/2013</dd>
                                <dt>GENDER</dt>
                                <dd>Male</dd>
                              </dl>
                            </div>-->
                            <div class=" col-md-9 col-lg-9 "> 
                              <table class="table table-user-information">
                                <tbody>
                                  <tr>
                                    <td>EMPSNO:</td>
                                    <td> {{ empsrno }} </td>
                                  </tr>
                                  <tr>
                                    <td>DEPARTMENT:</td>
                                    <td> {{ comname }} </td>
                                  </tr>
                                  <tr>
                                    <td>DOB</td>
                                    <td> dob </td>
                                  </tr>
                               
                                     
                                 
                                </tbody>
                              </table>
                              
                                  
                                </div>
                              </div>
                            </div>
                                    <div class="panel-footer">
                                        
                                    </div>
                            
                          </div>
                        </div>
                      </div>  

              </div>
              </div><!-- page content -->
              </div>

            <div class="tab-pane " id="tab_2">
                  <div style ="padding-top:10px; background: #3f444c;"></div>
                  <div class="page-content-wrap">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <h3 class="panel-title">Attendance</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive " style="padding-left: 100px;padding-right: 100px" id="attend">
                                        <div class="col-md-3" style="padding-bottom: 50px" >
                                            <label > EC.NO :</label>
                                            <input type='text' name='txt_code' id='txt_code' v-model='txt_code' data-toggle="tooltip" required  class='form-control' maxlength="100" style='text-transform:uppercase;'  autocomplete="off" >
                                            
                                            <div class="input-group" style="display: inline-flex;">
                                                 <input type="text" class="form-control datepicker" value="" v-model="date">      
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                                                     
                                            </div>

                                            <button type="button" name="submit" class="btn btn-primary " @click='loadattend' >Submit</button>
                                        </div>
                                         
                                      <table class="table table-bordered" >
                                        <thead >
                                          <tr >
                                            <th scope="col">EC.NO</th>
                                            <th scope="col">NAME</th>
                                            <th scope="col">PRESENT DAYS</th>
                                            <th scope="col">WEEKOFF DAYS</th>
                                            <th scope="col">LEAVE DAYS</th>
                                            
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <th scope="row">{{ empcode }}</th>
                                            <td>{{ empname }}</td>
                                            <td>{{ present }}</td>
                                            <td>{{ absent }}</td>
                                            <td>{{ leave }}</td>
                                            
                                            
                                          </tr>
                                          
                                        </tbody>
                                      </table>

                                    </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane active " id="tab_3">
                  <div style ="padding-top:10px; background: #3f444c;"></div>
                  <div class="page-content-wrap">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <h3 class="panel-title">Special</h3>
                        </div>
                        <div class="panel-body">
                            <style type="text/css">
                            .t1{
                                float: right;
                                cursor: pointer;
                                margin-right: 5px;
                                margin-top: 0px;
                                position: relative;
                                margin-left: -16px;
                                font-size: 20px
                            }
                            .inline {
                                display: inline;
                            }
                            /* Styles go here */

                    #hovertimer {
                      border: 1px solid black;
                    }
                    #play_timer {
                      display:none;
                      position:absolute;
                      background: grey;
                      color:white;
                      width: 50%;
                      height:50%;
                    }
                            </style>
                            <div id="timer" class="t1 inline">
                                
                                <span class="fa fa-clock-o" id="hovertimer" style="padding: 10px;"></span><span>APPROVAL DESK REQUEST ENTRY  REGULAR PROCESS</span>
                                <div id="play_timer">
                                    <span class="fa fa-pause"></span><span class="fa fa-stop"></span><span >00:00:00</span>
                                </div>
                                <script type="text/javascript">
                                    $("#hovertimer").hover(function(){
                                      $("#play_timer").show()
                                    },function(e){
                                      alert(e.pageX)
                                      $("#secret").hide()
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


              </div>   <!-- Tab End -->

               
           
                </div>
                <!-- END PAGE CONTENT WRAPPER -->                                                 
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

        <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Are you sure you want to log out?</p>                    
                        <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="pages-login.html" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->

        <!-- START PRELOADS -->
        <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
        <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
        <!-- END PRELOADS -->          
        
    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="../js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="../js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='../js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="../js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="../js/settings.js"></script>
        
        <script type="text/javascript" src="../js/plugins.js"></script>        
        <script type="text/javascript" src="../js/actions.js"></script>        
        <!-- END TEMPLATE -->
        <script type="text/javascript" src="js/vue.js"></script>
        <script type="text/javascript" src="js/axios.js"></script>
        <script type="text/javascript" src="js/app.js"></script>
         
        <script type="text/javascript">
             
          
        </script>

    <!-- END SCRIPTS -->         
    </body>
    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>



