var myApp3 = angular.module("myApp3", []);
	myApp3.controller("MyController3", function MyController3($scope){ 
		$scope.week  = [{"srno" : "1", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "8160", "Supname" : "CROWN FAB INTERNATIONAL", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44018", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-2", "Typ" : "W", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "2880", "OrdPV" : "3.08", "OrdSV" : "5", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2880", "PENPV" : "3.08", "PENSV" : "5"},
		{"srno" : "2", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "15921", "Supname" : "SILK INDIA", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44011", "Pordisc" : "0", "Porspdc" : "9", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-2", "Typ" : "W", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "2880", "OrdPV" : "2.78", "OrdSV" : "4.46", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2880", "PENPV" : "2.78", "PENSV" : "4.46"},
		{"srno" : "3", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "17872", "Supname" : "SIYARAM SILK MILLS LIMITED", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "43994", "Pordisc" : "0", "Porspdc" : "10", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-2", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "2160", "OrdPV" : "7.47", "OrdSV" : "12.42", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2160", "PENPV" : "7.47", "PENSV" : "12.42"},
		{"srno" : "4", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "20878", "Supname" : "S.K.SYNTHETICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44007", "Pordisc" : "10", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-2", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "2400", "OrdPV" : "2.39", "OrdSV" : "3.53", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2400", "PENPV" : "2.39", "PENSV" : "3.53"},
		{"srno" : "5", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "29143", "Supname" : "SHITAL FABRICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "43987", "Pordisc" : "3", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-2", "Typ" : "W", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "1200", "OrdPV" : ".97", "OrdSV" : "1.5", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1200", "PENPV" : ".97", "PENSV" : "1.5"},
		{"srno" : "6", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "29143", "Supname" : "SHITAL FABRICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "43909", "Pordisc" : "3", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "25/09/2015", "POREDDT" : "29/09/2015", "Diff" : "-6", "Typ" : "W", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "600", "OrdPV" : ".64", "OrdSV" : "1.01", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "600", "PENPV" : ".64", "PENSV" : "1.01"},
		{"srno" : "7", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "29452", "Supname" : "SOMANI NX SYNFAB PVT.LTD", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "43928", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "25/09/2015", "POREDDT" : "29/09/2015", "Diff" : "-6", "Typ" : "W", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "1824", "OrdPV" : "2.12", "OrdSV" : "3.6", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1824", "PENPV" : "2.12", "PENSV" : "3.6"},
		{"srno" : "8", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "8160", "Supname" : "CROWN FAB INTERNATIONAL", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44137", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "3480", "OrdPV" : "3.51", "OrdSV" : "5.89", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3480", "PENPV" : "3.51", "PENSV" : "5.89"},
		{"srno" : "9", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "24235", "Supname" : "DISCOVERY INDUSTRIES", "Ctyname" : "BHIWANDI", "Poryear" : "2015-16", "Pornumb" : "44154", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "2880", "OrdPV" : "5.04", "OrdSV" : "8.5", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2880", "PENPV" : "5.04", "PENSV" : "8.5"},
		{"srno" : "10", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "26021", "Supname" : "SEVEN DAYS FASHIONS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44162", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1800", "OrdPV" : "2.88", "OrdSV" : "4.95", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1800", "PENPV" : "2.88", "PENSV" : "4.95"},
		{"srno" : "11", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "27727", "Supname" : "SUNIL ENTERPRISES", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44151", "Pordisc" : "15", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "2760", "OrdPV" : "2.67", "OrdSV" : "3.5", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2760", "PENPV" : "2.67", "PENSV" : "3.5"},
		{"srno" : "12", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "29301", "Supname" : "SANSKRITI CREATION", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44156", "Pordisc" : "3", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "2040", "OrdPV" : "3.79", "OrdSV" : "6.02", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2040", "PENPV" : "3.79", "PENSV" : "6.02"},
		{"srno" : "13", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "30713", "Supname" : "KUMAR SYNTHETICS PVT.LTD", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "43854", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/09/2015", "POREDDT" : "30/09/2015", "Diff" : "-7", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "200", "OrdPV" : ".31", "OrdSV" : ".49", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "200", "PENPV" : ".31", "PENSV" : ".49"},
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