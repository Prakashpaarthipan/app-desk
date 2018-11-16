var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'route-segment', 'view-segment' , 'myApp.datepicker']);

app.config(function($routeSegmentProvider,  $routeProvider) {

    $routeSegmentProvider.options.autoLoadTemplates = true;

    $routeSegmentProvider

        .when('/Approved Approvals',          'Approved Approvals')
        .when('/Pending Approvals',           'Pending Approvals')
        .when('/Rejected Approvals',          'Rejected Approvals')
        .when('/Internal Verification Approvals',          'Internal Verification Approvals')
        .when('/Acknowledge Alternate Approvals',          'Acknowledge Alternate Approvals')
        // main menu
        //.when('/:id',      'menu')

        // Profile page Link
        .segment('Approved Approvals', { templateUrl: 'view/view.html',  controller: 'aaCtrl'})
        .segment('Pending Approvals', { templateUrl: 'view/view.html', controller: 'paCtrl'})
        .segment('Rejected Approvals', { templateUrl: 'view/view.html', controller: 'raCtrl'})
        .segment('Internal Verification Approvals', { templateUrl: 'view/view.html', controller: 'ivaCtrl'})
        .segment('Acknowledge Alternate Approvals', { templateUrl: 'view/view.html', controller: 'aaaCtrl'})

    //$routeSegmentProvider
    $routeProvider.otherwise({redirectTo: '/Approved Approvals'});
}) ;

app.value('loader', {show: false});

