var myApp4 = angular.module("myApp4", []);
	myApp4.controller("MyController4", function MyController4($scope){ 
		$scope.month = [{"srno" : "1", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "7859", "Supname" : "LAKSHMI TEXTILES", "Ctyname" : "NEGAMAM", "Poryear" : "2015-16", "Pornumb" : "48057", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "15", "OrdPV" : ".18", "OrdSV" : ".29", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "15", "PENPV" : ".18", "PENSV" : ".29"},
		{"srno" : "2", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "28336", "Supname" : "GANGA TEX", "Ctyname" : "PUNJAI PULIAMPATTI", "Poryear" : "2015-16", "Pornumb" : "48455", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "1560", "OrdPV" : "13.94", "OrdSV" : "22.27", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1560", "PENPV" : "13.94", "PENSV" : "22.27"},
		{"srno" : "3", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "29462", "Supname" : "KUMARAVADIVEL SAREES", "Ctyname" : "AVINASHI", "Poryear" : "2015-16", "Pornumb" : "48446", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "01/10/2015", "POREDDT" : "05/10/2015", "Diff" : "-13", "Typ" : "M", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "1600", "OrdPV" : "17.25", "OrdSV" : "28.72", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1600", "PENPV" : "17.25", "PENSV" : "28.72"},
		{"srno" : "4", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "25105", "Supname" : "A.JAYALAKSHMI TEXTILES", "Ctyname" : "POLLACHI", "Poryear" : "2015-16", "Pornumb" : "48252", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "50", "OrdPV" : ".4", "OrdSV" : ".6", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "50", "PENPV" : ".4", "PENSV" : ".6"},
		{"srno" : "5", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "28336", "Supname" : "GANGA TEX", "Ctyname" : "PUNJAI PULIAMPATTI", "Poryear" : "2015-16", "Pornumb" : "48254", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "50", "OrdPV" : ".43", "OrdSV" : ".65", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "50", "PENPV" : ".43", "PENSV" : ".65"},
		{"srno" : "6", "G_Srno" : "18", "Grpname" : "COTTON SAREES", "Seccode" : "71", "Secsrno" : "45", "Secname" : "COTTON SAREES (EXL)", "Supcode" : "29036", "Supname" : "D.SAGUNTHALAA", "Ctyname" : "DODDAMPALAYAM", "Poryear" : "2015-16", "Pornumb" : "48253", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "02/10/2015", "POREDDT" : "06/10/2015", "Diff" : "-14", "Typ" : "M", "EmpDet" : "2245-KARTHIKEYAN M", "pormode" : "R", "OrdQ" : "150", "OrdPV" : "1.99", "OrdSV" : "2.99", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "150", "PENPV" : "1.99", "PENSV" : "2.99"},
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