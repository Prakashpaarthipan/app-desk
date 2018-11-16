<?php 
header('Cache-Control: no cache'); //no cache // This is for avoid failure in submit form  pagination form details page
session_cache_limiter('private_no_expire, must-revalidate'); // works // This is for avoid failure in submit form  pagination form details page

try {
error_reporting(0);
include('lib/config.php');
include('../db_connect/public_functions.php');
include('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}

if($_SESSION['auditor_login'] == 1) { ?>
	<script>alert('You dont have rights to access this page.'); window.location="index.php";</script>
<? exit();
}

$sql_descode = select_query("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
									from trandata.employee_office@tcscentr emp, trandata.empsection@tcscentr sec, trandata.designation@tcscentr des, trandata.employee_salary@tcscentr sal 
									where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = '".$_SESSION['tcs_user']."') and sec.deleted = 'N' and sec.deleted = 'N' 
										and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno 
								union
									select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
									from trandata.employee_office@tcscentr emp, trandata.new_empsection@tcscentr sec, trandata.new_designation@tcscentr des, trandata.employee_salary@tcscentr sal 
									where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = '".$_SESSION['tcs_user']."') and sec.deleted = 'N' and sec.deleted = 'N' 
										and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno 
									order by EMPCODE");
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Approval Request List :: Approval Desk Process :: <?=$site_title?></title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="css/plugins/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/dataTables.tableTools.css" rel="stylesheet">
    <link href="css/fixedHeader.dataTables.css" rel="stylesheet">
	
    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Select2 -->
     <link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<link href="css/facebook_alert.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery_facebook.alert.js"></script>
	
<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Open+Sans:400,600);

.M1{
    width: 160%;
    height: 100px;
    position: absolute;
    align:left;
     }
    
.form-group{
   position: relative;
   width: auto;
    }

.form-control{
    background: transparent;
    width: 160%;
}
form {
    width: 320px;
    margin: 0;
}
form > div {
    position: relative;
    overflow: hidden;
}
form input, form textarea {
    width: 100%;
    border: 2px solid gray;
    background: none;
    position: relative;
    top: 0;
    left: 0;
    z-index: 1;
    padding: 8px 12px;
    outline: 0;
}
form input:valid, form textarea:valid {
    background: #F0F7F7;
}
form input:focus, form textarea:focus {
    border-color: #357EBD;
}
form input:focus + label, form textarea:focus + label {
    background: #FOA !important; /* if need change yellow color */
    color: white;
    font-size: 70%;
    padding: 1px 6px;
    z-index: 2;
    text-transform: uppercase;
}
form label {
    -webkit-transition: background 0.2s, color 0.2s, top 0.2s, bottom 0.2s, right 0.2s, left 0.2s;
    transition: background 0.2s, color 0.2s, top 0.2s, bottom 0.2s, right 0.2s, left 0.2s;
    position: absolute;
    color: #999;
    padding: 7px 6px;
    font-weight: normal;
}
form textarea {
    display: block;
    resize: vertical;
}
form.go-bottom input, form.go-bottom textarea {
    padding: 12px 12px 12px 12px;
}
form.go-bottom label {
    top: 0;
    bottom: 0;
    left: 0;
    width: 100%;
}
form.go-bottom input:focus, form.go-bottom textarea:focus {
    padding: 4px 6px 20px 6px;
}
form.go-bottom input:focus + label, form.go-bottom textarea:focus + label {
    top: 100%;
    margin-top: -16px;
}
form.go-right label {
    border-radius: 0 5px 5px 0;
    height: 100%;
    top: 0;
    right: 100%;
    width: 100%;
    margin-right: -100%;
}
form.go-right input:focus + label, form.go-right textarea:focus + label {
    right: 0;
    margin-right: 0;
    width: 40%;
    padding-top: 5px;
}

html,
body {
/*css for full size background image http://p1.pichost.me/i/66/1910857.jpg*/
  background: url() no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
    height: 100%;
  background-color: #FFFFFF;
  color: #fff;
  text-align: left;
  text-shadow: 0 1px 2px rgba(0,0,0,.5);
 
}

/* Extra markup and styles for table-esque vertical and horizontal centering */
.site-wrapper {
  display: table;
  width: 100%;
  height: 100%; /* For at least Firefox */
  min-height: 100%;
  -webkit-box-shadow: inset 0 0 100px rgba(0,0,0,.5);
          box-shadow: inset 0 0 100px rgba(0,0,0,.5);
}
.site-wrapper-inner {
  display: table-cell;
  vertical-align: top;
}
.cover-container {
  margin-right: auto;
  margin-left: auto;
}


/* Related to SIde MENU Text */

