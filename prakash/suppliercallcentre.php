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
        .load_page {
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

                .panel .panel-body {
                    padding: 15px 15px 15px 5px !important;
                    position: relative;
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
        <div  class = "load_page" id="load_page" style='display:none;padding:12% 40%;'></div> 
        <!-- START PAGE CONTAINER -->
        <div class="page-container page-navigation-toggled">

            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <? include 'lib/app_left_panel.php'; ?>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->

            <!-- PAGE CONTENT style="overflow: hidden;"-->
            <div class="page-content" >
            
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
                
               <?
              //$supplierCount = select_query_json("select count(*) as count from supplier where deleted = 'N' and SUPCODE>=7000","Centra","TCS");
              
               ?>                                
                
                <!-- PAGE TITLE -->
                <div class="page-title">                    
                   <h2><span class="fa fa-users"></span> Supplier Profile Contact <small></small></h2>
                </div>
                <!-- END PAGE TITLE -->                
                
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <p>Use search to find contacts. You can search by: name,code, mobile no.</p>
                                    <form class="form-horizontal" action="">
                                        <div class="form-group">
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="fa fa-search"></span>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Search " id="supplier_list" onchange ="myFunction(this)" onblur="loadback(this)" autocomplete="off" />
                                                   <div class="displayresult" style="display: none">
                                                       <h2><small><? echo ($resultCount[0]['COUNT']) ;?></small></h2>
                                                   </div><!-- end of .resultDiv -->
                                                  
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-primary" id="search_btn" type="submit">Search</button>
                                                    </div>
                                                    <!--<div class="input-group-btn">
                                                        <button class="btn btn-info pull-right"  id="asearch_btn" type="submit">Advanced Search</button>
                                                    </div>-->
                                                    <!--<div id="display"></div>-->
                                                </div>
                                            </div>
                                            <!--<div class="col-md-2">
                                                <button class="btn btn-success btn-block"><span class="fa fa-plus"></span> Add new contact</button>
                                            </div>-->
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
                    <style type="text/css">
                        .profile .profile-data .profile-data-name {
                                width: 100%;
                                height:30px;
                                float: left;
                                font-size: 12px;
                                font-weight: 400;
                                color: #FFF;
                            }
                            .profile .profile-data .profile-data-title {
                                width: 100%;
                                height:20px;
                                float: left;
                                font-size: 12px;
                                font-weight: 100;
                                color: #FFF;
                            }

                    </style>
                    <div class="row">
                        <div class="col-md-12" style="#fff">
                            <div id = "supplier">
                                <div id="load_data">
                                    
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
              $('#supplier_list').keyup(function(e){
                $.each($('#supplier').find('.col-md-3'), function(){
                    //alert('ga');

                    if($(this).text().toLowerCase().indexOf($('#supplier_list').val()) == -1){
                        $(this).hide();
                        //calldb();
                        var flag=1;
                    } else {

                        $(this).show();
                        
                    }         
                });
                var search = $('#supplier_list').val();

               // function calldb(){
                /*
                if(flag = 1){
                    //alert("hi");
                        $.ajax({ 
                           //AJAX type is "Post". 
                           type: "POST", 
                          
                           url: "prakash/supplier_list.php?action=textsearch", 
                           dataType:"text",
                           //Data, that will be sent  
                           data: { 
                               //Assigning value of "name" into "search" variable. 
                               search: search 
                           }, 
                           //If result found, this funtion will be called. 
                           success: function(html) { 
                               //Assigning result to "display" div in "search.php" file. 
                               //$("#display").html(html).show();
                               $("#display").append(html); 
                               //alert("text reeived");
                           } 
                     });
                
                }

                */
                /*
                function callbd(){
                clearTimeout($.data(this, 'timer'));
                if (e.keyCode == 13)
                  search_frm_db(true);
                else
                  $(this).data('timer', setTimeout(search_frm_db, 800));
                
                function search_frm_db(time){
                    var force;
                    var existingString = $("#supplier_list").val();
                     if (!force && existingString.length < 3) return; 
                    $.ajax({
                                type:'post',
                                data:{search:search},
                                url:"prakash/supplier_list.php?action=searchdb",
                                cache:false,
                               dataType:'text',
                               success:function(data)
                               {

                                $('#load_data').append(data);
                                $('.ajax').remove();
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                        alert('An error occurred... Try again!');
                                        //$("#load_page").fadeOut("fast");
                                        }

                            });
                 }
             }
               */
                /*
                 $.ajax({
                                type:'post',
                                data:{search:search},
                                url:"prakash/supplier_list.php?action=searchdb",
                                cache:false,
                               dataType:'text',
                               success:function(data)
                               {

                                $('#load_data').append(data);
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                        alert('An error occurred... Try again!');
                                        //$("#load_page").fadeOut("fast");
                                        }

                            });
                 */
            });

              function myFunction(){
                $("#load_data").hide();
              }

              function loadback(){
                $("#load_data").show();
              }

             
            //------ Search the supplier start -----//
            $(function() {
     

                       $("#search_btn").click(function(e){
                        $("#load_page").show();
                            //alert("hi");
                            var search = $('#supplier_list').val().toUpperCase();
                            //alert(search);

                           e.preventDefault();
                            $.ajax({
                                    type:'post',
                                    data:{search:search},
                                    url:"prakash/supplier_list.php?action=textsearch",
                                    async: true,
                                   dataType:'text',
                                   success:function(data)
                                   {
                                        if(data==''){
                                            alert("NO DATA FOUND!");
                                             $("#load_page").fadeOut("fast");
                                             $('#supplier_list').blur();
                                        } else{
                                            $('#load_data').append(data).show();
                                            $("#load_page").fadeOut("fast");
                                        $('#supplier_list').blur();
                                    }
                                    },
                                    cache:false,
                                    error: function(jqXHR, textStatus, errorThrown) {
                                            alert('An error occurred... Try again!');
                                            $("#load_page").fadeOut("fast");
                                            }

                                });
                            });
                       });

           //------ Search the supplier end -----//


             // Load content while scroll end position
             $(document).ready(function(){
                    var end =7012;
                     var limit = 13;
                     var start = 7000;
                     var action = 'inactive';
                     function load_supplier_data(limit, start)
                     {
                        
                      $.ajax({
                       url:"prakash/supplier_list.php",
                       method:"POST",
                       data:{limit:end, start:start},
                       cache:false,
                       dataType:'text',
                       success:function(data)
                       {

                       $('#load_data').append(data);

                            if(data == '')
                            {
                                alert("NO DATA!");
                             $('#load_data_message').html("<button type='button' class='btn btn-info'>No Data Found</button>");
                             action = 'active';
                            }
                            else
                            {
                             $('#load_data_message').html("<button type='button' class='btn btn-warning'>Please Wait....</button>");
                           // $('#load_data .ajax').remove();
                             action = "inactive";
                            }

                       },
                       error: function(jqXHR, textStatus, errorThrown) {
                                    alert('An error occurred... Try again!');
                                    //$("#load_page").fadeOut("fast");
                                    }

                      });
                     }

                     if(action == 'inactive')
                     {
                      action = 'active';
                      load_supplier_data(limit, start);
                     }
                     /*
                     $(window).scroll(function(){
                            //$(window).scrollTop() + $(window).height()
                      if(($(window).scrollTop() == $(document).height() + $(window).height() > $("#load_data").height()) && action == 'inactive')
                      {
                        console.log($(window).scrollTop());
                        console.log($(document).height());
                        console.log($(window).height());
                       action = 'active';
                       start = start + limit;
                       end = end+limit; 
                       setTimeout(function(){
                        
                        load_supplier_data(limit, start);
                       }, 1000);
                      }
                     });
                     */

                     $(window).on("scroll", function() {
                        var scrollHeight = $(document).height();
                        var scrollPosition = $(window).height() + $(window).scrollTop();
                        if ((scrollHeight - scrollPosition) / scrollHeight === 0 && action == 'inactive') {
                            // when scroll to bottom of the page
                        action = 'active';
                       start = start + limit;
                       end = end+limit; 
                       setTimeout(function(){
                        
                        load_supplier_data(limit, start);
                       }, 2000)
                        }
                    });
                     
                      
                    });
        </script>
    <!-- END SCRIPTS -->         
   </body>

    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>





