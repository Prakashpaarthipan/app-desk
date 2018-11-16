<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');

/* include('../approval_desk-ftp/lib/config.php');
include('../db_connect/public_functions.php');
include('../approval_desk-ftp/general_functions.php'); */
extract($_REQUEST);
function validateDate($date, $format = 'd-M-Y')
{
    $d = DateTime::createFromFormat($format, $date);   
    return $d && $d->format($format) === $date;
}

function getday($day, $monthyear)
{
    //$month=substr($monthyear,0,-4);
   // $year=substr($monthyear,-4);
    $date=$day.'-'.$monthyear;
    $checkdate=validateDate($date, $format = 'd-M-Y');
    if($checkdate)
    {
        $day=date('D',strtotime($date));
    }
    else
    {
        $day="Not Valid";
    }
    return $day;
}
if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script> 
<?php 
}
$sql_search=select_query_json("select brn.brnname branch,ehead.empcode||'-'||ehead.empname hname,emp.empcode||'-'||emp.empname ename,
max(decode(to_char(gfix.adddate,'dd'),'01',gfix.empgrad,'-')) D01,max(decode(to_char(gfix.adddate,'dd'),'02',gfix.empgrad,'-')) D02,
max(decode(to_char(gfix.adddate,'dd'),'03',gfix.empgrad,'-')) D03,max(decode(to_char(gfix.adddate,'dd'),'04',gfix.empgrad,'-')) D04,
max(decode(to_char(gfix.adddate,'dd'),'05',gfix.empgrad,'-')) D05,max(decode(to_char(gfix.adddate,'dd'),'06',gfix.empgrad,'-')) D06,
max(decode(to_char(gfix.adddate,'dd'),'07',gfix.empgrad,'-')) D07,max(decode(to_char(gfix.adddate,'dd'),'08',gfix.empgrad,'-')) D08,
max(decode(to_char(gfix.adddate,'dd'),'09',gfix.empgrad,'-')) D09,max(decode(to_char(gfix.adddate,'dd'),'10',gfix.empgrad,'-')) D10,
max(decode(to_char(gfix.adddate,'dd'),'11',gfix.empgrad,'-')) D11,max(decode(to_char(gfix.adddate,'dd'),'12',gfix.empgrad,'-')) D12,
max(decode(to_char(gfix.adddate,'dd'),'13',gfix.empgrad,'-')) D13,max(decode(to_char(gfix.adddate,'dd'),'14',gfix.empgrad,'-')) D14,
max(decode(to_char(gfix.adddate,'dd'),'15',gfix.empgrad,'-')) D15,max(decode(to_char(gfix.adddate,'dd'),'16',gfix.empgrad,'-')) D16,
max(decode(to_char(gfix.adddate,'dd'),'17',gfix.empgrad,'-')) D17,max(decode(to_char(gfix.adddate,'dd'),'18',gfix.empgrad,'-')) D18,
max(decode(to_char(gfix.adddate,'dd'),'19',gfix.empgrad,'-')) D19,max(decode(to_char(gfix.adddate,'dd'),'20',gfix.empgrad,'-')) D20,
max(decode(to_char(gfix.adddate,'dd'),'21',gfix.empgrad,'-')) D21,max(decode(to_char(gfix.adddate,'dd'),'22',gfix.empgrad,'-')) D22,
max(decode(to_char(gfix.adddate,'dd'),'23',gfix.empgrad,'-')) D23,max(decode(to_char(gfix.adddate,'dd'),'24',gfix.empgrad,'-')) D24,
max(decode(to_char(gfix.adddate,'dd'),'25',gfix.empgrad,'-')) D25,max(decode(to_char(gfix.adddate,'dd'),'26',gfix.empgrad,'-')) D26,
max(decode(to_char(gfix.adddate,'dd'),'27',gfix.empgrad,'-')) D27,max(decode(to_char(gfix.adddate,'dd'),'28',gfix.empgrad,'-')) D28,
max(decode(to_char(gfix.adddate,'dd'),'29',gfix.empgrad,'-')) D29,max(decode(to_char(gfix.adddate,'dd'),'30',gfix.empgrad,'-')) D30,
max(decode(to_char(gfix.adddate,'dd'),'31',gfix.empgrad,'-')) D31,
max(decode(to_char(gfix.adddate,'dd'),'01',gfix.emprmrk,'-')) R01,max(decode(to_char(gfix.adddate,'dd'),'02',gfix.emprmrk,'-')) R02,
max(decode(to_char(gfix.adddate,'dd'),'03',gfix.emprmrk,'-')) R03,max(decode(to_char(gfix.adddate,'dd'),'04',gfix.emprmrk,'-')) R04,
max(decode(to_char(gfix.adddate,'dd'),'05',gfix.emprmrk,'-')) R05,max(decode(to_char(gfix.adddate,'dd'),'06',gfix.emprmrk,'-')) R06,
max(decode(to_char(gfix.adddate,'dd'),'07',gfix.emprmrk,'-')) R07,max(decode(to_char(gfix.adddate,'dd'),'08',gfix.emprmrk,'-')) R08,
max(decode(to_char(gfix.adddate,'dd'),'09',gfix.emprmrk,'-')) R09,max(decode(to_char(gfix.adddate,'dd'),'10',gfix.emprmrk,'-')) R10,
max(decode(to_char(gfix.adddate,'dd'),'11',gfix.emprmrk,'-')) R11,max(decode(to_char(gfix.adddate,'dd'),'12',gfix.emprmrk,'-')) R12,
max(decode(to_char(gfix.adddate,'dd'),'13',gfix.emprmrk,'-')) R13,max(decode(to_char(gfix.adddate,'dd'),'14',gfix.emprmrk,'-')) R14,
max(decode(to_char(gfix.adddate,'dd'),'15',gfix.emprmrk,'-')) R15,max(decode(to_char(gfix.adddate,'dd'),'16',gfix.emprmrk,'-')) R16,
max(decode(to_char(gfix.adddate,'dd'),'17',gfix.emprmrk,'-')) R17,max(decode(to_char(gfix.adddate,'dd'),'18',gfix.emprmrk,'-')) R18,
max(decode(to_char(gfix.adddate,'dd'),'19',gfix.emprmrk,'-')) R19,max(decode(to_char(gfix.adddate,'dd'),'20',gfix.emprmrk,'-')) R20,
max(decode(to_char(gfix.adddate,'dd'),'21',gfix.emprmrk,'-')) R21,max(decode(to_char(gfix.adddate,'dd'),'22',gfix.emprmrk,'-')) R22,
max(decode(to_char(gfix.adddate,'dd'),'23',gfix.emprmrk,'-')) R23,max(decode(to_char(gfix.adddate,'dd'),'24',gfix.emprmrk,'-')) R24,
max(decode(to_char(gfix.adddate,'dd'),'25',gfix.emprmrk,'-')) R25,max(decode(to_char(gfix.adddate,'dd'),'26',gfix.emprmrk,'-')) R26,
max(decode(to_char(gfix.adddate,'dd'),'27',gfix.emprmrk,'-')) R27,max(decode(to_char(gfix.adddate,'dd'),'28',gfix.emprmrk,'-')) R28,
max(decode(to_char(gfix.adddate,'dd'),'29',gfix.emprmrk,'-')) R29,max(decode(to_char(gfix.adddate,'dd'),'30',gfix.emprmrk,'-')) R30,
max(decode(to_char(gfix.adddate,'dd'),'31',gfix.emprmrk,'-')) R31, 
sum(decode(gfix.empgrad,'A++',1,0)) Adoubleplus_grade ,sum(decode(gfix.empgrad,'A+',1,0)) Aplus_grade ,sum(decode(gfix.empgrad,'A',1,0)) A_grade ,sum(decode(gfix.empgrad,'B',1,0)) B_grade ,sum(decode(gfix.empgrad,'C',1,0)) C_grade ,count(*) total_
from employee_grade_fix gfix,employee_office emp,employee_office ehead,branch brn
where gfix.empsrno=emp.empsrno and gfix.emphdsr=ehead.empsrno and emp.brncode=brn.brncode and gfix.deleted='N' and to_char(gfix.adddate,'Mon-YYYY')='".$_REQUEST['search_fromdate']."'
group by brn.brnname,ehead.empcode||'-'||ehead.empname ,emp.empcode||'-'||emp.empname ,ehead.empcode 
order by ehead.empcode","Centra", "TEST");
  

/* $menu_name = 'EMPLOYEE GRADE FIX';
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
} */

$join=select_query_json("select hd.GRDFXNO, ef.EMPSRNO, ef.EMPCODE, ef.EMPNAME, hd.EMPGRAD, hd.EMPRMRK, to_char(hd.ADDDATE,'dd-MON-yyyy') HDADDDATE, 
                                    (select EMPCODE||' - '||EMPNAME from employee_office where empsrno = HD.emphdsr) HDUSER
                                from EMPLOYEE_GRADE_FIX HD, employee_office ef 
                                where HD.empsrno = ef.empsrno and HD.emphdsr='".$_SESSION['tcs_empsrno']."' and hd.deleted='N' 
                                order by HDADDDATE desc, ef.EMPSRNO", "Centra", "TCS");
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title>My Team Grade Fix Report :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->
    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->
     <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/jquery-customselect.css" rel="stylesheet" />
    <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
    <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
    <!-- multiple file upload -->
    <link href="css/jquery.filer.css" rel="stylesheet">
    <script src="js/angular.js"></script>
	<style>
#customers2 {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#customers2 td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers2 tr:nth-child(even){background-color: #f2f2f2;}

#customers2 tr:hover {background-color: #ddd;}

#customers2 th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}

.col-lg-12 {
		padding-right:0px;
		}
	.panel-body {
		padding: 10px !important;
		margin: 4px 4px 12px 0;

	}
	
	.table {
		margin-bottom: 0px;
		}
	#page-wrapper { 
	position: relative;
    float: right;
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

	.dataTables_length {
			width:40%;
			float:left;
			padding-right: 5px;
		    padding: 0px 0px 5px;
			border-bottom: none !important;
			font-size: 15px;
		}

	.dataTables_filter {
		width: 50%;
		float: right;
		padding-left: 5px;
		padding: 0px 0px 5px;
		border-bottom: none !important;
		font-size: 15px;
	}
	select.input-sm
	{
		height:40px;
	}
element.style {
    top: 3.5px;
    left: 230px;
	
}
button.Zebra_DatePicker_Icon {
    display: block;
    position: absolute;
    width: 28px;
    height: 16px;
    background: url(../images/calendar.png) no-repeat left top;
    text-indent: -9000px;
    z-index: 10;
    border: none;
    cursor: pointer;
    padding: 0;
    line-height: 0;
    vertical-align: top;
}
</style>

	
</head>
<body>

        <script type="text/javascript" src="js/jquery.js"></script>
		<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
         <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
		<script type="text/javascript">
       $(document).ready(function() {
      $('#dataTables-example').dataTable();
});
	function nsubmit(action){
		// alert("uable to call");
			var vurl = "task_details.php";
        //var vurl="viki/post_test.php";

			$.ajax({
            type: "POST",
            url: vurl,
			data:{
				txt_task_details:$("#txt_task_details").val(),
				txt_task_from_time:$("#txt_task_from_time").val(),
				txt_task_to_time:$("#txt_task_to_time").val(),
                tassrno: $('#tassrno').val(),
                sbmt_update:action
				//'txt_employee_grade':$("#txt_employee_grade").val(),
			},
				
			dataType:'html',
            success: function(data1) {
               alert(data1);
            },
			error: function(response, status, error)
			{		alert(error);
					//alert(response);
					//alert(status);
			}
			});
	}
function update_detail(){
 		var vurl = "task_details.php?sbmt_update=update";
	
 		$.ajax({
             type: "POST",
             url: vurl,
 			data:{

 				'txt_task_details':$("#txt_task_details").val(),
				'txt_task_from_time':$("#txt_task_from_time").val(),
				'txt_task_to_time':$("#txt_task_to_time").val(),
				
 			},
 			dataType:'html',
             success: function(data1) {
              alert("updated successfully");
},
 			error: function(response, status, error)
 			{		
                    alert(error);
					//alert(response);
 					//alert(status);
 			}
 			});

 	}

	
</script>

   
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
                <li class="active">Employee Grade Fix</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->	
<div class="row">
                <div class="col-md-12">			
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">MY TEAM EMPLOYEES GRADE REPORT</h3><br>

                        <div class="pull-right">
                            <?  $menu_name = 'EMPLOYEE GRADE MASTER';
                          //  echo "select * from srm_menu_access
                                                                                    //where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' 
                                                                                   // order by MNUCODE Asc";
                                $inner_submenu = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc", "Centra", 'TCS');
                                if($_SESSION['tcs_empsrno'] != '') {
                                    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' 
                                                                                    order by MNUCODE Asc", "Centra", 'TCS');
                                } else {
                                    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and SUPCODE = '".$_SESSION['tcs_userid']."' 
                                                                                    order by MNUCODE Asc", "Centra", 'TCS');
                                }
                               ?>
							   <a class="btn btn-danger" target="_blank" style="margin-right: 5px;" href="employee_add.php">(+) Add Employees</a>&nbsp;&nbsp;<a class="btn btn-danger" target="_blank" style="margin-right: 50px;" href="employee_grade_fix1.php">Grade Fix</a><br><br>
                        </div>
                    </div>

						 <div class="panel-body">
						 <div class="panel-body" style="overflow-x: scroll !important;">
                            <div class="form-group trbg non-printable">
                                <form role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                                    <div class="col-xs-2" style='text-align:center; padding:5px;'></div>

                                     <?/* <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 0;'>
                                        
										<input type='text' class="form-control" tabindex='1' autofocus name='search_subject' id='search_subject' value='<?=$_REQUEST['search_subject']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Details' title="Details" style='text-transform: uppercase;'>
                                    </div>  */?>

                                   
                                    <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 6px;'>
                                        <input type='hidden' name='search_add_findate' id='search_add_findate' value='ADDDATE' >
								
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" value="<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; }  ?>" autocomplete="off" readonly maxlength="11" tabindex='4'  data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
										
										
                                    </div>
								

                            <!--        <div class="col-xs-2" style='text-align:center; padding:5px;'>
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
										<button type="button" class="Zebra_DatePicker_Icon Zebra_DatePicker_Icon_Inside" style="top: 7px; left: 247px;">Pick a date</button>
                                    </div>   -->

                                    <div class="col-xs-2" style='text-align:left; padding:5px;'>
                                        <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
                                    </div>
                                </form>
                            </div>
                            <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                       <h2 style="text-align: center;"><b>THE CHENNAI SILKS</b></h2>
                        <table id="customers2" class="test" border="1">
					
                            <thead class="test">
                                <tr>
                                    
                                    <th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="DEPARTMENT HEAD">DEPARTMENT HEAD</th>
                                    <th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="TEAM MEMBERS">TEAM MEMBERS</th>
                                    <th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="01 / <?php echo getday('01', $_REQUEST['search_fromdate']); ?>">D01 </th>
                                    <th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="02 / <?php echo getday('02', $_REQUEST['search_fromdate']); ?>">D02</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="03 / <?php echo getday('03', $_REQUEST['search_fromdate']); ?>">D03</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="04 / <?php echo getday('04', $_REQUEST['search_fromdate']); ?>">D04</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="05 / <?php echo getday('05', $_REQUEST['search_fromdate']); ?>">D05</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="06 / <?php echo getday('06', $_REQUEST['search_fromdate']); ?>">D06</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="07 / <?php echo getday('07', $_REQUEST['search_fromdate']); ?>">D07</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="08 / <?php echo getday('08', $_REQUEST['search_fromdate']); ?>">D08</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="09 / <?php echo getday('09', $_REQUEST['search_fromdate']); ?>">D09</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="10 / <?php echo getday('10', $_REQUEST['search_fromdate']); ?>">D10</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="11 / <?php echo getday('11', $_REQUEST['search_fromdate']); ?>">D11</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="12 / <?php echo getday('12', $_REQUEST['search_fromdate']); ?>">D12</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="13 / <?php echo getday('13', $_REQUEST['search_fromdate']); ?>">D13</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="14 / <?php echo getday('14', $_REQUEST['search_fromdate']); ?>">D14</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="15 / <?php echo getday('15', $_REQUEST['search_fromdate']); ?>">D15</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="16 / <?php echo getday('16', $_REQUEST['search_fromdate']); ?>">D16</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="17 / <?php echo getday('17', $_REQUEST['search_fromdate']); ?>">D17</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="18 / <?php echo getday('18', $_REQUEST['search_fromdate']); ?>">D18</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="19 / <?php echo getday('19', $_REQUEST['search_fromdate']); ?>">D19</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="20 / <?php echo getday('20', $_REQUEST['search_fromdate']); ?>">D20</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="21 / <?php echo getday('21', $_REQUEST['search_fromdate']); ?>">D21</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="22 / <?php echo getday('22', $_REQUEST['search_fromdate']); ?>">D22</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="23 / <?php echo getday('23', $_REQUEST['search_fromdate']); ?>">D23</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="24 / <?php echo getday('24', $_REQUEST['search_fromdate']); ?>">D24</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="25 / <?php echo getday('25', $_REQUEST['search_fromdate']); ?>">D25</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="26 / <?php echo getday('26', $_REQUEST['search_fromdate']); ?>">D26</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="27 / <?php echo getday('27', $_REQUEST['search_fromdate']); ?>">D27</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="28 / <?php echo getday('28', $_REQUEST['search_fromdate']); ?>">D28</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="29 / <?php echo getday('29', $_REQUEST['search_fromdate']); ?>">D29</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="30 / <?php echo getday('30', $_REQUEST['search_fromdate']); ?>">D30</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="31 / <?php echo getday('31', $_REQUEST['search_fromdate']); ?>">D31</th>
                                    <th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="GRADE A++">A++</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="GRADE A+">A+</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="GRADE A">A</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="GRADE B">B</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="GRADE C">C</th>
									<th style="text-align:center;" data-toggle="tooltip" data-placement="top" title="TOTAL">TOTAL</th>
									                                </tr>
                            </thead>
                            <tbody>
							                            <? $cnt=count($sql_search); for($search_i = 0; $search_i < $cnt; $search_i++) { ?>
                            <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                               
                                
							<td class="center"><?=$sql_search[$search_i]['HNAME'];?></td>
							<td class="center"><?=$sql_search[$search_i]['ENAME'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R01'];?>"><?=$sql_search[$search_i]['D01'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R02'];?>"><?=$sql_search[$search_i]['D02'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R03'];?>"><?=$sql_search[$search_i]['D03'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R04'];?>"><?=$sql_search[$search_i]['D04'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R05'];?>"><?=$sql_search[$search_i]['D05'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R06'];?>"><?=$sql_search[$search_i]['D06'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R07'];?>"><?=$sql_search[$search_i]['D07'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R08'];?>"><?=$sql_search[$search_i]['D08'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R09'];?>"><?=$sql_search[$search_i]['D09'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R10'];?>"><?=$sql_search[$search_i]['D10'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R11'];?>"><?=$sql_search[$search_i]['D11'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R12'];?>"><?=$sql_search[$search_i]['D12'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R13'];?>"><?=$sql_search[$search_i]['D13'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R14'];?>"><?=$sql_search[$search_i]['D14'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R15'];?>"><?=$sql_search[$search_i]['D15'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R16'];?>"><?=$sql_search[$search_i]['D16'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R17'];?>"><?=$sql_search[$search_i]['D17'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R18'];?>"><?=$sql_search[$search_i]['D18'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R19'];?>"><?=$sql_search[$search_i]['D19'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R20'];?>"><?=$sql_search[$search_i]['D20'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R21'];?>"><?=$sql_search[$search_i]['D21'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R22'];?>"><?=$sql_search[$search_i]['D22'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R23'];?>"><?=$sql_search[$search_i]['D23'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R24'];?>"><?=$sql_search[$search_i]['D24'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R25'];?>"><?=$sql_search[$search_i]['D25'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R26'];?>"><?=$sql_search[$search_i]['D26'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R27'];?>"><?=$sql_search[$search_i]['D27'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R28'];?>"><?=$sql_search[$search_i]['D28'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R29'];?>"><?=$sql_search[$search_i]['D29'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R30'];?>"><?=$sql_search[$search_i]['D30'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['R31'];?>"><?=$sql_search[$search_i]['D31'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['ADOUBLEPLUS_GRADE'];?>"><?=$sql_search[$search_i]['ADOUBLEPLUS_GRADE'];?></td>
                            <td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['APLUS_GRADE'];?>"><?=$sql_search[$search_i]['APLUS_GRADE'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['A_GRADE'];?>"><?=$sql_search[$search_i]['A_GRADE'];?></td>
						    <td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['B_GRADE'];?>"><?=$sql_search[$search_i]['B_GRADE'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['C_GRADE'];?>"><?=$sql_search[$search_i]['C_GRADE'];?></td>
							<td class="center" data-toggle="tooltip" data-placement="top" title="<?=$sql_search[$search_i]['TOTAL_'];?>"><?=$sql_search[$search_i]['TOTAL_'];?></td>
						
                              
                                
                            </tr>
                            <? } ?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        			
                                       
    <? include "lib/app_footer.php"; ?>

    <!-- START PRELOADS -->
    
    <!-- END PRELOADS -->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>
    <!-- END TEMPLATE -->
	
	<?
     $currentdate=date('Y');
     $from=$currentdate-1;
     $to=$currentdate+1;
    ?>
	
<!-- my -->
	<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
		 <script type="text/javascript" src="js/zebra_datepicker.js"></script>
        <script type="text/javascript">
 $('#datepicker-example3').Zebra_DatePicker({
direction:false, // 1,
//format: 'M-Y',
         // pair: $('#datepicker-example4')
         
         format: 'M-Y',
        // direction: ['<?=$from?>-Jan', '<?=$currentmonth?>-Dec']
        });</script>

   <link rel="stylesheet" href="css/default.css" type="text/css">
    
    <? /* <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script> */ ?>
    <script src="js/monthpicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <script src="js/form-validation.js"></script>
    <script src="js/custom.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery-customselect.js"></script>
   
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
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
	

    function task_assign(empsrno, iv) {
        $('#load_page').show();
        var grade = $("#txt_employee_grade_"+iv).val();
        switch(grade) {
            case 'A+':
                    grade = 1; break;
            case 'A':
                    grade = 2; break;
            case 'B':
                    grade = 3; break;
            default :
                    grade = 4; break;
        }
        var strURL="ajax/fix_grade.php?action=fix_grade&empsrno="+empsrno+"&grade="+grade;
        if(grade != '') {
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data1) {
                    if(data1 == 0) {

                    }
                }
            });
        }
        $('#load_page').hide();
    }
	
	
