<?
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');

echo('<pre>');

for($i=0;$i<=30;$i++)
{
	$sql1 = select_query_json("select SUM(APPRVAL) SUMAPPRVAL, req.APPFVAL, to_char(req.adddate,'dd-MON-yyyy hh:mi:ss AM') adddate, req.aprnumb from trandata.APPROVAL_REQUEST@tcscentr REQ, trandata.approval_budget_planner_temp@tcscentr PLN 
where REQ.APRNUMB = PLN.APRNUMB AND REQ.DELETED = 'N' and trunc(pln.adddate) = trunc(sysdate-".$i.") and arqyear = '2018-19' AND arqsrno in (select max(arqsrno) from trandata.APPROVAL_REQUEST@tcscentr where ARQCODE = req.ARQCODE and aprnumb = req.aprnumb and 
ARQYEAR = req.ARQYEAR and ATYCODE = req.ATYCODE and ATMCODE = req.ATMCODE and APMCODE = req.APMCODE and ATCCODE = req.ATCCODE) group by req.APPFVAL, req.adddate, req.aprnumb having SUM(APPRVAL) <> APPFVAL Order by req.adddate, req.aprnumb, req.APPFVAL", "Centra", 'TCS');

/*echo("<br>"."select SUM(APPRVAL) SUMAPPRVAL, req.APPFVAL, to_char(req.adddate,'dd-MON-yyyy hh:mi:ss AM') adddate, req.aprnumb from trandata.APPROVAL_REQUEST@tcscentr REQ, trandata.approval_budget_planner_temp@tcscentr PLN 
where REQ.APRNUMB = PLN.APRNUMB AND REQ.DELETED = 'N' and trunc(pln.adddate) = trunc(sysdate-".$i.") and arqyear = '2018-19' AND arqsrno in (select max(arqsrno) from trandata.APPROVAL_REQUEST@tcscentr where ARQCODE = req.ARQCODE and aprnumb = req.aprnumb and 
ARQYEAR = req.ARQYEAR and ATYCODE = req.ATYCODE and ATMCODE = req.ATMCODE and APMCODE = req.APMCODE and ATCCODE = req.ATCCODE) group by req.APPFVAL, req.adddate, req.aprnumb having SUM(APPRVAL) <> APPFVAL Order by req.adddate, req.aprnumb, req.APPFVAL"."<br>");
echo("<br>"."select SUM(APPRVAL) SUMAPPRVAL, req.APPFVAL, to_char(req.adddate,'dd-MON-yyyy hh:mi:ss AM') adddate, req.aprnumb from trandata.APPROVAL_REQUEST@tcscentr REQ, trandata.approval_budget_planner@tcscentr PLN 
where REQ.APRNUMB = PLN.APRNUMB AND REQ.DELETED = 'N' and trunc(pln.adddate) = trunc(sysdate-".$i.") and arqyear = '2018-19' AND arqsrno in (select max(arqsrno) from trandata.APPROVAL_REQUEST@tcscentr where ARQCODE = req.ARQCODE and aprnumb = req.aprnumb and 
ARQYEAR = req.ARQYEAR and ATYCODE = req.ATYCODE and ATMCODE = req.ATMCODE and APMCODE = req.APMCODE and ATCCODE = req.ATCCODE) group by req.APPFVAL, req.adddate, req.aprnumb having SUM(APPRVAL) <> APPFVAL Order by req.adddate, req.aprnumb, req.APPFVAL"."<br>");*/


$sql2 = select_query_json("select SUM(APPRVAL) SUMAPPRVAL, req.APPFVAL, to_char(req.adddate,'dd-MON-yyyy hh:mi:ss AM') adddate, req.aprnumb from trandata.APPROVAL_REQUEST@tcscentr REQ, trandata.approval_budget_planner@tcscentr PLN 
where REQ.APRNUMB = PLN.APRNUMB AND REQ.DELETED = 'N' and trunc(pln.adddate) = trunc(sysdate-".$i.") and arqyear = '2018-19' AND arqsrno in (select max(arqsrno) from trandata.APPROVAL_REQUEST@tcscentr where ARQCODE = req.ARQCODE and aprnumb = req.aprnumb and 
ARQYEAR = req.ARQYEAR and ATYCODE = req.ATYCODE and ATMCODE = req.ATMCODE and APMCODE = req.APMCODE and ATCCODE = req.ATCCODE) group by req.APPFVAL, req.adddate, req.aprnumb having SUM(APPRVAL) <> APPFVAL Order by req.adddate, req.aprnumb, req.APPFVAL", "Centra", 'TCS');

echo("<br>=========  ".$i."<br>");
echo('<pre>');
if(count($sql1)>0)
{
	print_r($sql1);
}
if(count($sql2)>0)
{
	print_r($sql2);
}
//print_r($sql2);
}
?>