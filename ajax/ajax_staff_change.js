function emp_remove(gridid) {
   if ($('.parts3 .part3').length == 0) {
      alert("No more row to remove.");
   }
   var id = ($('.parts3 .part3').length - 1).toString();
   $('#partint3').val(id);
   $(".parts3 .part3:last").remove();

}

function emp_brn_transfer(gridid) {
	if( ($('.parts3 .part3').length+1) > 99) {
		alert("Maximum 100 Employee allowed.");
    } else {
    	$('[data-toggle="tooltip"]').tooltip();
		var id = ($('.parts3 .part3').length + 2).toString();
        $('#partint3').val(id);
        $('.parts3').append(
	'<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
	  '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
	     '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
	     	'<div class="fg-line">&nbsp;'+id+'</div>'+
	     '</div>'+

	  '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
				'<div>'+
					'<input type="text" name="EMPNAME[]" id="txt_staffcode_'+id+'" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff('+id+',1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div style="clear: both;"></div>'+
			'</div>'+

			'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
				'<div id="photo_'+id+'">'+

				'</div>'+
				'<input type="hidden"  name="EMPSRNO[]" id="empsrno_'+id+'" value="">	'+
    		'</div>'+

            '<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<input type="text"  name="CUREXP[]" id="curexp_'+id+'" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<input type="text"  name="DATEOFJOIN[]" id="curdoj_'+id+'" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<input type="text"  name="CURBRN[]" id="curbrn_'+id+'" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    			'<input type="text"  name="CURDEP[]" id="curdep_'+id+'" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    			'<input type="text"  name="CURDES[]" id="curdes_'+id+'" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
			 	'<div id = "staff_branch_'+id+'"></div>'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			' <div id = "staff_dept_'+id+'"></div>'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			 '<div id = "staff_des_'+id+'"></div>'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
				'<input type="text" name="REPORTTO[]" id="txt_reportto_'+id+'" required="required" maxlength="100" placeholder="Reporting To" title="Reporting To" class="form-control" data-toggle="tooltip"   data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
    		'</div>'+
    		'</div>'+
	'</div>');
    $.getScript("js/jquery-customselect.js");
	$(".chosn").customselect();
	getbrn(id);
     $('#txt_staffcode_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
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
		autoFocus: true,
		minLength: 0
	});

	 $('#txt_reportto_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
				   action: 'allbrnemp'
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
		autoFocus: true,
		minLength: 0
	});

    }
}

function emp_des_change(gridid) {
	if( ($('.parts3 .part3').length+1) > 99) {
		alert("Maximum 100 Employee allowed.");
    } else {
    	$('[data-toggle="tooltip"]').tooltip();
		var id = ($('.parts3 .part3').length + 2).toString();
        $('#partint3').val(id);
        $('.parts3').append(
	'<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
	  '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
	     '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
	     	'<div class="fg-line">&nbsp;'+id+'</div>'+
	     '</div>'+

	  '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
				'<div>'+
					'<input type="text" name="EMPNAME[]" id="txt_staffcode_'+id+'" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff('+id+',1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div style="clear: both;"></div>'+
			'</div>'+

			'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
				'<div id="photo_'+id+'">'+

				'</div>'+
				'<input type="hidden"  name="EMPSRNO[]" id="empsrno_'+id+'" value="">'+
    		'</div>'+

            '<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
              /*'<label id="exp_'+id+'"></label>'+*/
              '<input type="text"  name="CUREXP[]" id="curexp_'+id+'" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			/*'<label id="doj_'+id+'"></label>'+*/
    			'<input type="text"  name="DATEOFJOIN[]" id="curdoj_'+id+'" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			/*'<label id="cbrn_'+id+'"></label> <br>'+*/
    			'<input type="text"  name="CURBRN[]" id="curbrn_'+id+'" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">'+
    			/*'<label id="cdep_'+id+'"></label> <br>'+*/
    			'<input type="text"  name="CURDEP[]" id="curdep_'+id+'" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">'+
    			/*'<label id="cdes_'+id+'"></label> <br>'+*/
    			'<input type="text"  name="CURDES[]" id="curdes_'+id+'" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<div id = "staff_des_'+id+'"></div>'+
    		'</div>'+
    		'<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<div id = "staff_dept_'+id+'"></div>'+
    		'</div>'+
    		'<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    		'<input type="text" name="REPORTTO[]" id="txt_reportto_'+id+'" required="required" maxlength="100" placeholder="Reporting To" title="Reporting To" class="form-control" data-toggle="tooltip"   data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
    		'</div>'+
    		'</div>'+
	'</div>');
    $.getScript("js/jquery-customselect.js");
	$(".chosn").customselect();
	getdes(id);
	getdept(id);
     $('#txt_staffcode_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
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
		autoFocus: true,
		minLength: 0
	});

	$('#txt_reportto_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
				   action: 'allbrnemp'
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
		autoFocus: true,
		minLength: 0
	});

    }
}



