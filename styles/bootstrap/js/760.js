var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample = [
		{'url' : '2016-17_1384_5_1_2232_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1384','ENTSRNO' : '5','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '41281','DESNUMB' : '2232','PRDRATN' : '','PURRATE' : '3250','PURSALR' : '8995','tcsdesnumb' : '68346','SALDISC' : '0','VRTNAME' : 'BM JOTHPURI SUIT','CATLOG' : '','PLANING' : '','prdcode' : '41281','id' : '1','idv' : '0','ival' : '1',},
		{'url' : '2016-17_1384_4_1_02230_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1384','ENTSRNO' : '4','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '41281','DESNUMB' : '02230','PRDRATN' : '','PURRATE' : '3000','PURSALR' : '7995','tcsdesnumb' : '68345','SALDISC' : '0','VRTNAME' : 'BM JOTHPURI SUIT','CATLOG' : '','PLANING' : '','prdcode' : '41281','id' : '2','idv' : '1','ival' : '2',},
		{'url' : '2016-17_1384_3_1_2230_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1384','ENTSRNO' : '3','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '41281','DESNUMB' : '2230','PRDRATN' : '','PURRATE' : '3000','PURSALR' : '7995','tcsdesnumb' : '68344','SALDISC' : '0','VRTNAME' : 'BM JOTHPURI SUIT','CATLOG' : '','PLANING' : '','prdcode' : '41281','id' : '3','idv' : '2','ival' : '3',},
		{'url' : '2016-17_1384_2_1_112_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1384','ENTSRNO' : '2','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '41281','DESNUMB' : '112','PRDRATN' : '','PURRATE' : '3250','PURSALR' : '8995','tcsdesnumb' : '68343','SALDISC' : '0','VRTNAME' : 'BM JOTHPURI SUIT','CATLOG' : '','PLANING' : '','prdcode' : '41281','id' : '4','idv' : '3','ival' : '4',},
		{'url' : '2016-17_1384_1_1_2228_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1384','ENTSRNO' : '1','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '41281','DESNUMB' : '2228','PRDRATN' : '','PURRATE' : '3250','PURSALR' : '8995','tcsdesnumb' : '68342','SALDISC' : '0','VRTNAME' : 'BM JOTHPURI SUIT','CATLOG' : '','PLANING' : '','prdcode' : '41281','id' : '5','idv' : '4','ival' : '5',},
		{'url' : '2016-17_1384_7_1_2238_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1384','ENTSRNO' : '7','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '41281','DESNUMB' : '2238','PRDRATN' : '','PURRATE' : '3250','PURSALR' : '8995','tcsdesnumb' : '68348','SALDISC' : '0','VRTNAME' : 'BM JOTHPURI SUIT','CATLOG' : '','PLANING' : '','prdcode' : '41281','id' : '6','idv' : '5','ival' : '6',},
		{'url' : '2016-17_1384_6_1_2224_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1384','ENTSRNO' : '6','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '41281','DESNUMB' : '2224','PRDRATN' : '','PURRATE' : '3250','PURSALR' : '8995','tcsdesnumb' : '68347','SALDISC' : '0','VRTNAME' : 'BM JOTHPURI SUIT','CATLOG' : '','PLANING' : '','prdcode' : '41281','id' : '7','idv' : '6','ival' : '7',},
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
		window.open('pop_img1.php?url='+url+'&BRNCODE='+BRNCODE+'&ENTYEAR='+ENTYEAR+'&ENTNUMB='+ENTNUMB+'&ENTSRNO='+ENTSRNO+'&DESSRNO='+DESSRNO+'&PRDCODE='+PRDCODE+'&DESNUMB='+DESNUMB+'&id='+id+'&TCS_DESNUMB='+TCS_DESNUMB+'&PURRATE='+PURRATE+'&PURSALR='+PURSALR, 'name', 'width=1000, height=500');
		 };
		});