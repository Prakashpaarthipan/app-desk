var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "5", "Grpname" : "SALWAR KAMEEZ", "Seccode" : "90", "Secsrno" : "180", "Secname" : "SALWAR KAMEEZ OTHER BRAND", "Supcode" : "10143", "Supname" : "PARSHWA ART", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "35864", "Pordisc" : "7", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "08/09/2015", "POREDDT" : "12/09/2015", "Diff" : "6", "Typ" : "O", "EmpDet" : "1450-SELVARAJ A ", "pormode" : "R", "OrdQ" : "16", "OrdPV" : ".19", "OrdSV" : ".29", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "16", "PENPV" : ".19", "PENSV" : ".29"},
		{"srno" : "2", "G_Srno" : "5", "Grpname" : "SALWAR KAMEEZ", "Seccode" : "90", "Secsrno" : "180", "Secname" : "SALWAR KAMEEZ OTHER BRAND", "Supcode" : "10143", "Supname" : "PARSHWA ART", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "38619", "Pordisc" : "7", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "13/09/2015", "POREDDT" : "17/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "1450-SELVARAJ A ", "pormode" : "R", "OrdQ" : "5", "OrdPV" : ".06", "OrdSV" : ".11", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "5", "PENPV" : ".06", "PENSV" : ".11"},
		]
		$scope.getTotal = function(){
			var total1 = 0;
			var total2 = 0;
			var total3 = 0;
			var total4 = 0;
			var total5 = 0;
			var total6 = 0;
			for(var i = 0; i < $scope.tomorrow.length; i++){
				var ordqt = $scope.tomorrow[i]["OrdQ"];
				var ordvval = $scope.tomorrow[i]["OrdSV"];
				var resqt = $scope.tomorrow[i]["RecQ"];
				var resvval = $scope.tomorrow[i]["RecSV"];
				var penqt = $scope.tomorrow[i]["PENQ"];
				var penval = $scope.tomorrow[i]["PENSV"];
				
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
		angular.bootstrap(document.getElementById('App2'),['myApp2']);
		});