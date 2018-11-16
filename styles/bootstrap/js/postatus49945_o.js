var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "7883", "Supname" : "AMSAVENI", "Ctyname" : "COIMBATORE", "Poryear" : "2015-16", "Pornumb" : "50467", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "111", "OrdPV" : "2.46", "OrdSV" : "4.02", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "111", "PENPV" : "2.46", "PENSV" : "4.02"},
		{"srno" : "2", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "8654", "Supname" : "RAMALINGA CREATIONS", "Ctyname" : "KARAIKUDI", "Poryear" : "2015-16", "Pornumb" : "48834", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "273", "OrdPV" : "2.71", "OrdSV" : "4.22", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "273", "PENPV" : "2.71", "PENSV" : "4.22"},
		{"srno" : "3", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "8957", "Supname" : "PATHI TEX", "Ctyname" : "RASIPURAM", "Poryear" : "2015-16", "Pornumb" : "50442", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "2", "OrdPV" : ".05", "OrdSV" : ".08", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2", "PENPV" : ".05", "PENSV" : ".08"},
		{"srno" : "4", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "9319", "Supname" : "SHANMUGA HANDLOOM TEXTILES", "Ctyname" : "RASIPURAM", "Poryear" : "2015-16", "Pornumb" : "50461", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "34", "OrdPV" : ".58", "OrdSV" : ".95", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "34", "PENPV" : ".58", "PENSV" : ".95"},
		{"srno" : "5", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "10351", "Supname" : "SURIYA TEX", "Ctyname" : "COIMBATORE", "Poryear" : "2015-16", "Pornumb" : "50410", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "38", "OrdPV" : ".84", "OrdSV" : "1.36", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "38", "PENPV" : ".84", "PENSV" : "1.36"},
		{"srno" : "6", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "11639", "Supname" : "N.V.SUBRAMANIAM HANDLOOM", "Ctyname" : "ERODE", "Poryear" : "2015-16", "Pornumb" : "50622", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "439", "OrdPV" : "3.67", "OrdSV" : "5.58", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "439", "PENPV" : "3.67", "PENSV" : "5.58"},
		{"srno" : "7", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "21246", "Supname" : "RISHAB CREATIONS", "Ctyname" : "COIMBATORE", "Poryear" : "2015-16", "Pornumb" : "50655", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "195", "OrdPV" : "4.49", "OrdSV" : "7.25", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "195", "PENPV" : "4.49", "PENSV" : "7.25"},
		{"srno" : "8", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "24708", "Supname" : "SREE SAKTHI SAREES", "Ctyname" : "COIMBATORE", "Poryear" : "2015-16", "Pornumb" : "50453", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "227", "OrdPV" : "4.61", "OrdSV" : "7.35", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "227", "PENPV" : "4.61", "PENSV" : "7.35"},
		{"srno" : "9", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "29036", "Supname" : "D.SAGUNTHALAA", "Ctyname" : "DODDAMPALAYAM", "Poryear" : "2015-16", "Pornumb" : "50459", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "207", "OrdPV" : "3.74", "OrdSV" : "5.91", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "207", "PENPV" : "3.74", "PENSV" : "5.91"},
		{"srno" : "10", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "29462", "Supname" : "KUMARAVADIVEL SAREES", "Ctyname" : "AVINASHI", "Poryear" : "2015-16", "Pornumb" : "50596", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "279", "OrdPV" : "7.04", "OrdSV" : "11.4", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "279", "PENPV" : "7.04", "PENSV" : "11.4"},
		{"srno" : "11", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "30145", "Supname" : "J.ANBALAGAN", "Ctyname" : "DODDAMPALAYAM", "Poryear" : "2015-16", "Pornumb" : "50665", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "128", "OrdPV" : "2.47", "OrdSV" : "3.85", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "128", "PENPV" : "2.47", "PENSV" : "3.85"},
		{"srno" : "12", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "32001", "Supname" : "KAVITHA REVATHI HANDLOOMS", "Ctyname" : "SATHYAMANGALAM", "Poryear" : "2015-16", "Pornumb" : "50462", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "30", "OrdPV" : ".65", "OrdSV" : "1.05", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "30", "PENPV" : ".65", "PENSV" : "1.05"},
		{"srno" : "13", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "32002", "Supname" : "NARANDRA KUMAR HANDLOOM", "Ctyname" : "SATHYAMANGALAM", "Poryear" : "2015-16", "Pornumb" : "50457", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "470", "OrdPV" : "10.23", "OrdSV" : "16.68", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "470", "PENPV" : "10.23", "PENSV" : "16.68"},
		{"srno" : "14", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "32086", "Supname" : "V.NAGARAJ & SONS", "Ctyname" : "DODDAMPALAYAM", "Poryear" : "2015-16", "Pornumb" : "50430", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "11/09/2015", "POREDDT" : "15/09/2015", "Diff" : "7", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "583", "OrdPV" : "10.79", "OrdSV" : "16.9", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "583", "PENPV" : "10.79", "PENSV" : "16.9"},
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