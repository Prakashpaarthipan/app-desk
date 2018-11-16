<?php
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');?>
<?if($_REQUEST['id']==1){?>
<img src='images/invalid_approval.png' style="height:100px;width:300px"/>
<?}else if($_REQUEST['id']==2){?>
<img src='images/ktm-jewellery-logo.png' style="height:100px;width:300px"/><?}?>