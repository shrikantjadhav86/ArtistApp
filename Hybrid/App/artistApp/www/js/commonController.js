angular.module('starter.commonControllers', [])

        .controller('common', function ($scope,$ionicLoading,$rootScope,$ionicModal, $http, $timeout, $window, $location, $sessionStorage,$state) {
            $rootScope.currentEventData = {};
            $rootScope.currentBlogData = {};
			
			$scope.show = function() {
				$ionicLoading.show({
				  template: '<p>Loading...</p><ion-spinner></ion-spinner>'
				});
			};

			$scope.hide = function(){
					$ionicLoading.hide();
			};
            /* 
             $scope.getEvent = function(){
             //$window.sessionStorage.setItem("SavedString","I'm a value saved with SessionStorage");
             
             //RETRIEVE VALUE
             // $scope.name = $window.sessionStorage.getItem("SavedString");
             $http.get('http://192.168.1.120/artist_app/api/v1/event').success( function(data) {
             $scope.eventData = data.data;
             console.log('event message', $scope.eventData);
             console.log('All Event Response Data', $scope.eventData);
             });
             }*/
			 
			 
			$rootScope.currentMemberData = {};
            $scope.memberlist = function () {
				
				var userId =$window.sessionStorage.getItem("user_id");
				var authToken=$window.sessionStorage.getItem("auth_token");
				if(userId==null || authToken==null)
				{
					alert("Please Login First...");
					//$state.go('app.login');
					//$window.location.assign('/');
					$window.location.href = '#/app/login';
				}
				else{
					$scope.show($ionicLoading);
					$http({
						method: "get",
						url: $window.baseUrl + "/user/member?auth_token=" + $window.sessionStorage.getItem("auth_token") + "&user_id=" + $window.sessionStorage.getItem("user_id"),
						headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
					}).success(function (data) {
						$rootScope.memberData = data.data;
						console.log('All Member Response Data', $rootScope.memberData);
						
						$ionicLoading.hide();
						
						$window.location.href = '#/app/memberlist';
					}).error(function (data) {
						alert(data);
						$ionicLoading.hide();
						//$window.location.href = '#/app/welcome';
					});
				}
            }
			
            $scope.getEvent = function () {
				var userId =$window.sessionStorage.getItem("user_id");
				var authToken=$window.sessionStorage.getItem("auth_token");
				if(userId==null || authToken==null)
				{
					alert("Please Login First...");
					//$window.location.assign('/');
					$window.location.href = '#/app/login';
				}
				else{
					$scope.show($ionicLoading);
					$http({
						method: "get",
						url: $window.baseUrl + "/event?auth_token=" + $window.sessionStorage.getItem("auth_token") + "&user_id=" + $window.sessionStorage.getItem("user_id"),
						headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
					}).success(function (data) {
						$rootScope.eventData = data.data;
						console.log('event message', $rootScope.eventData);
						console.log('All Event Response Data', $rootScope.eventData);
						
						
						$ionicLoading.hide();
					
						$window.location.href = '#/app/events';
						
					}).error(function (data) {
						alert(data.message);
						$ionicLoading.hide();
						//$window.location.href = '#/app/welcome';
					});
				}
            }


            $scope.getBlog = function () {
				var userId =$window.sessionStorage.getItem("user_id");
				var authToken=$window.sessionStorage.getItem("auth_token");
				if(userId==null || authToken==null)
				{
					alert("Please Login First...");
					//$window.location.assign('/');
					$window.location.href = '#/app/login';
				}
				else{
					//$http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
					$scope.show($ionicLoading);
					$http({
						method: "get",
						url: $window.baseUrl + "/blog?auth_token=" + $window.sessionStorage.getItem("auth_token") + "&user_id=" + $window.sessionStorage.getItem("user_id"),
						headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"

					}).success(function (data) {
						$rootScope.blogData = data.data;
						console.log($rootScope.blogData);
						console.log('Response Data from blog', $rootScope.blogData);
						
						$ionicLoading.hide();
					 
					$window.location.href = '#/app/blogs';
						
					}).error(function (data) {
						alert(data);
						$ionicLoading.hide();
					});
				}
            }

            
            $rootScope.currentMusicData = {};
			$scope.musiclists = function ($ionicView) {
				
				var userId =$window.sessionStorage.getItem("user_id");
				var authToken=$window.sessionStorage.getItem("auth_token");
				if(userId==null || authToken==null)
				{
					alert("Please Login First...");
					//$window.location.assign('/');
					$window.location.href = '#/app/login';
				}
				else{
					$scope.show($ionicLoading);
					
					$http({
						method: "get",
						url: $window.baseUrl + "/song?auth_token=" + $window.sessionStorage.getItem("auth_token") + "&user_id=" + $window.sessionStorage.getItem("user_id"),
						headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
					}).success(function (data) {
						$rootScope.musicData = data.data;
						console.log('music message', $rootScope.musicData);
						$window.location.href ="#/app/musiclists" 
						
						$ionicLoading.hide();
						
												
						
					}).error(function (data) {
						alert(data);
						$ionicLoading.hide();
						//$window.location.href = '#/app/welcome';
					});
					
				}
				
            }
			

            $rootScope.currentImageData = {};
            $scope.imagelists = function () {
				var userId =$window.sessionStorage.getItem("user_id");
				var authToken=$window.sessionStorage.getItem("auth_token");
				console.log("authToken="+authToken);
				console.log("userId="+userId);
				if(userId==null || authToken==null)
				{
					alert("Please Login First...");
					//$window.location.assign('/');
					$window.location.href = '#/app/login';
				}
				else{
					$scope.show($ionicLoading);
					$http({
						method: "get",
						url: $window.baseUrl + "/image?auth_token=" + $window.sessionStorage.getItem("auth_token") + "&user_id=" + $window.sessionStorage.getItem("user_id"),
						headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
					}).success(function (data) {
						$rootScope.imageData = data.data;
						console.log('Image message', $rootScope.imageData);
						console.log('All Image Response Data', $rootScope.imageData);
						
						$ionicLoading.hide();
						
						$window.location.href = '#/app/imagelists';
					}).error(function (data) {
						alert(data);
						$ionicLoading.hide();
						//$window.location.href = '#/app/welcome';
					});
				}
            }

            $rootScope.currentVideoData = {};
            $scope.videolists = function () {
				var userId =$window.sessionStorage.getItem("user_id");
				var authToken=$window.sessionStorage.getItem("auth_token");
				if(userId==null || authToken==null)
				{
					alert("Please Login First...");
					//$window.location.assign('/');
					$window.location.href = '#/app/login';
				}
				else{
					$scope.show($ionicLoading);
					$http({
						method: "get",
						url: $window.baseUrl + "/video?auth_token=" + $window.sessionStorage.getItem("auth_token") + "&user_id=" + $window.sessionStorage.getItem("user_id"),
						headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
					}).success(function (data) {
						$rootScope.videoData = data.data;
						console.log('video message', $rootScope.videoData);
						console.log('All video Response Data', $rootScope.videoData);
						
						$ionicLoading.hide();
					
						$window.location.href = '#/app/videolists';
					}).error(function (data) {
						alert(data);
						$ionicLoading.hide();
						//$window.location.href = '#/app/welcome';
					});
				}
            }
			
			
			
			/*$scope.logout = function() {
					
				$http({
					method: "post",
					url: $window.baseUrl+"/user/logout",
					headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
				}).success( function(data) {
					$rootScope.logoutData = data.data;
					$window.location.href = '#/app/welcome';
				}).error(function(data){alert(data.message);
					
				});
			
			}*/
        })
