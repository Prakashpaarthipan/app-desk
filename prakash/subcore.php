<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//include('../../../approval-desk/lib/config.php');
include_once('../lib/function_connect.php');
	//$filter = $_GET["id"];
	//$filter = $_POST['top_core'];

	$sql_descode=select_query_json("select distinct sec.esecode, substr(sec.esename, 4, 25) esename
											from APPROVAL_master apm, APPROVAL_topcore atc, empsection sec
											where sec.esecode = apm.subcore and apm.topcore = atc.atccode and apm.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N'
											order by ESENAME asc", "Centra", "TCS"); //TEST and TCS
												 //and atc.ATCCODE = '".$filter."'
	//".$_SESSION['tcs_empsrno']."
	//print_r($sql_descode);
$users_arr = array();

	foreach($sql_descode as $sectionrow) {
		$id = $sectionrow['ESECODE'];
    $name = $sectionrow['ESENAME'];

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