/*$(function() {
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
        });*/
		 

        $(document).ready(function() {
            $('#load_page').hide();

            $('#customer2').dataTable({
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "order": [[ 0, 'asc' ]],
                "language": {
                    "zeroRecords": "No results available"
                },
                "displayLength": 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="10">'+group+'</td></tr>' // 05052017
                            );

                            last = group;
                        }
                    } );

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                            );

                            last = group;
                        }
                    } );
                }
            });

            // Order by the grouping
            $('#customer2 tbody').on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
                    table.order( [ 0, 'desc' ] ).draw();
                }
                else {
                    table.order( [ 0, 'asc' ] ).draw();
                }
            });
        });

    function cmnt_mail(aprnumb)
    {
        var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
		//var sendurl="viki/post_test.php";
        $.ajax({
        url:sendurl,
        success:function(data){
            $("#myModal2").modal('show');
            $('#modal-body2').html(data);
            $('#txtmailcnt').val("");
            }
        });
    }
 $(document).ready(function() {
            $('#customer2').dataTable({
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "order": [[ 0, 'asc' ]],
                "language": {
                    "zeroRecords": "No results available"
                },
                "displayLength": 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="10">'+group+'</td></tr>' // 05052017
                            );

                            last = group;
                        }
                    } );

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                            );

                            last = group;
                        }
                    } );
                }
            });

            // Order by the grouping
            $('#customer2 tbody').on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
                    table.order( [ 0, 'desc' ] ).draw();
                }
                else {
                    table.order( [ 0, 'asc' ] ).draw();
                }
            });
        });

  /* $('#search_frm').keyup(function(){
            $.each($('#customers').find('th'), function(){
                // alert('ga');
                if($(this).text().toLowerCase().indexOf($('#search_frm').val()) == -1){
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });*/
    </script>
<!-- END SCRIPTS -->

</body>
</html>

    <!-- Light Box - New -->
    <!-- Custom Scripts - Arun Rama Balan.G -->
<!-- END SCRIPTS -->
</body>
</html>


