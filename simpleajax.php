<?php
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);
if($action == "get")
{

echo "TEST AJAX DETAILS ADASDASDASDA";
}
if($action == "company")
{
	$sqlcom = select_query_json("select cmpsrno,cmpname from contractor_employees order by cmpsrno");
	foreach ($sqlcom as $key => $res) {?>
		<option value="<?=$res['CMPSRNO']?>"><?=$res['CMPNAME']?></option>	
	<?}

}
?>