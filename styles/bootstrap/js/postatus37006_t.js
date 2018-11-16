var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "26480", "Supname" : "SHREE GARMENTS", "Ctyname" : "TIRUPUR", "Poryear" : "2015-16", "Pornumb" : "31772", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "31/07/2015", "POREDDT" : "04/08/2015", "Diff" : "45", "Typ" : "T", "EmpDet" : "2481-VISHNU NIKAICH D R", "pormode" : "R", "OrdQ" : "270", "OrdPV" : ".36", "OrdSV" : ".53", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "270", "PENPV" : ".36", "PENSV" : ".53"},
		{"srno" : "2", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "31315", "Supname" : "DEV APPARELS", "Ctyname" : "LUDHIANA", "Poryear" : "2015-16", "Pornumb" : "37205", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/08/2015", "POREDDT" : "15/08/2015", "Diff" : "34", "Typ" : "T", "EmpDet" : "-", "pormode" : "S", "OrdQ" : "10915", "OrdPV" : "8.08", "OrdSV" : "13.64", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "10915", "PENPV" : "8.08", "PENSV" : "13.64"},
		{"srno" : "3", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "23298", "Supname" : "MAHAVIR FASHION", "Ctyname" : "ULHASNAGAR", "Poryear" : "2015-16", "Pornumb" : "38752", "Pordisc" : "2", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "15/08/2015", "POREDDT" : "19/08/2015", "Diff" : "30", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "910", "OrdPV" : "1.12", "OrdSV" : "1.84", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "910", "PENPV" : "1.12", "PENSV" : "1.84"},
		{"srno" : "4", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "23298", "Supname" : "MAHAVIR FASHION", "Ctyname" : "ULHASNAGAR", "Poryear" : "2015-16", "Pornumb" : "38753", "Pordisc" : "2", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "15/08/2015", "POREDDT" : "19/08/2015", "Diff" : "30", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "65", "OrdPV" : ".08", "OrdSV" : ".13", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "65", "PENPV" : ".08", "PENSV" : ".13"},
		{"srno" : "5", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "10471", "Supname" : "VIJAYALAKSHMI TRADERS", "Ctyname" : "CHENNAI", "Poryear" : "2015-16", "Pornumb" : "41318", "Pordisc" : "3", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/08/2015", "POREDDT" : "25/08/2015", "Diff" : "24", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "900", "OrdPV" : "1.4", "OrdSV" : "2.21", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "900", "PENPV" : "1.4", "PENSV" : "2.21"},
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