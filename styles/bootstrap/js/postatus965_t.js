var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "26035", "Supname" : "K.M.APPARELS", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "26566", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "15/08/2015", "POREDDT" : "19/08/2015", "Diff" : "38", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1800", "OrdPV" : "6.48", "OrdSV" : "11.61", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1800", "PENPV" : "6.48", "PENSV" : "11.61"},
		{"srno" : "2", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "23351", "Supname" : "RAKESH APPARELS", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "48532", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "08/09/2015", "POREDDT" : "12/09/2015", "Diff" : "14", "Typ" : "T", "EmpDet" : "1602-SIVASHANKAR D", "pormode" : "R", "OrdQ" : "1080", "OrdPV" : "4.64", "OrdSV" : "8.05", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1080", "PENPV" : "4.64", "PENSV" : "8.05"},
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