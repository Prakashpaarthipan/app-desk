var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "19", "Grpname" : "DRESS MATERIAL", "Seccode" : "20", "Secsrno" : "49", "Secname" : "COT.DRESS MATERIAL", "Supcode" : "24622", "Supname" : "SHREE PAWAN TEXTILES", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "46042", "Pordisc" : "7", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "12/09/2015", "POREDDT" : "16/09/2015", "Diff" : "6", "Typ" : "O", "EmpDet" : "1634-RAJESH P ", "pormode" : "R", "OrdQ" : "7000", "OrdPV" : "30.27", "OrdSV" : "47.85", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "7000", "PENPV" : "30.27", "PENSV" : "47.85"},
		{"srno" : "2", "G_Srno" : "19", "Grpname" : "DRESS MATERIAL", "Seccode" : "20", "Secsrno" : "49", "Secname" : "COT.DRESS MATERIAL", "Supcode" : "28941", "Supname" : "SIYARAM SILK MILLS LIMITED", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "50559", "Pordisc" : "0", "Porspdc" : "3", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "12/09/2015", "POREDDT" : "16/09/2015", "Diff" : "6", "Typ" : "O", "EmpDet" : "1634-RAJESH P ", "pormode" : "R", "OrdQ" : "2000", "OrdPV" : "6.98", "OrdSV" : "11.94", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2000", "PENPV" : "6.98", "PENSV" : "11.94"},
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