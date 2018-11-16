<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
$btype=$_REQUEST['btype'];
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

$userexist=select_query_json("select count(adduser) as ucount from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."' and entryyr='".$_REQUEST['entryyr']."' and entryno='".$_REQUEST['entryno']."' and entsrno='".$_REQUEST['entsrno']."'","Centra","TEST");
//LIKE DISLIKE AND FAVI COUNT
$LIKENO = select_query_json("Select nvl(Max(REQLIKE),0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
$DLIKENO = select_query_json("Select nvl(Max(REQDSLK),0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
$FAVINO = select_query_json("Select nvl(REQFAVI,0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");

if($userexist[0]['UCOUNT']==0)
{	//echo "hi 0";
	if($btype=='like')
		{	
				//echo("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
				$tbl_appplan1 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan1 = array();
				$field_appplan1['REQLIKE'] = $LIKENO[0]['MAXENTRY']+1;
				
				$where_appplan1="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				$insert_appplan1 = update_dbquery($field_appplan1, $tbl_appplan1, $where_appplan1);
				//echo($LIKENO[0]['MAXENTRY']);
				//INSERTINT IN THE RATINGS TABLE
				{	$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '';
					$g_fld4['REQLIKE'] = '1';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					//$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = insert_dbquery($g_fld4, $g_table4);
					//print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
		}
		else if($btype=='dislike')
		{		$tbl_appplan1 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan1 = array();
				$field_appplan1['REQDSLK'] = $DLIKENO[0]['MAXENTRY']+1;
				$where_appplan1="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				$insert_appplan1 = update_dbquery($field_appplan1, $tbl_appplan1, $where_appplan1);
				//echo($LIKENO[0]['MAXENTRY']);
				//INSERTINT IN THE RATINGS TABLE
				{	$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQDSLK'] = '1';
					$g_fld4['REQLIKE'] = '';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					//$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = insert_dbquery($g_fld4, $g_table4);
					//print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
				}
		}
		else if($btype=='favorite')
		{		//echo("Select nvl(Max(REQFAVI),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
				$tbl_appplan3 = "PROCESS_REQUIREMENT_ENTRY";
				$field_appplan3 = array();
				$where_appplan3="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
				$field_appplan3['REQFAVI'] = 1;
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
				$g_insert_subject = insert_dbquery($g_fld4,$g_table4);
				//echo '';
		}
		
}else
{		//echo "hi 1";
		$LIKENO = select_query_json("Select nvl(Max(REQLIKE),0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno'				]."'","Centra","TEST");
		$DLIKENO = select_query_json("Select nvl(Max(REQDSLK),0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno'		]."'","Centra","TEST");
		$ldexist=select_query_json("select reqlike,reqdslk,reqfavi  from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."'and entryyr='".$_REQUEST['entryyr']."' and entryno='".$_REQUEST['entryno']."' and entsrno='".$_REQUEST['entsrno']."'","Centra","TEST");
		if($btype=='like')
		{	
			//echo "select reqlike,reqdslk  from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."and entryyr='".$_REQUEST['entryyr']."' and entryno='".$_REQUEST['entryno']."' and entsrno='".		$_REQUEST['entsrno']."'";
			//print_r( $lexist);
			if($ldexist[0]['REQLIKE']=='')
			{	if($ldexist[0]['REQDSLK']=='1')
				{
					
					//echo("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
					$tbl_appplan1 = "PROCESS_REQUIREMENT_ENTRY";
					$field_appplan1 = array();
					$field_appplan1['REQLIKE'] = $LIKENO[0]['MAXENTRY']+1;
					
					if($DLIKENO[0]['MAXENTRY']-1==0 || $LIKENO[0]['MAXENTRY']-1<=0)
					{	$field_appplan1['REQDSLK'] = '';
					}
					else{
						$field_appplan1['REQDSLK'] = $DLIKENO[0]['MAXENTRY']-1;
					}
					$where_appplan1="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($field_appplan1, $tbl_appplan1, $where_appplan1);
					
					//echo($LIKENO[0]['MAXENTRY']);
				}
				else if($ldexist[0]['REQDSLK']=='')
				{	//echo("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
					$tbl_appplan1 = "PROCESS_REQUIREMENT_ENTRY";
					$field_appplan1 = array();
					$field_appplan1['REQLIKE'] = $LIKENO[0]['MAXENTRY']+1;
					$where_appplan1="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($field_appplan1, $tbl_appplan1, $where_appplan1);
					//echo($LIKENO[0]['MAXENTRY']);
				}	
			
					$g_table4 = "PROCESS_REQUIREMENT_RATING";
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
					//print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
			}	
			else{
				//echo '00';
			}
		}
		else if($btype=='dislike')
		{	
			//echo "select reqlike,reqdslk  from process_requirement_rating where adduser='".$_SESSION['tcs_usrcode']."and entryyr='".$_REQUEST['entryyr']."' and entryno='".$_REQUEST['entryno']."' and entsrno='".		$_REQUEST['entsrno']."'";
			//print_r( $lexist);
			if($ldexist[0]['REQDSLK']=='')
			{	if($ldexist[0]['REQLIKE']=='1')
				{
					
					//echo("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
					$tbl_appplan1 = "PROCESS_REQUIREMENT_ENTRY";
					$field_appplan1 = array();
					if($LIKENO[0]['MAXENTRY']-1==0 || $LIKENO[0]['MAXENTRY']-1<=0)
					{	$field_appplan1['REQLIKE'] = '';
					}
					else{
						$field_appplan1['REQLIKE'] = $LIKENO[0]['MAXENTRY']-1;
					}
					$field_appplan1['REQDSLK'] = $DLIKENO[0]['MAXENTRY']+1;
					$where_appplan1="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($field_appplan1, $tbl_appplan1, $where_appplan1);
					
					//echo($LIKENO[0]['MAXENTRY']);
				}
				else if($ldexist[0]['REQLIKE']=='')
				{	//echo("Select nvl(Max(REQLIKE),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
					$tbl_appplan1 = "PROCESS_REQUIREMENT_ENTRY";
					$field_appplan1 = array();
					$field_appplan1['REQDSLK'] = $DLIKENO[0]['MAXENTRY']+1;
					$where_appplan1="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($field_appplan1, $tbl_appplan1, $where_appplan1);
					//echo($LIKENO[0]['MAXENTRY']);
				}	
			
					$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQLIKE'] = '';
					$g_fld4['REQDSLK'] = '1';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($g_fld4, $g_table4, $where_appplan4);
					//print_r( array($LIKENO[0]['MAXENTRY'],$DLIKENO[0]['MAXENTRY']));
			}	
			
			else{
				//echo '00';
			}
		}
		else if($btype=='favorite')
			{	
				
				if($ldexist[0]['REQFAVI']=='')
				{
					$FAVINO = select_query_json("Select nvl(REQFAVI,0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
					//echo("Select nvl(Max(REQFAVI),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
					$tbl_appplan3 = "PROCESS_REQUIREMENT_ENTRY";
					$field_appplan3 = array();
					$where_appplan3="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$field_appplan3['REQFAVI'] = $FAVINO[0]['MAXENTRY']+1;
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
					//echo '12';

				}
				else{
					$FAVINO = select_query_json("Select nvl(REQFAVI,0) MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
					//echo("Select nvl(Max(REQFAVI),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'");
					$tbl_appplan3 = "PROCESS_REQUIREMENT_ENTRY";
					$field_appplan3 = array();
					$where_appplan3="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					if($FAVINO[0]['MAXENTRY']-1==0||$FAVINO[0]['MAXENTRY']-1<0)
					{
						$field_appplan3['REQFAVI']='';
					}else
					{
						$field_appplan3['REQFAVI'] = $FAVINO[0]['MAXENTRY']-1;
					}
					$insert_appplan = update_dbquery($field_appplan3, $tbl_appplan3, $where_appplan3);
					//echo($field_appplan3['REQFAVI']);
					
					$g_table4 = "PROCESS_REQUIREMENT_RATING";
					$g_fld4 = array();
					$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
					$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
					$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
					$g_fld4['REQFAVI'] = '';
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$where_appplan4="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
					$insert_appplan1 = update_dbquery($g_fld4, $g_table4, $where_appplan4);
				}
			}
			
	}
	$MAXLIKE = select_query_json("Select nvl(Max(REQLIKE),0) MAX From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
	$MAXDISLIKE = select_query_json("Select nvl(Max(REQDSLK),0) MAX From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
	$MAXFAVORITE = select_query_json("Select nvl(Max(REQFAVI),0) MAX From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'","Centra","TEST");
	echo ($MAXLIKE[0]['MAX']."!".$MAXDISLIKE[0]['MAX']."!".$MAXFAVORITE[0]['MAX']);
	//$array = array("LIKE" => $MAXLIKE[0]['MAX'],"DSLK" => $MAXDISLIKE[0]['MAX'],"FAVI" => $MAXFAVORITE[0]['MAX']);
	