box-sizing: border-box;
}
body {
    background: url(http://habrastorage.org/files/90a/010/3e8/90a0103e8ec749c4843ffdd8697b10e2.jpg);
    text-align: right;
    padding-top: 40px;
    padding-left: 20px;
}
.btn-nav {
    color: Green;
    background-color: #fff;
    border: 1px solid #e0e1db;
    -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
    -moz-box-sizing: border-box;    /* Firefox, other Gecko */
    box-sizing: border-box;         /* Opera/IE 8+ */
}
.btn-nav:hover {
    color: #e92d00;
    cursor: pointer;
    -webkit-transition: color 1s; /* For Safari 3.1 to 6.0 */
    transition: color 1s;
}
.btn-nav.active {
    color: #e92d00;
    padding: 2px;
    border-top: 6px solid #e92d00;
    border-bottom: 6px solid #e92d00;
    border-left: 0;
    border-right: 0;
    box-sizing:border-box;
    -moz-box-sizing:border-box;
    -webkit-box-sizing:border-box;
    -webkit-transition: border 0.3s ease-out, color 0.3s ease 0.5s;
    -moz-transition: border 0.3s ease-out, color 0.3s ease 0.5s;
    -ms-transition: border 0.3s ease-out, color 0.3s ease 0.5s; /* IE10 is actually unprefixed */
    -o-transition: border 0.3s ease-out, color 0.3s ease 0.5s;
    transition: border 0.3s ease-out, color 0.3s ease 0.5s;
    -webkit-animation: pulsate 1.2s linear infinite;
    animation: pulsate 1.2s linear infinite;
}
.btn-nav.active:before {
    content: '';
    position: absolute;
    border-style: solid;
    border-width: 6px 6px 0;
    border-color: #e92d00 transparent;
    display: block;
    width: 0;
    z-index: 1;
    margin-left: -6px;
    top: 0;
    left: 50%;
}
.btn-nav .glyphicon {
    padding-top: 16px;
    font-size: 40px;
}
.btn-nav.active p {
    margin-bottom: 8px;
}
@-webkit-keyframes pulsate {
 50% { color: #000; }
}
@keyframes pulsate {
 50% { color: #000; }
}
@media (max-width: 480px) {
    .btn-group {
        display: block !important;
        float: none !important;
        width: 100% !important;
        max-width: 100% !important;
    }
}
@media (max-width: 600px) {
    .btn-nav .glyphicon {
        padding-top: 12px;
        font-size: 26px;
    }
}
  
/**************************************************************************************************/



div.bhoechie-tab-container{
  z-index: 10;
  background-color: #FOA;
  padding: 0 !important;
  border-radius: 4px;
  -moz-border-radius: 4px;
  border:1px solid #ddd;
  margin-top: 20px;
  margin-left: 50px;
  -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
  box-shadow: 0 6px 12px rgba(0,0,0,.175);
  -moz-box-shadow: 0 6px 12px rgba(0,0,0,.175);
  background-clip: padding-box;
  opacity: 0.97;
  filter: alpha(opacity=97);
}
div.bhoechie-tab-menu{
  padding-right: 0;
  padding-left: 0;
  padding-bottom: 0;
}
div.bhoechie-tab-menu div.list-group{
  margin-bottom: 0;
}
div.bhoechie-tab-menu div.list-group>a{
  margin-bottom: 0;
}
div.bhoechie-tab-menu div.list-group>a .glyphicon,
div.bhoechie-tab-menu div.list-group>a .fa {
  color: #5A55A3;
}
div.bhoechie-tab-menu div.list-group>a:first-child{
  border-top-right-radius: 0;
  -moz-border-top-right-radius: 0;
}
div.bhoechie-tab-menu div.list-group>a:last-child{
  border-bottom-right-radius: 0;
  -moz-border-bottom-right-radius: 0;
}
div.bhoechie-tab-menu div.list-group>a.active,
div.bhoechie-tab-menu div.list-group>a.active .glyphicon,
div.bhoechie-tab-menu div.list-group>a.active .fa{
  background-color: #5A55A3;
  background-image: #5A55A3;
  color: #ffffff;
}
div.bhoechie-tab-menu div.list-group>a.active:after{
  content: '';
  position: absolute;
  left: 100%;
  top: 50%;
  margin-top: -13px;
  border-left: 0;
  border-bottom: 13px solid transparent;
  border-top: 13px solid transparent;
  border-left: 10px solid #5A55A3;
}

div.bhoechie-tab-content{
  background-color: #ffffff;
  /* border: 1px solid #eeeeee; */
  padding-left: 20px;
  padding-top: 10px;
}

div.bhoechie-tab div.bhoechie-tab-content:not(.active){
  display: none;
}

table {    width: 100%;}
table, th, td { border: 1px solid black;    border-collapse: collapse;}
th, td {    padding: 5px;   text-align: left;}
table#t01 tr:nth-child(even) {  background-color: #F6E6E3;}
table#t01 tr:nth-child(odd) {   background-color: #7E9987;}
table#t01 th {  background-color: Green;    color: white;}

</style>
<script type="text/javascript">
$(document).ready(function() {
	$(".btn-pref .btn").click(function () {
	    $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
	    $(this).removeClass("btn-default").addClass("btn-primary");   
	});
});
</script>
</head>

<body>
<div id='load_page' class="loader"></div>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            
			<? include("lib/app_header.php"); ?>
            <!-- /.navbar-header -->

			<? include("lib/app_notification.php"); ?>
            <!-- /.navbar-top-links -->

			<? include("lib/app_leftpanel.php"); ?>
            <!-- /.navbar-static-side -->
			
        </nav>

        <div id="page-wrapper" style='height:auto;'>

            <div class="row" style='padding-top:0px;'>
                <div class="col-lg-12" style="padding:0px;">
				
                    <!-- /.panel -->
					<div class="panel-body" style='display:none;'>
                        <div id="morris-area-chart"></div>
						<div id="morris-donut-chart"></div>
						<div id="morris-bar-chart"></div>
                    </div>
					
				

				<div class="container-fluid">
					  <div class="container">
					  <div class="row">



					        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 bhoechie-tab-menu">
					              <div class="list-group">
					                <a href="#" class="list-group-item active text-center">
					                  <h4 class="glyphicon glyphicon-user"></h4><br/>New Duty Add
					                </a>
					                <a href="#" class="list-group-item text-center">
					                  <h4 class="glyphicon glyphicon-thumbs-up"></h4><br/>Duty Assigned
					                </a>
					                <a href="#" class="list-group-item text-center">
					                  <h4 class=" glyphicon glyphicon-thumbs-down"></h4><br/>Duty Not Assigned
					                </a>
					                <a href="#" class="list-group-item text-center">
					                  <h4 class="glyphicon glyphicon-hand-right "></h4><br/>Duty Assign to
					                </a>
					               
					              </div>
					            </div>









					        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-9 bhoechie-tab-container">
					                
					              

					            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
					                <!-- flight section -->
					                <div class="bhoechie-tab-content active">
					                    <center>
					                      <h1 class="glyphicon glyphicon-user" style="font-size:14em;color:#55518a"></h1>
					                      <h2 style="margin-top: 0;color:#55518a">Coming Soon</h2>
					                      <h3 style="margin-top: 0;color:#55518a">Add User</h3>
					                    </center>
					                </div>
					                <!-- train section -->
					                <div class="bhoechie-tab-content">
					                    <center>
					                      <h1 class="glyphicon glyphicon-thumbs-up" style="font-size:12em;color:#55518a"></h1>
					                      <h2 style="margin-top: 0;color:#55518a"></h2>
					                      <h3 style="margin-top: 0;color:#55518a">Assigned</h3>
					                    </center>
					                </div>
					    
					                <!-- hotel search -->
					                <div class="bhoechie-tab-content">
					                    <center>
					                      <h1 class="glyphicon glyphicon-thumbs-down" style="font-size:12em;color:#55518a"></h1>
					                      <h2 style="margin-top: 0;color:#55518a"></h2>
					                      <h3 style="margin-top: 0;color:#55518a">Not Assigned</h3>
					                    </center>
					                </div>
					                <div class="bhoechie-tab-content">
					                    <center>
					                      <h1 class="glyphicon glyphicon-hand-right" style="font-size:12em;color:#55518a"></h1>
					                      <h2 style="margin-top: 0;color:#55518a"></h2>
					                      <h3 style="margin-top: 0;color:#55518a">Assign To</h3>
					                    </center>
					                </div>
					                <div class="bhoechie-tab-content">
					                    <center>
					                      <h1 class="glyphicon glyphicon-home" style="font-size:12em;color:#55518a"></h1>
					                      <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
					                      <h3 style="margin-top: 0;color:#55518a">Credit Card</h3>
					                    </center>
					                </div>
					            </div>
					        </div>
					  </div>
					</div>

					  
					</div>
					
				</div>
				</div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
	<div class='clear'></div>
	
	<div>
		<? include("lib/app_footer.php"); ?>
	</div>
    <!-- /#wrapper -->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>
	
	<link rel="stylesheet" href="../bootstrap/css/default.css" type="text/css">
	<script type="text/javascript" src="../bootstrap/js/zebra_datepicker1.js"></script>	
	
    <!-- DataTables JavaScript -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="js/dataTables.tableTools.js"></script>
    <script src="js/dataTables.fixedHeader.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

    <!-- Select2 -->
	<script src="../dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
	
	<!-- <script type="text/javascript" src="js/jquery211.min.js" charset="UTF-8"></script> -->
	<script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript">
	  $(document).ready(function() {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});
  
	</script>

</body>

</html>
<? 
} 
catch(Exception $e) {
	echo 'Unknown Error. Try again.';
}
?>