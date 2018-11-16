<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

echo "<br>**"; print_r($txt_newfrmdate); echo "**";
echo "<br>**"; print_r($txt_newtodate); echo "**";
echo "<br>**"; print_r($txt_reason); echo "**";
echo "<br>**"; print_r($slt_changeduedate); echo "**";
?>