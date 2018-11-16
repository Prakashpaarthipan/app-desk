<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
// print_r($_REQUEST);
//echo('1');

$supplier = select_query_json("select sup.supname, sup.supcode ,rre.reqmsg,rre.REQNUMB,rre.REQSRNO from supplier sup, service_register_entry rre where sup.supcode=rre.requser and rre.REQUSRTYP='S' AND REQSTAT='N'","Centra","TEST");
//print_r($supplier);
?>

<div class="list-group-item" style="background-color:#3f444c">
	 <div class="row">
	   <label class="control-label" ><span style='color:white'>Not Assigned</span></label>
	   <input style = "display:block;border:none" type="text" class="control-label pull-right" placeholder="Search" id="notassigned_list" onkeyup ="notassign_search(this)" onblur="notassign_loadback(this)" autocomplete="off" />
	 </div>
</div>

<div class = "border-bottom " id="notassign" style=" height:200px;overflow-y: scroll;position: relative;">
<?for($k=0;$k<sizeof($supplier);$k++) {?>

        <a onclick="details(<?echo $supplier[$k]['REQNUMB'];?>,<?echo $supplier[$k]['REQSRNO'];?>)" class="list-group-item">
            <div class="list-group-status status-offline"></div>
            <img src="images/logo-original.png" class="pull-left" alt="<?echo $supplier[$k]['SUPNAME'];?>">
          <span class="contacts-title">Request Id :&nbsp<?echo substr($supplier[$k]['REQNUMB'],0,20);?> </span>
						<p><?echo $supplier[$k]['SUPNAME'];?></p>
        </a>
<? } ?>
</div>

  <div style="clear: both;padding-top:5px"></div>


<div  class="list-group-item" style="background-color:#1caf9a">
		<div class="row">
			<label class="control-label"><span style='color:white'>Assigned</span></label>
			<input style = "display:block;border:none" type="text" class="control-label pull-right" placeholder="Search" id="assigned_list" onkeyup ="javascript:assign_search(this);" onblur="javascript:assign_loadback(this)" autocomplete="off" />
		</div>
</div>
<div class = "border-bottom" id="assign" style=" height:200px;position: absolute;overflow-y: scroll;">
	<?$supplier = select_query_json("select sup.supname, sup.supcode ,rre.reqmsg,rre.REQNUMB,rre.REQSRNO from supplier sup, service_register_entry rre where sup.supcode=rre.requser and rre.REQUSRTYP='S' AND REQSTAT='A'","Centra","TEST");
for($k=0;$k<sizeof($supplier);$k++) {
	$assignmbr = select_query_json("select eof.empname,eof.empcode,deg.desname,ese.esename from employee_office eof,designation deg,empsection ese,service_register_response srr where eof.empcode=srr.restusr and srr.reqnumb='".$supplier[$k]['REQNUMB']."' and srr.reqsrno='".$supplier[$k]['REQSRNO']."' and srr.ressrno='1' and deg.descode=eof.descode and ese.esecode=eof.esecode","Centra","TEST");?>

        <a onclick="details(<?echo $supplier[$k]['REQNUMB'];?>,<?echo $supplier[$k]['REQSRNO'];?>)" class="list-group-item">
            <div class="list-group-status status-online"></div>
            <img src="images/logo-original.png" class="pull-left" alt="<?echo $supplier[$k]['SUPNAME'];?>">
            <span class="contacts-title">Request Id :&nbsp<?echo substr($supplier[$k]['REQNUMB'],0,20);?> </span>
						<p><?echo substr($supplier[$k]['SUPNAME'],0,20);?></p>
						<p ><span style="color:red">ASSIGNED</span>&nbsp:&nbsp<?echo $assignmbr[0]['EMPNAME'];?></p>
        </a>

<? } ?>
</div>
