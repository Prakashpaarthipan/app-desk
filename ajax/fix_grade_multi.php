<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

if($action == 'fix_grade') { 
	/* $sql_empgrade = select_query_json("select * from EMPLOYEE_GRADE_FIX where EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = trunc(sysdate)", 'Centra', 'TEST');
	if(count($sql_empgrade) > 0) { */
		// Update in EMPLOYEE_GRADE_FIX Table
		$tbl_apprq = "EMPLOYEE_GRADE_FIX";
		$field_apprq['DELETED'] = 'Y';
		$field_apprq['DELUSER'] = $_SESSION['tcs_usrcode'];
		$field_apprq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$where_apprq = "EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = trunc(sysdate)";
		// print_r($field_apprq); echo "<br>";
		$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		// Update in EMPLOYEE_GRADE_FIX Table
	// }

	$grade_fixno = select_query_json("Select NVL(Max(GRDFXNO), TO_CHAR(sysdate+1, 'yyyymmdd')||'000000')+1 MAXGRDFXNO From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = trunc(sysdate)", 'Centra', 'TEST');
	
	/* $sql_todayentry = select_query_json("select * from EMPLOYEE_GRADE_FIX where trunc(ADDDATE) = trunc(sysdate)", 'Centra', 'TEST');
	if(count($sql_todayentry) > 0) {
		$grade_fixno = select_query_json("Select NVL(Max(GRDFXNO), TO_CHAR(sysdate+1, 'yyyymmdd')||'00000')+1 MAXGRDFXNO From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = trunc(sysdate)", 'Centra', 'TEST');
	} else {
		$grade_fixno[0]['MAXGRDFXNO'] = date("Ymd")."1";
	} */
	
	switch ($grade) {
		case 1:
			$grade = 'A+';
			break;
		case 2:
			$grade = 'A';
			break;
		case 3:
			$grade = 'B';
			break;
		
		default:
			$grade = 'C';
			break;
	}

	// INSERT in EMPLOYEE_GRADE_FIX Table
	$tbl_docs = "EMPLOYEE_GRADE_FIX";
	$field_docs['GRDFXNO'] = $grade_fixno[0]['MAXGRDFXNO'];
	$field_docs['EMPSRNO'] = $empsrno;
	$field_docs['EMPHDSR'] = $_SESSION['tcs_empsrno'];
	$field_docs['EMPGRAD'] = $grade;

	$field_docs['ADDUSER'] = $_SESSION['tcs_usrcode'];
	$field_docs['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$field_docs['DELETED'] = 'N';
	$insert_docs = insert_test_dbquery($field_docs, $tbl_docs);
	print_r($field_docs);
	// INSERT in EMPLOYEE_GRADE_FIX Table
	echo $insert_docs;
} 
if($action == 'add_employee') {
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$g_table = "EMPLOYEE_HEAD_USER";
	$head=explode(' - ', $_REQUEST['txt_employee_head'][0]);
	$g_fld4 = array();
	$sql_head = select_query_json("select empsrno from employee_office where empcode='".$head[0]."'", "Centra", "TCS");
	for($i=0;$i<count($_REQUEST['txt_employee_code']);$i++)
	{	
		$emp=explode(' - ',$_REQUEST['txt_employee_code'][$i]);
		$sql_emp = select_query_json("select empsrno from employee_office where empcode='".$emp[0]."'", "Centra", "TCS");
		$g_fld['EMPHDSR'] = $sql_head[0]['EMPSRNO'];
		$g_fld['EMPSRNO'] = $sql_emp[0]['EMPSRNO'];
		$g_fld['EMPCODE'] = $emp[0];
		$g_fld['EMPNAME'] = $emp[1];
		$g_fld['DELETED'] = 'N';
		$g_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		echo("----------------");
		print_r($g_fld);
		$g_insert_subject = insert_test_dbquery($g_fld,$g_table);
	}
}

if($action == 'edit_employee') {
	
	$sql_emplist = select_query_json("select distinct hd.emphdsr, ef.EMPCODE, ef.EMPCODE||' - '||ef.empname EMPLOYEE, ef.EMPSRNO
                                                                                from EMPLOYEE_HEAD_USER HD, employee_office ef 
                                                                                where HD.empsrno = ef.empsrno and deleted = 'N' and hd.emphdsr = '".$emphead."'
                                                                                order by EMPCODE, EMPLOYEE", "Centra", "TEST");$headid =0;
                                            foreach ($sql_emplist as $key => $emplist_value) {
                                                //echo $emplist_value['EMPLOYEE']."<br>";
												?>
												<div class="form-group input-group">

					  <input type="text" name="txt_employee[]" id="txt_employee_code<?echo $headid;?>" value="<?echo $emplist_value['EMPLOYEE'];?>" title="Team Employee" class="form-control find_empcode newEmp" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" readonly>
					  <span class="input-group-btn"><button id="add_emp_button<?echo $headid;?>" type="button" class="btn btn-danger btn-remove" onclick="javascript:var head1 = document.getElementById('txt_employee_code<?echo $headid;?>');teamremove('<?echo $emplist_value['EMPLOYEE'];?>','<?=$emphead?>');">-</button></span>
					  </div>
					  
					  <script type="text/javascript">
								/*function teamremove(head,srno){
								
								var myhead = head.value;
								//alert(myhead);
								var headid = myhead.split(" - ");
								var teamcode = headid[0];
								//alert(headcode);
								if(confirm("Are you sure want to remove :("+myhead+") from list?")){
								var p_id = 0;
								$.ajax({
									type:"post",
									url:"ajax/fix_grade_multi.php?action=remove_employee&empid="+teamcode+"&headid="+srno",
									
									dataType: 'text',
									success: function(data, textStatus, jqXHR){
										
										alert("Removed");
										window.location.reload(true);
										console.log(data);
										},
									error: function(jqXHR, textStatus, errorThrown) {
										alert('An error occurred... Try again!');
										window.location.reload(true);
										//$("#load_page").fadeOut("fast");
										}
								});	
								}else{
								window.location.reload(true);}
							}*/
							</script>
				<?
						$headid++;
				}?>
				<div class="form-group input-group">

					  <input type="text" name="txt_employee[]" id="txt_employee0" value="" title="Team Employee" placeholder="ENTER THE EMPLOYEE CODE" class="form-control find_empcode newEmp" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" onchange="javascript:return removeduperunnew(this);">
					   <span class="input-group-btn"><button id="add_emp_button" type="button" onclick="javascript:var head2 = document.getElementById('txt_employee0');addNew(head2,'<?=$emphead?>');" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More"><i class="fa fa-save"></i></button></span>
				</div>
				<script>
				$('#txt_employee0').autocomplete({
               source: function( request, response ) {
                 $.ajax({
                   url : 'ajax/ajax_employee_details.php',
                   dataType: "json",
                   data: {
                      slt_emp: request.term,
                      brncode: 888,
                      action: 'allemp'
                   },
                   success: function( data ) {
                     response( $.map( data, function( item ) {
                       return {
                         label: item,
                         value: item
                       }
                     }));
                   }
                 });
               },
               change: function(event,ui)
                { if (ui.item == null) 
                {
                    $("#txt_employee0").val('');
                    $("#txt_employee0").focus(); 
                } 
                },
               autoFocus: true,
               minLength: 0
             });
         
		   function removeduperunnew(t) {
        var values = $('.newEmp').map(function() {
            return this.value;
        }).toArray();
        values = values.filter(function(e){return e}); 
        var hasDups = !values.every(function(v,i) {
            return values.indexOf(v) == i;
        });
        if(hasDups){
             // having duplicate values
             alert("Please do not repeat the same employee");
            t.value = '';
        }
    }
				</script>
				<?
}?>
<?

if($action == 'remove_employee') {
	$g_table = "EMPLOYEE_HEAD_USER";
	$sql_emplist_where ="emphdsr = '".$headid."' and EMPCODE = '".$empid."' and DELETED = 'N'";
		$sql_emplist_field= array();
		$sql_emplist_field['DELETED'] = 'Y';
		$sql_emplist_field['EDTUSER'] = $_SESSION['tcs_empsrno'];
		$sql_emplist_field['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		
		
		
		$g_insert_subject = update_dbquery($sql_emplist_field,$g_table,$sql_emplist_where);
		
		if ($g_insert_subject==1) {
			echo "1";
		}else{
			echo "0";
		}

}

if($action == 'addNew_employee') 
{
	
	$sql_team = select_query_json("select empsrno,empname from employee_office where empcode='".$empid."'", "Centra", "TCS");
	
	
	$g_table = "EMPLOYEE_HEAD_USER";
	
	
		$checkUser = select_query_json("select empsrno,EMPHDSR from EMPLOYEE_HEAD_USER where  EMPHDSR = '".$headid."' and empsrno = '".$sql_team[0]['EMPSRNO']."'","Centra","TEST");
		if(count($checkUser)>0){
			$g_table1 = "EMPLOYEE_HEAD_USER";
			$g_where = "EMPHDSR = '".$headid."' and empsrno = '".$sql_team[0]['EMPSRNO']."'";
			$g_fld_up['DELETED'] = 'N';
			$g_update_subject = update_test_dbquery($g_fld_up,$g_table1,$g_where);
			
		}
		else
		{	$g_fld['EMPHDSR'] = $headid;
			$g_fld['EMPSRNO'] = $sql_team[0]['EMPSRNO'];
			$g_fld['EMPCODE'] = $empid;
			$g_fld['EMPNAME'] = $sql_team[0]['EMPNAME'];
			$g_fld['DELETED'] = 'N';
			$g_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
			$g_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			
			$g_insert_subject = insert_dbquery($g_fld,$g_table);
		}
		
		if ($g_insert_subject==1) {
			echo "1";
		}else{
			echo "0";
		}

	
	
	
}
if($action == 'load_head') 
{
	$sql_team = select_query_json("select empsrno,empname, empcode from employee_office where empsrno='".$emphead."'", "Centra", "TCS");
	echo $sql_team[0]['EMPCODE']." - ".$sql_team[0]['EMPNAME'];
	
}




?>