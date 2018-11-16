var myApp3 = angular.module("myApp3", []);
	myApp3.controller("MyController3", function MyController3($scope){ 
		$scope.week  = [{"srno" : "1", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "16874", "Supname" : "MINTO CLOTHING CO", "Ctyname" : "BANGALORE", "Poryear" : "2015-16", "Pornumb" : "43626", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-6", "Typ" : "W", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "457", "OrdPV" : "1.99", "OrdSV" : "4.41", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "457", "PENPV" : "1.99", "PENSV" : "4.41"},
		{"srno" : "2", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "20032", "Supname" : "CASH ZONE", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "47109", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-6", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "240", "OrdPV" : ".86", "OrdSV" : "1.67", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "240", "PENPV" : ".86", "PENSV" : "1.67"},
		{"srno" : "3", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "26551", "Supname" : "BLUE CLOTHING", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "47509", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-6", "Typ" : "W", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "275", "OrdPV" : "1.14", "OrdSV" : "2.46", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "275", "PENPV" : "1.14", "PENSV" : "2.46"},
		{"srno" : "4", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "43621", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-6", "Typ" : "W", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "228", "OrdPV" : ".74", "OrdSV" : "1.81", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "228", "PENPV" : ".74", "PENSV" : "1.81"},
		{"srno" : "5", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "30288", "Supname" : "UPASANA APPARELS", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "46970", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-6", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "2700", "OrdPV" : "7.7", "OrdSV" : "16.07", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2700", "PENPV" : "7.7", "PENSV" : "16.07"},
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