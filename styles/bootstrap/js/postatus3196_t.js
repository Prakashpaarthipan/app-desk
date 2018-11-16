var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "9", "Grpname" : "MENS TROUSERS", "Seccode" : "69", "Secsrno" : "226", "Secname" : "MENS BRANDED TROUSERS", "Supcode" : "31288", "Supname" : "ADITYA BIRLA NUVO LTD(SETHISH MARKETING)", "Ctyname" : "BANGALORE", "Poryear" : "2015-16", "Pornumb" : "38484", "Pordisc" : "2", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "15/08/2015", "POREDDT" : "12/08/2015", "Diff" : "41", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "3", "OrdPV" : ".05", "OrdSV" : ".07", "RecQ" : "3", "RecPV" : ".05", "RecSV" : ".07", "PENQ" : "0", "PENPV" : "0", "PENSV" : "0"},
		]
		$scope.getTotal = function(){
			var total1 = 0;
			var total2 = 0;
			var total3 = 0;
			var total4 = 0;
			var total5 = 0;
			var total6 = 0;
			for(var i = 0; i < $scope.today.length; i++){
				var ordqt = $scope.today[i]["OrdQ"];
				var ordvval = $scope.today[i]["OrdSV"];
				var resqt = $scope.today[i]["RecQ"];
				var resvval = $scope.today[i]["RecSV"];
				var penqt = $scope.today[i]["PENQ"];
				var penval = $scope.today[i]["PENSV"];
				
				total1 = +total1 + +ordqt;
				total2 = +total2 + +ordvval;
				total3 = +total3 + +resqt;
				total4 = +total4 + +resvval;
				total5 = +total5 + +penqt;
				total6 = +total6 + +penval;
				
			}
			total = total1+"@"+total2.toFixed(2)+"@"+total3+"@"+total4.toFixed(2)+"@"+total5+"@"+total6.toFixed(2);
			 //var values = value.split(",")
			//alert(total);
			return total;
		}
		});
		angular.element(document).ready(function() {
		angular.bootstrap(document.getElementById('App1'),['myApp1']);
		});