var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "28223", "Supname" : "B.M.GARMENTS", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "47997", "Pordisc" : "10", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "1602-SIVASHANKAR D", "pormode" : "R", "OrdQ" : "720", "OrdPV" : "3.04", "OrdSV" : "5.36", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "720", "PENPV" : "3.04", "PENSV" : "5.36"},
		{"srno" : "2", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "29267", "Supname" : "HEERA FASHION", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "44253", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "1602-SIVASHANKAR D", "pormode" : "R", "OrdQ" : "1440", "OrdPV" : "3.82", "OrdSV" : "6.41", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1440", "PENPV" : "3.82", "PENSV" : "6.41"},
		{"srno" : "3", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "23351", "Supname" : "RAKESH APPARELS", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "51231", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "1602-SIVASHANKAR D", "pormode" : "R", "OrdQ" : "1500", "OrdPV" : "5.72", "OrdSV" : "10.13", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1500", "PENPV" : "5.72", "PENSV" : "10.13"},
		{"srno" : "4", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "23351", "Supname" : "RAKESH APPARELS", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "51290", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "1602-SIVASHANKAR D", "pormode" : "R", "OrdQ" : "1050", "OrdPV" : "7.3", "OrdSV" : "12.55", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1050", "PENPV" : "7.3", "PENSV" : "12.55"},
		{"srno" : "5", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "26035", "Supname" : "K.M.APPARELS", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "47487", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1800", "OrdPV" : "4.41", "OrdSV" : "7.11", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1800", "PENPV" : "4.41", "PENSV" : "7.11"},
		{"srno" : "6", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "30133", "Supname" : "BLUE STITCH FASHION", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "51411", "Pordisc" : "8", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "1602-SIVASHANKAR D", "pormode" : "R", "OrdQ" : "270", "OrdPV" : "2.35", "OrdSV" : "3.77", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "270", "PENPV" : "2.35", "PENSV" : "3.77"},
		{"srno" : "7", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "112", "Secsrno" : "97", "Secname" : "BOYS COTTON PANT", "Supcode" : "30133", "Supname" : "BLUE STITCH FASHION", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "51412", "Pordisc" : "8", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "1602-SIVASHANKAR D", "pormode" : "R", "OrdQ" : "60", "OrdPV" : ".53", "OrdSV" : ".86", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "60", "PENPV" : ".53", "PENSV" : ".86"},
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