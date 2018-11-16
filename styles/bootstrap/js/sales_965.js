var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample_det = [
		
		{'url' : 'S31507P10205D1009Z8N336M595IKBC4951443.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10205','ITMDESG' : '1009','NETRATE' : '336','RATE' : '595','ITMCODE' : 'KBC4951443','PRODUCT_NAME' : '31507-F/P SET PLAIN','ival' : '1'},
		{'url' : 'S31507P10205D1010Z6N336M595IKBC4951451.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10205','ITMDESG' : '1010','NETRATE' : '336','RATE' : '595','ITMCODE' : 'KBC4951451','PRODUCT_NAME' : '31507-F/P SET PLAIN','ival' : '2'},
		{'url' : 'S31507P10205D1011Z3N336M595IKBC4951457.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10205','ITMDESG' : '1011','NETRATE' : '336','RATE' : '595','ITMCODE' : 'KBC4951457','PRODUCT_NAME' : '31507-F/P SET PLAIN','ival' : '3'},
		{'url' : 'S31507P13710D1112Z3N321M595IKBB3866429.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '13710','ITMDESG' : '1112','NETRATE' : '321','RATE' : '595','ITMCODE' : 'KBB3866429','PRODUCT_NAME' : '31507-F/P SET COT FANCY','ival' : '4'},
		{'url' : 'S31507P13710D1112Z3N321M595IKAC1726025.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '13710','ITMDESG' : '1112','NETRATE' : '321','RATE' : '595','ITMCODE' : 'KAC1726025','PRODUCT_NAME' : '31507-F/P SET COT FANCY','ival' : '5'},
		{'url' : 'S31121P13717D1517Z3N586M1095IJLC7319851.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31121','SUPNAME' : 'MAHATAB DRESSES','CTYNAME' : 'KOLKATTA','PRDCODE' : '13717','ITMDESG' : '1517','NETRATE' : '586','RATE' : '1095','ITMCODE' : 'JLC7319851','PRODUCT_NAME' : '31121-F/P SET P.WEAR','ival' : '6'},
		{'url' : 'S31507P10226D2003Z7N379M695IKBC4951502.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10226','ITMDESG' : '2003','NETRATE' : '379','RATE' : '695','ITMCODE' : 'KBC4951502','PRODUCT_NAME' : '31507-F/P SET O/C','ival' : '7'},
		{'url' : 'S31507P10226D2005Z8N379M695IKBC4951527.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10226','ITMDESG' : '2005','NETRATE' : '379','RATE' : '695','ITMCODE' : 'KBC4951527','PRODUCT_NAME' : '31507-F/P SET O/C','ival' : '8'},
		{'url' : 'S31507P10226D2008Z8N379M695IKBD5453457.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10226','ITMDESG' : '2008','NETRATE' : '379','RATE' : '695','ITMCODE' : 'KBD5453457','PRODUCT_NAME' : '31507-F/P SET O/C','ival' : '9'},
		{'url' : 'S31905P52454D2122Z10N1405M2495IKCA7218054.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31905','SUPNAME' : 'MARK CREATION','CTYNAME' : 'MUMBAI','PRDCODE' : '52454','ITMDESG' : '2122','NETRATE' : '1405','RATE' : '2495','ITMCODE' : 'KCA7218054','PRODUCT_NAME' : 'BOYS F/SUIT FANCY','ival' : '10'},
		{'url' : 'S31905P52454D2135Z2N1279M2295IKCA7218056.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31905','SUPNAME' : 'MARK CREATION','CTYNAME' : 'MUMBAI','PRDCODE' : '52454','ITMDESG' : '2135','NETRATE' : '1279','RATE' : '2295','ITMCODE' : 'KCA7218056','PRODUCT_NAME' : 'BOYS F/SUIT FANCY','ival' : '11'},
		{'url' : 'S30012P52454D2290Z1N758M1395IKBC5205247.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30012','SUPNAME' : 'J.M .CREATIONS','CTYNAME' : 'INDORE','PRDCODE' : '52454','ITMDESG' : '2290','NETRATE' : '758','RATE' : '1395','ITMCODE' : 'KBC5205247','PRODUCT_NAME' : 'BOYS F/SUIT FANCY','ival' : '12'},
		{'url' : 'S30012P52454D2307Z2N817M1595IKBC5205263.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30012','SUPNAME' : 'J.M .CREATIONS','CTYNAME' : 'INDORE','PRDCODE' : '52454','ITMDESG' : '2307','NETRATE' : '817','RATE' : '1595','ITMCODE' : 'KBC5205263','PRODUCT_NAME' : 'BOYS F/SUIT FANCY','ival' : '13'},
		{'url' : 'S32878P10909D3010Z7N1097M1995IKCA7219771.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '32878','SUPNAME' : 'SHREE NATHJI CREATION','CTYNAME' : 'INDORE','PRDCODE' : '10909','ITMDESG' : '3010','NETRATE' : '1097','RATE' : '1995','ITMCODE' : 'KCA7219771','PRODUCT_NAME' : '32878-F/P SET FAN D/T','ival' : '14'},
		{'url' : 'S32878P10909D3030Z6N1079M1995IKCA7219763.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '32878','SUPNAME' : 'SHREE NATHJI CREATION','CTYNAME' : 'INDORE','PRDCODE' : '10909','ITMDESG' : '3030','NETRATE' : '1079','RATE' : '1995','ITMCODE' : 'KCA7219763','PRODUCT_NAME' : '32878-F/P SET FAN D/T','ival' : '15'},
		{'url' : 'S14830P13712D3067Z10N1163M1995IKBE6628002.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '14830','SUPNAME' : 'SHRI AMBIKA GARMENTS','CTYNAME' : 'INDORE','PRDCODE' : '13712','ITMDESG' : '3067','NETRATE' : '1163','RATE' : '1995','ITMCODE' : 'KBE6628002','PRODUCT_NAME' : '14830-F/P D/T BOT 10 COL','ival' : '16'},
		{'url' : 'S14830P13712D3070Z9N1018M1695IKBE6627983.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '14830','SUPNAME' : 'SHRI AMBIKA GARMENTS','CTYNAME' : 'INDORE','PRDCODE' : '13712','ITMDESG' : '3070','NETRATE' : '1018','RATE' : '1695','ITMCODE' : 'KBE6627983','PRODUCT_NAME' : '14830-F/P D/T BOT 10 COL','ival' : '17'},
		{'url' : 'S14830P13712D3083Z10N1168M1995IKBE6628008.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '14830','SUPNAME' : 'SHRI AMBIKA GARMENTS','CTYNAME' : 'INDORE','PRDCODE' : '13712','ITMDESG' : '3083','NETRATE' : '1168','RATE' : '1995','ITMCODE' : 'KBE6628008','PRODUCT_NAME' : '14830-F/P D/T BOT 10 COL','ival' : '18'},
		{'url' : 'S32878P10909D3117Z8N1097M1995IKCA7219744.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '32878','SUPNAME' : 'SHREE NATHJI CREATION','CTYNAME' : 'INDORE','PRDCODE' : '10909','ITMDESG' : '3117','NETRATE' : '1097','RATE' : '1995','ITMCODE' : 'KCA7219744','PRODUCT_NAME' : '32878-F/P SET FAN D/T','ival' : '19'},
		{'url' : 'S32878P10909D3132Z7N1097M1995IKCA7219750.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '32878','SUPNAME' : 'SHREE NATHJI CREATION','CTYNAME' : 'INDORE','PRDCODE' : '10909','ITMDESG' : '3132','NETRATE' : '1097','RATE' : '1995','ITMCODE' : 'KCA7219750','PRODUCT_NAME' : '32878-F/P SET FAN D/T','ival' : '20'},
		{'url' : 'S30404P10379D3900Z4N410M695IKCA6931879.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30404','SUPNAME' : 'REGENT CREATION PVT.LTD','CTYNAME' : 'KOVILPATTI','PRDCODE' : '10379','ITMDESG' : '3900','NETRATE' : '410','RATE' : '695','ITMCODE' : 'KCA6931879','PRODUCT_NAME' : '30404-F/P BRD BOT 10 COL','ival' : '21'},
		{'url' : 'S30404P10379D3900Z9N410M695IKCA6932072.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30404','SUPNAME' : 'REGENT CREATION PVT.LTD','CTYNAME' : 'KOVILPATTI','PRDCODE' : '10379','ITMDESG' : '3900','NETRATE' : '410','RATE' : '695','ITMCODE' : 'KCA6932072','PRODUCT_NAME' : '30404-F/P BRD BOT 10 COL','ival' : '22'},
		{'url' : 'S30404P10379D3900Z8N410M695IKCA6932014.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30404','SUPNAME' : 'REGENT CREATION PVT.LTD','CTYNAME' : 'KOVILPATTI','PRDCODE' : '10379','ITMDESG' : '3900','NETRATE' : '410','RATE' : '695','ITMCODE' : 'KCA6932014','PRODUCT_NAME' : '30404-F/P BRD BOT 10 COL','ival' : '23'},
		{'url' : 'S30404P10379D3900Z9N410M695IKCA6932050.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30404','SUPNAME' : 'REGENT CREATION PVT.LTD','CTYNAME' : 'KOVILPATTI','PRDCODE' : '10379','ITMDESG' : '3900','NETRATE' : '410','RATE' : '695','ITMCODE' : 'KCA6932050','PRODUCT_NAME' : '30404-F/P BRD BOT 10 COL','ival' : '24'},
		{'url' : 'S30404P10379D3900Z8N410M695IKCA6932033.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30404','SUPNAME' : 'REGENT CREATION PVT.LTD','CTYNAME' : 'KOVILPATTI','PRDCODE' : '10379','ITMDESG' : '3900','NETRATE' : '410','RATE' : '695','ITMCODE' : 'KCA6932033','PRODUCT_NAME' : '30404-F/P BRD BOT 10 COL','ival' : '25'},
		{'url' : 'S30404P10379D3900Z9N410M695IKCA6932056.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30404','SUPNAME' : 'REGENT CREATION PVT.LTD','CTYNAME' : 'KOVILPATTI','PRDCODE' : '10379','ITMDESG' : '3900','NETRATE' : '410','RATE' : '695','ITMCODE' : 'KCA6932056','PRODUCT_NAME' : '30404-F/P BRD BOT 10 COL','ival' : '26'},
		{'url' : 'S30404P10379D3900Z10N410M695IKCA6932097.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30404','SUPNAME' : 'REGENT CREATION PVT.LTD','CTYNAME' : 'KOVILPATTI','PRDCODE' : '10379','ITMDESG' : '3900','NETRATE' : '410','RATE' : '695','ITMCODE' : 'KCA6932097','PRODUCT_NAME' : '30404-F/P BRD BOT 10 COL','ival' : '27'},
		{'url' : 'S30404P10379D3900Z10N410M695IKCA6932113.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30404','SUPNAME' : 'REGENT CREATION PVT.LTD','CTYNAME' : 'KOVILPATTI','PRDCODE' : '10379','ITMDESG' : '3900','NETRATE' : '410','RATE' : '695','ITMCODE' : 'KCA6932113','PRODUCT_NAME' : '30404-F/P BRD BOT 10 COL','ival' : '28'},
		{'url' : 'S31507P10890D4000Z8N499M895IKBC4951614.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4000','NETRATE' : '499','RATE' : '895','ITMCODE' : 'KBC4951614','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '29'},
		{'url' : 'S31507P10890D4000Z3N499M895IKBC4951534.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4000','NETRATE' : '499','RATE' : '895','ITMCODE' : 'KBC4951534','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '30'},
		{'url' : 'S31507P10890D4000Z8N499M895IKBC4951613.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4000','NETRATE' : '499','RATE' : '895','ITMCODE' : 'KBC4951613','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '31'},
		{'url' : 'S31507P10890D4000Z6N499M895IKBC4951580.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4000','NETRATE' : '499','RATE' : '895','ITMCODE' : 'KBC4951580','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '32'},
		{'url' : 'S31507P10890D4002Z6N499M795IKBD5453488.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4002','NETRATE' : '499','RATE' : '795','ITMCODE' : 'KBD5453488','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '33'},
		{'url' : 'S31507P10890D4002Z5N499M795IKBD5453486.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4002','NETRATE' : '499','RATE' : '795','ITMCODE' : 'KBD5453486','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '34'},
		{'url' : 'S31507P10890D4003Z8N499M795IKBD5453505.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4003','NETRATE' : '499','RATE' : '795','ITMCODE' : 'KBD5453505','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '35'},
		{'url' : 'S31507P10890D4004Z8N499M795IKBD5453517.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4004','NETRATE' : '499','RATE' : '795','ITMCODE' : 'KBD5453517','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '36'},
		{'url' : 'S31507P10890D4008Z8N499M795IKBD5453564.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31507','SUPNAME' : 'G.ORANGE KIDS WEAR','CTYNAME' : 'KOLKATTA','PRDCODE' : '10890','ITMDESG' : '4008','NETRATE' : '499','RATE' : '795','ITMCODE' : 'KBD5453564','PRODUCT_NAME' : '31507-F/P SET FAN D/T','ival' : '37'},
		{'url' : 'S25243P13714D4040Z10N694M1195IJLC7220640.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '25243','SUPNAME' : 'MANISH GARMENTS','CTYNAME' : 'INDORE','PRDCODE' : '13714','ITMDESG' : '4040','NETRATE' : '694','RATE' : '1195','ITMCODE' : 'JLC7220640','PRODUCT_NAME' : '25243-F/P BRD BOT 10 COL','ival' : '38'},
		{'url' : 'S25243P13714D4040Z9N694M1195IJLC7220638.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '25243','SUPNAME' : 'MANISH GARMENTS','CTYNAME' : 'INDORE','PRDCODE' : '13714','ITMDESG' : '4040','NETRATE' : '694','RATE' : '1195','ITMCODE' : 'JLC7220638','PRODUCT_NAME' : '25243-F/P BRD BOT 10 COL','ival' : '39'},
		{'url' : 'S23755P13698D4450Z1N454M795IKBD5992332.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '23755','SUPNAME' : 'YOGESH COMPANY','CTYNAME' : 'INDORE','PRDCODE' : '13698','ITMDESG' : '4450','NETRATE' : '454','RATE' : '795','ITMCODE' : 'KBD5992332','PRODUCT_NAME' : '23755-F/P D/FAN BOT10 COL','ival' : '40'},
		{'url' : 'S31071P17787D4650Z8N470M595IKBD5547030.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31071','SUPNAME' : 'S.SKYLAB','CTYNAME' : 'KOLKATTA','PRDCODE' : '17787','ITMDESG' : '4650','NETRATE' : '470','RATE' : '595','ITMCODE' : 'KBD5547030','PRODUCT_NAME' : 'ECO F/P SET COT FANCY','ival' : '41'},
		{'url' : 'S31071P17787D4650Z8N470M595IKBD5547019.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31071','SUPNAME' : 'S.SKYLAB','CTYNAME' : 'KOLKATTA','PRDCODE' : '17787','ITMDESG' : '4650','NETRATE' : '470','RATE' : '595','ITMCODE' : 'KBD5547019','PRODUCT_NAME' : 'ECO F/P SET COT FANCY','ival' : '42'},
		{'url' : 'S31071P17787D4650Z3N470M595IKBD5546939.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31071','SUPNAME' : 'S.SKYLAB','CTYNAME' : 'KOLKATTA','PRDCODE' : '17787','ITMDESG' : '4650','NETRATE' : '470','RATE' : '595','ITMCODE' : 'KBD5546939','PRODUCT_NAME' : 'ECO F/P SET COT FANCY','ival' : '43'},
		{'url' : 'S33234P13730D4824Z7N962M1695IKCA7234832.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '33234','SUPNAME' : 'COLORS KIDS WEAR','CTYNAME' : 'INDORE','PRDCODE' : '13730','ITMDESG' : '4824','NETRATE' : '962','RATE' : '1695','ITMCODE' : 'KCA7234832','PRODUCT_NAME' : '33234-F/P C/FAN BOT10 COL','ival' : '44'},
		{'url' : 'S30529P13716D4950Z7N505M795IKBD5323359.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '30529','SUPNAME' : 'H.S.CLOTHING','CTYNAME' : 'MUMBAI','PRDCODE' : '13716','ITMDESG' : '4950','NETRATE' : '505','RATE' : '795','ITMCODE' : 'KBD5323359','PRODUCT_NAME' : '30529-F/P SET P.WEAR','ival' : '45'},
		{'url' : 'S31091P17788D5000Z3N505M595IKBC4655028.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31091','SUPNAME' : 'S.I. PILOT INTERNATIONAL','CTYNAME' : 'KOLKATTA','PRDCODE' : '17788','ITMDESG' : '5000','NETRATE' : '505','RATE' : '595','ITMCODE' : 'KBC4655028','PRODUCT_NAME' : 'ECO BRANDED F/P SET','ival' : '46'},
		{'url' : 'S31221P10737D5267Z6N984M1695IKCA7129096.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31221','SUPNAME' : 'S.S.ENTERPRISES','CTYNAME' : 'INDORE','PRDCODE' : '10737','ITMDESG' : '5267','NETRATE' : '984','RATE' : '1695','ITMCODE' : 'KCA7129096','PRODUCT_NAME' : '31221-F/P SET FAN D/T','ival' : '47'},
		{'url' : 'S31221P10737D5273Z6N1036M1695IKCA7129089.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '31221','SUPNAME' : 'S.S.ENTERPRISES','CTYNAME' : 'INDORE','PRDCODE' : '10737','ITMDESG' : '5273','NETRATE' : '1036','RATE' : '1695','ITMCODE' : 'KCA7129089','PRODUCT_NAME' : '31221-F/P SET FAN D/T','ival' : '48'},
		{'url' : 'S27946P13719D5293Z3N643M1095IKBB3566980.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '27946','SUPNAME' : 'JHUNZUNU APPARELS','CTYNAME' : 'INDORE','PRDCODE' : '13719','ITMDESG' : '5293','NETRATE' : '643','RATE' : '1095','ITMCODE' : 'KBB3566980','PRODUCT_NAME' : '27946-F/P F/S BOT 10 COL','ival' : '49'},
		{'url' : 'S34010P13714D5400Z8N551M995IKBD5994308.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '34010','SUPNAME' : 'MEGHA DRESSES','CTYNAME' : 'INDORE','PRDCODE' : '13714','ITMDESG' : '5400','NETRATE' : '551','RATE' : '995','ITMCODE' : 'KBD5994308','PRODUCT_NAME' : '25243-F/P BRD BOT 10 COL','ival' : '50'},
		{'url' : 'S34010P13714D5400Z6N551M995IKBD5994267.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '34010','SUPNAME' : 'MEGHA DRESSES','CTYNAME' : 'INDORE','PRDCODE' : '13714','ITMDESG' : '5400','NETRATE' : '551','RATE' : '995','ITMCODE' : 'KBD5994267','PRODUCT_NAME' : '25243-F/P BRD BOT 10 COL','ival' : '51'},
		{'url' : 'S34010P13714D5400Z9N551M995IKBD5994332.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '34010','SUPNAME' : 'MEGHA DRESSES','CTYNAME' : 'INDORE','PRDCODE' : '13714','ITMDESG' : '5400','NETRATE' : '551','RATE' : '995','ITMCODE' : 'KBD5994332','PRODUCT_NAME' : '25243-F/P BRD BOT 10 COL','ival' : '52'},
		{'url' : 'S15772P13696D5700Z8N581M995IKBC4937557.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '15772','SUPNAME' : 'WINNER GARMENTS','CTYNAME' : 'INDORE','PRDCODE' : '13696','ITMDESG' : '5700','NETRATE' : '581','RATE' : '995','ITMCODE' : 'KBC4937557','PRODUCT_NAME' : '15772-BRANDED F/P SET','ival' : '53'},
		{'url' : 'S15772P13696D5700Z5N581M995IKBC4937500.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '15772','SUPNAME' : 'WINNER GARMENTS','CTYNAME' : 'INDORE','PRDCODE' : '13696','ITMDESG' : '5700','NETRATE' : '581','RATE' : '995','ITMCODE' : 'KBC4937500','PRODUCT_NAME' : '15772-BRANDED F/P SET','ival' : '54'},
		{'url' : 'S33499P17787D6760Z4N580M795IKBC4726906.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '33499','SUPNAME' : 'SONIA GARMENTS','CTYNAME' : 'KOLKATTA','PRDCODE' : '17787','ITMDESG' : '6760','NETRATE' : '580','RATE' : '795','ITMCODE' : 'KBC4726906','PRODUCT_NAME' : 'ECO F/P SET COT FANCY','ival' : '55'},
		{'url' : 'S33499P17787D6760Z3N580M795IKBC4726901.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '33499','SUPNAME' : 'SONIA GARMENTS','CTYNAME' : 'KOLKATTA','PRDCODE' : '17787','ITMDESG' : '6760','NETRATE' : '580','RATE' : '795','ITMCODE' : 'KBC4726901','PRODUCT_NAME' : 'ECO F/P SET COT FANCY','ival' : '56'},
		{'url' : 'S22799P17788D7000Z3N714M895IKBB4219997.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '22799','SUPNAME' : 'NEHA ENTERPRISE','CTYNAME' : 'MUMBAI','PRDCODE' : '17788','ITMDESG' : '7000','NETRATE' : '714','RATE' : '895','ITMCODE' : 'KBB4219997','PRODUCT_NAME' : 'ECO BRANDED F/P SET','ival' : '57'},
		{'url' : 'S22799P17788D7000Z5N714M895IKBB4220009.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '22799','SUPNAME' : 'NEHA ENTERPRISE','CTYNAME' : 'MUMBAI','PRDCODE' : '17788','ITMDESG' : '7000','NETRATE' : '714','RATE' : '895','ITMCODE' : 'KBB4220009','PRODUCT_NAME' : 'ECO BRANDED F/P SET','ival' : '58'},
		{'url' : 'S32235P17788D7750Z3N791M995IKBD5857929.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '32235','SUPNAME' : 'JISAAN FASHION WORLD','CTYNAME' : 'MUMBAI','PRDCODE' : '17788','ITMDESG' : '7750','NETRATE' : '791','RATE' : '995','ITMCODE' : 'KBD5857929','PRODUCT_NAME' : 'ECO BRANDED F/P SET','ival' : '59'},
		{'url' : 'S12851P53112D8923Z7N828M1395IJLB6829052.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '12851','SUPNAME' : 'BINDASS APPARELS PVT.LTD.','CTYNAME' : 'INDORE','PRDCODE' : '53112','ITMDESG' : '8923','NETRATE' : '828','RATE' : '1395','ITMCODE' : 'JLB6829052','PRODUCT_NAME' : 'BOYS F/SUIT D/TOP','ival' : '60'},
		{'url' : 'S12851P53112D8952Z4N828M1395IJLB6829070.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '12851','SUPNAME' : 'BINDASS APPARELS PVT.LTD.','CTYNAME' : 'INDORE','PRDCODE' : '53112','ITMDESG' : '8952','NETRATE' : '828','RATE' : '1395','ITMCODE' : 'JLB6829070','PRODUCT_NAME' : 'BOYS F/SUIT D/TOP','ival' : '61'},
		{'url' : 'S12851P13694D9006Z8N551M995IJLB6758435.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '12851','SUPNAME' : 'BINDASS APPARELS PVT.LTD.','CTYNAME' : 'INDORE','PRDCODE' : '13694','ITMDESG' : '9006','NETRATE' : '551','RATE' : '995','ITMCODE' : 'JLB6758435','PRODUCT_NAME' : '12851-F/P BRD BOT 10 COL','ival' : '62'},
		{'url' : 'S12851P13694D9021Z7N551M995IJLB6758718.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '12851','SUPNAME' : 'BINDASS APPARELS PVT.LTD.','CTYNAME' : 'INDORE','PRDCODE' : '13694','ITMDESG' : '9021','NETRATE' : '551','RATE' : '995','ITMCODE' : 'JLB6758718','PRODUCT_NAME' : '12851-F/P BRD BOT 10 COL','ival' : '63'},
		{'url' : 'S23572P52454D11406Z9N398M695IKBD5990827.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '23572','SUPNAME' : 'C.M.CLOTHING CO','CTYNAME' : 'INDORE','PRDCODE' : '52454','ITMDESG' : '11406','NETRATE' : '398','RATE' : '695','ITMCODE' : 'KBD5990827','PRODUCT_NAME' : 'BOYS F/SUIT FANCY','ival' : '64'},
		{'url' : 'S23572P52454D13405Z7N398M695IKBD5990807.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '23572','SUPNAME' : 'C.M.CLOTHING CO','CTYNAME' : 'INDORE','PRDCODE' : '52454','ITMDESG' : '13405','NETRATE' : '398','RATE' : '695','ITMCODE' : 'KBD5990807','PRODUCT_NAME' : 'BOYS F/SUIT FANCY','ival' : '65'},
		{'url' : 'S14830P13712D20280Z4N965M1595IKBE6627968.png','BRNCODE' : '1','ENTYEAR' : '','ENTNUMB' : '','ENTSRNO' : '','SUPCODE' : '14830','SUPNAME' : 'SHRI AMBIKA GARMENTS','CTYNAME' : 'INDORE','PRDCODE' : '13712','ITMDESG' : '20280','NETRATE' : '965','RATE' : '1595','ITMCODE' : 'KBE6627968','PRODUCT_NAME' : '14830-F/P D/T BOT 10 COL','ival' : '66'},
		]
		$scope.sample1 = [{'SECTION':'BOYS FULL PANT SET'},]
		$scope.sample = [
		]
		
		 $scope.view = function(prd,desnum,ival,type) {
		var brn=document.getElementById('sel_brn').value
		
		var chk1 =prd +'/'+ desnum +'/'+ ival +'/'+ 1 +'/'+ type;
		var w = 1500;var h = 800;var left = Number((screen.width/2)-(w/2)); var tops = Number((screen.height/2)-(h/2));
		window.open('sales_prd_detail.php?num='+chk1+'&brn='+brn, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+tops+', left='+left);
		 };
		 });
		