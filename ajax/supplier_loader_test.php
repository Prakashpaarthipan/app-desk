<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
//echo("hi to test");
// print_r($_REQUEST);
//echo('1');

$supplier = select_query_json("select sup.supname, sup.supcode, rre.reqmsg, rre.REQNUMB, rre.REQSRNO, rre.REQUSRTYP, com.comcode, com.comname, usr.usrname, usr.usrcode 
										from supplier sup, service_request rre, APP_COMPLAINT_MASTER com, userid usr 
										where rre.REQSRNO='1' and sup.supcode=rre.requser and com.COMCODE = rre.REQMODE and rre.ADDUSER = usr.usrcode and com.deleted='N' and REQSTAT IN ('N') 
										ORDER BY rre.REQNUMB DESC","Centra","TCS");
//print_r($supplier);
?>

<div class="list-group-item" style="background-color:#3f444c">
	 <div class="row">
	   <label class="control-label" ><span style='color:white'>Not Assigned</span></label>
	   <input style = "display:block;border:none" type="text" class="control-label pull-right" placeholder="Search" id="notassigned_list" onkeyup ="notassign_search(this)" onblur="notassign_loadback(this)" autocomplete="off" />
	 </div>
</div>

<div class = "border-bottom " id="notassign" style="min-height:300px;max-height:300px;overflow-y: scroll;position: relative;">
<?	if($supplier[0]==''){?>
	<div class="list-group-item" >
		<div class="row">
			<label class="control-label" ><span>No Record's Found</span></label>
		</div>
	</div>
<?
}

if(count($supplier) > 0) { ?>
	<div style="background-color: #FFFFFF; color: #FF0000; font-weight: bold; min-height: 25px; line-height: 25px; text-align: center;">[ <?=count($supplier)?> Requests are available. ]</div>
	<div style="clear: both;"></div>
	<? for($k=0;$k<sizeof($supplier);$k++) { 
		//////////////
					$suppsec1 = select_query_json("SELECT DISTINCT replace(SECLIST,'-',''),COUNT(*) FROM SUPPLIER WHERE SUPCODE='".$supplier[$k]['SUPCODE']."' AND SECLIST IS NOT NULL GROUP BY SECLIST","Centra","TCS");
				  //print_r($suppsec1);
				  $grp='';
				  if($suppsec1[0]['SECLIST']=='')
				  {
				     $suppsec1 = select_query_json("SELECT listagg(G.SECGRNO, ',') within group (order by G.SECGRNO) as SECLIST 
				                      FROM TRANDATA.REORDER_CONTENT_SUPPLIER@TCSCENTR R,TRANDATA.PRODUCT@TCSCENTR P,TRANDATA.SECTION@TCSCENTR S,TRANDATA.SECTION_GROUP_REPORT@TCSCENTR G 
				                      WHERE R.PRDCODE=P.PRDCODE AND P.SECCODE=S.SECCODE AND S.SECCODE=G.SECCODE AND R.MINQNTY>0 AND R.SUPCODE='".$supplier[$k]['SUPCODE']."'", "Centra", "TCS");
				  }
				  $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT WHERE SECGRNO IN(".$suppsec1[0]['SECLIST'].")","Centra","TCS");


				  if($suppsec1[0]['SECLIST']=='') 
				  {
				     $suppsec1 = select_query_json("SELECT DISTINCT SECCODE,COUNT(*) FROM SUPPLIER WHERE SUPCODE='".$supplier[$k]['SUPCODE']."' AND NVL(SECCODE,0)>0 GROUP BY SECCODE","Centra","TCS");
				     $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT WHERE SECCODE IN('".$suppsec1[0]['SECCODE']."')","Centra","TCS");
				  }

				  
				  if(count($suppsec2) == 0) {
				    $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT","Centra","TCS");
				  }
				  
				  for($i=0;$i<sizeof($suppsec2);$i++)
				    {
				        $grp=$grp.$suppsec2[$i]['SECNAME']." , ";
				    }

//////////////////////
		?>
		<a onclick="details('<? echo $supplier[$k]['REQNUMB']; ?>', '<? echo $supplier[$k]['REQSRNO']; ?>')" class="list-group-item">
			<div class="list-group-status status-away"></div>
			<img src="images/customers.png" class="pull-left" alt="<? echo $supplier[$k]['SUPNAME']; ?>">
			<span class="contacts-title">Request Id: <? echo $supplier[$k]['REQNUMB']; ?> </span>
			<p><? echo $supplier[$k]['SUPCODE']; ?> - <? echo $supplier[$k]['SUPNAME']; ?></p>
			<p><small class="label label-warning">Req. Type : <? echo $supplier[$k]['COMNAME']; ?></small></p>
			<p><small class="label label-danger">Req. By : <? if($supplier[$k]['REQUSRTYP'] == 'S') { echo "Supplier"; } else { echo $supplier[$k]['USRCODE']." - ".$supplier[$k]['USRNAME']; } ?></small></p>
			<p><small class="label label-warning">Section Group : <? echo $grp; ?></small></p>
		</a>
	<? } 
} ?>
</div>

<div style="clear: both;padding-top:5px"></div>
<div  class="list-group-item" style="background-color:#1caf9a">
	<div class="row">
		<label class="control-label"><span style='color:white'>Assigned</span></label>
		<input style = "display:block;border:none" type="text" class="control-label pull-right" placeholder="Search" id="assigned_list" onkeyup ="javascript:assign_search(this);" onblur="javascript:assign_loadback(this)" autocomplete="off" />
	</div>
</div>

<div class = "border-bottom" id="assign" style="min-height:300px;max-height:300px; position: relative;overflow-y: scroll;">
	<? $supplier = select_query_json("select sup.supname, sup.supcode, rre.reqmsg, rre.REQNUMB, rre.REQSRNO, rre.REQUSRTYP, com.comcode, com.comname, usr.usrname, usr.usrcode 
												from supplier sup, service_request rre, APP_COMPLAINT_MASTER com, userid usr
												where rre.REQSRNO='1' and sup.supcode=rre.requser and com.COMCODE = rre.REQMODE and rre.ADDUSER = usr.usrcode and com.deleted='N' and REQSTAT='A'
												ORDER BY rre.REQNUMB DESC","Centra","TCS");
		if($supplier[0]==''){?>
		<div class="list-group-item" >
			 <div class="row">
				<label class="control-label" ><span>No Record's Found</span></label>
			 </div>
		</div>
	<? }

	if(count($supplier) > 0) { ?>
		<div style="background-color: #FFFFFF; color: #FF0000; font-weight: bold; min-height: 25px; line-height: 25px; text-align: center;">[ <?=count($supplier)?> Requests are available. ]</div>
		<div style="clear: both;"></div>
		<? for($k=0;$k<sizeof($supplier);$k++) {
			$assignmbr = select_query_json("select eof.empname, eof.empcode, deg.desname, ese.esename 
													from employee_office eof, designation deg, empsection ese, service_response srr 
													where eof.empsrno=srr.restusr and srr.reqnumb='".$supplier[$k]['REQNUMB']."' and srr.reqsrno='".$supplier[$k]['REQSRNO']."' 
														and srr.ressrno='1' and deg.descode=eof.descode and ese.esecode=eof.esecode", "Centra", "TCS");
			///////////
					$suppsec1 = select_query_json("SELECT DISTINCT replace(SECLIST,'-',''),COUNT(*) FROM SUPPLIER WHERE SUPCODE='".$supplier[$k]['SUPCODE']."' AND SECLIST IS NOT NULL GROUP BY SECLIST","Centra","TCS");
				  //print_r($suppsec1);
				  $grp='';
				  if($suppsec1[0]['SECLIST']=='')
				  {
				     $suppsec1 = select_query_json("SELECT listagg(G.SECGRNO, ',') within group (order by G.SECGRNO) as SECLIST 
				                      FROM TRANDATA.REORDER_CONTENT_SUPPLIER@TCSCENTR R,TRANDATA.PRODUCT@TCSCENTR P,TRANDATA.SECTION@TCSCENTR S,TRANDATA.SECTION_GROUP_REPORT@TCSCENTR G 
				                      WHERE R.PRDCODE=P.PRDCODE AND P.SECCODE=S.SECCODE AND S.SECCODE=G.SECCODE AND R.MINQNTY>0 AND R.SUPCODE='".$supplier[$k]['SUPCODE']."'", "Centra", "TCS");
				  }
				  $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT WHERE SECGRNO IN(".$suppsec1[0]['SECLIST'].")","Centra","TCS");


				  if($suppsec1[0]['SECLIST']=='') 
				  {
				     $suppsec1 = select_query_json("SELECT DISTINCT SECCODE,COUNT(*) FROM SUPPLIER WHERE SUPCODE='".$supplier[$k]['SUPCODE']."' AND NVL(SECCODE,0)>0 GROUP BY SECCODE","Centra","TCS");
				     $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT WHERE SECCODE IN('".$suppsec1[0]['SECCODE']."')","Centra","TCS");
				  }

				  
				  if(count($suppsec2) == 0) {
				    $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT","Centra","TCS");
				  }
				  
				  for($i=0;$i<sizeof($suppsec2);$i++)
				    {
				        $grp=$grp.$suppsec2[$i]['SECNAME']." , ";
				    }
			////////////
														 ?>
	        <a onclick="details('<? echo $supplier[$k]['REQNUMB']; ?>', '<? echo $supplier[$k]['REQSRNO']; ?>')" class="list-group-item">
	            <div class="list-group-status status-online"></div>
	            <img src="images/customers.png" class="pull-left" alt="<? echo $supplier[$k]['SUPNAME']; ?>">
	            <span class="contacts-title">Request Id: <? echo $supplier[$k]['REQNUMB']; ?> </span>
				<p><? echo $supplier[$k]['SUPCODE']; ?> - <? echo $supplier[$k]['SUPNAME']; ?></p>
				<p><small class="label label-warning">Req. Type : <? echo $supplier[$k]['COMNAME']; ?></small></p>
				<p><small class="label label-danger">Req. By : <? if($supplier[$k]['REQUSRTYP'] == 'S') { echo "Supplier"; } else { echo $supplier[$k]['USRCODE']." - ".$supplier[$k]['USRNAME']; } ?></small></p>
				<p><small class="label label-warning">Section Group : <? echo $grp; ?></small></p>
				<p ><span style="color:red">ASSIGNED</span>&nbsp:&nbsp<? echo $assignmbr[0]['EMPNAME']; ?></p>
	        </a>
<? } } ?>
</div>
