var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "26554", "Supname" : "NATIONAL CLOTHING SUPPLY(INDIA) PVT.LTD", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "24433", "Pordisc" : "20", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/08/2015", "POREDDT" : "10/08/2015", "Diff" : "42", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "640", "OrdPV" : "4.2", "OrdSV" : "5.98", "RecQ" : "464", "RecPV" : "3.05", "RecSV" : "4.34", "PENQ" : "176", "PENPV" : "1.15", "PENSV" : "1.64"},
		{"srno" : "2", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "29087", "Supname" : "REGENT FASHION", "Ctyname" : "KOVILPATTI", "Poryear" : "2015-16", "Pornumb" : "36790", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "12/08/2015", "POREDDT" : "16/08/2015", "Diff" : "36", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "1848", "OrdPV" : "5.16", "OrdSV" : "9.15", "RecQ" : "1834", "RecPV" : "5.12", "RecSV" : "9.08", "PENQ" : "14", "PENPV" : ".04", "PENSV" : ".07"},
		{"srno" : "3", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "24537", "Supname" : "HAPPY DRESSES", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "26434", "Pordisc" : "10", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/08/2015", "POREDDT" : "18/08/2015", "Diff" : "34", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "336", "OrdPV" : "1.64", "OrdSV" : "2.72", "RecQ" : "134", "RecPV" : ".66", "RecSV" : "1.09", "PENQ" : "202", "PENPV" : ".98", "PENSV" : "1.63"},
		{"srno" : "4", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "26554", "Supname" : "NATIONAL CLOTHING SUPPLY(INDIA) PVT.LTD", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "26336", "Pordisc" : "20", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "15/08/2015", "POREDDT" : "19/08/2015", "Diff" : "33", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1360", "OrdPV" : "8.08", "OrdSV" : "11.69", "RecQ" : "824", "RecPV" : "4.96", "RecSV" : "7.14", "PENQ" : "536", "PENPV" : "3.12", "PENSV" : "4.55"},
		{"srno" : "5", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "27645", "Supname" : "ISHIKA FASHION", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "31043", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "22/08/2015", "POREDDT" : "26/08/2015", "Diff" : "26", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "A", "OrdQ" : "97", "OrdPV" : ".22", "OrdSV" : ".38", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "97", "PENPV" : ".22", "PENSV" : ".38"},
		{"srno" : "6", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "24455", "Supname" : "CHAITANYA CREATION", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "30341", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "25/08/2015", "POREDDT" : "29/08/2015", "Diff" : "23", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "210", "OrdPV" : ".49", "OrdSV" : ".83", "RecQ" : "150", "RecPV" : ".35", "RecSV" : ".59", "PENQ" : "60", "PENPV" : ".14", "PENSV" : ".24"},
		{"srno" : "7", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "12851", "Supname" : "BINDASS APPARELS PVT.LTD.", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "40873", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/08/2015", "POREDDT" : "30/08/2015", "Diff" : "22", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "50", "OrdPV" : ".2", "OrdSV" : ".35", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "50", "PENPV" : ".2", "PENSV" : ".35"},
		{"srno" : "8", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "30017", "Supname" : "SHRI HARI APPARELS", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "40868", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/08/2015", "POREDDT" : "30/08/2015", "Diff" : "22", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "298", "OrdPV" : "1.03", "OrdSV" : "1.77", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "298", "PENPV" : "1.03", "PENSV" : "1.77"},
		{"srno" : "9", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "27645", "Supname" : "ISHIKA FASHION", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "33851", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "30/08/2015", "POREDDT" : "03/09/2015", "Diff" : "18", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "A", "OrdQ" : "60", "OrdPV" : ".14", "OrdSV" : ".24", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "60", "PENPV" : ".14", "PENSV" : ".24"},
		{"srno" : "10", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "28428", "Supname" : "NOBLE CREATION", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "32749", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/09/2015", "POREDDT" : "06/09/2015", "Diff" : "15", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "375", "OrdPV" : ".83", "OrdSV" : "1.48", "RecQ" : "300", "RecPV" : ".66", "RecSV" : "1.19", "PENQ" : "75", "PENPV" : ".17", "PENSV" : ".3"},
		{"srno" : "11", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "24887", "Supname" : "CHUNILAL CHAMPALAL", "Ctyname" : "KOLKATTA", "Poryear" : "2015-16", "Pornumb" : "42710", "Pordisc" : "17", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "04/09/2015", "POREDDT" : "08/09/2015", "Diff" : "13", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "A", "OrdQ" : "69", "OrdPV" : ".23", "OrdSV" : ".34", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "69", "PENPV" : ".23", "PENSV" : ".34"},
		{"srno" : "12", "G_Srno" : "1", "Grpname" : "BOYS READYMADE", "Seccode" : "150", "Secsrno" : "88", "Secname" : "BOYS BABA SUIT", "Supcode" : "30017", "Supname" : "SHRI HARI APPARELS", "Ctyname" : "INDORE", "Poryear" : "2015-16", "Pornumb" : "35025", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "05/09/2015", "POREDDT" : "09/09/2015", "Diff" : "12", "Typ" : "T", "EmpDet" : "2464-THIYAGARAJAN N ", "pormode" : "R", "OrdQ" : "120", "OrdPV" : ".43", "OrdSV" : ".71", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "120", "PENPV" : ".43", "PENSV" : ".71"},
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