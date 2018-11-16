<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

if($_REQUEST['action'] == "request_list")
{
	if($and != '') {
      $sql_search = select_query_json("select ar.ADDDATE, ar.FINDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPRFOR, ar.APPFVAL, ar.APRTITL, ar.ARQSRNO, 
                                                  ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO,decode(ar.APPSTAT, 'N', 'NEW', 
                                                  'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 
                                                  'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4', 
                                                  'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST 
                                                  where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and 
                                                  ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE 
                                                  and APPSTAT = 'A') as APSTAT, (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as 
                                                  reqto, (select EMPNAME from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST 
                                                  where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and 
                                                  ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, 
                                                  ar.APPRDET, ar.IMSTATS, ar.IMFNIMG  
                                              from approval_request ar 
                                              where ar.ARQSRNO = 1 ".$rqusr." ".$and." and ar.deleted = 'N' 
                                          union
                                              select ar.ADDDATE, ar.FINDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPRFOR, ar.APPFVAL, ar.APRTITL, ar.ARQSRNO, 
                                                  ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO,decode(ar.APPSTAT, 'N', 'NEW', 
                                                  'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 
                                                  'R', 'REJECTED','NEW') APPSTATUS, decode(ar.APPSTAT, 'N','1','F', '2', 'A', '3', 'P', '4', 'Q', '5', 
                                                  'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST where ARQSRNO = 1 
                                                  and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE and 
                                                  ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT, 
                                                  (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as reqto, (select EMPNAME 
                                                  from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST 
                                                  where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and 
                                                  ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, 
                                                  ar.APPRDET, ar.IMSTATS, ar.IMFNIMG  
                                              from approval_request ar 
                                              where ar.ARQSRNO = 1 ".$rqusr." ".$and." and ar.deleted = 'N'  
                                              order by ADDDATE asc, APPORDER asc, APRNUMB desc", "Centra", 'TEST'); 
  }else{
      $rqusr = " and ar.ADDUSER = '".$_SESSION['tcs_empsrno']."' ";
      $sql_search = select_query_json("select ar.ADDDATE, ar.FINDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPRFOR, ar.APPFVAL, ar.APRTITL, ar.ARQSRNO, 
                                                  ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO,decode(ar.APPSTAT, 'N', 'NEW', 
                                                  'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 
                                                  'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4', 
                                                  'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST 
                                                  where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and 
                                                  ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE 
                                                  and APPSTAT = 'A') as APSTAT, (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as 
                                                  reqto, (select EMPNAME from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST 
                                                  where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and 
                                                  ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, 
                                                  ar.APPRDET, ar.IMSTATS, ar.IMFNIMG  
                                              from approval_request ar 
                                              where ar.ARQSRNO = 1 ".$rqusr." ".$and." and ar.deleted = 'N'  and ar.arqyear not in ('2016-17')
                                          union
                                              select ar.ADDDATE, ar.FINDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APPRFOR, ar.APPFVAL, ar.APRTITL, ar.ARQSRNO, 
                                                  ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO,decode(ar.APPSTAT, 'N', 'NEW', 
                                                  'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 
                                                  'R', 'REJECTED','NEW') APPSTATUS, decode(ar.APPSTAT, 'N','1','F', '2', 'A', '3', 'P', '4', 'Q', '5', 
                                                  'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST where ARQSRNO = 1 
                                                  and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE and 
                                                  ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT, 
                                                  (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as reqto, (select EMPNAME 
                                                  from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE 
                                                  and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and 
                                                  ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, ar.APPRDET, ar.IMSTATS, 
                                                  ar.IMFNIMG  
                                              from approval_request ar 
                                              where ar.ARQSRNO = 1 ".$rqusr." ".$and." and ar.deleted = 'N'  and ar.arqyear not in ('2016-17')  
                                              order by ADDDATE asc, APPORDER asc, APRNUMB desc", "Centra", 'TEST');
  }

$data = array();
for($search_i = 0; $search_i < count($sql_search); $search_i++) { $ij++; 
    // A - Approved; N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
    $editid = 0; $bgclr = ''; $clr = '#000000';
    if($sql_search[$search_i]['APPSTAT'] == 'A') { $appstatus = "3 - APPROVED"; $bgclr = '#DFF0D8'; $clr = '#000000'; }
    if($sql_search[$search_i]['APPSTAT'] == 'N') { $appstatus = "1 - NEW"; $editid = 1; }
    if($sql_search[$search_i]['APPSTAT'] == 'R') { $appstatus = "7 - REJECTED"; $bgclr = '#F2DEDE'; $clr = '#000000'; }
    if($sql_search[$search_i]['APPSTAT'] == 'F') { $appstatus = "2 - FORWARD"; }
    if($sql_search[$search_i]['APPSTAT'] == 'C') { $appstatus = "8 - COMPLETED"; }
    if($sql_search[$search_i]['APPSTAT'] == 'P') { $appstatus = "4 - PENDING"; $editid = 0; $bgclr = '#FAF4D1'; $clr = '#000000'; }
    if($sql_search[$search_i]['APPSTAT'] == 'S') { $appstatus = "5 - RESPONSE"; }
    if($sql_search[$search_i]['APPSTAT'] == 'Q') { $appstatus = "6 - QUERY"; }
    $filename = $sql_search[$search_i]['IMFNIMG'];

    $sql_pending = select_query_json("select REQSTFR, RQFRDES from APPROVAL_REQUEST 
                                        where aprnumb like '".$sql_search[$search_i]['APRNUMB']."' and ARQSRNO = (select max(ARQSRNO) 
                                            from APPROVAL_REQUEST where appstat in ('F', 'N') and 
                                            aprnumb like '".$sql_search[$search_i]['APRNUMB']."')", "Centra", 'TEST');
    $sql_pending_user = explode(" - ", $sql_pending[0]['RQFRDES']); 

    $nestedData=array();
    $nestedData[] = $appstatus;
    $nestedData[] = $ij;
    $nestedData[] = "<a href='view_pending_approval.php?action=view&reqid=".$sql_search[$search_i]['ARQCODE']."&year=".$sql_search[$search_i]['ARQYEAR']."&rsrid=1&creid=".$sql_search[$search_i]['ATCCODE']."&typeid=".$sql_search[$search_i]['ATYCODE']."' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'>".$sql_search[$search_i]['APRNUMB']."</a>";
                        $sql_internal = select_query_json("select appfrwd from trandata.approval_request@tcscentr where (arqsrno,aprnumb) in (select max(arqsrno) arqsrno, aprnumb 
                                                                  from trandata.approval_request@tcscentr
                                                                  where aprnumb in ('".$sql_search[$search_i]['APRNUMB']."') 
                                                                  group by aprnumb)", "Centra", 'TEST');
                      if($sql_internal[0]['APPFRWD'] == 'I') {
                        "<br><span class=\"badge badge-danger\" style=\"background-color:#AF2B28 !important; color: #FFFFFF !important; clear:both; font-size:10px;\">INTERNAL VERIFICATION</span>";
                      }
    $nestedData[] = ""; 
                      if($sql_search[$search_i]['APPRFOR'] == '1') {
                          $filepathname = $sql_search[$search_i]['APPRSUB'];
                          $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                          $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
                          $contents = fread($handle, filesize($filename));
                          fclose($handle);
                          echo strip_tags(str_replace("&nbsp;", " ", $contents));
                      } else {
                          echo $sql_search[$search_i]['APPRDET'];
                      }

    $nestedData[] = ""; if($sql_search[$search_i]['APPFVAL'] > 0) { echo moneyFormatIndia($sql_search[$search_i]['APPFVAL']); } else { echo "-Nil-"; }
    $nestedData[] = $sql_search[$search_i]['REQBY'];
    $nestedData[] = ""; if($sql_search[$search_i]['APPSTAT'] == 'N' or $sql_search[$search_i]['APPSTAT'] == 'F' or $sql_search[$search_i]['APPSTAT'] == 'P') { echo $sql_pending_user[1]; } else { echo "-"; }
    $nestedData[] = $sql_search[$search_i]['APRTITL']." ".$sql_search[$search_i]['REQTO'];
    $nestedData[] = $ij;
    $nestedData[] = $ij;

    $nestedData[] = $row["employee_age"];
    $data[] = $nestedData;
}

$json_data = array(
      "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
      "recordsTotal"    => intval( $totalData ),  // total number of records
      "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
      "data"            => $data   // total data array
      );

echo json_encode($json_data);  // send data as json format
}
?>