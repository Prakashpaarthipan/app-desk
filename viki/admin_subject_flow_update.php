<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(0);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

if($_REQUEST['action']=='insert')
{	$emp=explode(' - ',$_REQUEST['user']);
	$emp=$emp[0];
	$sql_users = select_query_json("select BRNHDCD,tarnumb,max(apr.brnhdsr)+1 nbrnhdsr from trandata.approval_branch_head apr where  apr.apmcode ='".$_REQUEST['gtarnumb']."'  and apr.APRVALU > 100000 and apr.brncode='".$_REQUEST['gbrn']."' group by BRNHDCD,tarnumb", "Centra", 'TEST');
	print_r($sql_users);
	$emp = select_query_json("select empsrno,empname,empcode from employee_office where empcode='".$emp."'", "Centra", 'TCS');
	echo("select empsrno,empname,empcode from employee_office where empcode='".$emp."'");
	$g_table='approval_branch_head';
	$g_fld['BRNHDCD']=$sql_users[0]['BRNHDCD'];
	$g_fld['BRNCODE']=$_REQUEST['gbrn'];
	$g_fld['EMPSRNO']=$emp[0]['EMPSRNO'];
	$g_fld['EMPCODE']=$emp[0]['EMPCODE'];
	$g_fld['EMPNAME']=$emp[0]['EMPNAME'];
	$g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
	$g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld['EDTUSER']='';
	$g_fld['EDTDATE']='';
	$g_fld['DELETED']='N';
	$g_fld['DELUSER']='';
	$g_fld['DELDATE']='';
	$g_fld['BRNHDSR']=$sql_users[0]['NBRNHDSR'];
	$g_fld['DEPCODE']='';
	$g_fld['TARNUMB']=$sql_users[0]['TARNUMB'];
	$g_fld['APRVALU']='1000000000';
	$g_fld['APMCODE']=$_REQUEST['gtarnumb'];
	print_r($g_fld);
	$insert_appplan1 = insert_test_dbquery($g_fld, $g_table);		
}
if($_REQUEST['action']=='alterflow')
{
	$sql_users = select_query_json("select BRNHDCD,BRNCODE,EMPCODE,EMPSRNO,DELETED,BRNHDSR,TARNUMB,APMCODE,APRVALU from trandata.approval_branch_head apr where  apr.apmcode ='".$_REQUEST['gtarnumb']."' and apr.APRVALU > 100000 and apr.brncode='".$_REQUEST['gbrn']."' order by BRNHDSR", "Centra", 'TEST');
	$g_table="approval_branch_head";
	for($i=0;$i<count($sql_users);$i++)
	{	
		if($sql_users[$i]['BRNHDSR']<50)
		{
			$g_fld['BRNHDSR']=(50+$i);
			$where="BRNHDCD='".$sql_users[$i]['BRNHDCD']."' and BRNCODE='".$sql_users[$i]['BRNCODE']."' and EMPSRNO='".$sql_users[$i]['EMPSRNO']."' and DELETED='".$sql_users[$i]['DELETED']."' and BRNHDSR='".$sql_users[$i]['BRNHDSR']."' and TARNUMB='".$sql_users[$i]['TARNUMB']."' and APMCODE='".$sql_users[$i]['APMCODE']."' and APRVALU='".$sql_users[$i]['APRVALU']."'";
			print_r($g_fld);
			print_r($where);
			$insert_appplan1 = update_test_dbquery($g_fld, $g_table,$where);
		}	
	}
	$arr=array();
	foreach ($_REQUEST['newflow'] as $key => $value) {
		$arr[$value]=($key+1);
	}
	asort($arr);
	echo("-----------------------y----------------------\n");
	print_r($arr);
	$sql_users = select_query_json("select BRNHDCD,BRNCODE,EMPCODE,EMPSRNO,DELETED,BRNHDSR,TARNUMB,APRVALU,APMCODE from trandata.approval_branch_head apr where  apr.apmcode ='".$_REQUEST['gtarnumb']."' and apr.APRVALU > 100000 and apr.brncode='".$_REQUEST['gbrn']."' and deleted='Y' order by BRNHDSR", "Centra", 'TEST');
	for($i=0;$i<count($sql_users);$i++)
	{	
			$g_fld['BRNHDSR']=(80+$i);
			$where="BRNHDCD='".$sql_users[$i]['BRNHDCD']."' and BRNCODE='".$sql_users[$i]['BRNCODE']."' and EMPSRNO='".$sql_users[$i]['EMPSRNO']."' and DELETED='".$sql_users[$i]['DELETED']."' and BRNHDSR='".$sql_users[$i]['BRNHDSR']."' and TARNUMB='".$sql_users[$i]['TARNUMB']."' and APMCODE='".$sql_users[$i]['APMCODE']."' and APRVALU='".$sql_users[$i]['APRVALU']."'";
			print_r($g_fld);
			print_r($where);
			$insert_appplan1 = update_test_dbquery($g_fld, $g_table,$where);
	}
	$sql_users = select_query_json("select BRNHDCD,BRNCODE,EMPCODE,EMPSRNO,DELETED,BRNHDSR,TARNUMB,APRVALU,APMCODE from trandata.approval_branch_head apr where  apr.apmcode ='".$_REQUEST['gtarnumb']."' and apr.APRVALU > 100000 and apr.brncode='".$_REQUEST['gbrn']."' and deleted='N' order by BRNHDSR", "Centra", 'TEST');
	$exist=array();$h=0;
	$flag=0;
	echo("----------------------n --------------------------------");
	foreach ($arr as $key => $value) 
	{	$chk=0;
		for($i=0;$i<count($sql_users);$i++)
		{	if($sql_users[$i]['EMPCODE']==$key)
			{	
				if($chk==1)
				{
					$flag+=1;
				}
				$g_fld['BRNHDSR']=$value+$flag;
				$where="BRNHDCD='".$sql_users[$i]['BRNHDCD']."' and BRNCODE='".$sql_users[$i]['BRNCODE']."' and EMPSRNO='".$sql_users[$i]['EMPSRNO']."' and DELETED='".$sql_users[$i]['DELETED']."' and BRNHDSR='".$sql_users[$i]['BRNHDSR']."' and TARNUMB='".$sql_users[$i]['TARNUMB']."' and APMCODE='".$sql_users[$i]['APMCODE']."' and APRVALU='".$sql_users[$i]['APRVALU']."'";
				print_r($g_fld);
				print_r($where);
				$chk=1;
				$insert_appplan1 = update_test_dbquery($g_fld, $g_table,$where);
			}
		}
		$chk=0;
	}
	$sql_users = select_query_json("select BRNHDCD,BRNCODE,EMPCODE,EMPSRNO,DELETED,BRNHDSR,TARNUMB,APRVALU,APMCODE from trandata.approval_branch_head apr where  apr.apmcode ='".$_REQUEST['gtarnumb']."' and apr.APRVALU > 100000 and apr.brncode='".$_REQUEST['gbrn']."' and deleted='Y' order by BRNHDSR", "Centra", 'TEST');
	$sql_maxbrn = select_query_json("select nvl(max(BRNHDSR),0)+1 MAXBRNHDSR from trandata.approval_branch_head apr where  apr.tarnumb ='9057' and apr.APRVALU > 100000 and apr.brncode='1' and deleted='N'", "Centra", 'TEST');
	for($i=0;$i<count($sql_users);$i++)
	{	
			$g_fld['BRNHDSR']=($sql_maxbrn[0]['MAXBRNHDSR']+$i);
			$where="BRNHDCD='".$sql_users[$i]['BRNHDCD']."' and BRNCODE='".$sql_users[$i]['BRNCODE']."' and EMPSRNO='".$sql_users[$i]['EMPSRNO']."' and DELETED='".$sql_users[$i]['DELETED']."' and BRNHDSR='".$sql_users[$i]['BRNHDSR']."' and TARNUMB='".$sql_users[$i]['TARNUMB']."' and APMCODE='".$sql_users[$i]['APMCODE']."' and APRVALU='".$sql_users[$i]['APRVALU']."'";
			print_r($g_fld);
			print_r($where);
			$insert_appplan1 = update_test_dbquery($g_fld, $g_table,$where);
	}

}
if($_REQUEST['action']=='delete')
{
	$g_table="approval_branch_head";
	$g_fld['DELETED']='Y';
	$g_fld['DELUSER']=$_SESSION['tcs_usrcode'];
	$g_fld['DELDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$where="BRNCODE='".$_REQUEST['gbrn']."' AND APMCODE='".$_REQUEST['gtarnumb']."' AND EMPCODE='".$_REQUEST['user']."'";
	print_r($g_fld);
	print_r($where);
	$insert_appplan1 = update_test_dbquery($g_fld, $g_table,$where);	
}
if($_REQUEST['action']=='load')
{
	//print_r($_REQUEST);
	$arr_brn = select_query_json("select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME from trandata.approval_branch_head apr where  apr.apmcode ='".$_REQUEST['tarnumb']."' and apr.APRVALU > 100000 and apr.brncode='".$_REQUEST['brn']."' and deleted='N' order by BRNHDSR", "Centra", 'TEST');
	//print_r($sql_users);
	?>
	<?$exist=array();
    for($i=0;$i<count($arr_brn);$i++)
    {    $temp=explode('-',$arr_brn[$i]['NAME']);
        if(!in_array($temp[0], $exist)){?>
        <div class="row" style="padding-top: 4px;">
            <? $user.=$arr_brn[$i]['NAME'].'~~';
                
                $exist[$i]=$temp[0];
            echo $arr_brn[$i]['NAME']; ?>
        </div>
        <?}}?>
    <input type="hidden" id="users_<?=$_REQUEST['brn'];?>_<?=$_REQUEST['tarnumb']?>" value="<?=$user;?>">
<?}?>