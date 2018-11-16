<?php
include('lib/config.php');
include("db_connect/public_functions.php");
extract($_REQUEST);
 
/*$start    = new DateTime('2018-5-01');
$end      = new DateTime('2018-7-06');
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);

$date = array();
foreach ($period as $dt) {
    $date[]="to_date('".$dt-> format("t/m/Y") . PHP_EOL."','dd/MM/yyyy')";
}
$date_remove = array_pop($date);
$end=date_format($end,"d/m/Y");
$end = "to_date('".$end."','dd/MM/yyyy')";
array_push($date,$end);
$val = implode(",", $date);*/

 
/*$sql = select_query("select entyear,entnumb from trandata.design_entry@tcscentr where deleted not in 'Y' order by entyear,entnumb");

foreach ($sql as $res) {

	$sql_det = select_query("select * from mobile_design_detail where entyear='".$res['ENTYEAR']."' and entnumb=".$res['ENTNUMB']." order by entsrno desc");

	foreach ($sql_det as $key => $value)
	{
		$all = array();
		if($key == 0){
			$all['ISACTIV'] ="A";
		}else{
			$all['ISACTIV'] ="N";
		}
		$where = "entyear='".$value['ENTYEAR']."' and entnumb=".$value['ENTNUMB']." and entsrno=".$value['ENTSRNO']."";

		$update = update_query($all,'mobile_design_detail',$where);

	}
}*/

/*update trandata.design_entry@tcscentr set entstat='N' where entyear='2018-19' and entnumb=6218;
delete from trandata.mobile_design_detail@tcscentr where entyear='2018-19' and entnumb=6218;
delete from trandata.mobile_design_branch_detail@tcscentr where entyear='2018-19' and entnumb=6218;

update trandata.design_entry@tcscentr set entstat='N' where entyear='2018-19' and entnumb=23950;
delete from trandata.mobile_design_detail@tcscentr where entyear='2018-19' and entnumb=23950;
*/
//$str = "update trandata.design_entry@tcscentr set entstat='G' where entyear='2018-19' and entnumb in (39409)";
//$str = "delete from trandata.mobile_design_detail@tcscentr where entyear='2018-19' and entnumb in (39409) and entsrno=4";
//$str = "delete from trandata.mobile_design_branch_detail@tcscentr where entyear='2018-19' and entnumb in (37521)";
// 41616
/*
update trandata.design_entry@tcscentr set entstat='H' where (entyear,entnumb) in 
(select distinct des.entyear,des.entnumb 
from trandata.design_entry@tcscentr des,
trandata.mobile_design_detail@tcscentr det
where des.entyear=det.entyear and des.entnumb=det.entnumb and des.grpcode=3866 and des.entstat='R' and des.reqtype='O' and det.entsrno=2 and det.desgrad=0);*/
/*
$str = "";
$update = delete_query($str);
echo $update;
*/

// Design Reverse 


/*$no = "";
$str1 ="update trandata.design_entry@tcscentr set entstat='N' where entyear='2018-19' and entnumb in (".$no.")";
$str2 = "delete from trandata.mobile_design_detail@tcscentr where entyear='2018-19' and entnumb  in (".$no.")  ";
$str3="delete from trandata.mobile_design_branch_detail@tcscentr where entyear='2018-19' and entnumb in (".$no.")";
$update = delete_query($str1);
$update = delete_query($str2);
$update = delete_query($str3);
*/


//update trandata.approval_request@tcscentr set REQSTFR=61579,RQFRDES='17108 - SELVA MUTHU KUMAR M.A', RQFRDSC=3,RQFRESC=137,RQFRDSN='MANAGER',RQFRESN='137 COST CONTROL' where aprnumb in ('MANAGEMENT / PACKING / GIFTING 2003350 / 19-01-2018 / 3350 / 05:07 PM')

/*
REQSTTO  approvals change.
update trandata.approval_request@tcscentr set RQESTTO=20118,RQTODES='1 -  SIVALINGAM K',RQTODSC=9,RQTOESC=12,RQTODSN='DEFAULT',RQTOESN='01 ADMINISTRATION' where aprnumb in ('MANAGEMENT / QC DEPT 2002812 / 15-12-2017 / 2812 / 07:09 PM');
insert into trandata.approval_mdhierarchy@tcscentr values (126,2,2,9,1,'Y','DEFAULT',0,'ADMIN / PROJECT 4007215 / 19-01-2018 / 7215 / 02:32 PM','');
insert into trandata.approval_mdhierarchy@tcscentr values (217,1,1,9,1,'Y','DEFAULT',0,'S-TEAM / AUDIT 1000720 / 07-02-2018 / 0720 / 05:37 PM','');
insert into trandata.approval_mdhierarchy@tcscentr values (126,3,3,78,1,'Y','ADMINISTATIVE OFFICER',0,'ADMIN / PROJECT 4007215 / 19-01-2018 / 7215 / 02:32 PM','');
update  trandata.approval_request@tcscentr set REQSTFR=21344 , RQFRDES='3 -  ARUN KARTHIKEYEN S' ,RQFRDSC=78,RQFRESC=95,RQFRDSN='ADMINISTATIVE OFFICER',RQFRESN='96 S TEAM',appstat='N' where aprnumb in ('OPERATION / GRM TOP FORMAL 3000501 / 17-01-2018 / 0501 / 11:45 AM')  and arqsrno=8
update  trandata.approval_request@tcscentr set REQSTBY=1280 , RQBYDES='2676 - SARAVANAN.P' ,REQDESC=189,REQESEC=20,REQDESN='DEPUTY GENERAL MANAGER',REQESEN='36 INFO TECH'
where aprnumb in ('MANAGEMENT / MANAGEMENT 2003518 / 30-01-2018 / 3518 / 05:27 PM')  and arqsrno=2;
*/

/*set linesize 4500;
 -- Verify the Final row must not properly added..
select * from trandata.APPROVAL_request@tcscentr rq where rq.appstat = 'F' and
rq.appfrwd in ('F', 'P', 'Q', 'S', 'I') and rq.deleted = 'N' and rq.ARQSRNO =
(select max(ARQSRNO) from trandata.APPROVAL_request@tcscentr where aprnumb =
rq.aprnumb and deleted = 'N') order by rq.aprnumb, rq.arqsrno;

-- Verify the approval final value = 0 and request value > 0..
select * from trandata.APPROVAL_REQUEST@tcscentr where APRQVAL > 0 and APPFVAL = 0
and appstat not in ('A', 'R') order by aprnumb, ARQSRNO;

-- Check the last approval is in waiting stage and not move to next level.
select * from trandata.APPROVAL_request@tcscentr where appstat = 'W' order by
aprnumb, arqsrno;
*/

/*
Operation Gm Insert mdhierarchy:
insert into trandata.approval_mdhierarchy@tcscentr values (818,3,2001,19,1,'Y','GENERAL MANAGER',0,'MANAGEMENT / MANAGEMENT 2003558 / 02-02-2018 / 3558 / 05:02 PM','');
*/
?>
