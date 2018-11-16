<?php
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');?>

<? if(isset($_REQUEST['id'])){
$sql_fields=select_query_json("select * from SUPMAIL_PROCESS a where a.PRCSNO='".$_REQUEST['id']."'","Centra",'TEST');


?>

									   <u><?=$sql_fields[0]['PRCDSC']?></u>
                                       
                                    <?php } ?>   

