var myApp3 = angular.module("myApp3", []);
	myApp3.controller("MyController3", function MyController3($scope){ 
		$scope.week  = [{"srno" : "1", "G_Srno" : "13", "Grpname" : "SILKS", "Seccode" : "37", "Secsrno" : "24", "Secname" : "SILK(DVM)", "Supcode" : "17726", "Supname" : "SRINIVASA PATTU CENTER", "Ctyname" : "DHARMAVARAM", "Poryear" : "2015-16", "Pornumb" : "47321", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "24", "OrdPV" : "2.16", "OrdSV" : "3.53", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "24", "PENPV" : "2.16", "PENSV" : "3.53"},
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