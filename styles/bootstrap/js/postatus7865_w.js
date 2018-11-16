var myApp3 = angular.module("myApp3", []);
	myApp3.controller("MyController3", function MyController3($scope){ 
		$scope.week  = [{"srno" : "1", "G_Srno" : "25", "Grpname" : "SHIRTING", "Seccode" : "233", "Secsrno" : "66", "Secname" : "SHIRTING 44"", "Supcode" : "29289", "Supname" : "ROYAL IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "49518", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-2", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "128", "OrdPV" : ".17", "OrdSV" : ".23", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "128", "PENPV" : ".17", "PENSV" : ".23"},
		{"srno" : "2", "G_Srno" : "25", "Grpname" : "SHIRTING", "Seccode" : "233", "Secsrno" : "66", "Secname" : "SHIRTING 44"", "Supcode" : "18028", "Supname" : "JITENDRA HARSHADKUMAR & CO", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "43984", "Pordisc" : "7", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1120", "OrdPV" : ".81", "OrdSV" : "1.4", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1120", "PENPV" : ".81", "PENSV" : "1.4"},
		{"srno" : "3", "G_Srno" : "25", "Grpname" : "SHIRTING", "Seccode" : "233", "Secsrno" : "66", "Secname" : "SHIRTING 44"", "Supcode" : "28520", "Supname" : "ARYA SILK FABRICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "45968", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "1020-BALAMURUGAN K ", "pormode" : "R", "OrdQ" : "250", "OrdPV" : ".25", "OrdSV" : ".41", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "250", "PENPV" : ".25", "PENSV" : ".41"},
		{"srno" : "4", "G_Srno" : "25", "Grpname" : "SHIRTING", "Seccode" : "233", "Secsrno" : "66", "Secname" : "SHIRTING 44"", "Supcode" : "29289", "Supname" : "ROYAL IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44053", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "610", "OrdPV" : ".84", "OrdSV" : "1.46", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "610", "PENPV" : ".84", "PENSV" : "1.46"},
		{"srno" : "5", "G_Srno" : "25", "Grpname" : "SHIRTING", "Seccode" : "233", "Secsrno" : "66", "Secname" : "SHIRTING 44"", "Supcode" : "9917", "Supname" : "SHREE KAILASH TEXTILES", "Ctyname" : "AHMEDABAD", "Poryear" : "2015-16", "Pornumb" : "51147", "Pordisc" : "0", "Porspdc" : "9", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-8", "Typ" : "W", "EmpDet" : "1020-BALAMURUGAN K ", "pormode" : "R", "OrdQ" : "384", "OrdPV" : ".44", "OrdSV" : ".77", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "384", "PENPV" : ".44", "PENSV" : ".77"},
		{"srno" : "6", "G_Srno" : "25", "Grpname" : "SHIRTING", "Seccode" : "233", "Secsrno" : "66", "Secname" : "SHIRTING 44"", "Supcode" : "29289", "Supname" : "ROYAL IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "45532", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-8", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "224", "OrdPV" : ".3", "OrdSV" : ".51", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "224", "PENPV" : ".3", "PENSV" : ".51"},
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