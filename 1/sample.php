<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-route.js"></script>
	</head>
	<body>
		<div class="panel panel-default" ng-app="myApp">
                                          <div ng-view></div>
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Related Approvals & Tags</strong></h3>
                                            </div>
                                            <div class="panel-body" id="id_tags_generation">
                                                <div ng-view>basic</div>
                                            </div>
                                            <script>
                                          		var app = angular.module("myApp", ["ngRoute"]);
                                          			app.config(function($routeProvider) {
                                          				$routeProvider
                                          				.when("/", {
                                          					templateUrl : "main.html"
                                          				});
                                          			});

                                          	</script>
                                        </div>
	</body>
	<!--<script>
		var app = angular.module("myApp", ["ngRoute"]);
			app.config(function($routeProvider) {
				$routeProvider
				.when("/", {
					templateUrl : "main.html"
				});
			});

	</script>-->
</html>