function emp_dept_change(gridid) {
	if( ($('.parts3 .part3').length+1) > 99) {
		alert("Maximum 100 Employee allowed.");
    } else {
    	$('[data-toggle="tooltip"]').tooltip();
		var id = ($('.parts3 .part3').length + 2).toString();
        $('#partint3').val(id);
        $('.parts3').append(
	'<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
	  '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
	     '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
	     	'<div class="fg-line">&nbsp;'+id+'</div>'+
	     '</div>'+

	  '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
				'<div>'+
					'<input type="text" name="EMPNAME[]" id="txt_staffcode_'+id+'" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff('+id+',1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div style="clear: both;"></div>'+
			'</div>'+

			'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
				'<div id="photo_'+id+'">'+

				'</div>'+
				'<input type="hidden"  name="EMPSRNO[]" id="empsrno_'+id+'" value="">'+
    		'</div>'+

            '<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 /*'<label id="exp_'+id+'"></label>'+*/
    			 '<input type="text"  name="CUREXP[]" id="curexp_'+id+'" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+

    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 /*'<label id="doj_'+id+'"></label>'+*/
    			 '<input type="text"  name="DATEOFJOIN[]" id="curdoj_'+id+'" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			/*'<label id="cbrn_'+id+'"></label> <br>'+*/
    			'<input type="text"  name="CURBRN[]" id="curbrn_'+id+'" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">'+
    			/*'<label id="cdep_'+id+'"></label> <br>'+*/
    			'<input type="text"  name="CURDEP[]" id="curdep_'+id+'" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">'+
    			/*'<label id="cdes_'+id+'"></label> <br>'+*/
    			'<input type="text"  name="CURDES[]" id="curdes_'+id+'" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<div id = "staff_dept_'+id+'"></div>'+
    		'</div>'+
    		'<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    		'<input type="text" name="REPORTTO[]" id="txt_reportto_'+id+'" required="required" maxlength="100" placeholder="Reporting To" title="Reporting To" class="form-control" data-toggle="tooltip"   data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
    		'</div>'+
    		'</div>'+
	'</div>');
    $.getScript("js/jquery-customselect.js");
	$(".chosn").customselect();
	getdept(id);
     $('#txt_staffcode_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
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
		autoFocus: true,
		minLength: 0
	});

	$('#txt_reportto_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
				   action: 'allbrnemp'
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
		autoFocus: true,
		minLength: 0
	});

    }
}

function emp_night_add(gridid) {
	if( ($('.parts3 .part3').length+1) > 99) {
		alert("Maximum 100 Employee allowed.");
    } else {
    	$('[data-toggle="tooltip"]').tooltip();
		var id = ($('.parts3 .part3').length + 2).toString();
        $('#partint3').val(id);
        $('.parts3').append(
	'<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
	  '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
	     '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
	     	'<div class="fg-line">&nbsp;'+id+'</div>'+
	     '</div>'+

	  '<div class="col-sm-2 colheight" style="padding: 1px 0px;">	'+
				'<div>'+
					'<input type="text" name="empname[]" id="txt_staffcode_'+id+'" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addempnit('+id+')" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div style="clear: both;"></div>'+
			'</div>'+

			'<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
				'<div id="photo_'+id+'">'+

				'</div>'+
				'<input type="hidden" name="empsrno[]" id="empsrno_'+id+'" value="">'+
				'<input type="hidden" name="descode[]" id="descode_'+id+'" value="">'+
				'<input type="hidden" name="esecode[]" id="esecode_'+id+'" value="">'+
			'</div>'+

            '<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<input type="text"  name="CURDES[]" id="curdes_'+id+'" placeholder="DESIGNATION"  required="required" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			 '<input type="text"  name="CURDEP[]" id="curdep_'+id+'" placeholder="DEPARTMENT" required="required" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+

    		'<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			  '<input type="text"  name="CURWORK[]" id="curwork_'+id+'" placeholder="NATURE OF WORK" required="required" data-toggle="tooltip" data-placement="top" title="NATURE OF WORK" class="form-control"   style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    		'	  <input type="text"  onkeypress="return validateFloatKeyPress(this,event,1);" maxlength="5" required="required" name="TOTWORK[]" id="totwork_'+id+'" placeholder="WORKING HOURS" data-toggle="tooltip" data-placement="top" title="WORKING HOURS" class="form-control"   style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+

    		'</div>'+
	'</div>');
  $.getScript("js/jquery-customselect.js");
	$(".chosn").customselect();
	// getdept(id);
    $('#txt_staffcode_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
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
		autoFocus: true,
		minLength: 0
	});
    }
}

