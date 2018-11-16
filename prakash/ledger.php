<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//include('../../../approval-desk/lib/config.php');
include_once('../lib/function_connect.php');
	//$filter = $_GET["id"];
	//$filter = $_POST['top_core'];
	

	$sql_descode=select_query_json("select distinct round(tarnumb) tarnumb, round(bpl.tarnumb)||' - '||decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname
from budget_planner_branch bpl, non_purchase_target tar, department_asset Dep
where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode and tar.brncode=bpl.brncode and tar.brncode=bpl.brncode and tar.PTNUMB=bpl.TARNUMB and bpl.TARYEAR=17 and bpl.TARMONT=4 and (bpl.tarnumb>8000)
group by bpl.tarnumb, bpl.depcode, bpl.brncode, tar.ptdesc, dep.depname order by Depname", "Centra", "TCS"); //TEST and TCS
												 //and atc.ATCCODE = '".$filter."'
	//".$_SESSION['tcs_empsrno']."
//	print_r($sql_descode);
$users_arr = array();

	 foreach($sql_descode as $sectionrow) {
		 $id = $sectionrow['TARNUMB'];
     $name = $sectionrow['DEPNAME'];
	//$name1 = explode(" - ",$name);
    $users_arr[] = array("id" => $id, "name" => $name);
	 }
 echo json_encode($users_arr);

/*
$.ajax({
	url: 'ajax/ajax_load_subcore.php',
	type: 'post',
	//data: {top_core:top_core},
	dataType: 'json',
	success:function(response){
	 var len = response.length;
	 $("#txt_subcore_0").append("<option value='' selected hidden>CHOOSE THE CORE</option>");
	 for( var i = 0; i<len; i++){
	  var id = response[i]['id'];
	  var name = response[i]['name'];
	  $("#txt_subcore_0").append("<option value='"+id+"'>"+name+"</option>");
	 }
	}
}); */
?>
