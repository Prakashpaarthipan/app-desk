var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample_det = [
		]
		$scope.sample1 = [{'SECTION':''},]
		$scope.sample = [
		
		]
		
		 $scope.view = function(prd,desnum,ival,brn,grp_total) {
		
		var sel_gm=document.getElementById('sel_gm').value;
		if(sel_gm==0){	alert('Select Any One GM');}else{var chk1 =prd +'/'+ desnum +'/'+ ival +'/'+ brn +'/'+grp_total;
		var w = 1500;var h = 800;var left = Number((screen.width/2)-(w/2)); var tops = Number((screen.height/2)-(h/2));
		popup = window.open('target_prd_det.php?num='+chk1+'&gm='+sel_gm, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+tops+', left='+left);popup.focus();
		  };  };
		 $scope.selection_process = function(prdcode,itmcode,ival,typ,refnumb,refyear,status) {
		var sel_gm=document.getElementById('sel_gm').value;
		if(sel_gm==0){	alert('Select Any One GM');}else{
		$('#load_page').show();
		var ref_val=document.getElementById('refnumd_det').value;
		var ref_det= new Array(ref_val);
		ref_det.push(refnumb+'#'+refyear+'@'+typ);document.getElementById('refnumd_det').value=ref_det;
		var value = $.ajax({
		type: 'POST',
		url: 'update_selection.php?prd='+prdcode+'&itm='+itmcode+'&ival='+ival+'&typ='+typ+'&refnumb='+refnumb+'&refyear='+refyear+'&status='+status+'&gm='+sel_gm,
		beforeSend: function() {
		},
		success: function(data) {
		if(data=='s') {
		var tot_qry=document.getElementById('total_count').value;var tot_sel=document.getElementById('total_selected').value;var final_val= +tot_sel + +1;document.getElementById('total_selected').value=final_val;tot_sel=document.getElementById('total_selected').value;
		if(tot_sel>0) {document.getElementById('ok_status').style.display = 'block';}
		var value_new = 'SELECTED';
		
		}else if (data=='r'){
		var tot_qry=document.getElementById('total_count').value;var tot_sel=document.getElementById('total_selected').value;var final_val= +tot_sel + +1;document.getElementById('total_selected').value=final_val;tot_sel=document.getElementById('total_selected').value;if(tot_sel>0) {document.getElementById('ok_status').style.display = 'block';}
		var value_new = 'REJECTED';
		}
		if(value_new=='SELECTED'){
		document.getElementById('ml_selected_'+ival).style.display='block';document.getElementById('mh_selected_'+ival).style.display='block';document.getElementById('ql_selected_'+ival).style.display='block';document.getElementById('qh_selected_'+ival).style.display='block';document.getElementById('ml_hide_ajx_1_'+ival).style.display='none';document.getElementById('mh_hide_ajx_1_'+ival).style.display='none';document.getElementById('ql_hide_ajx_1_'+ival).style.display='none';document.getElementById('qh_hide_ajx_1_'+ival).style.display='none';} else if(value_new=='REJECTED'){
		document.getElementById('ml_selected_'+ival).style.display='block';document.getElementById('mh_selected_'+ival).style.display='block';document.getElementById('ql_selected_'+ival).style.display='block';document.getElementById('qh_selected_'+ival).style.display='block';document.getElementById('ml_hide_ajx_1_'+ival).style.display='none';document.getElementById('mh_hide_ajx_1_'+ival).style.display='none';document.getElementById('ql_hide_ajx_1_'+ival).style.display='none';document.getElementById('qh_hide_ajx_1_'+ival).style.display='none';
		}
		
		$('#load_page').hide();
		},
		}).responseText;
		}
		};
		
		 $scope.selection_process_det = function(prdcode,itmcode,ival,typ,refnumb,refyear,status) {
		
		var ref_val=document.getElementById('refnumd_det').value;
					//alert(ref_val);
					
		var ref_det= new Array(ref_val);
		ref_det.push(refnumb+'#'+refyear+'@'+typ+'%'+itmcode);document.getElementById('refnumd_det').value=ref_det;
		if(typ=='s') {
		var tot_qry=document.getElementById('total_count').value;var tot_sel=document.getElementById('total_selected').value;var final_val= +tot_sel + +1;document.getElementById('total_selected').value=final_val;tot_sel=document.getElementById('total_selected').value;
		if(tot_sel>0) {document.getElementById('ok_status').style.display = 'block';}
		var value_new = 'SELECTED';
		
		}else if (typ=='r'){
		var tot_qry=document.getElementById('total_count').value;var tot_sel=document.getElementById('total_selected').value;var final_val= +tot_sel + +1;document.getElementById('total_selected').value=final_val;tot_sel=document.getElementById('total_selected').value;if(tot_sel>0) {document.getElementById('ok_status').style.display = 'block';}
		var value_new = 'REJECTED';
		}
		if(value_new=='SELECTED'){
		document.getElementById('selected_det_'+ival).style.display='block';if (window.opener != null && !window.opener.closed) {
		var ivalue=document.getElementById('cnt').value;
		
		
		}
		} else if(value_new=='REJECTED'){
		document.getElementById('reject_det_'+ival).style.display='block';
		if (window.opener != null && !window.opener.closed) {var ivalue=document.getElementById('cnt').value;
		window.opener.document.getElementById('hide_ajx_'+ivalue).style.display = 'none';
		
		}
		
		}document.getElementById('hide_ajx_det_'+ival).style.display = 'none';
		};
		 });