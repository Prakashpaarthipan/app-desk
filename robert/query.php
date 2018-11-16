<?include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
//$sql_prdlist = select_query_json("select * from employee_notice_detail WHERE ROWNUM<=5", "Centra", 'TEST');
$sql_prdlist = select_query_json("select count(*)+1 MAXNUM,(select count(*)+1 MAXNUM from employee_notice_detail WHERE EMPCODE='1595') MAXNAME from employee_notice_detail", "Centra", 'TEST');
//$sql_prdlist = select_query_json("Select * from Trandata.Attn_Register@Tcscentr where EMPSRNO=89419 and payyear = 2018 and paymont = 8 order by PAYYEAR, PAYMONT", "Centra", 'TCS');
print_r($sql_prdlist);
?>