function emp_increment(gridid) {
	if( ($('.parts3 .part3').length+1) > 99) {
		alert("Maximum 100 Employee allowed.");
    } else {
    	$('[data-toggle="tooltip"]').tooltip();
		var id = ($('.parts3 .part3').length + 2).toString();
        $('#partint3').val(id);
        $('.parts3').append(
	'<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
	'<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
			'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
				'<div class="fg-line">&nbsp;'+id+'</div>'+
			'</div>'+

			'<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
				'<div>'+
					'<input type="text" name="EMPNAME[]" id="txt_staffcode_'+id+'" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff('+id+',2)" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div style="clear: both;"></div>'+
			'</div>'+

			'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
				'<div id="photo_'+id+'">'+

				'</div>	'+
				'<input type="hidden"  name="EMPSRNO[]" id="empsrno_'+id+'" value="">'+
    		'</div>'+

            '<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			'<input type="text"  name="CUREXP[]" id="curexp_'+id+'" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			 '<input type="text"  name="DATEOFJOIN[]" id="curdoj_'+id+'" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			 '<input type="text"  name="CURBRN[]" id="curbrn_'+id+'" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">	'+
    			'<input type="text"  name="CURDEP[]" id="curdep_'+id+'" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
			'</div>'+
			'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">	'+
				'<input type="text"  name="CURDES[]" id="curdes_'+id+'" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
			'</div>'+
			'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
				'<input type="text"  name="CURBAS[]" id="curbas_'+id+'" placeholder="BASIC" data-toggle="tooltip" data-placement="top" title="BASIC" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
				'<input type="text"  name="INCAMT[]" id="incamt_'+id+'" placeholder="INCREMENT AMOUNT" data-toggle="tooltip" data-placement="top" title="INCREMENT AMOUNT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			'<input type="text"  name="NEWBAS[]" id="newbas_'+id+'" placeholder="NEW BASIC" data-toggle="tooltip" data-placement="top" title="NEW BASIC" class="form-control" style=" text-transform: uppercase;height: 25px;" onblur="newbasic('+id+');">'+
    		'</div>'+
    		'</div>'+
		'</div>');
    $.getScript("js/jquery-customselect.js");
	$(".chosn").customselect();
	getdept(id);
     $('#txt_staffcode_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
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
		autoFocus: true,
		minLength: 0
	});
    }
}






function emp_commision(gridid) {
	if( ($('.parts3 .part3').length+1) > 99) {
		alert("Maximum 100 Employee allowed.");
    } else {
    	$('[data-toggle="tooltip"]').tooltip();
		var id = ($('.parts3 .part3').length + 2).toString();
        $('#partint3').val(id);
        $('.parts3').append(
	'<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
	  '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
	     '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
	     	'<div class="fg-line">&nbsp;'+id+'</div>'+
	     '</div>'+

	  '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
				'<div>'+
					'<input type="text" name="EMPNAME[]" id="txt_staffcode_'+id+'" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff('+id+',3)" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div style="clear: both;"></div>'+
			'</div>'+

			'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
				'<div id="photo_'+id+'">'+

				'</div>'+
				'<input type="hidden"  name="EMPSRNO[]" id="empsrno_'+id+'" value="">'+
    		'</div>'+

            '<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			'<input type="text"  name="CUREXP[]" id="curexp_'+id+'" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<input type="text"  name="DATEOFJOIN[]" id="curdoj_'+id+'" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			 '<input type="text"  name="CURBRN[]" id="curbrn_'+id+'" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">'+
    			'<input type="text"  name="CURDEP[]" id="curdep_'+id+'" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
			'</div>'+
			'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">'+
				'<input type="text"  name="CURDES[]" id="curdes_'+id+'" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
			'</div>'+
			'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
				'<input type="text"  name="CURBAS[]" id="curbas_'+id+'" placeholder="BASIC" data-toggle="tooltip" data-placement="top" title="CURRENT BASIC" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
				'<input type="text"  name="CURCOMM[]" id="curcomm_'+id+'" placeholder="COMMISSION" data-toggle="tooltip" data-placement="top" title="CURRENT COMMISSION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+

            '<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
    			'<input type="text" name="NEWCOMM[]" id="newccomm_'+id+'" required="required" maxlength="100" placeholder="NEW COMMISSION" title="NEW COMMISSION" class="form-control" data-toggle="tooltip"   data-placement="top" onBlur="newcomm(1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">'+
				'<input type="text"  name="APRXAMT[]" id="aprxamt_'+id+'" placeholder="BASIC" data-toggle="tooltip" data-placement="top" title="BASIC" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'</div>'+
	'</div>');
    $.getScript("js/jquery-customselect.js");
	$(".chosn").customselect();
	 $('#txt_staffcode_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
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
		autoFocus: true,
		minLength: 0
	});
    }
}

