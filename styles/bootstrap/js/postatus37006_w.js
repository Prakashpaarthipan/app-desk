var myApp3 = angular.module("myApp3", []);
	myApp3.controller("MyController3", function MyController3($scope){ 
		$scope.week  = [{"srno" : "1", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "19166", "Supname" : "J.V.OVERSEAS EXPORTS", "Ctyname" : "TIRUPUR", "Poryear" : "2015-16", "Pornumb" : "46610", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-6", "Typ" : "W", "EmpDet" : "2481-VISHNU NIKAICH D R", "pormode" : "R", "OrdQ" : "2400", "OrdPV" : "3.24", "OrdSV" : "6.6", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2400", "PENPV" : "3.24", "PENSV" : "6.6"},
		{"srno" : "2", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "23306", "Supname" : "GEESON'S", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "47243", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "360", "OrdPV" : ".86", "OrdSV" : "1.34", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "360", "PENPV" : ".86", "PENSV" : "1.34"},
		{"srno" : "3", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "26251", "Supname" : "MONICA APPARELS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44511", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "90", "OrdPV" : ".26", "OrdSV" : ".41", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "90", "PENPV" : ".26", "PENSV" : ".41"},
		{"srno" : "4", "G_Srno" : "3", "Grpname" : "TEENAGE GIRLS", "Seccode" : "211", "Secsrno" : "156", "Secname" : "TEENAGE HOSEIRY", "Supcode" : "29647", "Supname" : "HETVI FASHION", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44523", "Pordisc" : "0", "Porspdc" : "3", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "75", "OrdPV" : ".11", "OrdSV" : ".19", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "75", "PENPV" : ".11", "PENSV" : ".19"},
		]
		$scope.getTotal = function(){
			var total1 = 0;
			var total2 = 0;
			var total3 = 0;
			var total4 = 0;
			var total5 = 0;
			var total6 = 0;
			for(var i = 0; i < $scope.week.length; i++){
				var ordqt = $scope.week[i]["OrdQ"];
				var ordvval = $scope.week[i]["OrdSV"];
				var resqt = $scope.week[i]["RecQ"];
				var resvval = $scope.week[i]["RecSV"];
				var penqt = $scope.week[i]["PENQ"];
				var penval = $scope.week[i]["PENSV"];
				
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
		angular.bootstrap(document.getElementById('App3'),['myApp3']);
		});