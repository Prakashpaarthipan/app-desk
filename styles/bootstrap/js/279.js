var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample = [
		{'url' : '2016-17_1456_2_1_102_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '2','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '102','PRDRATN' : '','PURRATE' : '69','PURSALR' : '125','tcsdesnumb' : '208','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '1','idv' : '0','ival' : '1',},
		{'url' : '2016-17_1456_7_1_3686_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '7','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '3686','PRDRATN' : '','PURRATE' : '72','PURSALR' : '125','tcsdesnumb' : '213','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '84020','id' : '2','idv' : '1','ival' : '2',},
		{'url' : '2016-17_1456_8_1_3685_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '8','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '3685','PRDRATN' : '','PURRATE' : '72','PURSALR' : '125','tcsdesnumb' : '214','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '3','idv' : '2','ival' : '3',},
		{'url' : '2016-17_1456_10_1_3676_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '10','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '3676','PRDRATN' : '','PURRATE' : '73','PURSALR' : '125','tcsdesnumb' : '216','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '4','idv' : '3','ival' : '4',},
		{'url' : '2016-17_1456_9_1_3693_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '9','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '3693','PRDRATN' : '','PURRATE' : '73','PURSALR' : '115','tcsdesnumb' : '215','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '5','idv' : '4','ival' : '5',},
		{'url' : '2016-17_1456_3_1_103_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '3','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '103','PRDRATN' : '','PURRATE' : '69','PURSALR' : '125','tcsdesnumb' : '209','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '6','idv' : '5','ival' : '6',},
		{'url' : '2016-17_1456_1_1_101_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '1','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '101','PRDRATN' : '','PURRATE' : '73','PURSALR' : '125','tcsdesnumb' : '207','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '7','idv' : '6','ival' : '7',},
		{'url' : '2016-17_1456_4_1_104_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '4','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '104','PRDRATN' : '','PURRATE' : '69','PURSALR' : '125','tcsdesnumb' : '210','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '8','idv' : '7','ival' : '8',},
		{'url' : '2016-17_1456_5_1_105_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '5','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '105','PRDRATN' : '','PURRATE' : '64','PURSALR' : '125','tcsdesnumb' : '211','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '9','idv' : '8','ival' : '9',},
		{'url' : '2016-17_1456_6_1_3687_fr_1.jpg','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1456','ENTSRNO' : '6','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '84020','DESNUMB' : '3687','PRDRATN' : '','PURRATE' : '72','PURSALR' : '125','tcsdesnumb' : '212','SALDISC' : '0','VRTNAME' : '36" FANCY LIGHT SHIR','CATLOG' : '','PLANING' : '','prdcode' : '84020','id' : '10','idv' : '9','ival' : '10',},
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