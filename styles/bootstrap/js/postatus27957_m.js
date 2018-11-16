var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "2", "Grpname" : "GIRLS READYMADE", "Seccode" : "164", "Secsrno" : "113", "Secname" : "GIRLS RM FROCK", "Supcode" : "22671", "Supname" : "VINI ART", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "45017", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "1368-SENTHILNATHAN T", "pormode" : "R", "OrdQ" : "1480", "OrdPV" : "5.21", "OrdSV" : "8.68", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1480", "PENPV" : "5.21", "PENSV" : "8.68"},
		{"srno" : "2", "G_Srno" : "2", "Grpname" : "GIRLS READYMADE", "Seccode" : "164", "Secsrno" : "113", "Secname" : "GIRLS RM FROCK", "Supcode" : "24577", "Supname" : "R.SHILPA DRESSES PVT LTD", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "49615", "Pordisc" : "12", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "1368-SENTHILNATHAN T", "pormode" : "R", "OrdQ" : "744", "OrdPV" : "1.48", "OrdSV" : "2.19", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "744", "PENPV" : "1.48", "PENSV" : "2.19"},
		{"srno" : "3", "G_Srno" : "2", "Grpname" : "GIRLS READYMADE", "Seccode" : "164", "Secsrno" : "113", "Secname" : "GIRLS RM FROCK", "Supcode" : "26968", "Supname" : "TOP LINE", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "48048", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "3000", "OrdPV" : "8.22", "OrdSV" : "14.55", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3000", "PENPV" : "8.22", "PENSV" : "14.55"},
		{"srno" : "4", "G_Srno" : "2", "Grpname" : "GIRLS READYMADE", "Seccode" : "164", "Secsrno" : "113", "Secname" : "GIRLS RM FROCK", "Supcode" : "28717", "Supname" : "AVANI IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "45472", "Pordisc" : "16", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "1368-SENTHILNATHAN T", "pormode" : "R", "OrdQ" : "600", "OrdPV" : "2.8", "OrdSV" : "4.05", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "600", "PENPV" : "2.8", "PENSV" : "4.05"},
		{"srno" : "5", "G_Srno" : "2", "Grpname" : "GIRLS READYMADE", "Seccode" : "164", "Secsrno" : "113", "Secname" : "GIRLS RM FROCK", "Supcode" : "29244", "Supname" : "CHOICE POINT", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "45473", "Pordisc" : "25", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "1368-SENTHILNATHAN T", "pormode" : "R", "OrdQ" : "675", "OrdPV" : "2.63", "OrdSV" : "3.34", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "675", "PENPV" : "2.63", "PENSV" : "3.34"},
		{"srno" : "6", "G_Srno" : "2", "Grpname" : "GIRLS READYMADE", "Seccode" : "164", "Secsrno" : "113", "Secname" : "GIRLS RM FROCK", "Supcode" : "27155", "Supname" : "M.DANISH DRESSES", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "48118", "Pordisc" : "20", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/10/2015", "POREDDT" : "10/10/2015", "Diff" : "-18", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1080", "OrdPV" : "1.03", "OrdSV" : "1.35", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1080", "PENPV" : "1.03", "PENSV" : "1.35"},
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