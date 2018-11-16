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

$menu_name = 'ADMIN DASHBOARD';
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
    <title>Supplier Chat :: Approval Desk :: <?php echo $site_title; ?></title>
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
        <!-- multiple file upload -->
        <link href="css/jquery.filer.css" rel="stylesheet">

    <style type="text/css">
        .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        opacity: 0.4;
        background: url('images/l2.gif') 50% 50% no-repeat rgb(249,249,249);
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
    </style>
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->
    </head>
    <body>
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> */ ?>
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
                    <li class="active">Supplier Chat</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE CONTENT WRAPPER -->
                
                                                       
                
                <!-- PAGE TITLE -->
                <div class="page-title">                    
                    <h2><span class="fa fa-users"></span> Supplier Profile Contact <small>139 contacts</small></h2>
                </div>
                <!-- END PAGE TITLE -->                
                
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <p>Use search to find contacts. You can search by: name, address, phone. Or use the advanced search.</p>
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="fa fa-search"></span>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Search ?" id="supplier_list" data-search/>
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-primary">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-success btn-block"><span class="fa fa-plus"></span> Add new contact</button>
                                            </div>
                                            <script>
                                                   /* $('[data-search]').on('keyup', function() {
                                                    var searchVal = $(this).val();
                                                    var filterItems = $('[data-filter-item]');

                                                    if ( searchVal != '' ) {
                                                        filterItems.addClass('hidden');
                                                        $('[data-filter-item][data-filter-name*="' + searchVal.toLowerCase() + '"]').removeClass('hidden');
                                                    } else {
                                                        filterItems.removeClass('hidden');
                                                    }
                                                });*/

                                            </script>
                                        </div>
                                    </form>                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="row">
                        <div id = "supplier">
                        <div class="col-md-3">

                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default list">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="assets/images/users/user3.jpg" alt="Nadia Ali"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">Nadia Ali</div>
                                        <div class="profile-data-title">Singer-Songwriter</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/>(555) 555-55-55</p>
                                        <p><small>Email</small><br/>nadiaali@domain.com</p>
                                        <p><small>Address</small><br/>123 45 Street San Francisco, CA, USA</p>                                   
                                    </div>
                                </div>                                
                            </div>    
                        </div>
                        
                        <div class="col-md-3">
                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="assets/images/users/user.jpg" alt="Dmitry Ivaniuk"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">Dmitry Ivaniuk</div>
                                        <div class="profile-data-title">Web Developer / UI/UX Designer</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/>(333) 333-33-22</p>
                                        <p><small>Email</small><br/>dmitry@domain.com</p>                                        
                                        <p><small>Address</small><br/>123 45 Street San Francisco, CA, USA</p>                                   
                                    </div>
                                </div>                                
                            </div>
                            <!-- END CONTACT ITEM -->
                        </div>
                        <div class="col-md-3">
                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="assets/images/users/user2.jpg" alt="John Doe"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">John Doe</div>
                                        <div class="profile-data-title">Web Developer/Designer</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/>(234) 567-89-12</p>
                                        <p><small>Email</small><br/>john@domain.com</p>
                                        <p><small>Address</small><br/>123 45 Street San Francisco, CA, USA</p>                                   
                                    </div>
                                </div>                                
                            </div>
                            <!-- END CONTACT ITEM -->
                        </div>
                        <div class="col-md-3">
                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="assets/images/users/user4.jpg" alt="Brad Pitt"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">Brad Pitt</div>
                                        <div class="profile-data-title">Actor and Film Producer</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/>(321) 777-55-11</p>
                                        <p><small>Email</small><br/>brad@domain.com</p>
                                        <p><small>Address</small><br/>123 45 Street San Francisco, CA, USA</p>                                   
                                    </div>
                                </div>                                
                            </div>
                            <!-- END CONTACT ITEM -->
                        </div>
                        <div class="col-md-3">
                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="assets/images/users/user5.jpg" alt="John Travolta"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">John Travolta</div>
                                        <div class="profile-data-title">Actor</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/>(111) 222-33-78</p>
                                        <p><small>Email</small><br/>travolta@domain.com</p>
                                        <p><small>Address</small><br/>123 45 Street San Francisco, CA, USA</p>                                   
                                    </div>
                                </div>                                
                            </div>
                            <!-- END CONTACT ITEM -->
                        </div>
                        <div class="col-md-3">
                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="assets/images/users/user6.jpg" alt="Darth Vader"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">Darth Vader</div>
                                        <div class="profile-data-title">Cyborg</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/>(000) 000-00-01</p>
                                        <p><small>Email</small><br/>vader@domain.com</p>
                                        <p><small>Address</small><br/>Somewhere deep in space</p>                                   
                                    </div>
                                </div>                                
                            </div>
                            <!-- END CONTACT ITEM -->
                        </div>
                        <div class="col-md-3">
                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="assets/images/users/user7.jpg" alt="Samuel Leroy Jackson"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">Samuel Leroy Jackson</div>
                                        <div class="profile-data-title">Actor and film producer</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/>(552) 221-23-25</p>
                                        <p><small>Email</small><br/>samuel@domain.com</p>
                                        <p><small>Address</small><br/>123 45 Street San Francisco, CA, USA</p>                                   
                                    </div>
                                </div>                                
                            </div>
                            <!-- END CONTACT ITEM -->
                        </div>
                        <div class="col-md-3">
                            <!-- CONTACT ITEM -->

                            <div class="panel panel-default">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="assets/images/users/no-image.jpg" alt="Samuel Leroy Jackson"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">Alex Sonar</div>
                                        <div class="profile-data-title">Designer</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="pages-profile.html" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/>(213) 428-74-13</p>
                                        <p><small>Email</small><br/>alex@domain.com</p>
                                        <p><small>Address</small><br/>123 45 Street San Francisco, CA, USA</p>                                   
                                    </div>
                                </div>                                
                            </div>
                            <!-- END CONTACT ITEM -->
                        </div> 
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="pagination pagination-sm pull-right push-down-10 push-up-10">
                                <li class="disabled"><a href="#">«</a></li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>                                    
                                <li><a href="#">»</a></li>
                            </ul>                            
                        </div>
                    </div>

                </div>
                <!-- END PAGE CONTENT WRAPPER -->                                                 
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

        <!-- START PRELOADS -->
        <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
        <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
        <!-- END PRELOADS -->          
        
    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/settings.js"></script>
        
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>        
        <!-- END TEMPLATE -->

        <script type="text/javascript">
           /* var options = {
              valueNames: [ 'profile-data-name', 'profile-data-title' ]
          };
            var userList = new List('supplier', options);*/

            //------ Search the supplier -----//
              $('#supplier_list').keyup(function(){
                $.each($('#supplier').find('.col-md-3'), function(){
                    //alert('ga');
                    if($(this).text().toLowerCase().indexOf($('#supplier_list').val()) == -1){
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
            //------ Search the supplier end -----//

            //------ Load the content while scroll -----//
            $(window).scroll(function() {
            if($(window).scrollTop() == $(document).height() - $(window).height()) {
           // ajax call get data from server and append to the div
                }
            });
             //------ Load the content while scroll end -----//
        </script>
    <!-- END SCRIPTS -->         
   </body>

    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>





