var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "31203", "Supname" : "ROHRA TRADERS", "Ctyname" : "ULHASNAGAR", "Poryear" : "2015-16", "Pornumb" : "44954", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "05/09/2015", "POREDDT" : "09/09/2015", "Diff" : "9", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "5760", "OrdPV" : "5.73", "OrdSV" : "11.45", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "5760", "PENPV" : "5.73", "PENSV" : "11.45"},
		{"srno" : "2", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "27754", "Supname" : "DHWANI MARKETING", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "47442", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/09/2015", "POREDDT" : "10/09/2015", "Diff" : "8", "Typ" : "O", "EmpDet" : "1070-KANDASAMY T ", "pormode" : "R", "OrdQ" : "6000", "OrdPV" : "2.4", "OrdSV" : "3.9", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "6000", "PENPV" : "2.4", "PENSV" : "3.9"},
		{"srno" : "3", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "7972", "Supname" : "NAIDU HALL APPARELS", "Ctyname" : "CHENNAI", "Poryear" : "2015-16", "Pornumb" : "47771", "Pordisc" : "0", "Porspdc" : "7", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "09/09/2015", "POREDDT" : "13/09/2015", "Diff" : "5", "Typ" : "O", "EmpDet" : "1070-KANDASAMY T ", "pormode" : "R", "OrdQ" : "456", "OrdPV" : ".93", "OrdSV" : "1.4", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "456", "PENPV" : ".93", "PENSV" : "1.4"},
		{"srno" : "4", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "7972", "Supname" : "NAIDU HALL APPARELS", "Ctyname" : "CHENNAI", "Poryear" : "2015-16", "Pornumb" : "49927", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "09/09/2015", "POREDDT" : "13/09/2015", "Diff" : "5", "Typ" : "O", "EmpDet" : "1070-KANDASAMY T ", "pormode" : "R", "OrdQ" : "150", "OrdPV" : ".31", "OrdSV" : ".47", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "150", "PENPV" : ".31", "PENSV" : ".47"},
		{"srno" : "5", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "21296", "Supname" : "A.P.TRADERS", "Ctyname" : "COIMBATORE", "Poryear" : "2015-16", "Pornumb" : "47797", "Pordisc" : "11", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "09/09/2015", "POREDDT" : "13/09/2015", "Diff" : "5", "Typ" : "O", "EmpDet" : "1070-KANDASAMY T ", "pormode" : "R", "OrdQ" : "312", "OrdPV" : ".48", "OrdSV" : ".74", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "312", "PENPV" : ".48", "PENSV" : ".74"},
		{"srno" : "6", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "21296", "Supname" : "A.P.TRADERS", "Ctyname" : "COIMBATORE", "Poryear" : "2015-16", "Pornumb" : "47809", "Pordisc" : "11", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "09/09/2015", "POREDDT" : "13/09/2015", "Diff" : "5", "Typ" : "O", "EmpDet" : "1070-KANDASAMY T ", "pormode" : "R", "OrdQ" : "360", "OrdPV" : ".54", "OrdSV" : ".83", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "360", "PENPV" : ".54", "PENSV" : ".83"},
		{"srno" : "7", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "21296", "Supname" : "A.P.TRADERS", "Ctyname" : "COIMBATORE", "Poryear" : "2015-16", "Pornumb" : "47845", "Pordisc" : "11", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "09/09/2015", "POREDDT" : "13/09/2015", "Diff" : "5", "Typ" : "O", "EmpDet" : "1070-KANDASAMY T ", "pormode" : "R", "OrdQ" : "240", "OrdPV" : ".45", "OrdSV" : ".69", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "240", "PENPV" : ".45", "PENSV" : ".69"},
		{"srno" : "8", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "30690", "Supname" : "NEW SHAKRA ENTERPRISES", "Ctyname" : "CHENNAI", "Poryear" : "2015-16", "Pornumb" : "50476", "Pordisc" : "3", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "12/09/2015", "POREDDT" : "16/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "560", "OrdPV" : "1.5", "OrdSV" : "2.23", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "560", "PENPV" : "1.5", "PENSV" : "2.23"},
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