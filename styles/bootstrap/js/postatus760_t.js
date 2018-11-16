var myApp1 = angular.module("myApp1", []);
	myApp1.controller("MyController1", function MyController1($scope){ 
		$scope.today = [{"srno" : "1", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "16874", "Supname" : "MINTO CLOTHING CO", "Ctyname" : "BANGALORE", "Poryear" : "2015-16", "Pornumb" : "22479", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "18/07/2015", "POREDDT" : "22/07/2015", "Diff" : "59", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "358", "OrdPV" : "1.69", "OrdSV" : "3.56", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "358", "PENPV" : "1.69", "PENSV" : "3.56"},
		{"srno" : "2", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "16874", "Supname" : "MINTO CLOTHING CO", "Ctyname" : "BANGALORE", "Poryear" : "2015-16", "Pornumb" : "18478", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/07/2015", "POREDDT" : "30/07/2015", "Diff" : "51", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "P", "OrdQ" : "325", "OrdPV" : "1.34", "OrdSV" : "2.91", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "325", "PENPV" : "1.34", "PENSV" : "2.91"},
		{"srno" : "3", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "25383", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/08/2015", "POREDDT" : "18/08/2015", "Diff" : "32", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "R", "OrdQ" : "1", "OrdPV" : "0", "OrdSV" : ".01", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1", "PENPV" : "0", "PENSV" : ".01"},
		{"srno" : "4", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "25502", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "26/08/2015", "POREDDT" : "31/08/2015", "Diff" : "19", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "P", "OrdQ" : "864", "OrdPV" : "2.82", "OrdSV" : "6.87", "RecQ" : "435", "RecPV" : "1.42", "RecSV" : "3.46", "PENQ" : "429", "PENPV" : "1.4", "PENSV" : "3.41"},
		{"srno" : "5", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "16874", "Supname" : "MINTO CLOTHING CO", "Ctyname" : "BANGALORE", "Poryear" : "2015-16", "Pornumb" : "33644", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "29/08/2015", "POREDDT" : "02/09/2015", "Diff" : "17", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "151", "OrdPV" : ".6", "OrdSV" : "1.5", "RecQ" : "143", "RecPV" : ".57", "RecSV" : "1.42", "PENQ" : "8", "PENPV" : ".03", "PENSV" : ".08"},
		{"srno" : "6", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "16874", "Supname" : "MINTO CLOTHING CO", "Ctyname" : "BANGALORE", "Poryear" : "2015-16", "Pornumb" : "33650", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "29/08/2015", "POREDDT" : "02/09/2015", "Diff" : "17", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "872", "OrdPV" : "3.49", "OrdSV" : "8.68", "RecQ" : "827", "RecPV" : "3.31", "RecSV" : "8.23", "PENQ" : "45", "PENPV" : ".19", "PENSV" : ".45"},
		{"srno" : "7", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "16874", "Supname" : "MINTO CLOTHING CO", "Ctyname" : "BANGALORE", "Poryear" : "2015-16", "Pornumb" : "33657", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "29/08/2015", "POREDDT" : "02/09/2015", "Diff" : "17", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "871", "OrdPV" : "3.53", "OrdSV" : "8.67", "RecQ" : "826", "RecPV" : "3.34", "RecSV" : "8.22", "PENQ" : "45", "PENPV" : ".19", "PENSV" : ".45"},
		{"srno" : "8", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "34967", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "31/08/2015", "POREDDT" : "04/09/2015", "Diff" : "15", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "720", "OrdPV" : "2.38", "OrdSV" : "5.72", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "720", "PENPV" : "2.38", "PENSV" : "5.72"},
		{"srno" : "9", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "34969", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "31/08/2015", "POREDDT" : "04/09/2015", "Diff" : "15", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "134", "OrdPV" : ".46", "OrdSV" : "1.07", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "134", "PENPV" : ".46", "PENSV" : "1.07"},
		{"srno" : "10", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "36170", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "03/09/2015", "POREDDT" : "07/09/2015", "Diff" : "12", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "217", "OrdPV" : ".7", "OrdSV" : "1.73", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "217", "PENPV" : ".7", "PENSV" : "1.73"},
		{"srno" : "11", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "36173", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "03/09/2015", "POREDDT" : "07/09/2015", "Diff" : "12", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "368", "OrdPV" : "1.19", "OrdSV" : "2.93", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "368", "PENPV" : "1.19", "PENSV" : "2.93"},
		{"srno" : "12", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "36192", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "03/09/2015", "POREDDT" : "07/09/2015", "Diff" : "12", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "298", "OrdPV" : ".97", "OrdSV" : "2.37", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "298", "PENPV" : ".97", "PENSV" : "2.37"},
		{"srno" : "13", "G_Srno" : "7", "Grpname" : "MENS CASUAL SHIRTS", "Seccode" : "253", "Secsrno" : "205", "Secname" : "MENS VITOMINE CL SHIRTS", "Supcode" : "28037", "Supname" : "AVINASH IMPEX", "Ctyname" : "MUMBAI", "Poryear" : "2015-16", "Pornumb" : "36438", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "03/09/2015", "POREDDT" : "07/09/2015", "Diff" : "12", "Typ" : "T", "EmpDet" : "5005-GAJENDRAN C ", "pormode" : "F", "OrdQ" : "632", "OrdPV" : "2.05", "OrdSV" : "5.02", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "632", "PENPV" : "2.05", "PENSV" : "5.02"},
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