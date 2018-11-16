var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample_det = [
		
		]
		$scope.sample1 = [{'SECTION':'FOOTWEARS'},]
		$scope.sample = [
		]
		
		 $scope.view = function(prd,desnum,ival,type) {
		var brn=document.getElementById('sel_brn').value
		
		var chk1 =prd +'/'+ desnum +'/'+ ival +'/'+ 47 +'/'+ type;
		var w = 1500;var h = 800;var left = Number((screen.width/2)-(w/2)); var tops = Number((screen.height/2)-(h/2));
		window.open('sales_prd_detail.php?num='+chk1+'&brn='+brn, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+tops+', left='+left);
		 };
		 });
		