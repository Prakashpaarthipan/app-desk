<?
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
$sql_prdlist = select_query_json("Select * from Trandata.Attn_Register@Tcscentr attn,trandata.employee_office emp where attn.EMPSRNO='".$_REQUEST['emp']."' and attn.payyear = 2018 and attn.paymont = '".$_REQUEST['mon']."' and attn.empsrno=emp.empsrno order by PAYYEAR, PAYMONT", "Centra", 'TCS');
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
	echo($val.' = '.$sql_prdlist[0][$val].'<br>');
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
echo($sql_prdlist[0]['EMPNAME'].'<br>');
echo("present =".$p.'<br>');
echo("weekoff =".$w.'<br>');
echo("leave =".$l.'<br>');
$sql_prdlist = select_query_json("Select trandata.User_Access('2280588','M').tcscentr lock_ From dual", "Centra", 'TCS');
echo($sql_prdlist);
?>