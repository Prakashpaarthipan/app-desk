<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$action = $_REQUEST['action'];
$search_summary = $_REQUEST['search_summary'];
?>
<select class="form-control" tabindex='2' name='search_summary_value' id='search_summary_value' data-toggle="tooltip" data-placement="top" title="Choose" >
	<option value='0' <? if($search_summary_value == '0') { ?> selected <? } ?>>ALL</option>
	<? 	switch ($_REQUEST['search_summary']) {
			case 'TOPCORE':
				$sql_sumry = select_query_json("select ATCCODE GRIDCODE, ATCNAME GRIDNAME from approval_topcore where deleted = 'N' order by ATCSRNO", "Centra", 'TCS');
				break;
			case 'SUBCORE':
				$sql_sumry = select_query_json("select CORCODE gridcode, ' ( '||top.atcname||' ) '||CORNAME gridname from empcore_section sub, approval_topcore top 
														where top.atccode = sub.topcore and top.deleted = 'N' and sub.deleted = 'N' order by sub.TOPCORE, sub.CORNAME", "Centra", 'TCS');
				break;

			case 'BRANCH':
				$sql_sumry = select_query_json("select brn.brncode gridcode, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) gridname from branch brn 
														where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K') and 
															brn.brncode in (1,10,11,14,22,23,100,102,104,107,108,109,110,111,112,113,114,116,117,118,119,201,202,203,204,205) 
														order by brn.BRNCODE", "Centra", 'TCS');
				break;
			case 'PROJECT':
				/////////////////// $sql_sumry = select_query_json("select APRCODE gridcode, APRNAME gridname from approval_project where DELETED = 'N' and selmode='N' order by APRCODE Asc", "Centra", 'TCS'); -- TEST QUERY
				$sql_sumry = select_query_json("select APRCODE gridcode, APRCODE||' - '||APRNAME gridname from approval_project where DELETED = 'N' order by APRCODE Asc", "Centra", 'TCS');
				break;

			case 'EXPENSE':
				$sql_sumry = select_query_json("select DISTINCT EXPSRNO gridcode, EXPNAME gridname from department_asset where DELETED = 'N' and expsrno > 0 order by EXPSRNO, EXPNAME", "Centra", 'TCS');
				break;
			case 'DEPARTMENT':
				$sql_sumry = select_query_json("select DEPCODE gridcode, ' ( '||EXPNAME||' ) '||DEPNAME gridname from department_asset where deleted = 'N' and expsrno > 0 order by EXPNAME, DEPNAME", "Centra", 'TCS');
				break;

			default:
				// $sql_sumry = select_query_json("select ATCCODE GRIDCODE, ATCNAME GRIDNAME from approval_topcore where deleted = 'N' order by ATCSRNO");
				break;
		}

		foreach ($sql_sumry as $sumry_key => $sumry_value) { ?>
			<option value="<?=$sumry_value['GRIDCODE']?>" <? if($search_summary_value == $sumry_value['GRIDCODE']) { ?> selected <? } ?>><?=$sumry_value['GRIDNAME']?></option>
		<? } ?>
</select>