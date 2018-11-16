var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "27445", "Supname" : "SHIPRA COMMERCIAL PVT LTD", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "38950", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-12", "Typ" : "M", "EmpDet" : "-", "pormode" : "A", "OrdQ" : "2115", "OrdPV" : "2.21", "OrdSV" : "3.53", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2115", "PENPV" : "2.21", "PENSV" : "3.53"},
		{"srno" : "2", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "27445", "Supname" : "SHIPRA COMMERCIAL PVT LTD", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "38951", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-12", "Typ" : "M", "EmpDet" : "-", "pormode" : "A", "OrdQ" : "165", "OrdPV" : ".17", "OrdSV" : ".27", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "165", "PENPV" : ".17", "PENSV" : ".27"},
		{"srno" : "3", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "29819", "Supname" : "NRS HOSIERY PRIVATE LIMITED", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "50933", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-12", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "16560", "OrdPV" : "15.14", "OrdSV" : "27.28", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "16560", "PENPV" : "15.14", "PENSV" : "27.28"},
		{"srno" : "4", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "28380", "Supname" : "A.K.CLOTHING", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44936", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-17", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "360", "OrdPV" : ".74", "OrdSV" : "1.12", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "360", "PENPV" : ".74", "PENSV" : "1.12"},
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