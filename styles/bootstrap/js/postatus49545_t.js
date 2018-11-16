var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "30834", "Supname" : "R-NEERSONS NIZHONI CLOTHINGS LIFESTYLE LLP", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "20298", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/07/2015", "POREDDT" : "05/07/2015", "Diff" : "79", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "730", "OrdPV" : "7.71", "OrdSV" : "15.66", "RecQ" : "493", "RecPV" : "5.19", "RecSV" : "10.53", "PENQ" : "237", "PENPV" : "2.53", "PENSV" : "5.13"},
		{"srno" : "2", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "21520", "Supname" : "SHREE KRISHNA ENTERPRISE", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "28135", "Pordisc" : "13", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/07/2015", "POREDDT" : "25/07/2015", "Diff" : "59", "Typ" : "T", "EmpDet" : "5579-SURESH S", "pormode" : "R", "OrdQ" : "18", "OrdPV" : ".25", "OrdSV" : ".4", "RecQ" : "17", "RecPV" : ".24", "RecSV" : ".38", "PENQ" : "1", "PENPV" : ".01", "PENSV" : ".02"},
		{"srno" : "3", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "31005", "Supname" : "DZIRE APPARELS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "26686", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "30/07/2015", "POREDDT" : "03/08/2015", "Diff" : "50", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "306", "OrdPV" : "2.73", "OrdSV" : "4.97", "RecQ" : "72", "RecPV" : ".67", "RecSV" : "1.22", "PENQ" : "234", "PENPV" : "2.06", "PENSV" : "3.75"},
		{"srno" : "4", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "31005", "Supname" : "DZIRE APPARELS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "26692", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "30/07/2015", "POREDDT" : "03/08/2015", "Diff" : "50", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "720", "OrdPV" : "6.21", "OrdSV" : "11.29", "RecQ" : "324", "RecPV" : "2.78", "RecSV" : "5.06", "PENQ" : "396", "PENPV" : "3.42", "PENSV" : "6.23"},
		{"srno" : "5", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "31747", "Supname" : "REAL VALUE GARMENTS PVT.LTD", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "32827", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "03/08/2015", "POREDDT" : "07/08/2015", "Diff" : "46", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "328", "OrdPV" : "2.47", "OrdSV" : "4.26", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "328", "PENPV" : "2.47", "PENSV" : "4.26"},
		{"srno" : "6", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "31747", "Supname" : "REAL VALUE GARMENTS PVT.LTD", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "32828", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "03/08/2015", "POREDDT" : "07/08/2015", "Diff" : "46", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "164", "OrdPV" : "1.23", "OrdSV" : "2.13", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "164", "PENPV" : "1.23", "PENSV" : "2.13"},
		{"srno" : "7", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "30669", "Supname" : "S.R.ENTERPRISES", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "33697", "Pordisc" : "10", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/08/2015", "POREDDT" : "10/08/2015", "Diff" : "43", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "40", "OrdPV" : ".41", "OrdSV" : ".69", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "40", "PENPV" : ".41", "PENSV" : ".69"},
		{"srno" : "8", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "25486", "Supname" : "M/S B.R.ENTERPRISES", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "27972", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "08/08/2015", "POREDDT" : "12/08/2015", "Diff" : "41", "Typ" : "T", "EmpDet" : "7469-RANJITH M", "pormode" : "R", "OrdQ" : "600", "OrdPV" : "3.3", "OrdSV" : "6.57", "RecQ" : "148", "RecPV" : ".81", "RecSV" : "1.62", "PENQ" : "452", "PENPV" : "2.48", "PENSV" : "4.95"},
		{"srno" : "9", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "26230", "Supname" : "MUKUND CLOTHING", "Ctyname" : "CHENNAI", "Poryear" : "2015-16", "Pornumb" : "26250", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/08/2015", "POREDDT" : "18/08/2015", "Diff" : "35", "Typ" : "T", "EmpDet" : "5579-SURESH S", "pormode" : "R", "OrdQ" : "28", "OrdPV" : ".17", "OrdSV" : ".28", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "28", "PENPV" : ".17", "PENSV" : ".28"},
		{"srno" : "10", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "31750", "Supname" : "JANISHA CLOTHING CO", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "27995", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/08/2015", "POREDDT" : "24/08/2015", "Diff" : "29", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "348", "OrdPV" : "3.97", "OrdSV" : "7.99", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "348", "PENPV" : "3.97", "PENSV" : "7.99"},
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