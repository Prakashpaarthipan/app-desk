var myApp3 = angular.module("myApp3", []);
	myApp3.controller("MyController3", function MyController3($scope){ 
		$scope.week  = [{"srno" : "1", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "7859", "Supname" : "LAKSHMI TEXTILES", "Ctyname" : "NEGAMAM", "Poryear" : "2015-16", "Pornumb" : "48043", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "16", "OrdPV" : ".21", "OrdSV" : ".34", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "16", "PENPV" : ".21", "PENSV" : ".34"},
		{"srno" : "2", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "11896", "Supname" : "KHARTHIKA SAREES", "Ctyname" : "COIMBATORE", "Poryear" : "2015-16", "Pornumb" : "46096", "Pordisc" : "3", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "177", "OrdPV" : "3.65", "OrdSV" : "5.57", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "177", "PENPV" : "3.65", "PENSV" : "5.57"},
		{"srno" : "3", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "21304", "Supname" : "SRI AYYAPPA TEX", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "47082", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "42", "OrdPV" : ".5", "OrdSV" : ".77", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "42", "PENPV" : ".5", "PENSV" : ".77"},
		{"srno" : "4", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "24281", "Supname" : "RASI SILKS & COTTONS", "Ctyname" : "RASIPURAM", "Poryear" : "2015-16", "Pornumb" : "46367", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "10", "OrdPV" : ".26", "OrdSV" : ".42", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "10", "PENPV" : ".26", "PENSV" : ".42"},
		{"srno" : "5", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "24281", "Supname" : "RASI SILKS & COTTONS", "Ctyname" : "RASIPURAM", "Poryear" : "2015-16", "Pornumb" : "46368", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-3", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "5", "OrdPV" : ".13", "OrdSV" : ".22", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "5", "PENPV" : ".13", "PENSV" : ".22"},
		{"srno" : "6", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "28336", "Supname" : "GANGA TEX", "Ctyname" : "PUNJAI PULIAMPATTI", "Poryear" : "2015-16", "Pornumb" : "48085", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-8", "Typ" : "W", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "3300", "OrdPV" : "27.66", "OrdSV" : "41.24", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3300", "PENPV" : "27.66", "PENSV" : "41.24"},
		{"srno" : "7", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "29462", "Supname" : "KUMARAVADIVEL SAREES", "Ctyname" : "AVINASHI", "Poryear" : "2015-16", "Pornumb" : "48445", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-8", "Typ" : "W", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "1800", "OrdPV" : "15.15", "OrdSV" : "24.21", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1800", "PENPV" : "15.15", "PENSV" : "24.21"},
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