function emp_mrg_gift(gridid) {
	if( ($('.parts3 .part3').length+1) > 99) {
		alert("Maximum 100 Employee allowed.");
    } else {
    	$('[data-toggle="tooltip"]').tooltip();
		var id = ($('.parts3 .part3').length + 2).toString();
        $('#partint3').val(id);
        $('.parts3').append(
	'<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
	  '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
			'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
			'<div class="fg-line">&nbsp;'+id+'</div>'+
			'</div>'+

			'<div class="col-sm-2 colheight" style="padding: 1px 0px;">	'+
				'<div>'+
					'<input type="text" name="empname[]" id="txt_staffcode_'+id+'" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addempgift('+id+')" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div style="clear: both;"></div>'+
			'</div>'+

			'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
				'<div id="photo_'+id+'">'+

				'</div>	'+
				'<input type="hidden" name="empsrno[]" id="empsrno_'+id+'" value="">'+
			'</div>'+

            '<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			 '<input type="text"  name="CUREXP[]" id="curexp_'+id+'" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			 '<input type="text"  name="DATEOFJOIN[]" id="curdoj_'+id+'" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	'+
    			 '<input type="text"  name="CURBRN[]" id="curbrn_'+id+'" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	'+
    			 '<input type="text"  name="CURDEP[]" id="curdep_'+id+'" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	'+
    			 '<input type="text"  name="CURDES[]" id="curdes_'+id+'" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+

    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	'+
    			 '<input type="text"  name="OWNGIFT[]" id="owngift_'+id+'" placeholder="GRAM" data-toggle="tooltip" data-placement="top" title="GIFT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    		'</div>'+
    		'<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	'+
    			 '<input type="text"   name="TRUSTAMT[]" id="trustamt_'+id+'" placeholder="TRUST AMOUNT" data-toggle="tooltip" data-placement="top" title="TRUST AMOUNT" class="form-control ttlsum" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >'+
    	'</div>'+
        '</div>'+
	'</div>');
    $.getScript("js/jquery-customselect.js");
	$(".chosn").customselect();
	 $('#txt_staffcode_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
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
		autoFocus: true,
		minLength: 0
	});
    }
}

// Product Code Add
function add_dynamic_product_code(gridid) {
	alert("CAME---");
	if( ($('.parts3 .part3').length+1) > 24) {
		alert("Maximum 25 Products allowed..");
    } else {
    	$('[data-toggle="tooltip"]').tooltip();
		var id = ($('.parts3 .part3').length + 1).toString();
        $('#partint3').val(id);
        $('.parts3').append(
			'<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
				'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
					'<div class="fg-line">&nbsp;'+id+'</div>'+
				'</div>'+
				'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_prdcode[]" id="txtdynamic_prd_prdcode_'+id+'" required="required" maxlength="100" placeholder="PRODUCT CODE" title="PRODUCT CODE" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_prdname[]" id="txtdynamic_prd_prdname_'+id+'" required="required" maxlength="100" placeholder="PRODUCT NAME" title="PRODUCT NAME" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_subprdcode[]" id="txtdynamic_prd_subprdcode_'+id+'" required="required" maxlength="100" placeholder="SUB PRODUCT CODE" title="SUB PRODUCT CODE" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_subprdname[]" id="txtdynamic_prd_subprdname_'+id+'" required="required" maxlength="100" placeholder="SUB PRODUCT NAME" title="SUB PRODUCT NAME" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+

				'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_subprdhsn[]" id="txtdynamic_prd_subprdhsn_'+id+'" required="required" maxlength="100" placeholder="SUB PRODUCT HSN CODE" title="SUB PRODUCT HSN CODE" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_subprdcgst[]" id="txtdynamic_prd_subprdcgst_'+id+'" required="required" maxlength="100" placeholder="SUB PRODUCT CGST %" title="SUB PRODUCT CGST %" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_subprdsgst[]" id="txtdynamic_prd_subprdsgst_'+id+'" required="required" maxlength="100" placeholder="SUB PRODUCT SGST %" title="SUB PRODUCT SGST %" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_subprdigst[]" id="txtdynamic_prd_subprdigst_'+id+'" required="required" maxlength="100" placeholder="SUB PRODUCT IGST %" title="SUB PRODUCT IGST %" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
				'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
					'<input type="text" name="txtdynamic_prd_subprdunit[]" id="txtdynamic_prd_subprdunit_'+id+'" required="required" maxlength="100" placeholder="UNIT" title="UNIT" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">'+
				'</div>'+
			'</div>'+
			'</div>'+
		'<div class="clear clear_both"></div>');

    /* $.getScript("js/jquery-customselect.js");
	$(".chosn").customselect();
    $('#txt_staffcode_'+id).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   slt_emp: request.term,
				   brncode: $('#slt_brnch_0').val(),
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
		autoFocus: true,
		minLength: 0
		}); */
    }
}
// Product Code Add
