var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "13751", "Supname" : "PARAG FASHION", "Ctyname" : "BELLARY", "Poryear" : "2015-16", "Pornumb" : "48021", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/10/2015", "POREDDT" : "10/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "1602-SIVASHANKAR D", "pormode" : "R", "OrdQ" : "1920", "OrdPV" : "4.51", "OrdSV" : "7.58", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1920", "PENPV" : "4.51", "PENSV" : "7.58"},
		]
		$scope.getTotal = function(){
			var total1 = 0;
			var total2 = 0;
			var total3 = 0;
			var total4 = 0;
			var total5 = 0;
			var total6 = 0;
			for(var i = 0; i < $scope.month.length; i++){
				var ordqt = $scope.month[i]["OrdQ"];
				var ordvval = $scope.month[i]["OrdSV"];
				var resqt = $scope.month[i]["RecQ"];
				var resvval = $scope.month[i]["RecSV"];
				var penqt = $scope.month[i]["PENQ"];
				var penval = $scope.month[i]["PENSV"];
				
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
		angular.bootstrap(document.getElementById('App4'),['myApp4']);
		});