<?php
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");

include_once('../../lib/function_connect.php');
include_once('../../lib/config.php');
extract($_REQUEST);

if($profile == 'emp') {
$body = json_decode(file_get_contents('PHP://input',true));

$va= $body->id;
$va2= $body->name;
$arr = $body->array;

//echo $va;

$empname =select_query_json("select eo.empname,eo.empsrno,eo.brncode, b.comname from employee_office eo, branch b where eo.EMPCODE = '".$va."' and
							 b.brncode = eo.brncode","Centra","TCS");
//$dpart = select_query_json("select COMNAME from branch where brncode = '".$empname[0]['BRNCODE']."'");



//echo $empname[0]['EMPNAME'];
//echo $empname[0]['EMPSRNO'];
//echo $empname[0]['BRNCODE'];
//echo $empname[0]['DATEOFJOI'];

$list = array();
foreach ($empname[0] as $key => $value) {
	# code...
	$list[$key]=$value;
}

$json = json_encode($list);
print_r($json);
//include_once('general_functions.php');
//print_r($_POST);
//
//print_r($_REQUEST);

//echo $body['name'];
//print_r($body);
//print_r($_POST);
//var_dump($body);
exit;
}


if($attend == 'emp'){

		$body = json_decode(file_get_contents('PHP://input',true));

		$va= $body->id;
		$year = $body->year;
		$month = $body->month;

		$sql_prdlist = select_query_json("Select att.*,emp.EMPNAME from Attn_Register att,employee_office emp where att.EMPSRNO=emp.empsrno and emp.empcode='".$va."'  and payyear = '".$year."' and paymont = '".$month."' order by PAYYEAR, PAYMONT", "Centra", 'TCS');
		$p=0;
		$w=0;
		$l=0;
		for($i=1;$i<=31;$i++)
		{	if($i<=9)
			{
				$val='D0'.$i;
			}else
			{
				$val='D'.$i;
			}
			//echo($val.' = '.$sql_prdlist[0][$val].'<br>');
			if($sql_prdlist[0][$val]=='X')
			{	$p++;
			}
			if($sql_prdlist[0][$val]=='W')
			{
				$w++;
			}
			if($sql_prdlist[0][$val]=='L')
			{
				$l++;
			}

		}

		//print_r($sql_prdlist);
		// echo("present =".$p.'<br>');
		// echo("weekoff =".$w.'<br>');
		// echo("leave =".$l.'<br>');
		$days = array();
		array_push($days,$va,$sql_prdlist[0]['EMPNAME'],$p,$w,$l);
		//echo $va;
		$json = json_encode($days);
		print_r($json);
		//echo "Select att.*,emp.EMPNAME from Attn_Register att,employee_office emp where att.EMPSRNO=emp.empsrno and emp.empcode='".$va."'  and payyear = '".$year."' and paymont = '".$month."' order by PAYYEAR, PAYMONT";



}

?>