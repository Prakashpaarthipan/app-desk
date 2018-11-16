var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "6", "Grpname" : "MENS FORMAL SHIRTS", "Seccode" : "9", "Secsrno" : "196", "Secname" : "MENS FORMAL SHIRTS", "Supcode" : "26230", "Supname" : "MUKUND CLOTHING", "Ctyname" : "CHENNAI", "Poryear" : "2015-16", "Pornumb" : "48651", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "300", "OrdPV" : "1.47", "OrdSV" : "2.5", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "300", "PENPV" : "1.47", "PENSV" : "2.5"},
		{"srno" : "2", "G_Srno" : "6", "Grpname" : "MENS FORMAL SHIRTS", "Seccode" : "9", "Secsrno" : "196", "Secname" : "MENS FORMAL SHIRTS", "Supcode" : "28157", "Supname" : "RAJ CLOTHING ", "Ctyname" : "MADURAI", "Poryear" : "2015-16", "Pornumb" : "48654", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "200", "OrdPV" : ".28", "OrdSV" : ".39", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "200", "PENPV" : ".28", "PENSV" : ".39"},
		{"srno" : "3", "G_Srno" : "6", "Grpname" : "MENS FORMAL SHIRTS", "Seccode" : "9", "Secsrno" : "196", "Secname" : "MENS FORMAL SHIRTS", "Supcode" : "28611", "Supname" : "KHODAL CREATION ", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "48652", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "200", "OrdPV" : "1.1", "OrdSV" : "1.7", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "200", "PENPV" : "1.1", "PENSV" : "1.7"},
		{"srno" : "4", "G_Srno" : "6", "Grpname" : "MENS FORMAL SHIRTS", "Seccode" : "9", "Secsrno" : "196", "Secname" : "MENS FORMAL SHIRTS", "Supcode" : "28028", "Supname" : "HARI KRISHNA GARMENTS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "52872", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/10/2015", "POREDDT" : "10/10/2015", "Diff" : "-18", "Typ" : "M", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "1526", "OrdPV" : "5.11", "OrdSV" : "10.61", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1526", "PENPV" : "5.11", "PENSV" : "10.61"},
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