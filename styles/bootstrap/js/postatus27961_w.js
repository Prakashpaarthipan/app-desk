var myApp3 = angular.module("myApp3", []);
	myApp3.controller("MyController3", function MyController3($scope){ 
		$scope.week  = [{"srno" : "1", "G_Srno" : "6", "Grpname" : "MENS FORMAL SHIRTS", "Seccode" : "9", "Secsrno" : "196", "Secname" : "MENS FORMAL SHIRTS", "Supcode" : "27445", "Supname" : "SHIPRA COMMERCIAL PVT LTD", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "38959", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-8", "Typ" : "W", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "A", "OrdQ" : "2098", "OrdPV" : "6.31", "OrdSV" : "12.48", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2098", "PENPV" : "6.31", "PENSV" : "12.48"},
		{"srno" : "2", "G_Srno" : "6", "Grpname" : "MENS FORMAL SHIRTS", "Seccode" : "9", "Secsrno" : "196", "Secname" : "MENS FORMAL SHIRTS", "Supcode" : "27445", "Supname" : "SHIPRA COMMERCIAL PVT LTD", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "38960", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-8", "Typ" : "W", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "A", "OrdQ" : "162", "OrdPV" : ".49", "OrdSV" : ".96", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "162", "PENPV" : ".49", "PENSV" : ".96"},
		]
		$scope.getTotal = function(){
			var total1 = 0;
			var total2 = 0;
			var total3 = 0;
			var total4 = 0;
			var total5 = 0;
			var total6 = 0;
			for(var i = 0; i < $scope.week.length; i++){
				var ordqt = $scope.week[i]["OrdQ"];
				var ordvval = $scope.week[i]["OrdSV"];
				var resqt = $scope.week[i]["RecQ"];
				var resvval = $scope.week[i]["RecSV"];
				var penqt = $scope.week[i]["PENQ"];
				var penval = $scope.week[i]["PENSV"];
				
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
		angular.bootstrap(document.getElementById('App3'),['myApp3']);
		});