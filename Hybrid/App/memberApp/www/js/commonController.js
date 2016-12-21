angular.module('starter.commonControllers', [])

.controller('common',function($scope,$rootScope,$ionicLoading,$ionicModal,$http,$timeout,$window,$location,$sessionStorage){

	$scope.show = function() {
		$ionicLoading.show({
		  template: '<p>Loading...</p><ion-spinner></ion-spinner>'
		});
	};

	$scope.hide = function(){
			$ionicLoading.hide();
	};



	$rootScope.currentEventData = {};
	$scope.getEvent = function(){
	var userId =$window.sessionStorage.getItem("user_id");
	var authToken=$window.sessionStorage.getItem("auth_token");
	if(userId==null || authToken==null)
	{
		 alert("Please Login First...");
		 $window.location.href = '#/app/login';

	}
	else{
	//alert(userId);
		$scope.show($ionicLoading);
		$http({
			method: "get",
			url: $window.baseUrl+"/event?auth_token="+$window.sessionStorage.getItem("auth_token")+"&user_id="+ $window.sessionStorage.getItem("user_id"),
			headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
		}).success( function(data) {
			$rootScope.eventData = data.data;
			console.log('event message', $rootScope.eventData);
			console.log('All Event Response Data', $rootScope.eventData);

			$ionicLoading.hide();

			$window.location.href = '#/app/events';
		}).error(function(data){alert(data.message);
		    $ionicLoading.hide();
			//$window.location.href = '#/app/login';
		});

	}

	}


	$rootScope.currentBlogData = {};
	$scope.getBlog = function(){
		
	var userId =$window.sessionStorage.getItem("user_id");
	var authToken=$window.sessionStorage.getItem("auth_token");
	if(userId==null || authToken==null)
	{
		alert("Please Login First...");
		 $window.location.href = '#/app/login';

	}
	else{
		$scope.show($ionicLoading);
		$http({
		  method: "get",
		  url: $window.baseUrl+"/blog?auth_token="+$window.sessionStorage.getItem("auth_token")+"&user_id="+$window.sessionStorage.getItem("user_id"),
		  headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"

	}).success( function(data) {
		
               $rootScope.blogData = data.data;
			   console.log($rootScope.blogData);
			   //alert($scope.loginData.message);
			   console.log('Response Data from blog', $rootScope.blogData);

				$ionicLoading.hide();

			   $window.location.href = '#/app/blogs';
            }).error(function(data){alert(data.message);
			$ionicLoading.hide();
		});
	}
	}



	$rootScope.currentMusicData = {};
	$scope.musiclists = function() {
	var userId =$window.sessionStorage.getItem("user_id");
	var authToken=$window.sessionStorage.getItem("auth_token");
	if(userId==null || authToken==null)
	{
		alert("Please Login First...");
		  $window.location.href = '#/app/login';

	}
	else{
	$scope.show($ionicLoading);

	 $http({
			method: "get",
			url: $window.baseUrl+"/song?auth_token="+$window.sessionStorage.getItem("auth_token")+"&user_id="+ $window.sessionStorage.getItem("user_id"),
			headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
		}).success( function(data) {
	       	$rootScope.musicData = data.data;
			console.log('music message', $rootScope.musicData);
			console.log('All Music Response Data', $rootScope.musicData);

			$ionicLoading.hide();

			$window.location.href = '#/app/musiclists';
	    }).error(function(data){alert(data.message);
			$ionicLoading.hide();
	    });
	}
  }


	$rootScope.currentImageData = {};
	$scope.imagelists = function() {
	var userId =$window.sessionStorage.getItem("user_id");
	var authToken=$window.sessionStorage.getItem("auth_token");
	if(userId==null || authToken==null)
	{
		alert("Please Login First...");
		 $window.location.href = '#/app/login';

	}
	else{
		$scope.show($ionicLoading);
	 $http({
			method: "get",
			url: $window.baseUrl+"/image?auth_token="+$window.sessionStorage.getItem("auth_token")+"&user_id="+ $window.sessionStorage.getItem("user_id"),
			headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
		}).success( function(data) {
	       	$rootScope.imageData = data.data;
			console.log('Image message', $rootScope.imageData);
			console.log('All Image Response Data', $rootScope.imageData);

			$ionicLoading.hide();

		  $window.location.href = '#/app/imagelists';

	    }).error(function(data){alert(data.message);
			$ionicLoading.hide();
	    	//$window.location.href = '#/app/welcome';
	    });
	}
    }

		$rootScope.currentVideoData = {};
		$scope.videolists = function() {
		var userId =$window.sessionStorage.getItem("user_id");
		var authToken=$window.sessionStorage.getItem("auth_token");
		if(userId==null || authToken==null)
		{
			alert("Please Login First...");
			 $window.location.href = '#/app/login';

		}
		else{
			$scope.show($ionicLoading);
		 $http({
				method: "get",
				url: $window.baseUrl+"/video?auth_token="+$window.sessionStorage.getItem("auth_token")+"&user_id="+ $window.sessionStorage.getItem("user_id"),
				headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }"
			}).success( function(data) {
				$rootScope.videoData = data.data;
				console.log('video message', $rootScope.videoData);
				console.log('All video Response Data', $rootScope.videoData);

				$ionicLoading.hide();

				$window.location.href = '#/app/videolists';
			}).error(function(data){alert(data.message);
				$ionicLoading.hide();
				//$window.location.href = '#/app/welcome';
			});
		}
	  }

	   /* $scope.logout = function() {


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
