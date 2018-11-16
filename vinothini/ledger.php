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
                url: 'prakash/ledger.php',
                type: 'post',
                //data: {top_core:top_core},
                dataType: 'json',
                success:function(response){
                 var len = response.length;
                 // $("#txt_ledger_code").append("<option value='' selected hidden>CHOOSE THE LEDGER CODE</option>");
                 $("#txt_ledger_name1").append("<option value='' selected hidden>CHOOSE THE LEDGER NAME</option>");

                 for( var i = 0; i<len; i++){
                  var id = response[i]['id'];
                  var name = response[i]['name'];
                  // $("#txt_ledger_code").append("<option value='"+id+"'>"+id+"</option>");
                  $("#txt_ledger_name1").append("<option value='"+name+"'>"+name+"</option>");

                 }
                }
            });
*/
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
/*
function subject_addnew4() {

        $('[data-toggle="tooltip"]').tooltip();
        //var id = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        var value = $('#add_ledger_row').val();
        var id = (parseInt(value) + 1).toString();
		//var id = value +1;
        $('#add_ledger_row').val(id);
		
        $('#add_ledger').append(
              '<div class="form-group input-group">'+
				'<div style="width:100%">'+
					'<div style="width:70%;float:left;">'+
						'<select class="form-control" autofocus required name="txt_ledger_name[]" id="txt_ledger_name'+id+'" data-toggle="tooltip" data-placement="top" title="ledger Name" ></select>'+
					'</div>'+
					'<div style="width:30%;float:right">'+
						'<input type="text" name = "txt_value[]" id= "txt_values'+id+'"class="form-control" pattern="^[0-9]*$" placeholder= "VALUES" data-toggle="tooltip" title ="values" required onkeypress="javascript:return isNumber(event)" autocomplete="off" maxlength = "7">'+
					'</div>'+
				'</div>'+
				'<span class="input-group-btn"><button id="add_ledger_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip" title ="remove">-</button></span>'+
			  '</div>');

                 $.ajax({
					url: 'prakash/ledger.php',
					type: 'post',
					//data: {top_core:top_core},
					dataType: 'json',
					success:function(response){
		     		var len = response.length;
					// $("#txt_ledger_code").append("<option value='' selected hidden>CHOOSE THE LEDGER CODE</option>");
					$("#txt_ledger_name"+id).append("<option value='' selected hidden>CHOOSE THE LEDGER NAME</option>");

					 for( var i = 0; i<len; i++){
					  var idd = response[i]['id'];
					  var name = response[i]['name'];
					  // $("#txt_ledger_code").append("<option value='"+id+"'>"+id+"</option>");
					  $("#txt_ledger_name"+id).append("<option value='"+name+"'>"+name+"</option>");
					 }
					}
				});
                 $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });
        }
		*/
?>
