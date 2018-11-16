<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(0);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
//include_once('../lib/function_connect.php');

if($_REQUEST['action']=='insert')
{	print_r($_REQUEST);
	// $EMP=explode(' - ',$_REQUEST['sempsrno']);
	// $EMP=explode(') ',$EMP[0]);
	// $EMP=$EMP[1];
	// print_r($EMP);
	$EMP=$_REQUEST['sempsrno'];
	print_r($EMP);
	$arr=array();
	
	$nuser=sizeof($_REQUEST['txt_value']);
	$sql_cor = select_query_json("select * from approval_branch_head where tarnumb ='".$_REQUEST['tarnumb']."'  order by BRNHDCD,BRNHDSR", "Centra", "TEST");
	foreach($sql_cor as $key=>$value)
	{	
		$arr[$value['BRNHDCD']][count($arr[$value['BRNHDCD']])]=$value;
	}
	foreach($arr as $key=>$value)
	{	$flag=1;
		$count=0;
		$BRNHDSR;
		for($i=sizeof($value)-1;$i>=0;$i--)
		{
			if($value[$i]['EMPCODE']==$EMP[0])
			{
				$flag=0;
				$BRNHDSR=$value[$i]['BRNHDSR'];
				continue;
			}
			if($flag==1)
			{	$count++;
				$g_table = "APPROVAL_BRANCH_HEAD";
				$g_fld4 = array();
				$g_fld4['BRNHDSR'] = $value[$i]['BRNHDSR']+$nuser;
				$g_fld4['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld4['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_appplan="BRNHDCD = '".$value[$i]['BRNHDCD']."' and BRNHDSR = '".$value[$i]['BRNHDSR']."'";
				print_r($g_fld4);
				print_r($where_appplan);
				echo("----------------");
				$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
			}
		}
		for($i=0;$i<$nuser;$i++)
		{	
			$sr=explode(' - ',$_REQUEST['txt_value'][$i]);
			$sql_emp = select_query_json("select empsrno from employee_office where empcode='".$sr[0]."'", "Centra", "TEST");
			$g_table = "APPROVAL_BRANCH_HEAD";
			$g_fld4 = array();
			$g_fld['BRNHDCD'] =  $value[0]['BRNHDCD'];
			$g_fld['BRNCODE'] =  $value[0]['BRNCODE'];
			$g_fld['EMPSRNO'] =  $sql_emp[0]['EMPSRNO'];
			$g_fld['EMPCODE'] =  $sr[0];
			$g_fld['EMPNAME'] =  $sr[1];
			$g_fld['ADDUSER'] =  $_SESSION['tcs_usrcode'];
			$g_fld['ADDDATE'] =  'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$g_fld['EDTUSER'] =  '';
			$g_fld['EDTDATE'] =  '';
			$g_fld['DELETED'] =  'N';
			$g_fld['DELUSER'] =  '';
			$g_fld['DELDATE'] =  '';
			$g_fld['BRNHDSR'] =  $BRNHDSR+$i+1;
			$g_fld['DEPCODE'] =  $value[0]['DEPCODE'];
			$g_fld['TARNUMB'] =  $value[0]['TARNUMB'];
			$g_fld['APRVALU'] =  $value[0]['APRVALU'];
			$g_fld['APMCODE'] =  $value[0]['APMCODE'];
			echo("----------------");
			$g_insert_subject = insert_test_dbquery($g_fld,$g_table);
			print_r($g_fld);
				print_r($g_table);
		}
		$count=0;
		$flag=0;
	}
}
if($_REQUEST['action']=='alter')
{	print_r($_REQUEST);
	$arr=array();
	$sql_cor = select_query_json("select * from approval_branch_head where tarnumb = '".$_REQUEST['tarnumb']."' and deleted='N' order by BRNHDCD,BRNHDSR", "Centra", "TEST");
	foreach($sql_cor as $key=>$value)
	{	
		$arr[$value['BRNHDCD']][count($arr[$value['BRNHDCD']])]=$value;
	}
	$newflow=$_REQUEST['newflow'];
	$newflow=array_flip($newflow);

	foreach($arr as $key=>$value)
	{
		for($i=0;$i<sizeof($value);$i++)
		{	echo("++".$newflow[$value[$i]['EMPCODE']]."   =>");
			if(!is_null($newflow[$value[$i]['EMPCODE']]))
			{
				$g_table = "APPROVAL_BRANCH_HEAD";
				$g_fld4 = array();
				$g_fld4['BRNHDSR'] = $value[$i]['BRNHDSR']+50;
				$where_appplan="BRNHDCD = '".$value[$i]['BRNHDCD']."' and BRNHDSR = '".$value[$i]['BRNHDSR']."' and TARNUMB='".$_REQUEST['tarnumb']."'";
				print_r($g_fld4);
				print_r($where_appplan);
				$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
				echo('\n');
			}
		}
		
	}
	foreach($arr as $key=>$value)
	{
		for($i=0;$i<sizeof($value);$i++)
		{	if(!is_null($newflow[$value[$i]['EMPCODE']]))
			{	
				$g_table = "APPROVAL_BRANCH_HEAD";
				$g_fld4 = array();
				$g_fld4['BRNHDSR'] = $newflow[$value[$i]['EMPCODE']]+5;
				$g_fld4['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld4['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_appplan="BRNHDCD = '".$value[$i]['BRNHDCD']."' and BRNHDSR = '".($value[$i]['BRNHDSR']+50)."' and TARNUMB='".$_REQUEST['tarnumb']."'";
				print_r($g_fld4);
				print_r($where_appplan);
				$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
				echo('\n');
			}
		}
	}
}

if($_REQUEST['action']=='corpload')
{	//print_r($_REQUEST);
	$sql_cor = select_query_json("select * from approval_branch_head where tarnumb = '".$_REQUEST['tarnumb']."' and deleted = 'N' and APRVALU > 0 AND BRNCODE=888 order by brncode, brnhdsr", "Centra", "TEST");
	//print_r($sql_cor);
	?>
	 <form name='mainform' id='mainform' action='' method='post'>
        <input type="hidden" name="action" id="action" value="insert">
        <input type="hidden" value="" id="sempsrno" name="sempsrno"/>
        <input type="hidden" value="" id="tarnumb" name="tarnumb"/>
        <ul>
            <?for($i=0;$i<sizeof($sql_cor);$i++){?>
                <li id="<?=$i;?>" class="cus" >(<?=$i+1?>) <?=$sql_cor[$i]['EMPCODE']?> - <?=$sql_cor[$i]['EMPNAME']?></li>
            <?}?>
        </ul>
        <center>
            <input type="button" id="up" value="up" class="btn btn-primary" />
            <input type="button" value="down" id="down" class="btn btn-danger"/>
        </center>
        
        <div class="col-lg-12 col-md-12">
            <div class="col-lg-4 col-md-4">
                <label style="height: 100%;">Add Member<span style='color:red'>*</span></label>
        </div>
            <div id="add_filed">
                <div style="width:100%;">
                    <div style="width:90%;float:left;padding:10px 0px;">
                        <input type="text" name = 'txt_value[]' class="form-control  txt_mem" id='txt_value1'  placeholder= "NAME" style="text-transform: uppercase;" data-toggle ="tooltip" title ="values" required/>
                    </div>
                    <span style="width: 10%;float:right;padding:10px 0px;" class="input-group-btn"><button id="add_ledger_button" type="button" onclick="add_feild()" class="btn btn-success btn-add" title ="Add More">+</button>
                  </span>
                </div>
            </div>
        </div>
        <center style="padding-bottom: 10px">
            <input type="button" name="submit" value="Submit" class="btn btn-warning" onclick="nsubmit();" />
        </center>
    </form>
<?}
if($_REQUEST['action']=='branchalter')
{
	print_r($_REQUEST);
	$arr=array();
	$sql_cor = select_query_json("select * from approval_branch_head where tarnumb='".$_REQUEST['tarnumb']."' and brncode='".$_REQUEST['branch']."' and deleted='N' order by brnhdsr", "Centra", "TEST");
	foreach($sql_cor as $key=>$value)
	{	
		$arr[$value['BRNHDCD']][count($arr[$value['BRNHDCD']])]=$value;
	}
	print_r($arr);
	$newflow=$_REQUEST['newflow'];
	$newflow=array_flip($newflow);
	print_r($newflow);
	foreach($arr as $key=>$value)
	{
		for($i=0;$i<sizeof($value);$i++)
		{	
			if(!is_null($newflow[$value[$i]['EMPCODE']]))
			{
				$g_table = "APPROVAL_BRANCH_HEAD";
				$g_fld4 = array();
				$g_fld4['BRNHDSR'] = $value[$i]['BRNHDSR']+50;
				$where_appplan="BRNHDCD = '".$value[$i]['BRNHDCD']."' and BRNHDSR = '".$value[$i]['BRNHDSR']."' and tarnumb='".$_REQUEST['tarnumb']."'";
				print_r($g_fld4);
				print_r($where_appplan);
				$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
			}
		}
	}
	foreach($arr as $key=>$value)
	{
		for($i=0;$i<sizeof($value);$i++)
		{	if(!is_null($newflow[$value[$i]['EMPCODE']]))
			{	$g_table = "APPROVAL_BRANCH_HEAD";
				$g_fld4 = array();
				$g_fld4['BRNHDSR'] = $newflow[$value[$i]['EMPCODE']]+5;
				$g_fld4['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld4['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_appplan="BRNHDCD = '".$value[$i]['BRNHDCD']."' and BRNHDSR = '".($value[$i]['BRNHDSR']+50)."'";
				print_r($g_fld4);
				print_r($where_appplan);
				$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
			}
		}
	}
}

if($_REQUEST['action']=='dataload')
{	//print_r($_REQUEST);
	$sql_cor = select_query_json("select * from approval_branch_head where tarnumb = '".$_REQUEST['tarnumb']."' and deleted = 'N' and APRVALU > 0 AND BRNCODE=888 order by brncode, brnhdsr","Centra", "TEST");
	//print_r($sql_cor);
	echo(json_encode($sql_cor));
}
if($_REQUEST['action']=='alterload')
{	//print_r($_REQUEST);
	$sql_cor = select_query_json("select * from approval_branch_head where tarnumb = '".$_REQUEST['tarnumb']."' and deleted = 'N' and APRVALU > 0 AND BRNCODE=888 order by brncode, brnhdsr","Centra", "TEST");?>
	<ul id="columns">
        <?for($i=0;$i<sizeof($sql_cor);$i++){?>
            <li id="l<?=$i;?>" class="column" draggable="true" style="margin: 5px;">(<?=$i+1?>) <?=$sql_cor[$i]['EMPCODE']?> - <?=$sql_cor[$i]['EMPNAME']?>
            <span class="fa fa-times" style="float: right;" onclick="removecorp(<?=$sql_cor[$i]['BRNHDSR']?>,<?=$sql_cor[$i]['EMPCODE'];?>);"></span></li>
        <?}?>
    </ul>
    <center style="padding-bottom: 10px">
        <input type="button" id="up" value="Alter" class="btn btn-primary"  onclick="alterflow()"/>
    </center>
<?}
if($_REQUEST['action']=='branchload')
{	//print_r($_REQUEST);
	?>
	<?$sql_brn = select_query_json("select brnhdsr,empcode,empname from approval_branch_head where tarnumb='".$_REQUEST['tarnumb']."' and brncode='".$_REQUEST['branch']."' and deleted='N' order by brnhdsr", "Centra", "TEST");?>
	<ul id="branches">
	    <?for($i=0;$i<sizeof($sql_brn);$i++){?>
	        <li id="b<?=$i;?>" class="column" draggable="true" style="margin: 5px;">(<?=$i+1?>) <?=$sql_brn[$i]['EMPCODE']?> - <?=$sql_brn[$i]['EMPNAME']?><span class="fa fa-times" style="float: right;" onclick="removebranch(<?=$sql_brn[$i]['BRNHDSR']?>,<?=$sql_brn[$i]['EMPCODE']?>);"></span></li>
	    <?}?>
	</ul>
	<center style="padding-bottom: 10px">
	    <input type="button" id="up" value="Alter" class="btn btn-primary"  onclick="branchalter();"/>
	</center>
<?}
if($_REQUEST['action']=='removebranch')
{	$g_table = "APPROVAL_BRANCH_HEAD";
	$g_fld4 = array();
	$g_fld4['DELETED'] = 'Y';
	$g_fld4['EDTUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld4['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$where_appplan="BRNCODE = '".$_REQUEST['branch']."' and TARNUMB = '".$_REQUEST['tarnumb']."' AND EMPCODE ='".$_REQUEST['empcode']."' and brnhdsr='".$_REQUEST['brnhdsr']."'";
	print_r($g_fld4);
	print_r($where_appplan);
	$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
}
if($_REQUEST['action']=='removecorp')
{	$g_table = "APPROVAL_BRANCH_HEAD";
	$g_fld4 = array();
	$g_fld4['DELETED'] = 'Y';
	$g_fld4['EDTUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld4['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$where_appplan="TARNUMB = '".$_REQUEST['tarnumb']."' AND EMPCODE ='".$_REQUEST['empcode']."' and brnhdsr='".$_REQUEST['brnhdsr']."'";
	print_r($g_fld4);
	print_r($where_appplan);
	$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
}

?>



admin_subject_update.php