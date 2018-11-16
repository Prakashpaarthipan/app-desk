<?
header('Content-Type:text/json,utf-8');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');

echo("running");
print_r($_SESSION);
$advt=array(8,9,39,40,41,42,43,44,45,46);
$memb='';
if(in_array(1,$advt))
{
	$memb=',177';
}
$sql_prdlist = select_query_json("select hea.empsrno,hea.empcode,emp.empname,emp.descode,hea.brnhdsr from trandata.approval_branch_head@tcscentr hea,trandata.employee_office@tcscentr emp,trandata.designation@tcscentr des where 
emp.empsrno=hea.empsrno and emp.descode=des.descode and hea.brncode=1 and hea.deleted='N' and hea.aprvalu>0 
and ((emp.empsrno in (188,19256,125,1682,452,21344,43400,20118,83815".$memb.")) or (emp.descode in (92,189) and emp.brncode not in (888) ) ) group by hea.empsrno,hea.empcode,emp.empname,emp.descode,hea.brnhdsr order by hea.brnhdsr"	,"Centra","TCS");
$arr_head=array();
foreach($sql_prdlist as $key => $value)
{
	if($arr_head[$value['EMPSRNO']]=='')
	{
		$arr_head[$value['EMPSRNO']]=$value;
	}
}
$arr_head_sort=array();
foreach($arr_head as $key => $value)
{
  $arr_head_sort[$value['BRNHDSR']]=$value;
}
ksort($arr_head_sort);	
print_r($arr_head_sort);							
?>
