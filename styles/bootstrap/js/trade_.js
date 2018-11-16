var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample_det = [
		
		]
		$scope.sample1 = [{'SECTION':'BOYS BABA SUIT'},]
		$scope.sample = [
		{'url' : 'target_images/P71917D101.png','SUPNAME' : '','SUPCODE' : '','PKSLIP' : '','PRDCODE' : '71917','ITMDESG' : '101','PRODUCT_NAME' : '','REFNUMB' : '','TOTAL_CNT' : 0,'ITMCODE' : '','MRP' : ,'TYPE' : 'all','iv' : '1'},
		{'url' : 'target_images/P75244D18.png','SUPNAME' : '','SUPCODE' : '','PKSLIP' : '','PRDCODE' : '75244','ITMDESG' : '18','PRODUCT_NAME' : '','REFNUMB' : '','TOTAL_CNT' : 0,'ITMCODE' : '','MRP' : ,'TYPE' : 'all','iv' : '2'},
		{'url' : 'target_images/P79900D1010.png','SUPNAME' : '','SUPCODE' : '','PKSLIP' : '','PRDCODE' : '79900','ITMDESG' : '1010','PRODUCT_NAME' : '','REFNUMB' : '','TOTAL_CNT' : 0,'ITMCODE' : '','MRP' : ,'TYPE' : 'all','iv' : '3'},
		{'url' : 'target_images/P79900D127.png','SUPNAME' : '','SUPCODE' : '','PKSLIP' : '','PRDCODE' : '79900','ITMDESG' : '127','PRODUCT_NAME' : '','REFNUMB' : '','TOTAL_CNT' : 0,'ITMCODE' : '','MRP' : ,'TYPE' : 'all','iv' : '4'},
		{'url' : 'target_images/P79900D66.png','SUPNAME' : '','SUPCODE' : '','PKSLIP' : '','PRDCODE' : '79900','ITMDESG' : '66','PRODUCT_NAME' : '','REFNUMB' : '','TOTAL_CNT' : 0,'ITMCODE' : '','MRP' : ,'TYPE' : 'all','iv' : '5'},
		{'url' : 'target_images/P79900D67.png','SUPNAME' : '','SUPCODE' : '','PKSLIP' : '','PRDCODE' : '79900','ITMDESG' : '67','PRODUCT_NAME' : '','REFNUMB' : '','TOTAL_CNT' : 0,'ITMCODE' : '','MRP' : ,'TYPE' : 'all','iv' : '6'},
		{'url' : 'target_images/P79900D8052.png','SUPNAME' : '','SUPCODE' : '','PKSLIP' : '','PRDCODE' : '79900','ITMDESG' : '8052','PRODUCT_NAME' : '','REFNUMB' : '','TOTAL_CNT' : 0,'ITMCODE' : '','MRP' : ,'TYPE' : 'all','iv' : '7'},
		
		]
		
		 $scope.view = function(prd,desnum,ival,type) {
		var chk1 =prd +'/'+ desnum +'/'+ ival +'/'+ 150 +'/'+ type;
		var w = 1500;var h = 800;var left = Number((screen.width/2)-(w/2)); var tops = Number((screen.height/2)-(h/2));
		window.open('trade_prd_det.php?num='+chk1, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+tops+', left='+left);
		 };
		
		 $scope.selection_process = function(PRDCODE,ITMCODE,ival,typ,refnumb) {
		var sel_gm=document.getElementById('sel_gm').value;
		if(sel_gm==0){	alert('Select Some GM');}else{
		var chk1 = PRDCODE +'/'+ ITMCODE +'/'+ ival +'/'+ typ ;
		var value = $.ajax({
		type: 'POST',
		url: 'update_selection.php?num='+chk1,
		beforeSend: function() {
		},
		success: function(data) {
		if(data=='s') {
		var value_new = 'SELECTED';
		
		}else if (data=='r'){
		var value_new = 'REJECTED';
		
		}
		if(value_new=='SELECTED'){
		document.getElementById('selected_'+ival).style.display='block'} else if(value_new=='REJECTED'){
		document.getElementById('reject_'+ival).style.display='block'
		}
		document.getElementById('hide_ajx_1_'+ival).style.display = 'none';
		$.ajax({
		type: 'POST',
		url: 'ajax_approval_insert.php?gm='+sel_gm+'&refnumb='+refnumb,
		})},
		}).responseText;
		
		}
		};
		 });