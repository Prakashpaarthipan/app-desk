var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "24910", "Supname" : "R-NEERSONS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "48724", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "10/09/2015", "POREDDT" : "14/09/2015", "Diff" : "8", "Typ" : "O", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "392", "OrdPV" : "3.77", "OrdSV" : "8.38", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "392", "PENPV" : "3.77", "PENSV" : "8.38"},
		{"srno" : "2", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "7280", "Supname" : "DEEPAK ENTERPRISES", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50224", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "12", "OrdPV" : ".13", "OrdSV" : ".2", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "12", "PENPV" : ".13", "PENSV" : ".2"},
		{"srno" : "3", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "29168", "Supname" : "M.M.&CO.", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50236", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "5579-SURESH S", "pormode" : "R", "OrdQ" : "320", "OrdPV" : "2.4", "OrdSV" : "3.91", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "320", "PENPV" : "2.4", "PENSV" : "3.91"},
		{"srno" : "4", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "7280", "Supname" : "DEEPAK ENTERPRISES", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50045", "Pordisc" : "16", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/09/2015", "POREDDT" : "18/09/2015", "Diff" : "4", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "980", "OrdPV" : "10.25", "OrdSV" : "15.93", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "980", "PENPV" : "10.25", "PENSV" : "15.93"},
		{"srno" : "5", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "7280", "Supname" : "DEEPAK ENTERPRISES", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50064", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "15/09/2015", "POREDDT" : "19/09/2015", "Diff" : "3", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "300", "OrdPV" : "3.2", "OrdSV" : "4.97", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "300", "PENPV" : "3.2", "PENSV" : "4.97"},
		{"srno" : "6", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "258", "Secsrno" : "214", "Secname" : "MENS PARTY WEAR SHIRTS", "Supcode" : "7280", "Supname" : "DEEPAK ENTERPRISES", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50069", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "15/09/2015", "POREDDT" : "19/09/2015", "Diff" : "3", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1020", "OrdPV" : "10.69", "OrdSV" : "16.69", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1020", "PENPV" : "10.69", "PENSV" : "16.69"},
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