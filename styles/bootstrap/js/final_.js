var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample = [
		{'url' : '2014-15_6795_1_7_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '7','PRDCODE' : '77647','DESNUMB' : '8979','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8984','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '1','idv' : '0','ival' : '1',},
		{'url' : '2014-15_6795_1_8_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '8','PRDCODE' : '77647','DESNUMB' : '8980','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8985','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '2','idv' : '1','ival' : '2',},
		{'url' : '2014-15_6795_1_6_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '6','PRDCODE' : '77647','DESNUMB' : '8972','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8983','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '3','idv' : '2','ival' : '3',},
		{'url' : '2014-15_6795_1_5_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '5','PRDCODE' : '77647','DESNUMB' : '8919','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8982','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '4','idv' : '3','ival' : '4',},
		{'url' : '2014-15_6795_1_3_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '3','PRDCODE' : '77647','DESNUMB' : '8928','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8980','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '5','idv' : '4','ival' : '5',},
		{'url' : '2014-15_6795_1_10_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '10','PRDCODE' : '77647','DESNUMB' : '8966','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8987','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '6','idv' : '5','ival' : '6',},
		{'url' : '2014-15_6795_1_11_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '11','PRDCODE' : '77647','DESNUMB' : '8985','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8988','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '7','idv' : '6','ival' : '7',},
		{'url' : '2014-15_6795_1_14_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '14','PRDCODE' : '77647','DESNUMB' : '8987','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8989','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '8','idv' : '7','ival' : '8',},
		{'url' : '2014-15_6795_1_4_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '4','PRDCODE' : '77647','DESNUMB' : '8944','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8981','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '9','idv' : '8','ival' : '9',},
		{'url' : '2014-15_6795_1_9_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '1','DESSRNO' : '9','PRDCODE' : '77647','DESNUMB' : '8978','PURRATE' : '700','PURSALR' : '1195','TCS_DESNUMB' : '8986','VRTNAME' : 'CL CECCO PLAIN ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77647','id' : '10','idv' : '9','ival' : '10',},
		{'url' : '2014-15_6795_2_7_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '2','DESSRNO' : '7','PRDCODE' : '77794','DESNUMB' : '003','PURRATE' : '775','PURSALR' : '1295','TCS_DESNUMB' : '4751','VRTNAME' : 'CL SEECO UNIFORM ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77794','id' : '11','idv' : '10','ival' : '11',},
		{'url' : '2014-15_6795_2_24_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '2','DESSRNO' : '24','PRDCODE' : '77794','DESNUMB' : '020','PURRATE' : '775','PURSALR' : '1295','TCS_DESNUMB' : '4766','VRTNAME' : 'CL SEECO UNIFORM ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77794','id' : '12','idv' : '11','ival' : '12',},
		{'url' : '2014-15_6795_2_5_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6795','ENTSRNO' : '2','DESSRNO' : '5','PRDCODE' : '77794','DESNUMB' : '001','PURRATE' : '775','PURSALR' : '1295','TCS_DESNUMB' : '4749','VRTNAME' : 'CL SEECO UNIFORM ','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '77794','id' : '13','idv' : '12','ival' : '13',},
		{'url' : '2014-15_6743_1_5_1.png','BRNCODE' : '888','ENTYEAR' : '2014-15','ENTNUMB' : '6743','ENTSRNO' : '1','DESSRNO' : '5','PRDCODE' : '77709','DESNUMB' : '201','PURRATE' : '895','PURSALR' : '1495','TCS_DESNUMB' : '608','VRTNAME' : 'DHARWAD SEMI GADWAL','CATLOG' : '','PLANING' : '','prdcode' : '77709','id' : '14','idv' : '13','ival' : '14',},
		]
		 $scope.plan_process = function(BRNCODE,ENTYEAR,ENTNUMB,ENTSRNO,DESSRNO,PRDCODE,DESNUMB,ival) {
		
		
		
		
		window.open('insert_plan_grade.php?BRNCODE='+BRNCODE+'&ENTYEAR='+ENTYEAR+'&ENTNUMB='+ENTNUMB+'&ENTSRNO='+ENTSRNO+'&DESSRNO='+DESSRNO+'&PRDCODE='+PRDCODE+'&DESNUMB='+DESNUMB+'&ival='+ival, 'name', 'width=800, height=500');
		 };
		 $scope.reject_process = function(BRNCODE,ENTYEAR,ENTNUMB,ENTSRNO,DESSRNO,PRDCODE,DESNUMB,id) {
		var chk1 = BRNCODE +'/'+ ENTYEAR +'/'+ ENTNUMB +'/'+ ENTSRNO +'/'+ DESSRNO +'/'+ PRDCODE +'/'+ DESNUMB +'/'+ id ;
		var value = $.ajax({
		type: 'POST',
		url: 'reject_2ed_stage.php?num='+chk1,
		beforeSend: function() {
		},
		success: function(data) {
		var value = 'REJECTED';
		var result = value.fontcolor('red');
		
		document.getElementById('hide_ajx_'+id).style.display = 'none';
		document.getElementById('reject_'+id).style.display = 'block';
		},
		async: false
		}).responseText;
		};
		 $scope.catlog_process = function(BRNCODE,ENTYEAR,ENTNUMB,ENTSRNO,DESSRNO,PRDCODE,DESNUMB,id) {
		var chk1 = BRNCODE +'/'+ ENTYEAR +'/'+ ENTNUMB +'/'+ ENTSRNO +'/'+ DESSRNO +'/'+ PRDCODE +'/'+ DESNUMB +'/'+ id ;
		var value = $.ajax({
		type: 'POST',
		url: 'catlog.php?num='+chk1,
		beforeSend: function() {
		},
		success: function(data) {
		var value = 'CATALOG';
		var result = value.fontcolor('BLUE');
		
		document.getElementById('hide_ajx_'+id).style.display = 'none';
		document.getElementById('catlog_'+id).style.display = 'block';
		},
		async: false
		}).responseText;
		};
		 $scope.catlog_remove = function(BRNCODE,ENTYEAR,ENTNUMB,ENTSRNO,DESSRNO,PRDCODE,DESNUMB,id) {
		var chk1 = BRNCODE +'/'+ ENTYEAR +'/'+ ENTNUMB +'/'+ ENTSRNO +'/'+ DESSRNO +'/'+ PRDCODE +'/'+ DESNUMB +'/'+ id ;
		var value = $.ajax({
		type: 'POST',
		url: 'catlog_remove.php?num='+chk1,
		beforeSend: function() {
		},
		success: function(data) {
		var value = 'CATALOG REMOVED';
		
		document.getElementById('hide_ajx_'+id).style.display = 'none';
		document.getElementById('catlog_remove'+id).style.display = 'block';
		document.getElementById('select_ajx_'+id).style.display = 'none';
		},
		async: false
		}).responseText;
		};
		 $scope.click_img = function(url,BRNCODE,ENTYEAR,ENTNUMB,ENTSRNO,DESSRNO,PRDCODE,DESNUMB,id,TCS_DESNUMB,PURRATE,PURSALR) {
		window.open('pop_img.php?url='+url+'&BRNCODE='+BRNCODE+'&ENTYEAR='+ENTYEAR+'&ENTNUMB='+ENTNUMB+'&ENTSRNO='+ENTSRNO+'&DESSRNO='+DESSRNO+'&PRDCODE='+PRDCODE+'&DESNUMB='+DESNUMB+'&id='+id+'&TCS_DESNUMB='+TCS_DESNUMB+'&PURRATE='+PURRATE+'&PURSALR='+PURSALR, 'name', 'width=800, height=500');
		 };
		});