app.controller('mainController', function($scope, $routeSegment, $http, $window, loader) {
  $scope.viewReport = function(id){
    $scope.answer = id;
    // $window.alert(id);
    $http({
        method : 'POST',
        url : 'ajax/approvals_list_view.php',
        data: postData,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function (response) {$scope.emp_report = response.data.records;});
  }
  $scope.$on('routeSegmentChange', function() {
      loader.show = false;
  })
});
app.controller('aaCtrl', function($scope, $routeSegment, $http, $window, loader) {
  $scope.limit = 50;
  $scope.route_id = '1';
  $scope.title = "Approved Approvals";
  //$('#mydiv').hide(slow);
  $('#load_page').show();
  var formData = { 'id': '1'};
  var postData = 'myData='+JSON.stringify(formData);
  $http({
      method : 'POST',
      url : 'ajax/approvals_list_view.php',
      data: postData,
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
  }).then(function (response) {
    $scope.emp_report = response.data.records;
    $('#load_page').hide();

  });

  $scope.filterFromdate = function(fdt, tdt){
    //$('#mydiv').show(slow);
    $('#load_page').show();
    //$scope.tdt = '';

    var formData = { 'id': '1' , 'from_date': fdt , 'to_date' : tdt};
    var postData = 'myData='+JSON.stringify(formData);
  //   $window.alert(fdt);
    $http({
        method : 'POST',
        url : 'ajax/approvals_list_view_filter.php',
        data: postData,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function (response) {
      $scope.emp_report = response.data.records;
      //$('#mydiv').hide(slow);
      $('#load_page').hide();
    });

  }
  /*
  $scope.filterFromdat = function(){
    $('#load_page').show();
    var data_serialize = $("#frm_filter").serialize();
    $http({
        method : 'POST',
        url : 'ajax/approvals_list_view_filter.php',
        data: data_serialize,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function (response) {
      $scope.emp_report = response.data.records;
      $('#load_page').hide();
    });

  }
  */
});
app.controller('paCtrl', function($scope, $routeSegment, $http, $window, loader) {
  $scope.limit = 50;
  $scope.route_id = '2';
  $scope.title = "Pending Approvals";
  $('#load_page').show();
  var formData = { 'id': '2'};
  var postData = 'myData='+JSON.stringify(formData);
  $http({
      method : 'POST',
      url : 'ajax/approvals_list_view.php',
      data: postData,
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
  }).then(function (response) {
    $scope.emp_report = response.data.records;
    $('#load_page').hide();
  });
  //  FILTER CODE
  $scope.filterFromdate = function(fdt, tdt){
    //$('#mydiv').show(slow);
    $('#load_page').show();
    //$scope.tdt = '';

    var formData = { 'id': '2' , 'from_date': fdt , 'to_date' : tdt};
    var postData = 'myData='+JSON.stringify(formData);
  //   $window.alert(fdt);
    $http({
        method : 'POST',
        url : 'ajax/approvals_list_view_filter.php',
        data: postData,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function (response) {
      $scope.emp_report = response.data.records;
      //$('#mydiv').hide(slow);
      $('#load_page').hide();
    });

  }

});

app.controller('raCtrl', function($scope, $routeSegment, $http, $window, loader) {
  $scope.limit = 50;
  $scope.route_id = '3';
  $scope.title = "Rejected Approvals";
  $('#load_page').show();
  var formData = { 'id': '3'};
  var postData = 'myData='+JSON.stringify(formData);
  $http({
      method : 'POST',
      url : 'ajax/approvals_list_view.php',
      data: postData,
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
  }).then(function (response) {
    $scope.emp_report = response.data.records;
    $('#load_page').hide();

  });
  //  FILTER CODE
  $scope.filterFromdate = function(fdt, tdt){
    //$('#mydiv').show(slow);
    $('#load_page').show();
    //$scope.tdt = '';

    var formData = { 'id': '3' , 'from_date': fdt , 'to_date' : tdt};
    var postData = 'myData='+JSON.stringify(formData);
  //   $window.alert(fdt);
    $http({
        method : 'POST',
        url : 'ajax/approvals_list_view_filter.php',
        data: postData,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function (response) {
      $scope.emp_report = response.data.records;
      //$('#mydiv').hide(slow);
      $('#load_page').hide();
    });

  }

});

app.controller('ivaCtrl', function($scope, $routeSegment, $http, $window, loader) {
  $scope.limit = 50;
  $scope.route_id = '4';
  $scope.title = "Internal Verification Approvals";
  $('#load_page').show();
  var formData = { 'id': '4'};
  var postData = 'myData='+JSON.stringify(formData);
  $http({
      method : 'POST',
      url : 'ajax/approvals_list_view.php',
      data: postData,
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
  }).then(function (response) {
    $scope.emp_report = response.data.records;
    $('#load_page').hide();
  });
  //  FILTER CODE
  $scope.filterFromdate = function(fdt, tdt){
    //$('#mydiv').show(slow);
    $('#load_page').show();
    //$scope.tdt = '';

    var formData = { 'id': '4' , 'from_date': fdt , 'to_date' : tdt};
    var postData = 'myData='+JSON.stringify(formData);
  //   $window.alert(fdt);
    $http({
        method : 'POST',
        url : 'ajax/approvals_list_view_filter.php',
        data: postData,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function (response) {
      $scope.emp_report = response.data.records;
      //$('#mydiv').hide(slow);
      $('#load_page').hide();
    });

  }


});
app.controller('aaaCtrl', function($scope, $routeSegment, $http, $window, loader) {
  $scope.limit = 50;
  $scope.route_id = '5';
  $scope.title = "Acknowledge Alternate Approvals";
  $('#load_page').show();
  var formData = { 'id': '5'};
  var postData = 'myData='+JSON.stringify(formData);
  $http({
      method : 'POST',
      url : 'ajax/approvals_list_view.php',
      data: postData,
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
  }).then(function (response) {
    $scope.emp_report = response.data.records;
    $('#load_page').hide();
  });
  //  FILTER CODE
  $scope.filterFromdate = function(fdt, tdt){
    //$('#mydiv').show(slow);
    $('#load_page').show();
    //$scope.tdt = '';

    var formData = { 'id': '5' , 'from_date': fdt , 'to_date' : tdt};
    var postData = 'myData='+JSON.stringify(formData);
  //   $window.alert(fdt);
    $http({
        method : 'POST',
        url : 'ajax/approvals_list_view_filter.php',
        data: postData,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function (response) {
      $scope.emp_report = response.data.records;
      //$('#mydiv').hide(slow);
      $('#load_page').hide();
    });

  }

});
app.controller('ErrorCtrl', function($scope, error) {
    $scope.error = error;
});

app.controller('SlowDataCtrl', function($scope, data, loader) {
    loader.show = false;
    $scope.data = data;
});
