<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

if($_REQUEST['action'] == 'subcore')
{
	$cm = $_REQUEST['topcore_id'];
	if($cm == 4) {
		$sql_subcore = select_query_json("select ec.*, decode(ec.CORCODE, 37, 1, 110, 2, 61, 3, 22, 4, 62, 5, 40, 6, 9, 7, 66, 8, 27, 9, 23, 10, 11) ordrby
												from empcore_section ec
												where ec.DELETED = 'N' and ec.TOPCORE in (".$cm.") and ec.esecode > 0 order by ordrby, ec.TOPCORE, ec.CORNAME Asc", "Centra", 'TCS');
	} else {
		$sql_subcore = select_query_json("select * from empcore_section where DELETED = 'N' and TOPCORE in (".$cm.") and esecode > 0 order by TOPCORE, CORNAME Asc", "Centra", 'TCS');
	}
	for($subcore_i = 0; $subcore_i < count($sql_subcore); $subcore_i++) { ?>
		<option value='<?=$sql_subcore[$subcore_i]['CORCODE']?>'
			<? if($sql_reqid[0]['CORCODE'] == $sql_subcore[$subcore_i]['CORCODE']) { ?> selected <? } ?>><?=$sql_subcore[$subcore_i]['CORNAME']?>
		</option>
	<? }
}

if($_REQUEST['action'] == 'projectid'){
	$sql_proid = select_query_json("select nvl(max(aprcode),0)+1 proid from approval_project where brncode=".$brncode." and topcore=".$topcore." and subcore=".$subcore."", "Centra", 'TCS');
	echo $sql_proid[0]['PROID'];
}

if($_REQUEST['action'] == 'employee'){
	if($brncode == 100)
  {
		$brncode=888;
	}
	$result = select_query_json("select * from employee_office where (empcode like '".$slt_emp."%' or empname like '".strtoupper($slt_emp)."%') and brncode=".$brncode."", "Centra", 'TCS');
	$data = array();
    if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result);$rowi++)
    {
			array_push($data, $result[$rowi]['EMPCODE']." - ".$result[$rowi]['EMPNAME']);
	  }
	} else {
		array_push($data, '');
	}
    echo json_encode($data);
}

if($_REQUEST['action'] == 'find_subtype')
{
	$cm = $_REQUEST['slt_project'];
	$sql_project = select_query_json("select * from approval_project where DELETED = 'N' and APRCODE = '".$cm."' order by APRCODE Asc", "Centra", 'TCS'); ?>
	<select class="form-control custom-select chosn" onblur="get_targetdates(); find_tags();" onChange="getsubtype(this.value)" tabindex='3' required name='slt_submission' id='slt_submission' data-toggle="tooltip" data-placement="top" data-original-title="Type of Submission">
        <?  if($sql_project[0]['BRNPROJ'] == 'P')
        {
        		$sql_submission_type = select_query_json("select * from approval_type where DELETED = 'N' order by ATYSRNO", "Centra", 'TEST'); // 1 for FIXED BUDGET, 7 for Extra Budget
        }
        elseif($sql_project[0]['BRNPROJ'] == 'B')
        {
        		$sql_submission_type = select_query_json("select * from approval_type where DELETED = 'N' order by ATYSRNO", "Centra", 'TEST'); // 1 for FIXED BUDGET, 7 for Extra Budget
        } ?>
            <option value='' <? if($sql_reqid[0]['ATYCODE'] == '') { ?> selected <? } ?>>-- Choose Type of Submission --</option>
            <? for($submission_type_i = 0; $submission_type_i < count($sql_submission_type); $submission_type_i++)
            { ?>
                <option value='<?=$sql_submission_type[$submission_type_i]['ATYCODE']?>' <? if($sql_reqid[0]['ATYCODE'] == $sql_submission_type[$submission_type_i]['ATYCODE']) { ?> selected <? } ?>><?=$sql_submission_type[$submission_type_i]['ATYNAME']?></option>
        <?  } ?>
    </select>
<? } ?>
