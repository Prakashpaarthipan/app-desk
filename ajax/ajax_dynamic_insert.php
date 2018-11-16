<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}

