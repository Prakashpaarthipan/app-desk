<?
header('Content-Type:text/json,utf-8');
include_once('../lib/config.php');

include_once('../lib/function_connect.php');
include_once('../general_functions.php');

$sql_prdlist = select_query_json("select ssm.BRNCODE, ssm.ENTYEAR, ssm.ENTNUMB, cus.CUSCODE, cus.CUSNAME, cus.CUSMOBL, pop.*, tim.*, usr.*, usr.usrcode empcd, usr.USRCODE, usr.USRNAME, to_char(ssm.DUEDATE,'dd-MON-yyyy') SSMDUEDATE  from STITCHING_SUMMARY ssm,trandata.tlu_order_timer@tcscentr tim , trandata.tlu_stitching_detail@tcscentr pop where cus.brncode = ssm.brncode and 
																cus.CUSCODE = ssm.CUSCODE and 
																usr.usrcode = tim.adduser 
																and pop.BRNCODE = ssm.BRNCODE 
																and pop.ENTYEAR = ssm.ENTYEAR  pop.ENTSRNO = tim.ENTSRNO and pop.SUBSRNO = tim.SUBSRNO and tim.deleted = 'N' and ssm.deleted = 'N' and pop.deleted = 'N' and cus.deleted = 'N' and ssm.ITMISSU not in ('Y')  and tim.entnumb = 269 and ssm.CASPAID not in ('Y')",121,"TCS");










//$sql_prdlist1 = select_query_json("select  ENTYEAR , ENTNUMB,ENTSRNO,adduser,BRNCODE from tlu_order_timer where entnumb = 305 ","Centra","TCS");
//$sql_prdlist2 = select_query_json("select * from tlu_stitching_detail where entnumb = 305 ","Centra","TCS");
//$sql_prdlist3 = select_query_json("select * from customers_tailyou where entnumb = 305 ","Centra","TCS");


print_r($sql_prdlist);

print_r($sql_prdlist1);

print_r($sql_prdlist2);

print_r($sql_prdlist3);
/*
$sql_prdlist = select_query_json("select  ROW_NUMBER()  OVER (ORDER BY tim.prscode,pop.PRIORIT, pop.BRNCODE, pop.ENTYEAR, pop.ENTNUMB, pop.ENTSRNO, pop.SUBSRNO) As SrNo, ssm.BRNCODE, ssm.ENTYEAR, ssm.ENTNUMB, cus.CUSCODE, cus.CUSNAME, cus.CUSMOBL, pop.*, tim.*, usr.*, usr.usrcode empcd, usr.USRCODE, usr.USRNAME,
to_char(ssm.DUEDATE,'dd-MON-yyyy') SSMDUEDATE, (select to_char(tl.STRTDAT,'dd-MM-yyyy HH:mi:ss AM')||'!!'||tl.STRTDAT||'!!'||tl.ENDTDAT||'!!'||tl.STRTTIM||'!!'||tl.ENDTTIM||'!!'||tl.PRCSTIM||'!!'||tl.TIMRMOD 
from trandata.tlu_order_process_time@tcscentr tl where tl.brncode = tim.BRNCODE and tl.ENTYEAR = tim.ENTYEAR and tl.ENTNUMB = tim.ENTNUMB 
and tl.ENTSRNO = tim.ENTSRNO and tl.SUBSRNO = tim.SUBSRNO and tl.PRSCODE = tim.PRSCODE and tl.TIMSRNO = (select nvl(max(TIMSRNO),0) 
from trandata.tlu_order_process_time@tcscentr where brncode = tim.BRNCODE and ENTYEAR = tim.ENTYEAR and ENTNUMB = tim.ENTNUMB and 
ENTSRNO = tim.ENTSRNO and SUBSRNO = tim.SUBSRNO and PRSCODE = tim.PRSCODE)) STRTDAT1, (select tl.STRTDAT 
from trandata.tlu_order_process_time@tcscentr tl where tl.brncode = tim.BRNCODE and tl.ENTYEAR = tim.ENTYEAR and tl.ENTNUMB = tim.ENTNUMB and 
tl.ENTSRNO = tim.ENTSRNO and tl.SUBSRNO = tim.SUBSRNO and tl.PRSCODE = tim.PRSCODE and tl.TIMRMOD = 1 and rownum <= 1) STRTDAT2 
from STITCHING_SUMMARY ssm, trandata.customers_tailyou@tcscentr cus, trandata.tlu_stitching_detail@tcscentr pop, 
trandata.tlu_order_timer@tcscentr tim, trandata.userid@tcscentr usr 
where cus.brncode = ssm.brncode and cus.CUSCODE = ssm.CUSCODE and usr.usrcode = tim.adduser and pop.BRNCODE = ssm.BRNCODE and pop.ENTYEAR = ssm.ENTYEAR 
and pop.ENTNUMB = ssm.ENTNUMB and pop.BRNCODE = tim.BRNCODE and pop.ENTYEAR = tim.ENTYEAR and pop.ENTNUMB = tim.ENTNUMB and 
pop.ENTSRNO = tim.ENTSRNO and pop.SUBSRNO = tim.SUBSRNO and tim.deleted = 'N' and ssm.deleted = 'N' and pop.deleted = 'N' and cus.deleted = 'N' 
and tim.PRSCODE = 1 and ssm.ITMISSU not in ('Y') and ssm.CASPAID not in ('Y')  and SrNo > 10 and SrNo < 21 order by SrNo ", 121, 'TCS');
//$sql_prdlist = select_query_json("Select * from employee_notice_detail b,city c where rownum<=2", "Centra", 'TCS');
print_r($sql_prdlist);
 and tim.deleted = 'N' and ssm.deleted = 'N' and pop.deleted = 'N' and cus.deleted = 'N' 
//tim.SUBSRNO between 10 and 20*/
?>