<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
// print_r($_REQUEST);
//echo('1');

	$commentno = select_query_json("select prc.reqcmnt,eof.empname,eof.empcode,usid.empsrno,to_char(prc.adddate,'dd/MM/yyyy HH:mi:ss AM') add_time from process_requirement_comment prc,employee_office eof,userid usid where prc.entryyr='".$_REQUEST['entryyr']."' and prc.entryno='".$_REQUEST['entryno']."' and prc.entsrno='".$_REQUEST['entsrno']."' and usid.usrcode=prc.adduser and eof.empsrno=usid.empsrno","Centra","TEST");
	
	for($k=0;$k<sizeof($commentno);$k++) {?>
	<li class="media">
		<a class="pull-left" href="#">
			<img class="media-object img-text" alt="Cinque Terre" src="profile_img.php?profile_img=<?=$commentno[$k]['EMPSRNO']?>" style="height:70px; width:70px; border-radius:100px; border: 1px solid #A0A0A0;" title="	">
			<!--<img class="media-object img-text" src="assets/images/users/user.jpg" alt="<?echo $commentno[$k]['EMPNAME']?>" width="64">-->
		</a>
		<div class="media-body">
			<h4 class="media-heading"><?echo $commentno[$k]['EMPNAME']?></h4>
			<p><?echo $commentno[$k]['REQCMNT']?> </p>
			<h><?=$commentno[$k]['ADD_TIME']?><h>
		</div>                                            
	</li>
<? } ?>