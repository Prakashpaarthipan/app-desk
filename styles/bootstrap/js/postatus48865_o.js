var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "22247", "Supname" : "KIRTIKUMAR & COMPANY", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "47892", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "10/09/2015", "POREDDT" : "14/09/2015", "Diff" : "9", "Typ" : "O", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "1920", "OrdPV" : "3.46", "OrdSV" : "5.76", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1920", "PENPV" : "3.46", "PENSV" : "5.76"},
		{"srno" : "2", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "22247", "Supname" : "KIRTIKUMAR & COMPANY", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "47898", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "10/09/2015", "POREDDT" : "14/09/2015", "Diff" : "9", "Typ" : "O", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "960", "OrdPV" : "1.06", "OrdSV" : "1.87", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "960", "PENPV" : "1.06", "PENSV" : "1.87"},
		{"srno" : "3", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "20878", "Supname" : "S.K.SYNTHETICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "38687", "Pordisc" : "10", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/09/2015", "POREDDT" : "18/09/2015", "Diff" : "5", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "240", "OrdPV" : ".18", "OrdSV" : ".25", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "240", "PENPV" : ".18", "PENSV" : ".25"},
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