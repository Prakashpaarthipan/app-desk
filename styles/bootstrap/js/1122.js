var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample = [
		{'url' : '2015-16_2203_1_1_1.png','BRNCODE' : '888','ENTYEAR' : '2015-16','ENTNUMB' : '2203','ENTSRNO' : '1','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '59239','DESNUMB' : '101','PRDRATN' : '','PURRATE' : '1395','PURSALR' : '2195','tcsdesnumb' : '9653','VRTNAME' : ' BRANDED SAL (L)','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '59239','id' : '1','idv' : '0','ival' : '1',},
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