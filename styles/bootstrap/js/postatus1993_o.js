var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "22565", "Supname" : "BABA ENTERPRISES", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "51236", "Pordisc" : "19", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "12/09/2015", "POREDDT" : "16/09/2015", "Diff" : "5", "Typ" : "O", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "45", "OrdPV" : ".24", "OrdSV" : ".34", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "45", "PENPV" : ".24", "PENSV" : ".34"},
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