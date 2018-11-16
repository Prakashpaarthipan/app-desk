var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "20878", "Supname" : "S.K.SYNTHETICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "31735", "Pordisc" : "10", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "05/08/2015", "POREDDT" : "09/08/2015", "Diff" : "45", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "120", "OrdPV" : ".09", "OrdSV" : ".11", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "120", "PENPV" : ".09", "PENSV" : ".11"},
		{"srno" : "2", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "20878", "Supname" : "S.K.SYNTHETICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "31738", "Pordisc" : "10", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "05/08/2015", "POREDDT" : "09/08/2015", "Diff" : "45", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "120", "OrdPV" : ".09", "OrdSV" : ".11", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "120", "PENPV" : ".09", "PENSV" : ".11"},
		{"srno" : "3", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "16364", "Supname" : "S.R.FABRICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44370", "Pordisc" : "2", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/09/2015", "POREDDT" : "05/09/2015", "Diff" : "18", "Typ" : "T", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "120", "OrdPV" : ".13", "OrdSV" : ".21", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "120", "PENPV" : ".13", "PENSV" : ".21"},
		{"srno" : "4", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "16364", "Supname" : "S.R.FABRICS", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44371", "Pordisc" : "2", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/09/2015", "POREDDT" : "05/09/2015", "Diff" : "18", "Typ" : "T", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "120", "OrdPV" : ".13", "OrdSV" : ".23", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "120", "PENPV" : ".13", "PENSV" : ".23"},
		{"srno" : "5", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "22273", "Supname" : "NANDLAL VIJAYKUMAR", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "44376", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/09/2015", "POREDDT" : "05/09/2015", "Diff" : "18", "Typ" : "T", "EmpDet" : "1259-PRABHAKARAN.G ", "pormode" : "R", "OrdQ" : "120", "OrdPV" : ".36", "OrdSV" : ".59", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "120", "PENPV" : ".36", "PENSV" : ".59"},
		{"srno" : "6", "G_Srno" : "26", "Grpname" : "CUTPIECE", "Seccode" : "4", "Secsrno" : "70", "Secname" : "CUTPIECE (SH)", "Supcode" : "30713", "Supname" : "KUMAR SYNTHETICS PVT.LTD", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "49546", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "08/09/2015", "POREDDT" : "12/09/2015", "Diff" : "11", "Typ" : "T", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "400", "OrdPV" : ".79", "OrdSV" : "1.3", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "400", "PENPV" : ".79", "PENSV" : "1.3"},
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