angular.module('starter.userControllers', [])

        .controller('UserCtrl', function ($scope, $rootScope, $ionicModal, $http, $timeout, $window) {
			
			$scope.userData ={};
            $scope.registration = function () {
                console.log('Register as member', $scope.userData);
				
                //var authToken = $window.sessionStorage.getItem("auth_token");
                //var userId = $window.sessionStorage.getItem("user_id");
               // $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + "/user/register",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: "name=" + $scope.userData.name + "&password=" + $scope.userData.password

                }).success(function (data) {
                    $scope.userData = data;
                    console.log('Member Data', $scope.userData);
                    $window.location.href = '#/app/welcome';
                }).error(function (data) {
                    alert("In error"+data);
                });
            };
        })

