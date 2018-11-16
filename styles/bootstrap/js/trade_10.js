var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample_det = [
		{'url' : 'S17332P77698D3Z0N333M475IIAD2038303.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '3','ITMSIZE' : '','NETRATE' : '333','BRANCH' : '1','REMARK' : '','RATE' : '475','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038303','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '1'},
		{'url' : 'S17332P77698D3Z0N333M475IIAD2038306.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '3','ITMSIZE' : '','NETRATE' : '333','BRANCH' : '1','REMARK' : '','RATE' : '475','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038306','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '2'},
		{'url' : 'S17332P77698D3Z0N333M475IIAD2038307.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '3','ITMSIZE' : '','NETRATE' : '333','BRANCH' : '1','REMARK' : '','RATE' : '475','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038307','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '3'},
		{'url' : 'S17332P77698D3Z0N333M475IIAD2038308.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '3','ITMSIZE' : '','NETRATE' : '333','BRANCH' : '1','REMARK' : '','RATE' : '475','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038308','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '4'},
		{'url' : 'S17332P77698D3Z0N333M475IIAD2038315.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '3','ITMSIZE' : '','NETRATE' : '333','BRANCH' : '1','REMARK' : '','RATE' : '475','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038315','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '5'},
		{'url' : 'S17332P77698D4Z0N356M495IIAD2038321.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '4','ITMSIZE' : '','NETRATE' : '356','BRANCH' : '1','REMARK' : '','RATE' : '495','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038321','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '6'},
		{'url' : 'S17332P77698D4Z0N356M495IIAD2038326.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '4','ITMSIZE' : '','NETRATE' : '356','BRANCH' : '1','REMARK' : '','RATE' : '495','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038326','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '7'},
		{'url' : 'S17332P77698D4Z0N356M495IIAD2038329.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '4','ITMSIZE' : '','NETRATE' : '356','BRANCH' : '1','REMARK' : '','RATE' : '495','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038329','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '8'},
		{'url' : 'S17332P77698D4Z0N356M495IIAD2038330.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '4','ITMSIZE' : '','NETRATE' : '356','BRANCH' : '1','REMARK' : '','RATE' : '495','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2038330','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '9'},
		{'url' : 'S17332P77698D77698Z0N190M275IIAD2316605.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '77698','ITMSIZE' : '','NETRATE' : '190','BRANCH' : '1','REMARK' : '','RATE' : '275','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2316605','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '10'},
		{'url' : 'S17332P77698D77698Z0N190M275IIAD2316614.png','SUPCODE' : '17332','PKSLIP' : '2015-16 142046(PJV)','PRDCODE' : '77698','ITMDESG' : '77698','ITMSIZE' : '','NETRATE' : '190','BRANCH' : '1','REMARK' : '','RATE' : '275','REFNUMB' : '227471','REFYEAR' : '2015-16','ITMCODE' : 'IAD2316614','PRODUCT_NAME' : 'TNT FANCY PRINT','ival' : '11'},
		]
		$scope.sample1 = [{'SECTION':''},]
		$scope.sample = [
		{'url' : 'target_images/target_image_10/D227471~1.png','SUPNAME' : 'SRI GANESH HANDLOOMS','SUPCODE' : '17332','BRNCODE' : '1','BRNNAME' : 'TUP','PRDCODE' : '77698','ITMDESG' : '3','PRODUCT_NAME' : 'TNT FANCY PRINT','REFNUMB' : '227471','TOTAL_CNT' : 11,'ITMCODE' : 'IAD2038303','REFYEAR' : '2015-16','TYPE' : '','MRP' : 475,'iv' : '1'},
		
		]
		
		 $scope.view = function(prd,desnum,ival,brn,grp_total) {
		
		var sel_gm=0;var chk1 =prd +'/'+ desnum +'/'+ ival +'/'+ brn +'/'+grp_total;
		var w = 1500;var h = 800;var left = Number((screen.width/2)-(w/2)); var tops = Number((screen.height/2)-(h/2));
		popup = window.open('target_prd_det.php?num='+chk1+'&gm='+sel_gm, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+tops+', left='+left);popup.focus();
		  };
		 $scope.selection_process = function(prdcode,itmcode,ival,typ,refnumb,refyear,status) {
		var sel_gm=0;
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