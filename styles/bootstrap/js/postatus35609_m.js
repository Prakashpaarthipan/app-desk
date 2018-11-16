var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "15", "Grpname" : "SAREES", "Seccode" : "259", "Secsrno" : "6", "Secname" : "SYN SET SAREES", "Supcode" : "7468", "Supname" : "RASHMI PRINTS", "Ctyname" : "SURAT", "Poryear" : "2015-16", "Pornumb" : "46471", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "2547-KARTHIK.V ", "pormode" : "R", "OrdQ" : "2400", "OrdPV" : "4.08", "OrdSV" : "6.6", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "2400", "PENPV" : "4.08", "PENSV" : "6.6"},
		{"srno" : "2", "G_Srno" : "15", "Grpname" : "SAREES", "Seccode" : "259", "Secsrno" : "6", "Secname" : "SYN SET SAREES", "Supcode" : "26144", "Supname" : "LAXMINATHJEE SILK MILLS PVT.LTD", "Ctyname" : "SURAT", "Poryear" : "2015-16", "Pornumb" : "46461", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "2547-KARTHIK.V ", "pormode" : "R", "OrdQ" : "1760", "OrdPV" : "3.08", "OrdSV" : "4.84", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1760", "PENPV" : "3.08", "PENSV" : "4.84"},
		{"srno" : "3", "G_Srno" : "15", "Grpname" : "SAREES", "Seccode" : "259", "Secsrno" : "6", "Secname" : "SYN SET SAREES", "Supcode" : "26144", "Supname" : "LAXMINATHJEE SILK MILLS PVT.LTD", "Ctyname" : "SURAT", "Poryear" : "2015-16", "Pornumb" : "46462", "Pordisc" : "5", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "2547-KARTHIK.V ", "pormode" : "R", "OrdQ" : "240", "OrdPV" : ".42", "OrdSV" : ".66", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "240", "PENPV" : ".42", "PENSV" : ".66"},
		{"srno" : "4", "G_Srno" : "15", "Grpname" : "SAREES", "Seccode" : "259", "Secsrno" : "6", "Secname" : "SYN SET SAREES", "Supcode" : "31452", "Supname" : "SHANTI SYNTHETICS", "Ctyname" : "SURAT", "Poryear" : "2015-16", "Pornumb" : "50094", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1320", "OrdPV" : "2.93", "OrdSV" : "4.55", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1320", "PENPV" : "2.93", "PENSV" : "4.55"},
		{"srno" : "5", "G_Srno" : "15", "Grpname" : "SAREES", "Seccode" : "259", "Secsrno" : "6", "Secname" : "SYN SET SAREES", "Supcode" : "31799", "Supname" : "KIRAN FASHIONS PVT LTD", "Ctyname" : "SURAT", "Poryear" : "2015-16", "Pornumb" : "48151", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "100", "OrdPV" : ".15", "OrdSV" : ".23", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "100", "PENPV" : ".15", "PENSV" : ".23"},
		{"srno" : "6", "G_Srno" : "15", "Grpname" : "SAREES", "Seccode" : "259", "Secsrno" : "6", "Secname" : "SYN SET SAREES", "Supcode" : "9408", "Supname" : "SHREEJEE INTERNATIONAL", "Ctyname" : "SURAT", "Poryear" : "2015-16", "Pornumb" : "46472", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/10/2015", "POREDDT" : "10/10/2015", "Diff" : "-18", "Typ" : "M", "EmpDet" : "2547-KARTHIK.V ", "pormode" : "R", "OrdQ" : "3400", "OrdPV" : "5.61", "OrdSV" : "9.35", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "3400", "PENPV" : "5.61", "PENSV" : "9.35"},
		{"srno" : "7", "G_Srno" : "15", "Grpname" : "SAREES", "Seccode" : "259", "Secsrno" : "6", "Secname" : "SYN SET SAREES", "Supcode" : "30677", "Supname" : "A.V.M.PRINTS", "Ctyname" : "SURAT", "Poryear" : "2015-16", "Pornumb" : "48073", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "06/10/2015", "POREDDT" : "10/10/2015", "Diff" : "-18", "Typ" : "M", "EmpDet" : "2547-KARTHIK.V ", "pormode" : "R", "OrdQ" : "1560", "OrdPV" : "3.43", "OrdSV" : "5.38", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1560", "PENPV" : "3.43", "PENSV" : "5.38"},
		]
		$scope.getTotal = function(){
			var total1 = 0;
			var total2 = 0;
			var total3 = 0;
			var total4 = 0;
			var total5 = 0;
			var total6 = 0;
			for(var i = 0; i < $scope.month.length; i++){
				var ordqt = $scope.month[i]["OrdQ"];
				var ordvval = $scope.month[i]["OrdSV"];
				var resqt = $scope.month[i]["RecQ"];
				var resvval = $scope.month[i]["RecSV"];
				var penqt = $scope.month[i]["PENQ"];
				var penval = $scope.month[i]["PENSV"];
				
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
		angular.bootstrap(document.getElementById('App4'),['myApp4']);
		});