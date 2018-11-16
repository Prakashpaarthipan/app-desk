<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$sql_search = select_query_json("select * from 
                                   ( select a.*, ROWNUM rnum from 
                                        ( select distinct ar.APRNUMB, ar.APPSTAT, ar.APPFRWD, ar.APPRSUB, ar.APPFVAL, ar.APRTITL,ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.RQESTTO, 
                                        		decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') 
                                        		APPSTATUS, decode(ar.APPSTAT, 'N','1','F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, ar.ARQSRNO, ar.APPRDET, 
                                        		ar.PRICODE, ar.APPRFOR, ar.RQTODES reqto, ar.RQFRDES pndingby, (select EMPNAME from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST 
                                                where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and 
                                                APMCODE = ar.APMCODE and deleted = 'N')) as reqby, pr.PRICODE||' - '||pr.PRINAME priority 
                                            from APPROVAL_REQUEST ar, approval_priority pr 
                                            where ar.PRICODE = pr.PRICODE and pr.deleted = 'N' and ar.DELETED = 'N' ".$appfrwd." ".$and." 
                                            order by ar.ARQYEAR desc, APRNUMB desc ) a
										  	where ROWNUM <= ".$to_cnt." )
										where rnum  > ".$frm_cnt.""); 
	for($search_i = 0; $search_i < count($sql_search); $search_i++) { 
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
        ?>
	<tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
	    <td><?=$sql_search[$search_i]['PRIORITY']?></td>
	    <td class="center" style='text-align:center;'><? echo $appstatus; ?></td>
	    <td style='text-align:center'><?=$sql_search[$search_i]['RNUM']?></td>
	    <td><a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_search[$search_i]['APRNUMB']; ?></a></td>
	    <td class="center show_moreless">
	    <?  if($sql_search[$search_i]['APPRFOR'] == '1') {
	            $filepathname = $sql_search[$search_i]['APPRSUB'];
	            $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
	            $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
	            $contents = fread($handle, filesize($filename));
	            fclose($handle);
	            // echo strip_tags(str_replace("&nbsp;", " ", $contents));
	            echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);
	        } else {
	            echo $sql_search[$search_i]['APPRDET'];
	        }
	    ?></td>
	    <td><? if($sql_search[$search_i]['APPFVAL'] > 0) { echo moneyFormatIndia($sql_search[$search_i]['APPFVAL']); } else { echo "-Nil-"; } ?></td>
	    <td class="center"><? echo $sql_search[$search_i]['REQBY']; ?></td>
	    <? if($_REQUEST['status'] == 'IV') { ?>
	        <td class="center"><? echo $sql_search[$search_i]['PNDINGBY']; ?></td>
	    <? } ?>
	    <td class="center"><? echo $sql_search[$search_i]['APRTITL']." ".$sql_search[$search_i]['REQTO']; ?></td>
	    <td class="center" style='text-align:center;'>
	        <? if($sql_search[$search_i]['APPSTAT'] == 'N' or $sql_search[$search_i]['APPSTAT'] == 'P') { ?><a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a><? } else { ?>
	            <a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a>
	        <? } ?></td>
	</tr>
<? } ?>
<tr class='aload1'><td colspan="8" class='aload1' style="text-align:center; width:100%;"><a href='javascript:void(0)' id="load_1" class='aload1' onclick="loadmore_1()"><i class="fa fa-spinner"></i> Load More!!</a></td></tr>