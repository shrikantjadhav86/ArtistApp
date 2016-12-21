angular.module('starter.loginControllers', [])

.controller('LoginCtrl', function($scope,$ionicLoading,$rootScope,$ionicModal,$http,$timeout,$window,$location,$localStorage,$sessionStorage) {

	$rootScope.userData ={};
	$scope.loginData ={};
	
	$scope.show = function() {
    $ionicLoading.show({
		  template: '<p>Loading...</p><ion-spinner></ion-spinner>'
		});
	};

	$scope.hide = function(){
		$ionicLoading.hide();
	};
	
 $scope.doLogin = function() {
		
	console.log('Doing login', $scope.loginData);
	$scope.show($ionicLoading);
	$http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
	$http({
		  method: "post",
		  url: $window.baseUrl+"/user/login",
		  data: "username="+ $scope.loginData.username+"&password="+ $scope.loginData.password
	}).success( function(data) {
		    $scope.userData = data.data;
		    console.log($scope.userData.user_id);
		    $window.sessionStorage.setItem("user_id",$scope.userData.user_id);
		    $window.sessionStorage.setItem("auth_token",$scope.userData.auth_token);
		    console.log('Response Data from login', $scope.userData);
			$ionicLoading.hide();
			$window.location.href = '#/app/welcome';
            }).error(function(data){alert(data.message);
			});

  };
}).config(function($sceProvider) {
   $sceProvider.enabled(false);
});
