var myApp3 = angular.module("myApp3", []);
	myApp3.controller("MyController3", function MyController3($scope){ 
		$scope.week  = [{"srno" : "1", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "7330", "Supname" : "SRI MENAKA FABRICS", "Ctyname" : "JALAKANDAPURAM", "Poryear" : "2015-16", "Pornumb" : "50816", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "400", "OrdPV" : "1.75", "OrdSV" : "2.48", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "400", "PENPV" : "1.75", "PENSV" : "2.48"},
		{"srno" : "2", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "10982", "Supname" : "REENA TEX", "Ctyname" : "ELAMPILLAI", "Poryear" : "2015-16", "Pornumb" : "51826", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "550", "OrdPV" : "2.47", "OrdSV" : "3.1", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "550", "PENPV" : "2.47", "PENSV" : "3.1"},
		{"srno" : "3", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "10982", "Supname" : "REENA TEX", "Ctyname" : "ELAMPILLAI", "Poryear" : "2015-16", "Pornumb" : "51849", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "150", "OrdPV" : ".35", "OrdSV" : ".5", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "150", "PENPV" : ".35", "PENSV" : ".5"},
		{"srno" : "4", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "10982", "Supname" : "REENA TEX", "Ctyname" : "ELAMPILLAI", "Poryear" : "2015-16", "Pornumb" : "51859", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "800", "OrdPV" : "2.63", "OrdSV" : "3.36", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "800", "PENPV" : "2.63", "PENSV" : "3.36"},
		{"srno" : "5", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "17723", "Supname" : "SREE VINAYAGAR TEX", "Ctyname" : "ERODE", "Poryear" : "2015-16", "Pornumb" : "51830", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "200", "OrdPV" : ".98", "OrdSV" : "1.49", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "200", "PENPV" : ".98", "PENSV" : "1.49"},
		{"srno" : "6", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "17723", "Supname" : "SREE VINAYAGAR TEX", "Ctyname" : "ERODE", "Poryear" : "2015-16", "Pornumb" : "51831", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "200", "OrdPV" : ".84", "OrdSV" : ".99", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "200", "PENPV" : ".84", "PENSV" : ".99"},
		{"srno" : "7", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "20552", "Supname" : "OBULI CHETTY SILK", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "50817", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "600", "OrdPV" : "4.77", "OrdSV" : "7.47", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "600", "PENPV" : "4.77", "PENSV" : "7.47"},
		{"srno" : "8", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "26804", "Supname" : "K.P.JANARTHANAN & CO", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "50676", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "1660", "OrdPV" : "20.7", "OrdSV" : "34.36", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1660", "PENPV" : "20.7", "PENSV" : "34.36"},
		{"srno" : "9", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "27766", "Supname" : "V.R.K.FABRICS", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "51853", "Pordisc" : "7", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "50", "OrdPV" : ".35", "OrdSV" : ".57", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "50", "PENPV" : ".35", "PENSV" : ".57"},
		{"srno" : "10", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "27813", "Supname" : "GOMATHI FASHIONS", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "51835", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "500", "OrdPV" : "2.29", "OrdSV" : "2.78", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "500", "PENPV" : "2.29", "PENSV" : "2.78"},
		{"srno" : "11", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "28504", "Supname" : "K.V.P.TEX", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "51870", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "50", "OrdPV" : ".42", "OrdSV" : ".72", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "50", "PENPV" : ".42", "PENSV" : ".72"},
		{"srno" : "12", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "29056", "Supname" : "V.M.S.TEXTILES", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "50840", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "450", "OrdPV" : "1.57", "OrdSV" : "2.38", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "450", "PENPV" : "1.57", "PENSV" : "2.38"},
		{"srno" : "13", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "29056", "Supname" : "V.M.S.TEXTILES", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "51851", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "100", "OrdPV" : ".35", "OrdSV" : ".52", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "100", "PENPV" : ".35", "PENSV" : ".52"},
		{"srno" : "14", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "29091", "Supname" : "LAKSHMI TEXTILES", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "50828", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "750", "OrdPV" : "3.41", "OrdSV" : "4.97", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "750", "PENPV" : "3.41", "PENSV" : "4.97"},
		{"srno" : "15", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "30412", "Supname" : "RENUKA TEXTILES", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "50365", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "200", "OrdPV" : "1.7", "OrdSV" : "2.79", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "200", "PENPV" : "1.7", "PENSV" : "2.79"},
		{"srno" : "16", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "30485", "Supname" : "SMS SILKS", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "50257", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "1600", "OrdPV" : "10.59", "OrdSV" : "16.72", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1600", "PENPV" : "10.59", "PENSV" : "16.72"},
		{"srno" : "17", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "30485", "Supname" : "SMS SILKS", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "51854", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "50", "OrdPV" : ".23", "OrdSV" : ".35", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "50", "PENPV" : ".23", "PENSV" : ".35"},
		{"srno" : "18", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "32091", "Supname" : "SUN TEX", "Ctyname" : "ELAMPILLAI", "Poryear" : "2015-16", "Pornumb" : "51864", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "20/09/2015", "POREDDT" : "24/09/2015", "Diff" : "-4", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "150", "OrdPV" : ".51", "OrdSV" : ".59", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "150", "PENPV" : ".51", "PENSV" : ".59"},
		{"srno" : "19", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "7330", "Supname" : "SRI MENAKA FABRICS", "Ctyname" : "JALAKANDAPURAM", "Poryear" : "2015-16", "Pornumb" : "51230", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-5", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "1000", "OrdPV" : "4.1", "OrdSV" : "5.45", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1000", "PENPV" : "4.1", "PENSV" : "5.45"},
		{"srno" : "20", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "19224", "Supname" : "SHREE MUTHUKUMARAN SILKS", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "46289", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-5", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "800", "OrdPV" : "6.43", "OrdSV" : "10.06", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "800", "PENPV" : "6.43", "PENSV" : "10.06"},
		{"srno" : "21", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "27766", "Supname" : "V.R.K.FABRICS", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "50092", "Pordisc" : "7", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-5", "Typ" : "W", "EmpDet" : "1599-LIYAKATHALI M ", "pormode" : "R", "OrdQ" : "1030", "OrdPV" : "8.11", "OrdSV" : "12.4", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1030", "PENPV" : "8.11", "PENSV" : "12.4"},
		{"srno" : "22", "G_Srno" : "17", "Grpname" : "FANCY ART SAREES", "Seccode" : "59", "Secsrno" : "36", "Secname" : "FANCY ART SAREES(SLM)", "Supcode" : "31119", "Supname" : "A.BHUVANESWARAN", "Ctyname" : "SALEM", "Poryear" : "2015-16", "Pornumb" : "46281", "Pordisc" : "0", "Porspdc" : "0", "Porpcls" : "0", "despstat" : "N", "PORDEDT" : "21/09/2015", "POREDDT" : "25/09/2015", "Diff" : "-5", "Typ" : "W", "EmpDet" : "-", "pormode" : "R", "OrdQ" : "1450", "OrdPV" : "12.77", "OrdSV" : "21.53", "RecQ" : "0", "RecPV" : "0", "RecSV" : "0", "PENQ" : "1450", "PENPV" : "12.77", "PENSV" : "21.53"},
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