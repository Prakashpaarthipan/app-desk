var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample = [
		{'url' : '2016-17_2127_2_1_503_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '2127','ENTSRNO' : '2','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '77690','DESNUMB' : '503','PRDRATN' : '','PURRATE' : '1700','PURSALR' : '2795','tcsdesnumb' : '16811','SALDISC' : '0','VRTNAME' : 'ARANI SILK COTTON','CATLOG' : '','PLANING' : '','prdcode' : '77690','id' : '1','idv' : '0','ival' : '1',},
		{'url' : '2016-17_2127_3_1_504_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '2127','ENTSRNO' : '3','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '77690','DESNUMB' : '504','PRDRATN' : '','PURRATE' : '1800','PURSALR' : '2795','tcsdesnumb' : '16812','SALDISC' : '0','VRTNAME' : 'ARANI SILK COTTON','CATLOG' : '','PLANING' : '','prdcode' : '77690','id' : '2','idv' : '1','ival' : '2',},
		{'url' : '2016-17_2460_1_1_501_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '2460','ENTSRNO' : '1','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '78021','DESNUMB' : '501','PRDRATN' : '','PURRATE' : '350','PURSALR' : '475','tcsdesnumb' : '501','SALDISC' : '0','VRTNAME' : 'BANHATTI COTTON','CATLOG' : '','PLANING' : '','prdcode' : '78021','id' : '3','idv' : '2','ival' : '3',},
		{'url' : '2016-17_2127_1_1_501_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '2127','ENTSRNO' : '1','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '77690','DESNUMB' : '501','PRDRATN' : '','PURRATE' : '1900','PURSALR' : '2995','tcsdesnumb' : '16810','SALDISC' : '0','VRTNAME' : 'ARANI SILK COTTON','CATLOG' : '','PLANING' : '','prdcode' : '77690','id' : '4','idv' : '3','ival' : '4',},
		{'url' : '2016-17_2127_4_1_508_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '2127','ENTSRNO' : '4','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '77690','DESNUMB' : '508','PRDRATN' : '','PURRATE' : '1825','PURSALR' : '2795','tcsdesnumb' : '16813','SALDISC' : '0','VRTNAME' : 'ARANI SILK COTTON','CATLOG' : '','PLANING' : '','prdcode' : '77690','id' : '5','idv' : '4','ival' : '5',},
		{'url' : '2016-17_2127_5_1_511_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '2127','ENTSRNO' : '5','DESSRNO' : '1','SELSRNO' : '','PRDCODE' : '77690','DESNUMB' : '511','PRDRATN' : '','PURRATE' : '1950','PURSALR' : '2995','tcsdesnumb' : '16814','SALDISC' : '0','VRTNAME' : 'ARANI SILK COTTON','CATLOG' : '','PLANING' : '','prdcode' : '77690','id' : '6','idv' : '5','ival' : '6',},
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