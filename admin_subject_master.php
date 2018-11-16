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

if($inner_menuaccess[0]['VEWVALU'] == 'Y') { // Menu Permission is allowed ?>
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

modal {
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
background.-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
.cus {
    background: #cce5ff;
    margin: 5px;
    list-style: none;
    padding: 10px 10px;
    border: 1px solid #666666;
}
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
    width: 30%;
    height :800px;
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
        .label {
            white-space: normal !important;
        }

[draggable] {
  -moz-user-select: none;
  -khtml-user-select: none;
  -webkit-user-select: none;
  user-select: none;
  /* Required to make elements draggable in old WebKit */
  -khtml-user-drag: element;
  -webkit-user-drag: element;
}

#columns {
  list-style-type: none;
}
#branches{
  list-style-type: none;
}

.column {
  color: black;
  background-color: #cce5ff;
  border-bottom: 1px solid #ddd;
  border: 1px solid #666666;
  padding: 10px 10px;
  cursor: move;
}


.column.dragElem {
  opacity: 0.4;
}
.column.over {
  //border: 2px dashed #000;
  border-top: 2px solid blue;
}

    </style>
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    

        <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
        <link href="css/jquery.filer.css" rel="stylesheet">
    <!-- EOF CSS INCLUDE -->
    </head>
    <body>
        <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> */ ?>
        <!-- START PAGE CONTAINER -->
        <div id="load_page" style='display:block;padding:12% 40%;'></div>
        <div class="page-container page-navigation-toggled">

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
                    <li class="active">Approval Request List</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Approval Request List</h3>
                        </div>
                        <div class="panel-body" style="overflow-x: scroll !important;">
                            <div class="form-group trbg non-printable">
                                <form role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                                    <div class="col-xs-2" style='text-align:center; padding:5px;'></div>

                                    <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 5px;'>
                                        <select class="form-control" required name='txt_aprtype' id='txt_aprtype' data-toggle="tooltip" data-placement="top" title="Approval Type">
                                            <? $sql_type = select_query_json("select * from trandata.approval_type@tcscentr where deleted = 'N' and ATYSRNO not in (2,3) order by ATYSRNO", "Centra", "TCS");
                                                foreach ($sql_type as $key => $typevalue) { ?>
                                                    <option value="<?=$typevalue['ATYCODE']?>" <? if($typevalue['ATYCODE'] == $txt_aprtype) { ?> selected <? } ?>><?=$typevalue['ATYNAME']?></option>
                                            <? } ?>
                                        </select>
                                    </div>

                                    <div class="col-xs-2" style='text-align:center; padding:5px;'>
                                        <input type='text' class="form-control" tabindex='2' name='search_value' id='search_value' value='<?=$_REQUEST['search_value']?>' data-toggle="tooltip" data-placement="top" maxlength="10" placeholder='Value' title="Value" style='text-transform: uppercase;'>
                                    </div>

                                    <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 6px;'>
                                        <input type='hidden' name='search_add_findate' id='search_add_findate' value='ADDDATE' >
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                    </div>

                                    <div class="col-xs-2" style='text-align:center; padding:5px;'>
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
                                    </div>

                                    <div class="col-xs-2" style='text-align:left; padding:5px;'>
                                        <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
                                    </div>
                                </form>
                            </div>
                            <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                            <table id="customers2" class="table datatable" style="overflow-x: scroll !important;">
                                <thead>
                                    <tr>
                                        <th style='text-align:center'>#</th>
                                        <th style='text-align:center'>Approval Type</th>
                                        <th style='text-align:center'>Target NO</th>
                                        <th style='text-align:center'>Department</th>
                                        <th style='text-align:center'>Approval Master</th>
                                        <th style='text-align:center'>Approval Leads</th>
                                        <th style='text-align:center'>Status</th>
                                        <th style='text-align:center'>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?  $and = "";
                                    if($txt_aprtype != '') {
                                        $and .= " And ar.APRNUMB like '%".strtoupper($txt_aprtype)."%' ";
                                    }

                                    // if($and != '') {
                                        $sql_project = select_query_json("select ATYNAME, TARNUMB, apmcode, APMNAME, decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT 
                                                                                    from trandata.approval_master@tcscentr mas, trandata.approval_type@tcscentr typ, trandata.empsection@tcscentr sub 
                                                                                    where mas.atycode = typ.atycode and sub.esecode(+) = mas.SUBCORE and sub.deleted(+) = 'N' and mas.deleted = 'N' and 
                                                                                        typ.deleted = 'N' and ( apmcode = 954 or apmcode = 955 or apmcode = 888 or apmcode = 856 or apmcode > 965) ".$and." AND ROWNUM<=10
                                                                                    order by ATYNAME, TARNUMB, DEPT, APMNAME, apmcode", "Centra", "TCS");
                                        echo("<pre>select ATYNAME, TARNUMB, apmcode, APMNAME, decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT 
                                                                                    from trandata.approval_master@tcscentr mas, trandata.approval_type@tcscentr typ, trandata.empsection@tcscentr sub 
                                                                                    where mas.atycode = typ.atycode and sub.esecode(+) = mas.SUBCORE and sub.deleted(+) = 'N' and mas.deleted = 'N' and 
                                                                                        typ.deleted = 'N' and ( apmcode = 954 or apmcode = 955 or apmcode = 888 or apmcode = 856 or apmcode > 965) ".$and." AND ROWNUM<=10
                                                                                    order by ATYNAME, TARNUMB, DEPT, APMNAME, apmcode\n");
                                        $i = 0;
                                        for($project_i = 0; $project_i < count($sql_project); $project_i++) { $i++; 
                                            if($sql_project[$project_i]['TARNUMB'] >= 9000) { // For Budget Approvals
                                                $sql_mh = select_query_json("select distinct apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode, apr.brnhdsr, 
                                                                                        regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch
                                                                                    from trandata.approval_branch_head@tcscentr apr, trandata.branch@tcscentr brn 
                                                                                    where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' and ROWNUM<=10 AND
                                                                                        apr.tarnumb = ".$sql_project[$project_i]['TARNUMB']." and apr.APRVALU > 0 and 0 < aprvalu
                                                                                    Order by apr.brncode, apr.brnhdsr", "Centra", "TCS");
                                                echo("<pre>=============select distinct apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode, apr.brnhdsr, 
                                                                                        regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch
                                                                                    from trandata.approval_branch_head@tcscentr apr, trandata.branch@tcscentr brn 
                                                                                    where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' and ROWNUM<=10 AND
                                                                                        apr.tarnumb = ".$sql_project[$project_i]['TARNUMB']." and apr.APRVALU > 0 and 0 < aprvalu
                                                                                    Order by apr.brncode, apr.brnhdsr===============\n");
                                                if(count($sql_mh) > 0) { 
                                                    $usr = ''; $brn = 0; unset($mhuser); $mhuser = array(); 
                                                    for($j = 0; $j < count($sql_mh); $j++)
                                                    {
                                                        if($brn != $sql_mh[$j]['BRNCODE']) {
                                                            if($j != 0) {
                                                                $usr .= "<br><br>";
                                                            }
                                                            unset($mhuser); // $mhuser is gone
                                                            $mhuser = array(); // $mhuser is here again
                                                            $usr .= "<span style='color:#FF0000; font-weight:bold;'>".$sql_mh[$j]['BRANCH']." :</span> ";
                                                        }
                                                        $brn = $sql_mh[$j]['BRNCODE'];
                                                        if(!in_array($sql_mh[$j]['EMPCODE'], $mhuser)){
                                                            $mhuser[] = $sql_mh[$j]['EMPCODE'];
                                                            $usr .= "<b>".$sql_mh[$j]['EMPCODE']." - ".$sql_mh[$j]['EMPNAME']."; </b>"; //"(".$sql_mh[$j]['APPDAYS']."); , ".$sql_mh[$j]['APPTITL']." ";
                                                        }
                                                    }
                                                    $usr = rtrim($usr,"; ");
                                                } 
                                            } else { // For Non-Budget Approvals
                                                $sql_mh = select_query_json("select * from trandata.approval_branch_head@tcscentr 
                                                                                    where apmcode = ".$sql_project[$project_i]['APMCODE']." and deleted = 'N'
                                                                                    order by brncode, brnhdsr", "Centra", "TCS");
                                                if(count($sql_mh) > 0) { 
                                                    $usr = ''; unset($mhuser); $mhuser = array(); 
                                                    for($j = 0; $j < count($sql_mh); $j++)
                                                    {
                                                        if(!in_array($sql_mh[$j]['EMPCODE'], $mhuser)){
                                                            $mhuser[] = $sql_mh[$j]['EMPCODE'];
                                                            $usr .= "<b>".$sql_mh[$j]['EMPCODE']." - ".$sql_mh[$j]['EMPNAME']."; </b>"; //"(".$sql_mh[$j]['APPDAYS']."); , ".$sql_mh[$j]['APPTITL']." ";
                                                        }
                                                    }
                                                    $usr = rtrim($usr,"; ");
                                                }
                                            }

                                        if(count($sql_mh) > 0) { ?>
                                            <tr>
                                                <td style='text-align:center'><?=($i)?></td>
                                                <td style='text-align:left;'><? if($sql_project[$project_i]['ATYNAME'] == 'FIXED BUDGET') { echo 'BUDGET'; } else { echo $sql_project[$project_i]['ATYNAME']; } ?></td>
                                                <td style='text-align:center;'><? if($sql_project[$project_i]['TARNUMB'] >= 9000) { echo $sql_project[$project_i]['TARNUMB']; } else { echo "-"; } ?></td>
                                                <td style='text-align:left;'><?=$sql_project[$project_i]['DEPT']?></td>
                                                <td style='text-align:left;'><?=$sql_project[$project_i]['APMNAME']?></td>
                                                <td style='text-align:left;'><?=$usr?></td>
                                                <td style='text-align:center; white-space: nowrap;'><? /* ($sql_mh[0]['DELETED'] == 'N')?'Active':'Deleted'*/ ?>Active</td>
                                                <td style='text-align:center; white-space: nowrap;'><a id="edit" title='Edit' alt='Edit' onclick="edit('<? if($sql_project[$project_i]['TARNUMB'] >= 9000) { echo $sql_project[$project_i]['TARNUMB']; } else { echo "-"; } ?>')"><i class="fa fa-edit"></i> </a> / <a href='admin_mode_hierarchy.php?action=view&reqid=<? echo $sql_project[$project_i]['APMCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i></a> / <a href='javascript:void(0)' id="delete_confirm" onclick="call_confirm(<?=($i)?>, 'admin_mode_hierarchy.php?action=deleted&reqid=<? echo $sql_project[$project_i]['APMCODE']; ?>')" title='Delete' alt='Delete'><i class="fa fa-trash-o"></i></a></td>
                                            </tr>
                                    <?  } } 

                                    /* } else { ?>
                                        <tr><td colspan="8" style="color: #FF0000; font-weight: bold;">No Records Found..</td></tr>
                                    <? } */ ?>

                                </tbody>
                            </table>

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

        <!-- //////////////// -->
        <?
        $sql_brn = select_query_json("select distinct b.brncode,b.brnname from branch b,approval_branch_head ab where b.brncode=ab.brncode order by brncode", "Centra", "TEST");
        ?>
       
            
        <div id="myModal" class="modal">
            <style type="text/css">
                .ui-front {
                    z-index: 9999;
                }
            </style>
          <!-- Modal content -->
          <span class="close">&times;</span>
          <div id="modal_data" class="modal-content">
            <p><?//echo("<pre>")?><?//print_r($sql_cor);?></p>
            <!-- //////////////////// -->
            <h3>Tar No : <span id="modal_title" name="tarnumb" style="font-size: 20px;color: red;"></span></h3>
            <div class="panel panel-default tabs">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="false">INSERT FLOW</a></li>
                    <li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false">ALTER FLOW</a></li>
                    <li class=""><a href="#tab3" data-toggle="tab" aria-expanded="false">BRANCH FLOW</a></li>
                </ul>
                <div class="panel-body tab-content">

                    <div class="tab-pane active" id="tab1">
                    </div>
                    <!-- 222222222222222222222222222 -->
                    <div class="tab-pane" id="tab2">
                    </div>
                    <!-- 3333333333333333333333333333 -->
                    <div class="tab-pane" id="tab3">
                         
                         <div class="input-group" style="margin:10px">                                   
                                <select class="form-control custom-select chosn" autofocus tabindex='1' required id="branchname" name="branchname" onchange="branchload();">
                                    <option value="">Choose branch</option>
                                    <?for($k=0;$k<sizeof($sql_brn);$k++){?>
                                    <option value="<?=$sql_brn[$k]['BRNCODE']?>"><?=$sql_brn[$k]['BRNNAME']?></option>
                                    <?}?>
                                 </select>
                                <span class="input-group-btn" style="background-color: black">
                                    <button class="btn btn-default" type="button" style="background-color: black"><span  style="background-color: black;color: white;">Branch</span></button>
                                </span>                                    
                            </div>
                         <div id="branch">

                         </div>
                    </div> 
                    <!-- 22222222222222222222222222-->
                </div>
            </div>
            <!-- /////////////////// -->

          </div>
        </div>
       
        <!-- ////////////////// -->

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
        
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->
        <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

    <!-- THIS PAGE PLUGINS -->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
<!--     <script type="text/javascript" src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> -->
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
        <script type="text/javascript">
            var sql_data=0;

            console.log(sql_data);
            /////////////////////drag
            var dragSrcEl = null;

            function handleDragStart(e) {
              // Target (this) element is the source node.
              dragSrcEl = this;
              $(this).css('background','#ce7676');
              //console.log("hi");
              e.dataTransfer.effectAllowed = 'move';
              e.dataTransfer.setData('text/html', this.outerHTML);

              this.classList.add('dragElem');
            }
            function handleDragOver(e) {
              if (e.preventDefault) {
                e.preventDefault(); // Necessary. Allows us to drop.
              }
              this.classList.add('over');

              e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

              return false;
            }

            function handleDragEnter(e) {
              // this / e.target is the current hover target.
            }

            function handleDragLeave(e) {
              this.classList.remove('over');
            }

            function handleDrop(e) {
              // this/e.target is current target element.
              
              //this.classList.remove('dragElem');
              if (e.stopPropagation) {
                e.stopPropagation(); // Stops some browsers from redirecting.
              }
              if (dragSrcEl != this) {
                this.parentNode.removeChild(dragSrcEl);
                var dropHTML = e.dataTransfer.getData('text/html');
                this.insertAdjacentHTML('beforebegin',dropHTML);
                var dropElem = this.previousSibling;
                addDnDHandlers(dropElem);
              }
              this.classList.remove('over');
              return false;
            }

            function handleDragEnd(e) {
              this.classList.remove('over');
              this.classList.remove('dragElem');

            }

            function addDnDHandlers(elem) {
              elem.addEventListener('dragstart', handleDragStart, false);
              elem.addEventListener('dragenter', handleDragEnter, false)
              elem.addEventListener('dragover', handleDragOver, false);
              elem.addEventListener('dragleave', handleDragLeave, false);
              elem.addEventListener('drop', handleDrop, false);
              elem.addEventListener('dragend', handleDragEnd, false);

            }

            // var cols = document.querySelectorAll('#columns .column');
            // [].forEach.call(cols, addDnDHandlers);
            // var cols1 = document.querySelectorAll('#branches .column');
            // [].forEach.call(cols1, addDnDHandlers);

            function alterflow(){
                var nums = document.getElementById("columns");
                var listItem = nums.getElementsByTagName("li");
                var newNums = [];
                for (var i=0; i < listItem.length; i++) {
                    var str=listItem[i].innerHTML;
                    str=str.split(' - ');
                    str=str[0].split(') ');
                    console.log(str[1]);
                    newNums.push( parseInt(str[1], 10 ) );
                    //console.log(listItem[i].innerHTML);
                }
                console.log(newNums);
                modal.style.display = "none";
                var tarnumb=$('#modal_title').html();
                $('#load_page').fadeIn('fast');
                $.ajax({
                    url:'viki/admin_subject_update.php',
                    //url:"viki/post_test",
                    type: "POST",
                    data:{
                        action:'alter',
                        tarnumb:tarnumb,
                        newflow:newNums
                    },
                    complete:function(xhr, status, thrown){
                        // console.log(xhr);
                        // console.log(status);
                        // console.log(thrown);
                        alterload(tarnumb);
                        //window.location.reload();
                        //$('#load_page').fadeOut('slow');
                         
                    }
                });
            }

            /////////////////////drag
            /////////////////////branch
            function branchload(){
                //alert("load");
                //console.log("load");
                $('#load_page').fadeIn('fast');
                var tarnumb=$('#modal_title').html();
                var branch=$('#branchname').val();
                $.ajax({
                    url:'viki/admin_subject_update.php',
                    type: "POST",
                    dataType:'html',
                    data:{
                        action:'branchload',
                        tarnumb:tarnumb,
                        branch:branch
                    },
                    success:function(data){
                        $('#branch').html(data);
                        var cols1 = document.querySelectorAll('#branches .column');
                        [].forEach.call(cols1, addDnDHandlers);
                        $('#load_page').fadeOut('slow');
                    }
                });
            }
            function branchalter(){
                //alert("load");
                //console.log("load");
                var nums = document.getElementById("branches");
                var listItem = nums.getElementsByTagName("li");
                var newNums = [];
                for (var i=0; i < listItem.length; i++) {
                    var str=listItem[i].innerHTML;
                    str=str.split(' - ');
                    str=str[0].split(') ');
                    console.log(str[1]);
                    newNums.push( parseInt(str[1], 10 ) );
                }
                console.log(newNums);
                modal.style.display = "none";
                var branch=$('#branchname').val();
                $('#load_page').fadeIn('fast');
                var tarnumb=$('#modal_title').html();
                $.ajax({
                    url:'viki/admin_subject_update.php',
                    type: "POST",
                    data:{
                        action:'branchalter',
                        branch:branch,
                        tarnumb:tarnumb,
                        newflow:newNums
                    },
                    complete:function(xhr, status, thrown){
                        // console.log(xhr);
                        // console.log(status);
                        // console.log(thrown);
                        $('#branch').html('');
                        //window.location.reload();
                        //$('#load_page').fadeOut('slow');
                         
                    }
                });
            }
            /////////////////////branch
            var modal = document.getElementById('myModal');
            var btn = document.getElementById("view_msg");
            var span = document.getElementsByClassName("close")[0];
             
                   $('#txt_value1').autocomplete({
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
                    
                    $("#txt_value1").val('');
                    $("#txt_value1").focus(); 
                } 
                },
                  autoFocus: true,
                  minLength: 0
                });

              function callemp(txt){
                
                $('#txt_value'+txt).autocomplete({
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
                    
                    $("#txt_value"+txt).val('');
                    $("#txt_value"+txt).focus(); 
                } 
                },
                  autoFocus: true,
                  minLength: 0
                });
              }
        function nsubmit()
        {   //console.log($("#sempsrno").val());
            
            var err=0;
            if($("#sempsrno").val()=='')
            {
                err=1;
            }
            $(".txt_mem").each(function(){
                if($(this).val()=='')
                {
                    err=1;
                }
                for(var key in sql_data)
                {   var val=$(this).val();
                    
                    var str=sql_data[key].EMPCODE+' - '+sql_data[key].EMPNAME;
                   //console.log(str);
                   if(str==val)
                    {//console.log('true');
                        //console.log(val);
                        err=2;
                        
                    }
                }
                
            });
            $(".txt_mem").each(function(){
                var thisval=$(this).val();
                var thisid=this.id;
                //console.log(thisid);
                $(".txt_mem").each(function(){
                    var nval=$(this).val();
                    if(thisval==nval && this.id!=thisid)
                    {   //console.log(thisval);
                        err=2;
                    }
                });

            });
            if(err==0)
            {
            modal.style.display = "none";
            $("#load_page").fadeIn('slow');
              var form_data = new FormData(document.getElementById("mainform"));
              $.ajax({
             url:"viki/admin_subject_update.php",
              //url:"viki/post_test.php",
              type: "POST",
              data: form_data,
              processData: false,
              contentType: false,
              async:true,
              }).done(function(data)
              {
                  console.log(data);
                  $("#load_page").fadeOut("slow");
                  window.location.reload();
                  
              });   
            }
            else if(err==1){
                alert("field marked * is mandatory");
            }
            else if(err==2)
            {
                alert("USER alredy exist");
            }
            $("#load_page").fadeOut('slow');
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
       function edit(title){
            $('#modal_title').html(title);
            corpload(title);
            alterload(title);

            //$('#load_page').fadeIn('fast');
                //var tarnumb=$('#modal_title').html();
                //$('#load_page').fadeIn('fast');
                //var tarnumb=$('#modal_title').html();
                //var branch=$('#branchname').val();
               
                
            console.log(title);
            modal.style.display = "block";

        }
        function alterload(title)
        {
            //$('#load_page').fadeIn('fast');
                $.ajax({
                    url:'viki/admin_subject_update.php',
                    type: "POST",
                    dataType:'html',
                    data:{
                        action:'alterload',
                        tarnumb:title
                    },
                    success:function(data){
                        $('#tab2').html(data);
                        var cols1 = document.querySelectorAll('#columns .column');
                        [].forEach.call(cols1, addDnDHandlers);
                        $('#load_page').fadeOut('slow');
                    }
                });
        }
        function corpload(title)
        {
            $.ajax({
                    url:'viki/admin_subject_update.php',
                    type: "POST",
                    dataType:'json',
                    data:{
                        action:'dataload',
                        tarnumb:title
                    },
                    success:function(data){
                        sql_data=data;
                        //console.log(sql_data);
                        //console.log(Object.keys(sql_data).length);
                    }
                });

                $.ajax({
                    url:'viki/admin_subject_update.php',
                    type: "POST",
                    dataType:'html',
                    data:{
                        action:'corpload',
                        tarnumb:title,
                        //branch:branch
                    },
                    success:function(data){
                        $('#tab1').html(data);
                        $('#tarnumb').val(title);
                        $('#sempsrno').val(sql_data[0].EMPCODE);
                        $('#0').css("background","#ce7676");
                        //console.log(sql_data[0].EMPCODE);
                        $(".cus").click(function(){
                          //console.log($(this).attr('id'));
                          selected=$(this).attr('id');
                          $('.cus').each(function(){
                            $(this).css("background","#cce5ff");
                          });
                          $(this).css("background","#ce7676");
                          $("#sempsrno").val($(this).html());
                        });
                        $("#up").click(function (){
                            if(selected==0)
                          { selected=Object.keys(sql_data).length;
                          }
                          else{
                          selected=selected-1;
                          }
                           $('.cus').each(function(){
                            $(this).css("background","#cce5ff");
                          });
                          //console.log($("#"+selected).attr('id'));
                          $("#"+selected).css("background","#ce7676");
                          $("#sempsrno").val($("#"+selected).html());
                        });
                        $("#down").click(function(){
                            if(selected==Object.keys(sql_data).length)
                            selected=0;
                          else
                           selected=parseInt(selected)+1;
                          $('.cus').each(function(){
                            $(this).css("background","#cce5ff");
                          });
                          //console.log(selected);
                          $("#"+selected).css("background","#ce7676");
                          $("#sempsrno").val($("#"+selected).html());
                        });
                        $('#txt_value1').autocomplete({
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
                            
                            $("#txt_value1").val('');
                            $("#txt_value1").focus(); 
                        } 
                        },
                          autoFocus: true,
                          minLength: 0
                        });
                    }
                });
        }
        span.onclick = function() {
            modal.style.display = "none";
              //$("#modal_data").html(' ');
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                //$("#modal_data").html(' ');
            }
        }

        var selected=1;

        $(".cus").click(function(){
          //console.log($(this).attr('id'));
          selected=$(this).attr('id');
          $('.cus').each(function(){
            $(this).css("background","#cce5ff");
          });
          $(this).css("background","#ce7676");
          $("#sempsrno").val($(this).html());
        });
        $("#up").click(function (){
            if(selected==0)
          { selected=parseInt(<?=sizeof($sql_cor);?>);
          }
          else{
          selected=selected-1;
          }
           $('.cus').each(function(){
            $(this).css("background","#cce5ff");
          });
          //console.log($("#"+selected).attr('id'));
          $("#"+selected).css("background","#ce7676");
          $("#sempsrno").val($("#"+selected).html());
        });
        $("#down").click(function(){
            if(selected==parseInt(<?=sizeof($sql_cor);?>))
            selected=0;
          else
           selected=parseInt(selected)+1;
          $('.cus').each(function(){
            $(this).css("background","#cce5ff");
          });
          //console.log(selected);
          $("#"+selected).css("background","#ce7676");
          $("#sempsrno").val($("#"+selected).html());
        });
        var num=1;
         function removel(i)
        {      console.log(i);
             $('#l'+i).remove();
        }
        function add_feild() {
        num++;
        $('#add_filed').prepend(
              '<div id="l'+num+'">'+
                '<div style="width:100%;">'+
                    '<div style="width:90%;float:left;padding:10px 0px;">'+
                        '<input type="text" name = "txt_value[]"" class="form-control  txt_mem" id="txt_value'+num+'" onfocus="javascript:callemp('+num+')" placeholder= "NAME" style="text-transform: uppercase;" data-toggle ="tooltip" title ="values" required>'+
                    '</div>'+
                     '<span style="width: 10%;float:right;padding:10px 0px;" class="input-group-btn"><button id="add_ledger_button" type="button" class="btn btn-danger btn-remove"  onclick="javascript:removel('+num+')" title ="remove">-</button></span>'+
                '</div>'+
              '</div>'
              );
        }
        function removebranch(brnhdsr,empcode)
        {   modal.style.display='none';
            $('load_page').fadeIn('fast');
            var branch=$('#branchname').val();
            var tarnumb=$('#modal_title').html();
            $.ajax({
                    url:'viki/admin_subject_update.php',
                    type: "POST",
                    dataType:'html',
                    data:{
                        action:'removebranch',
                        tarnumb:tarnumb,
                        branch:branch,
                        empcode:empcode,
                        brnhdsr:brnhdsr
                    },
                    success:function(data){
                        alert('Deleted');
                        $('load_page').fadeOut('slow');
                        $('#branch').html('');
                        modal.style.display='none';

                        //ql_data=data;
                        //console.log(sql_data);
                        //console.log(Object.keys(sql_data).length);
                    }
                });
        }
        function removecorp(brnhdsr,empcode)
        {   modal.style.display='none';
            $('load_page').fadeIn('fast');
            var tarnumb=$('#modal_title').html();
            $.ajax({
                    url:'viki/admin_subject_update.php',
                    type: "POST",
                    dataType:'html',
                    data:{
                        action:'removecorp',
                        tarnumb:tarnumb,
                        empcode:empcode,
                        brnhdsr:brnhdsr
                    },
                    success:function(data){
                        alert('Deleted');
                        $('load_page').fadeOut('slow');
                        //window.location.reload();
                        $('#branch').html('');

                        //ql_data=data;
                        //console.log(sql_data);
                        //console.log(Object.keys(sql_data).length);
                    }
                });
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
