<?php
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);
$cm = $_REQUEST['topcore_id'];

if($action == 'find_subcore') { ?>
<input type='hidden' name='slt_topcore_name' id='slt_topcore_name' value='<?=$_SESSION['tcs_emptopcore']?>'>
<input type='hidden' name='slt_topcore' id='slt_topcore' value='<?=$_SESSION['tcs_emptopcore_code']?>'>
<!-- Sub Core -->
<div class="form-group trbg">
    <label class="col-md-3 control-label" style="text-align: right !important;">Core <span style='color:red'>*</span></label>
    <div class="col-lg-9 col-xs-12">
        <? /* if($_REQUEST['action'] == 'view') {
            $sql_subcore = select_query_json("select * from empcore_section 
                                                    where DELETED = 'N' and TOPCORE in (".$sql_reqid[0]['ATCCODE'].") and CORCODE = '".$sql_reqid[0]['SUBCORE']."' 
                                                    order by CORNAME Asc"); ?>
            : <?=$sql_subcore[0]['CORNAME']?>
        <? } else { */ ?>
            <? if($_REQUEST['action'] == 'edit') { ?> <input type='hidden' name='hid_slt_subcore' id='hid_slt_subcore' value='<?=$sql_reqid[0]['SUBCORE']?>'> <? } ?>
            <select <? if($_REQUEST['action'] == 'edit') { ?>disabled class="form-control custom-select"<? } else { ?>class="form-control custom-select chosn"<? } ?> tabindex='10' required name='slt_subcore' id='slt_subcore' data-toggle="tooltip" data-placement="top" title="Core" onChange="get_topcore(this.value)" onBlur="get_topcore(this.value)">
            <?  $sql_subcore = select_query_json("select distinct sec.esecode, substr(sec.esename, 4, 25) esename from empsection sec 
                                                        where DELETED = 'N' and esecode > 0 
                                                        order by ESENAME Asc", "Centra", 'TCS');
                for($subcore_i = 0; $subcore_i < count($sql_subcore); $subcore_i++) { ?>
                    <option value='<?=$sql_subcore[$subcore_i]['ESECODE']?>' <? if($sql_reqid[0]['SUBCORE'] == $sql_subcore[$subcore_i]['ESECODE']) { ?> selected <? } ?>><?=$sql_subcore[$subcore_i]['ESENAME']?></option>
            <? } ?>
            </select>
        <? // } ?>
    </div>
</div>
<div class='clear clear_both'></div>
<!-- Sub Core -->
<? } elseif($action == 'find_org_subcore') { 
	$sql_subcore = select_query_json("select distinct apm.APMCODE, apm.APMNAME, apm.TARNUMB, atc.ATCCODE, atc.ATCNAME, sec.esecode, substr(sec.esename, 4, 25) esename 
											from APPROVAL_master apm, APPROVAL_topcore atc, empsection sec 
											where sec.esecode = apm.subcore and apm.topcore = atc.atccode and apm.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N' 
												and apm.TARNUMB = '".$tarnumb."' and rownum <= 1 order by apm.APMNAME asc", "Centra", 'TEST');
    for($subcore_i = 0; $subcore_i < count($sql_subcore); $subcore_i++) { ?>
		<input type='hidden' name='hid_slt_subcore' id='hid_slt_subcore' value='<?=$sql_subcore[$subcore_i]['ESECODE']?>'>
		<input type='hidden' name='slt_subcore' id='slt_subcore' value='<?=$sql_subcore[$subcore_i]['ESECODE']?>'>
		<input type='hidden' name='slt_topcore_name' id='slt_topcore_name' value='<?=$sql_subcore[$subcore_i]['ATCNAME']?>'>
		<input type='hidden' name='slt_topcore' id='slt_topcore' value='<?=$sql_subcore[$subcore_i]['ATCCODE']?>'>
<? }
} 
elseif($action == 'product_rate'){?>

<div class="form-group trbg">
	<input type='hidden' name='slt_topcore_name' id='slt_topcore_name' value='<?=$_SESSION['tcs_emptopcore']?>'>
<input type='hidden' name='slt_topcore' id='slt_topcore' value='<?=$_SESSION['tcs_emptopcore_code']?>'>
    <label class="col-md-3 control-label" style="text-align: right !important;">Core <span style='color:red'>*</span></label>
    <div class="col-lg-9 col-xs-12">
        <? /* if($_REQUEST['action'] == 'view') {
            $sql_subcore = select_query_json("select * from empcore_section 
                                                    where DELETED = 'N' and TOPCORE in (".$sql_reqid[0]['ATCCODE'].") and CORCODE = '".$sql_reqid[0]['SUBCORE']."' 
                                                    order by CORNAME Asc"); ?>
            : <?=$sql_subcore[0]['CORNAME']?>
        <? } else { */ ?>
            <? if($_REQUEST['action'] == 'edit') { ?> <input type='hidden' name='hid_slt_subcore' id='hid_slt_subcore' value='<?=$sql_reqid[0]['SUBCORE']?>'> <? } ?>
            <select <? if($_REQUEST['action'] == 'edit') { ?>disabled class="form-control custom-select"<? } else { ?>class="form-control custom-select chosn"<? } ?> tabindex='10' required name='slt_subcore' id='slt_subcore' data-toggle="tooltip" data-placement="top" title="Core" onChange="get_topcore(this.value)" >
			
            <?  $sql_subcore = select_query_json("select distinct sec.esecode, substr(sec.esename, 4, 25) esename from empsection sec 
                                                        where DELETED = 'N' and esecode =40
                                                        order by ESENAME Asc", "Centra", 'TCS');
                for($subcore_i = 0; $subcore_i < count($sql_subcore); $subcore_i++) { ?>
                    <option value='<?=$sql_subcore[$subcore_i]['ESECODE']?>'><?=$sql_subcore[$subcore_i]['ESENAME']?></option>
            <? } ?>
            </select>
        <? // } ?>
    </div>
</div>
<div class='clear clear_both'></div>
<?}

else { /* ?>
<!-- Sub Core -->
<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'>Core <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<? if($_REQUEST['action'] == 'view') { ?>
			: <?=$sql_reqid[0]['CORNAME']?>
		<? } else { ?>
			<? if($_REQUEST['action'] == 'edit') { ?> <input type='hidden' name='hid_slt_subcore' id='hid_slt_subcore' value='<?=$sql_reqid[0]['CORCODE']?>'> <? } ?>
			<select class="form-control" tabindex='5' required name='slt_subcore' id='slt_subcore' data-toggle="tooltip" data-placement="top" <? if($_REQUEST['action'] == 'edit') { ?> disabled <? } ?> title="Core" onChange="getapproval_listings()" onBlur="getapproval_listings()">
			<? 	if($_REQUEST['action'] == 'edit') {
			    	if($cm == 4) {
			    		$sql_subcore = select_query_json("select ec.*, decode(ec.CORCODE, 37, 1, 110, 2, 61, 3, 22, 4, 62, 5, 40, 6, 9, 7, 66, 8, 27, 9, 23, 10, 11) ordrby
																from empcore_section ec 
																where ec.DELETED = 'N' and ec.TOPCORE in (".$cm.") and ec.esecode > 0 order by ordrby, ec.TOPCORE, ec.CORNAME Asc", "Centra", 'TCS');
			    	} else { 
						$sql_subcore = select_query_json("select * from empcore_section where DELETED = 'N' and TOPCORE in (".$cm.") and esecode > 0 order by TOPCORE, CORNAME Asc", "Centra", 'TCS');
					}
			    } else {
			    	if($cm == 4) {
			    		$sql_subcore = select_query_json("select ec.*, decode(ec.CORCODE, 37, 1, 110, 2, 61, 3, 22, 4, 62, 5, 40, 6, 9, 7, 66, 8, 27, 9, 23, 10, 11) ordrby
																from empcore_section ec 
																where ec.DELETED = 'N' and ec.TOPCORE in (".$cm.") and ec.esecode > 0 order by ordrby, ec.TOPCORE, ec.CORNAME Asc", "Centra", 'TCS');
			    	} else { 
						$sql_subcore = select_query_json("select * from empcore_section where DELETED = 'N' and TOPCORE in (".$cm.") and esecode > 0 order by TOPCORE, CORNAME Asc", "Centra", 'TCS');
					}
			    }

				for($subcore_i = 0; $subcore_i < count($sql_subcore); $subcore_i++) { ?>
					<option value='<?=$sql_subcore[$subcore_i]['CORCODE']?>' <? if($sql_reqid[0]['CORCODE'] == $sql_subcore[$subcore_i]['CORCODE']) { ?> selected <? } ?>><?=$sql_subcore[$subcore_i]['CORNAME']?></option>
			<? 	} 
			
				if(count($sql_subcore) <= 0) { ?>
					<option value='0' <? if($sql_subcore[$subcore_i]['CORCODE'] == 0) { ?> selected <? } ?>> -- </option>
				<? } ?>
			</select>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Sub Core -->
<? */ } ?>