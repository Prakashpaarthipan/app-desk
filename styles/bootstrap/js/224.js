var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample = [
		{'url' : '2016-17_3001_2_1_302_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '2','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '4898','DESNUMB' : '302','PRDRATN' : '','PURRATE' : '3100','PURSALR' : '4495','tcsdesnumb' : '724','SALDISC' : '0','VRTNAME' : 'D/S EMBOSE BODY PLAI','CATLOG' : '','PLANING' : '','prdcode' : '4898','id' : '1','idv' : '0','ival' : '1',},
		{'url' : '2016-17_3001_6_1_306_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '6','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '4892','DESNUMB' : '306','PRDRATN' : '','PURRATE' : '3300','PURSALR' : '4895','tcsdesnumb' : '616','SALDISC' : '0','VRTNAME' : 'THR JAKKARD W.JARI T','CATLOG' : '','PLANING' : '','prdcode' : '4892','id' : '2','idv' : '1','ival' : '2',},
		{'url' : '2016-17_3001_8_1_308_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '8','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '4898','DESNUMB' : '308','PRDRATN' : '','PURRATE' : '3550','PURSALR' : '5295','tcsdesnumb' : '728','SALDISC' : '0','VRTNAME' : 'D/S EMBOSE BODY PLAI','CATLOG' : '','PLANING' : '','prdcode' : '4898','id' : '3','idv' : '2','ival' : '3',},
		{'url' : '2016-17_3001_1_1_301_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '1','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '5201','DESNUMB' : '301','PRDRATN' : '','PURRATE' : '2950','PURSALR' : '4295','tcsdesnumb' : '7028','SALDISC' : '0','VRTNAME' : 'D/S THREAD JAKKARD M','CATLOG' : '','PLANING' : '','prdcode' : '5201','id' : '4','idv' : '3','ival' : '4',},
		{'url' : '2016-17_3001_3_1_303_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '3','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '4898','DESNUMB' : '303','PRDRATN' : '','PURRATE' : '3200','PURSALR' : '4795','tcsdesnumb' : '725','SALDISC' : '0','VRTNAME' : 'D/S EMBOSE BODY PLAI','CATLOG' : '','PLANING' : '','prdcode' : '4898','id' : '5','idv' : '4','ival' : '5',},
		{'url' : '2016-17_3001_7_1_307_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '7','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '5254','DESNUMB' : '307','PRDRATN' : '','PURRATE' : '3700','PURSALR' : '5495','tcsdesnumb' : '608','SALDISC' : '0','VRTNAME' : 'SILK SLM PARTLY','CATLOG' : '','PLANING' : '','prdcode' : '5254','id' : '6','idv' : '5','ival' : '6',},
		{'url' : '2016-17_3001_9_1_309_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '9','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '4898','DESNUMB' : '309','PRDRATN' : '','PURRATE' : '3850','PURSALR' : '5895','tcsdesnumb' : '729','SALDISC' : '0','VRTNAME' : 'D/S EMBOSE BODY PLAI','CATLOG' : '','PLANING' : '','prdcode' : '4898','id' : '7','idv' : '6','ival' : '7',},
		{'url' : '2016-17_3001_4_1_304_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '4','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '4898','DESNUMB' : '304','PRDRATN' : '','PURRATE' : '3200','PURSALR' : '4795','tcsdesnumb' : '726','SALDISC' : '0','VRTNAME' : 'D/S EMBOSE BODY PLAI','CATLOG' : '','PLANING' : '','prdcode' : '4898','id' : '8','idv' : '7','ival' : '8',},
		{'url' : '2016-17_3001_5_1_305_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '3001','ENTSRNO' : '5','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '4898','DESNUMB' : '305','PRDRATN' : '','PURRATE' : '3200','PURSALR' : '4795','tcsdesnumb' : '727','SALDISC' : '0','VRTNAME' : 'D/S EMBOSE BODY PLAI','CATLOG' : '','PLANING' : '','prdcode' : '4898','id' : '9','idv' : '8','ival' : '9',},
		]
		 $scope.plan_process = function(BRNCODE,ENTYEAR,ENTNUMB,ENTSRNO,DESSRNO,PRDCODE,DESNUMB,ival) {
		
		
		document.getElementById('plan_'+ival).style.display = 'block';
		
		window.open('insert_plan_rating.php?BRNCODE='+BRNCODE+'&ENTYEAR='+ENTYEAR+'&ENTNUMB='+ENTNUMB+'&ENTSRNO='+ENTSRNO+'&DESSRNO='+DESSRNO+'&PRDCODE='+PRDCODE+'&DESNUMB='+DESNUMB+'&ival='+ival, 'name', 'width=800, height=500');
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
		var count=document.getElementById('count_rej').value;
		var tot_count= +count+1;
		document.getElementById('count_rej').value=tot_count;
		document.getElementById('count_reject').innerHTML=tot_count;
		var pending_cou=document.getElementById('pending_count').value;
		var tot_cou= pending_cou-1;
		document.getElementById('pending_count').value=tot_cou;
		document.getElementById('count_select').innerHTML=tot_cou;
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
		var count=document.getElementById('catalog').value;
		var tot_count= +count+1;
		document.getElementById('catalog').value=tot_count;
		document.getElementById('count_pen').innerHTML=tot_count;
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
		var count=document.getElementById('catalog').value;
		var tot_count= count-1;
		document.getElementById('catalog').value=tot_count;
		document.getElementById('count_pen').innerHTML=tot_count;
		},
		async: false
		}).responseText;
		};
		 $scope.click_img = function(url,BRNCODE,ENTYEAR,ENTNUMB,ENTSRNO,DESSRNO,PRDCODE,DESNUMB,id,TCS_DESNUMB,PURRATE,PURSALR) {
		window.open('pop_img.php?url='+url+'&BRNCODE='+BRNCODE+'&ENTYEAR='+ENTYEAR+'&ENTNUMB='+ENTNUMB+'&ENTSRNO='+ENTSRNO+'&DESSRNO='+DESSRNO+'&PRDCODE='+PRDCODE+'&DESNUMB='+DESNUMB+'&id='+id+'&TCS_DESNUMB='+TCS_DESNUMB+'&PURRATE='+PURRATE+'&PURSALR='+PURSALR, 'name', 'width=1000, height=500');
		 };
		});