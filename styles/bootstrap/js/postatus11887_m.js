var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "13", "Grpname" : "SILKS", "Seccode" : "37", "Secsrno" : "24", "Secname" : "SILK(DVM)", "Supcode" : "7190", "Supname" : "SHWETHA FABRICS", "Ctyname" : "DHARMAVARAM", "Poryear" : "2015-16", "Pornumb" : "48201", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "1710-NAGARAJ R ", "pormode" : "R", "OrdQ" : "3", "OrdPV" : ".28", "OrdSV" : ".45", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3", "PENPV" : ".28", "PENSV" : ".45"},
		{"srno" : "2", "G_Srno" : "13", "Grpname" : "SILKS", "Seccode" : "37", "Secsrno" : "24", "Secname" : "SILK(DVM)", "Supcode" : "22705", "Supname" : "D.K.RAO P", "Ctyname" : "DHARMAVARAM", "Poryear" : "2015-16", "Pornumb" : "48200", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "3", "OrdPV" : ".34", "OrdSV" : ".57", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3", "PENPV" : ".34", "PENSV" : ".57"},
		{"srno" : "3", "G_Srno" : "13", "Grpname" : "SILKS", "Seccode" : "37", "Secsrno" : "24", "Secname" : "SILK(DVM)", "Supcode" : "30577", "Supname" : "YSL SILK CREATIONS", "Ctyname" : "DHARMAVARAM", "Poryear" : "2015-16", "Pornumb" : "48202", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "3", "OrdPV" : ".23", "OrdSV" : ".36", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3", "PENPV" : ".23", "PENSV" : ".36"},
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