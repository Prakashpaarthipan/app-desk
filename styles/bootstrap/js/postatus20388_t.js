var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "16949", "Supname" : "MANISH CREATIONS", "Ctyname" : "CHENNAI", "Poryear" : "2015-16", "Pornumb" : "29142", "Pordisc" : "10", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/08/2015", "POREDDT" : "05/08/2015", "Diff" : "44", "Typ" : "T", "EmpDet" : "1070-KANDASAMY T ", "pormode" : "A", "OrdQ" : "12", "OrdPV" : ".04", "OrdSV" : ".05", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "12", "PENPV" : ".04", "PENSV" : ".05"},
		{"srno" : "2", "G_Srno" : "12", "Grpname" : "L.I.GARMENTS", "Seccode" : "78", "Secsrno" : "184", "Secname" : "L.I.GARMENTS (LINGERIES)", "Supcode" : "16949", "Supname" : "MANISH CREATIONS", "Ctyname" : "CHENNAI", "Poryear" : "2015-16", "Pornumb" : "32551", "Pordisc" : "0", "Porspdc" : "10", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "04/08/2015", "POREDDT" : "08/08/2015", "Diff" : "41", "Typ" : "T", "EmpDet" : "1070-KANDASAMY T ", "pormode" : "R", "OrdQ" : "18", "OrdPV" : ".05", "OrdSV" : ".06", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "18", "PENPV" : ".05", "PENSV" : ".06"},
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