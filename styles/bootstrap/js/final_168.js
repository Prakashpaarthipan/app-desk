var myApp = angular.module("myApp", []);
	myApp.controller("MyController", function MyController($scope){ 
		$scope.sample = [
		{'url' : '2016-17_1832_1_1_130_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1832','ENTSRNO' : '1','DESSRNO' : '1','PRDCODE' : '74167','DESNUMB' : '130','PURRATE' : '190','PURSALR' : '275','TCS_DESNUMB' : '30005','SALDISC' : '0','VRTNAME' : 'COT 4 STAR SAREES','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '74167','id' : '1','idv' : '0','ival' : '1',},
		{'url' : '2016-17_1646_1_1_708_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1646','ENTSRNO' : '1','DESSRNO' : '1','PRDCODE' : '82059','DESNUMB' : '708','PURRATE' : '215','PURSALR' : '345','TCS_DESNUMB' : '4445','SALDISC' : '0','VRTNAME' : 'COTTON 3 STAR SAREES','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '82059','id' : '2','idv' : '1','ival' : '2',},
		{'url' : '2016-17_1627_1_1_103_fr_1.png','BRNCODE' : '888','ENTYEAR' : '2016-17','ENTNUMB' : '1627','ENTSRNO' : '1','DESSRNO' : '1','PRDCODE' : '82057','DESNUMB' : '103','PURRATE' : '130','PURSALR' : '225','TCS_DESNUMB' : '82058','SALDISC' : '0','VRTNAME' : 'LEON 3 STAR SAREES','CATLOG' : '','PLANING' : 'SELECTED FOR PLANNING','prdcode' : '82057','id' : '3','idv' : '2','ival' : '3',},
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
		window.open('pop_img1.php?url='+url+'&BRNCODE='+BRNCODE+'&ENTYEAR='+ENTYEAR+'&ENTNUMB='+ENTNUMB+'&ENTSRNO='+ENTSRNO+'&DESSRNO='+DESSRNO+'&PRDCODE='+PRDCODE+'&DESNUMB='+DESNUMB+'&id='+id+'&TCS_DESNUMB='+TCS_DESNUMB+'&PURRATE='+PURRATE+'&PURSALR='+PURSALR, 'name', 'width=800, height=500');
		 };
		});