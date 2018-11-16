<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


    //include('../../../approval-desk/lib/config.php');
include_once('../lib/function_connect.php');
	//$filter = $_GET["id"];
	//$filter = $_POST['top_core'];

	$sql_descode=select_query_json("SELECT BRNCODE, BRNNAME ,NICNAME FROM branch where BRNCODE in (1 , 10 , 14 , 23 , 100 , 102 , 104 , 				107 , 112 ,113 , 116 , 201 , 203 , 204 , 206 , 300 , 888) ORDER BY BRNCODE", "Centra", "TCS"); //TEST and TCS
	//and atc.ATCCODE = '".$filter."'
	//".$_SESSION['tcs_empsrno']."
	//print_r($sql_descode);
	//120 - CLEAN INDIA TODAY BRANCH ID
    //$users_arr = array();

	 foreach($sql_descode as $sectionrow) {
	// $branch = explode('-',$sectionrow['BRNNAME']);
	//  $name = $branch[0];
	//  $name = substr($name,2,-1);
	// $name = $sectionrow['NICNAME'];
	 $id = $sectionrow['BRNCODE'];
     $name=  trim(preg_replace('/[^A-Za-z\-]/', ' ',$sectionrow['NICNAME'] ));
	 $brn = $sectionrow['BRNNAME'];
     $users_arr[] = array("id" => $id, "name" => $name , "brn" => $brn);
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
