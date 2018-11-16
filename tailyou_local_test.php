<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$sql_report = select_query_json("select * from STITCHING_SUMMARY where rownum<=10", "121", 'TCS'); 
echo "**".count($sql_report)."**<pre>"; print_r($sql_report); echo "</pre>**"; exit;

/* $sql_report = select_query_json("select prs.PRSTITL PROCESS, prs.PRSCODE, prs.PRSSRNO, count(tim.PRSCODE) CNT_PROCESS 
                                                        from tlu_process_master prs, tlu_order_timer tim 
                                                        where prs.PRSCODE = tim.PRSCODE(+) and tim.deleted(+) = 'N' and prs.deleted = 'N' 
                                                        group by prs.PRSCODE, prs.PRSSRNO, prs.PRSTITL 
                                                        Order by prs.PRSSRNO, prs.PRSCODE, prs.PRSTITL", "Centra", 'TCS');
// print_r($sql_report); exit;
*/

/*
$sql_ord_confirm = select_query_json("select * from tlu_process_master where deleted = 'N' order by PRSSRNO", "Centra", "TCS");
$tme = -1;
foreach ($sql_ord_confirm as $key => $ord_confirm_value) { $tme++; 
    $sql_report = select_query_json("select count(tim.PRSCODE) CNT_PROCESS
                                            from STITCHING_SUMMARY ssm, trandata.customers_tailyou@tcscentr cus, trandata.tlu_stitching_detail@tcscentr pop, trandata.tlu_order_timer@tcscentr tim, 
                                                trandata.userid@tcscentr usr 
                                            where cus.brncode = ssm.brncode and cus.CUSCODE = ssm.CUSCODE and usr.usrcode = tim.adduser and pop.BRNCODE = ssm.BRNCODE and pop.ENTYEAR = ssm.ENTYEAR and 
                                                pop.ENTNUMB = ssm.ENTNUMB and pop.BRNCODE = tim.BRNCODE and pop.ENTYEAR = tim.ENTYEAR and pop.ENTNUMB = tim.ENTNUMB and pop.ENTSRNO = tim.ENTSRNO and 
                                                pop.SUBSRNO = tim.SUBSRNO and tim.deleted = 'N' and ssm.deleted = 'N' and pop.deleted = 'N' and cus.deleted = 'N' and 
                                                tim.PRSCODE = '".$ord_confirm_value['PRSCODE']."' and ssm.ITMISSU not in ('Y')", 121, 'TCS');
    $a1[$tme]['PROCESS']     = $ord_confirm_value['PRSTITL'];
    $a1[$tme]['PRSCODE']     = $ord_confirm_value['PRSCODE'];
    $a1[$tme]['PRSSRNO']     = $ord_confirm_value['PRSSRNO'];
    $a1[$tme]['CNT_PROCESS'] = $sql_report[0]['CNT_PROCESS'];
}
print_r($a1);

$sql_report = select_query_json_test("select prs.PRSTITL PROCESS, prs.PRSCODE, prs.PRSSRNO, count(tim.PRSCODE) CNT_PROCESS 
                                                from trandata.tlu_process_master@tcscentr prs, trandata.tlu_order_timer@tcscentr tim 
                                                where prs.PRSCODE = tim.PRSCODE(+) and tim.deleted(+) = 'N' and prs.deleted = 'N' 
                                                group by prs.PRSCODE, prs.PRSSRNO, prs.PRSTITL
                                                Order by PRSSRNO, PRSCODE, PROCESS", 121, 'TCS');
echo "**".count($sql_report)."**<pre>"; print_r($sql_report); echo "</pre>**";

/* $sql_report = select_query_json("select * from STITCHING_SUMMARY ssm, trandata.tlu_order_timer@tcscentr tim 
                                        where tim.BRNCODE = ssm.BRNCODE and tim.ENTYEAR = ssm.ENTYEAR and tim.ENTNUMB = ssm.ENTNUMB and tim.deleted = 'N' and ssm.ITMISSU in ('Y')", "121", 'TCS');
echo "**".count($sql_report)."**<pre>"; print_r($sql_report); echo "</pre>**"; // exit; 

$sql_report = select_query_json_test("select prs.PRSTITL PROCESS, prs.PRSCODE, prs.PRSSRNO, count(tim.PRSCODE) CNT_PROCESS 
                                                from trandata.tlu_process_master@tcscentr prs, trandata.tlu_order_timer@tcscentr tim 
                                                where prs.PRSCODE = tim.PRSCODE(+) and tim.deleted(+) = 'N' and prs.deleted = 'N' 
                                                group by prs.PRSCODE, prs.PRSSRNO, prs.PRSTITL
                                        union
                                            select 'STITCHING' PROCESS, 0 PRSCODE, 0 PRSSRNO, count(tim.PRSCODE) CNT_PROCESS 
                                                from STITCHING_SUMMARY ssm, trandata.tlu_order_timer@tcscentr tim 
                                                where tim.BRNCODE = ssm.BRNCODE and tim.ENTYEAR = ssm.ENTYEAR and tim.ENTNUMB = ssm.ENTNUMB and tim.deleted = 'N' and tim.PRSCODE = '9' and ssm.ITMISSU in ('Y')
                                                group by '', 0
                                                Order by PRSSRNO, PRSCODE, PROCESS", 121, 'TCS');
echo "**".count($sql_report)."**<pre>"; print_r($sql_report); echo "</pre>**"; 

/* $sql_report = select_query_json("select * from STITCHING_SUMMARY ssm, trandata.tlu_order_timer@tcscentr tim 
                                        where tim.BRNCODE = ssm.BRNCODE and tim.ENTYEAR = ssm.ENTYEAR and tim.ENTNUMB = ssm.ENTNUMB and tim.deleted = 'N' and tim.PRSCODE = '9' and ssm.ITMISSU in ('Y')", "121", 'TCS');
echo "**".count($sql_report)."**<pre>"; print_r($sql_report); echo "</pre>**"; exit;  */
?>