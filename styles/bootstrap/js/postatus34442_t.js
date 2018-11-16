var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "14175", "Supname" : "G.N.BROS SAREES PVT.LTD", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "28598", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/08/2015", "POREDDT" : "10/08/2015", "Diff" : "40", "Typ" : "T", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "A", "OrdQ" : "125", "OrdPV" : "1.41", "OrdSV" : "2.12", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "125", "PENPV" : "1.41", "PENSV" : "2.12"},
		{"srno" : "2", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "26275", "Supname" : "GHANSHYAM SILKS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "32814", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/09/2015", "POREDDT" : "06/09/2015", "Diff" : "13", "Typ" : "T", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "320", "OrdPV" : "1.94", "OrdSV" : "3.07", "RecQ" : "200", "RecPV" : "1.19", "RecSV" : "1.89", "PENQ" : "120", "PENPV" : ".75", "PENSV" : "1.18"},
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