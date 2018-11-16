var app = angular.module('Approval_desk_App', []);
app.controller('Approval_desk_Ctrl', function($scope, $http) {
    $scope.limit = 25;
    $scope.fatch_duty_group = [];
    $scope.action = $('#hidcntl_action').val();
    $scope.statu = $('#hidcntl_status').val();
    
     $scope.click = function(parameter){ 
      $http.get("ajax/approvals_list.php?action="+$scope.action+"&status="+$scope.statu+"").then(function (response) { $scope.emp_report = response.data.records; });
     }
});
