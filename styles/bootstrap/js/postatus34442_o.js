var myApp2 = angular.module("myApp2", []);
	myApp2.controller("MyController2", function MyController2($scope){ 
		$scope.tomorrow  = [{"srno" : "1", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "26277", "Supname" : "NILESH SILK SAREES", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "35523", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "09/09/2015", "POREDDT" : "13/09/2015", "Diff" : "6", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "135", "OrdPV" : "1.33", "OrdSV" : "2.38", "RecQ" : "100", "RecPV" : "1.03", "RecSV" : "1.85", "PENQ" : "35", "PENPV" : ".3", "PENSV" : ".53"},
		{"srno" : "2", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "14175", "Supname" : "G.N.BROS SAREES PVT.LTD", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "50833", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "13/09/2015", "POREDDT" : "17/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "600", "OrdPV" : "5.7", "OrdSV" : "9.17", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "600", "PENPV" : "5.7", "PENSV" : "9.17"},
		{"srno" : "3", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "21280", "Supname" : "SRI PANDYA SILKS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "50910", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "13/09/2015", "POREDDT" : "17/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "500", "OrdPV" : "3.13", "OrdSV" : "4.98", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "500", "PENPV" : "3.13", "PENSV" : "4.98"},
		{"srno" : "4", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "21280", "Supname" : "SRI PANDYA SILKS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51065", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "13/09/2015", "POREDDT" : "17/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "300", "OrdPV" : "2.69", "OrdSV" : "4.44", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "300", "PENPV" : "2.69", "PENSV" : "4.44"},
		{"srno" : "5", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "21280", "Supname" : "SRI PANDYA SILKS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51080", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "13/09/2015", "POREDDT" : "17/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "250", "OrdPV" : "2.15", "OrdSV" : "3.44", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "250", "PENPV" : "2.15", "PENSV" : "3.44"},
		{"srno" : "6", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "21280", "Supname" : "SRI PANDYA SILKS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51091", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "13/09/2015", "POREDDT" : "17/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "100", "OrdPV" : "1.1", "OrdSV" : "2", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "100", "PENPV" : "1.1", "PENSV" : "2"},
		{"srno" : "7", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "21280", "Supname" : "SRI PANDYA SILKS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51094", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "13/09/2015", "POREDDT" : "17/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "200", "OrdPV" : "1.9", "OrdSV" : "3.19", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "200", "PENPV" : "1.9", "PENSV" : "3.19"},
		{"srno" : "8", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "31922", "Supname" : "AFROZ & SONS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51051", "Pordisc" : "8", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "13/09/2015", "POREDDT" : "17/09/2015", "Diff" : "2", "Typ" : "O", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "550", "OrdPV" : "5.17", "OrdSV" : "8.02", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "550", "PENPV" : "5.17", "PENSV" : "8.02"},
		{"srno" : "9", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "21280", "Supname" : "SRI PANDYA SILKS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51148", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/09/2015", "POREDDT" : "18/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "500", "OrdPV" : "3.13", "OrdSV" : "5.23", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "500", "PENPV" : "3.13", "PENSV" : "5.23"},
		{"srno" : "10", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "26871", "Supname" : "DARSHIKA SILK SAREES", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51301", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "14/09/2015", "POREDDT" : "18/09/2015", "Diff" : "1", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "300", "OrdPV" : "1.25", "OrdSV" : "2.09", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "300", "PENPV" : "1.25", "PENSV" : "2.09"},
		{"srno" : "11", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "14175", "Supname" : "G.N.BROS SAREES PVT.LTD", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51751", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "16/09/2015", "POREDDT" : "20/09/2015", "Diff" : "-1", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "100", "OrdPV" : ".86", "OrdSV" : "1.4", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "100", "PENPV" : ".86", "PENSV" : "1.4"},
		{"srno" : "12", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "21280", "Supname" : "SRI PANDYA SILKS", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51758", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "16/09/2015", "POREDDT" : "20/09/2015", "Diff" : "-1", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "50", "OrdPV" : ".5", "OrdSV" : ".85", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "50", "PENPV" : ".5", "PENSV" : ".85"},
		{"srno" : "13", "G_Srno" : "16", "Grpname" : "FANCY SAREES", "Seccode" : "11", "Secsrno" : "10", "Secname" : "FANCY SAREES(VNS)", "Supcode" : "29382", "Supname" : "KAJARIA FASHION", "Ctyname" : "VARANASI", "Poryear" : "2015-16", "Pornumb" : "51770", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "16/09/2015", "POREDDT" : "20/09/2015", "Diff" : "-1", "Typ" : "O", "EmpDet" : "1477-VARATHARAJ R ", "pormode" : "R", "OrdQ" : "50", "OrdPV" : ".59", "OrdSV" : ".95", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "50", "PENPV" : ".59", "PENSV" : ".95"},
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