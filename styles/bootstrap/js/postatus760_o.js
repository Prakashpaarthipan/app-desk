var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "20032", "Supname" : "CASH ZONE", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "46993", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/09/2015", "POREDDT" : "10/09/2015", "Diff" : "9", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1520", "OrdPV" : "5.4", "OrdSV" : "10.56", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1520", "PENPV" : "5.4", "PENSV" : "10.56"},
		{"srno" : "2", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "16874", "Supname" : "MINTO CLOTHING CO", "Ctyname" : "BANGALORE", "Poryear" : "2015-16", "Pornumb" : "36026", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "09/09/2015", "POREDDT" : "13/09/2015", "Diff" : "6", "Typ" : "O", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "P", "OrdQ" : "288", "OrdPV" : "1.38", "OrdSV" : "2.87", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "288", "PENPV" : "1.38", "PENSV" : "2.87"},
		{"srno" : "3", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "29672", "Supname" : "NBT CLOTHING COMPANY", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50066", "Pordisc" : "13", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/09/2015", "POREDDT" : "18/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "72", "OrdPV" : ".38", "OrdSV" : ".5", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "72", "PENPV" : ".38", "PENSV" : ".5"},
		{"srno" : "4", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "29672", "Supname" : "NBT CLOTHING COMPANY", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50068", "Pordisc" : "13", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/09/2015", "POREDDT" : "18/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "72", "OrdPV" : ".38", "OrdSV" : ".5", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "72", "PENPV" : ".38", "PENSV" : ".5"},
		{"srno" : "5", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "21712", "Supname" : "K.DEVENDRA & CO", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50050", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "15/09/2015", "POREDDT" : "19/09/2015", "Diff" : "0", "Typ" : "O", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "3000", "OrdPV" : "11.14", "OrdSV" : "23.29", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3000", "PENPV" : "11.14", "PENSV" : "23.29"},
		{"srno" : "6", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "29672", "Supname" : "NBT CLOTHING COMPANY", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50163", "Pordisc" : "13", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "16/09/2015", "POREDDT" : "20/09/2015", "Diff" : "-1", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "72", "OrdPV" : ".38", "OrdSV" : ".49", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "72", "PENPV" : ".38", "PENSV" : ".49"},
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