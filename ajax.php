<?php
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);
?>
<!DOCTYPE html>
    <html lang="en" ng-app="myApp">
    <head>
        <!-- META SECTION -->
        <title><?=$title_tag?> Request Entry :: Approval Desk :: <?php echo $site_title; ?></title>
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
        <link href="css/jquery-customselect.css" rel="stylesheet" />
        <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
        <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
        <!-- multiple file upload -->
        <link href="css/jquery.filer.css" rel="stylesheet">
        <? /* <script src="js/angular.js"></script> */ ?>
 	</head>
 	<body>
 		<div id="ajaxcontent" onclick="getCompany();">

 		</div>
 		<select id="company">
 			
 		</select>
 		<script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript">
        	$(document).ready(function() {
            	simpleAjax();
        	});

        	function simpleAjax()
        	{
        		$.ajax({
        			url:"simpleAjax.php?action=get",
        			success:function(data)
        			{
        				$('#ajaxcontent').html(data);
        			}
        		});	
        	}
        	function getCompany()
        	{
        		$.ajax({
        			url:"simpleAjax.php?action=company",
        			success:function(data)
        			{
        				$('#company').html(data);
        			}
        		});	
        	}
        </script>
 	</body>
	</html>	