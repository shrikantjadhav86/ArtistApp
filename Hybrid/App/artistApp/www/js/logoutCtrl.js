angular.module('starter.logoutControllers', [])

        .controller('LogoutCtrl', function ($scope,$ionicLoading, $rootScope, $ionicModal, $http, $timeout, $window, $location, $localStorage, $sessionStorage) {

            $scope.logoutData = {};
		
            $scope.logout = function() {
				var userId =$window.sessionStorage.getItem("user_id");
				var authToken=$window.sessionStorage.getItem("auth_token");
				if(userId==null || authToken==null)
				{
					alert("Please Login First...");
					//$window.location.assign('/');
					$window.location.href = '#/app/login';
				}
				else{
				
					$http({
						method: "post",
						url: $window.baseUrl+"/user/logout",
						headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
					}).success( function(data) {
						$rootScope.logoutData = data.data;
						//$window.location.href = '#/app/welcome';
					}).error(function(data){alert(data.message);
						
					});
				}
			
			};
})
