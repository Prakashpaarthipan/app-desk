<?php
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');?>

<? if(isset($_REQUEST['id'])){
		$process=explode(':',$_REQUEST['id']);
$processno=$process[0];
$processyear=$process[1];	
$sql_fields=select_query_json("select PRCDSC from SUPMAIL_PROCESS a where a.PRCSNO='".$processno."' and a.PRCSYR='".$processyear."' and a.DELETED='N'","Centra",'TEST');


?>

									   <u><?=$sql_fields[0]['PRCDSC']?></u>
                                       
                                    <?php } ?>   

