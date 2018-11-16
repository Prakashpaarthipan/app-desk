var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "27639", "Supname" : "K.L.GARMENTS", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "47212", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "300", "OrdPV" : ".6", "OrdSV" : ".89", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "300", "PENPV" : ".6", "PENSV" : ".89"},
		{"srno" : "2", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "28428", "Supname" : "NOBLE CREATION", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "46939", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "1200", "OrdPV" : "2.64", "OrdSV" : "4.74", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1200", "PENPV" : "2.64", "PENSV" : "4.74"},
		{"srno" : "3", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "30017", "Supname" : "SHRI HARI APPARELS", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "47202", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "475", "OrdPV" : "1.71", "OrdSV" : "2.83", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "475", "PENPV" : "1.71", "PENSV" : "2.83"},
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