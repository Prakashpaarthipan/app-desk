<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
$btype=$_REQUEST['btype'];
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

//print_r($_REQUEST);
//echo("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
	
	$userexist=select_query_json("select count(adduser) ucount from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'","Centra","TEST");
	if($userexist[0]['UCOUNT']=='0')
	{
		if($btype=='like')
		{	$lexist=select_query_json("select reqlike  from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'","Centra","TEST");
			//print_r( $lexist);
			if($lexist[0]['REQLIKE']=='')
			{
				$LIKENO = select_query_json("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				$DLIKENO = select_query_json("Select nvl(Max(REQDSLK),0)-1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				//echo("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
				$tbl_appplan1 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan1 = array();
				$field_appplan1['REQLIKE'] = $LIKENO[0]['MAXENTRY'];
				$field_appplan1['REQDSLK'] = $DLIKENO[0]['MAXENTRY'];
				$where_appplan1="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				$insert_appplan1 = update_dbquery($field_appplan1, $tbl_appplan1, $where_appplan1);
				//echo($LIKENO[0]['MAXENTRY']);
				
				
				if($ENTRYEXIST!='')
				{	$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '';
					$g_fld4['REQLIKE'] = '1';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($g_fld4, $g_table4, $where_appplan4);
					print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
				else{
					$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '';
					$g_fld4['REQLIKE'] = '1';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					//$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = insert_test_dbquery($g_fld4, $g_table4);
					print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}

			}
			else{
				echo '00';
			}
		}
		else if($btype=='dislike')
		{	$lexist=select_query_json("select REQDSLK from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'","Centra","TEST");
			echo "select REQDSLK from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'";
			print_r( $lexist);
			if($lexist[0]['REQDSLK']=='')
			{	$LIKENO = select_query_json("Select nvl(Max(REQLIKE),0)-1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST[		'entsrno']."'","Centra","TEST");
				$DLIKENO = select_query_json("Select nvl(Max(REQDSLK),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				//echo("Select nvl(Max(REQDSLK),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
				$tbl_appplan2 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan2 = array();
				$field_appplan2['REQDSLK'] = $DLIKENO[0]['MAXENTRY'];
				$field_appplan2['REQLIKE'] = $LIKENO[0]['MAXENTRY'];
				$where_appplan2="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				$insert_appplan2 = update_dbquery($field_appplan2, $tbl_appplan2, $where_appplan2);
				echo($DLIKENO[0]['MAXENTRY']);
				
				if($ENTRYEXIST!='')
				{	$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '1';
					$g_fld4['REQLIKE'] = '';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($g_fld4, $g_table4, $where_appplan4);
					print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
				else{
					$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '1';
					$g_fld4['REQLIKE'] = '';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					//$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = insert_test_dbquery($g_fld4, $g_table4);
					print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
			}
			else{
				echo '00';
			}
		}
		else if($btype=='favorite')
		{	$lexist=select_query_json("select reqfavi from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'","Centra","TEST");
			print_r( $lexist);
			if($lexist[0]['REQFAVI']=='')
			{
				$FAVINO = select_query_json("Select nvl(REQFAVI,0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				//echo("Select nvl(Max(REQFAVI),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
				$tbl_appplan3 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan3 = array();
				$where_appplan3="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				if($FAVINO[0]['MAXENTRY']==0)
				{	
					$field_appplan3['REQFAVI'] = 1;
				}
				else
				{	
					$field_appplan3['REQFAVI'] = 0;
				}
				
				$insert_appplan = update_dbquery($field_appplan3, $tbl_appplan3, $where_appplan3);
				//echo($field_appplan3['REQFAVI']);
				$g_table4 = "PROCESS_REQUIREMENT_RATING";
				$g_fld4 = array();
				$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
				$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
				$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
				$g_fld4['REQFAVI'] = '1';
				$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_insert_subject = insert_test_dbquery($g_fld4,$g_table4);
				echo '';

			}
			else{
				echo '00';
			}
		}
	}
	else
	{	if($btype=='like')
		{	$lexist=select_query_json("select reqlike  from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'","Centra","TEST");
			//print_r( $lexist);
			if($lexist[0]['REQLIKE']=='')
			{
				$LIKENO = select_query_json("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				$DLIKENO = select_query_json("Select nvl(Max(REQDSLK),0)-1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				//echo("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
				$tbl_appplan1 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan1 = array();
				$field_appplan1['REQLIKE'] = $LIKENO[0]['MAXENTRY'];
				$field_appplan1['REQDSLK'] = $DLIKENO[0]['MAXENTRY'];
				$where_appplan1="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				$insert_appplan1 = update_dbquery($field_appplan1, $tbl_appplan1, $where_appplan1);
				//echo($LIKENO[0]['MAXENTRY']);
				
				
				if($ENTRYEXIST!='')
				{	$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '';
					$g_fld4['REQLIKE'] = '1';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($g_fld4, $g_table4, $where_appplan4);
					print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
				else{
					$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '';
					$g_fld4['REQLIKE'] = '1';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					//$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = insert_test_dbquery($g_fld4, $g_table4);
					print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
			}
			else{
				echo '00';
			}
		}
		else if($btype=='dislike')
		{	$lexist=select_query_json("select REQDSLK from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'","Centra","TEST");
			//echo "select REQDSLK from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'";
			print_r( $lexist);
			if($lexist[0]['REQDSLK']=='')
			{	$LIKENO = select_query_json("Select nvl(Max(REQLIKE),0)-1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST[		'entsrno']."'","Centra","TEST");
				$DLIKENO = select_query_json("Select nvl(Max(REQDSLK),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				//echo("Select nvl(Max(REQDSLK),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
				$tbl_appplan2 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan2 = array();
				$field_appplan2['REQDSLK'] = $DLIKENO[0]['MAXENTRY'];
				$where_appplan2="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				$insert_appplan2 = update_dbquery($field_appplan2, $tbl_appplan2, $where_appplan2);
				//echo($DLIKENO[0]['MAXENTRY']);
				$ENTRYEXIST = select_query_json("Select * MAXENTRY From PROCESS_REQUIREMENT_RATING where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				if($ENTRYEXIST!='')
				{	$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '1';
					$g_fld4['REQLIKE'] = '';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($g_fld4, $g_table4, $where_appplan4);
					print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
				else{
					$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '1';
					$g_fld4['REQLIKE'] = '';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					//$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = insert_test_dbquery($g_fld4, $g_table4);
					print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
			}
			else{
				echo '00';
			}
		}
		else if($btype=='favorite')
		{	$lexist=select_query_json("select reqfavi from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'","Centra","TEST");
			print_r( $lexist);
			if($lexist[0]['REQFAVI']=='')
			{
				$FAVINO = select_query_json("Select nvl(REQFAVI,0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
				//echo("Select nvl(Max(REQFAVI),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
				$tbl_appplan3 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan3 = array();
				$where_appplan3="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				if($FAVINO[0]['MAXENTRY']==0)
				{	
					$field_appplan3['REQFAVI'] = 1;
				}
				else
				{	
					$field_appplan3['REQFAVI'] = 0;
				}
				
				$insert_appplan = update_dbquery($field_appplan3, $tbl_appplan3, $where_appplan3);
				//echo($field_appplan3['REQFAVI']);
				
				
				$g_table4 = "PROCESS_REQUIREMENT_RATING";
				$g_fld4 = array();
				$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
				$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
				$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
				$g_fld4['REQFAVI'] = '1';
				$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				$insert_appplan1 = update_dbquery($g_fld4, $g_table4, $where_appplan4);
				echo '12';

			}
			else{
				echo '00';
			}
		}
	}
?>