var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "5", "Grpname" : "SALWAR KAMEEZ", "Seccode" : "90", "Secsrno" : "180", "Secname" : "SALWAR KAMEEZ OTHER BRAND", "Supcode" : "13542", "Supname" : "D.L.CREATION", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "51395", "Pordisc" : "2", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-17", "Typ" : "M", "EmpDet" : "1450-SELVARAJ A ", "pormode" : "R", "OrdQ" : "235", "OrdPV" : "5.63", "OrdSV" : "9.02", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "235", "PENPV" : "5.63", "PENSV" : "9.02"},
		{"srno" : "2", "G_Srno" : "5", "Grpname" : "SALWAR KAMEEZ", "Seccode" : "90", "Secsrno" : "180", "Secname" : "SALWAR KAMEEZ OTHER BRAND", "Supcode" : "10143", "Supname" : "PARSHWA ART", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "48428", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-18", "Typ" : "M", "EmpDet" : "1450-SELVARAJ A ", "pormode" : "R", "OrdQ" : "150", "OrdPV" : "2.12", "OrdSV" : "3.44", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "150", "PENPV" : "2.12", "PENSV" : "3.44"},
		{"srno" : "3", "G_Srno" : "5", "Grpname" : "SALWAR KAMEEZ", "Seccode" : "90", "Secsrno" : "180", "Secname" : "SALWAR KAMEEZ OTHER BRAND", "Supcode" : "17192", "Supname" : "OORJAA", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "48430", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-18", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "100", "OrdPV" : "3.3", "OrdSV" : "5.7", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "100", "PENPV" : "3.3", "PENSV" : "5.7"},
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