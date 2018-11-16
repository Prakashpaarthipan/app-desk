var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "13", "Grpname" : "SILKS", "Seccode" : "37", "Secsrno" : "24", "Secname" : "SILK(DVM)", "Supcode" : "30434", "Supname" : "KRISHNA RANGA SILKS", "Ctyname" : "DHARMAVARAM", "Poryear" : "2015-16", "Pornumb" : "51389", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "16/09/2015", "POREDDT" : "20/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "3", "OrdPV" : ".34", "OrdSV" : ".58", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3", "PENPV" : ".34", "PENSV" : ".58"},
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