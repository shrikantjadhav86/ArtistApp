angular.module('starter.imageControllers', [])

        .controller('ImageCtrl', function ($scope, $ionicModal,$cordovaCamera, $rootScope, $cordovaSocialSharing, $timeout, $http, $window, $location) {



            $scope.addImage = function () {
                console.log('Add Image', $rootScope.currentImageData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + "/image",
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&image_title=" +
                            $rootScope.currentImageData.media_title + "&link=" + $rootScope.currentImageData.link
                }).success(function (data) {
                    $rootScope.currentImageData = data;
                    console.log('Image Data', $rootScope.currentEventData);
                    $scope.imagelists();
                    $window.location.href = '#/app/imagelists';
                }).error(function (data) {
                    alert("In addimage error"+data);
                });
            };
            $scope.showEdit = function (image) {
                $rootScope.currentImageData = image;
                console.log('Edit image', $rootScope.currentImageData);
            };
            $scope.editImage = function (id) {
                console.log('edit image', $rootScope.currentImageData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + "/image/edit/" + id,
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&image_title=" +
                            $rootScope.currentImageData.media_title + "&link=" + $rootScope.currentImageData.link
                }).success(function (data) {
                    $rootScope.currentImageData = data;
                    console.log('video Data', $rootScope.currentImageData);
                    $scope.imagelists();
                    $window.location.href = '#/app/imagelists';
                }).error(function (data) {
                    alert(data.message);
                    console.log("error message = " + data);
                });
                //console.log('Edit image', $scope.currentImageData);

            };


            $scope.deleteImage = function (id) {
                console.log('Delete image', $rootScope.currentImageData);
                console.log('Add image', $rootScope.currentImageData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                //$http.defaults.headers = {'Content-Type':'application/x-www-form-urlencoded'};
                $http({
                    method: "get",
                    url: $window.baseUrl + "/image/delete/" + id + "?auth_token=" + authToken + "&user_id=" + userId,
                    //data : "auth_token="+authToken +"&user_id="+userId +"&event_name="+$rootScope.currentEventData.event_name+"&event_location="+$rootScope.currentEventData.event_location+"&event_starttime="+$rootScope.currentEventData.event_starttime+"&event_endtime="+$rootScope.currentEventData.event_endtime+"&event_picture="+$rootScope.currentEventData.event_picture+"&event_description="+$rootScope.currentEventData.event_description
                }).success(function (data) {
                    $rootScope.currentImageData = data;
                    console.log('image Data', $rootScope.currentImageData);
                    $scope.imagelists();
                    $window.location.href = '#/app/imagelists';
                }).error(function (data) {
                    alert(data.message);
                    console.log("error message = " + data);
                });
            };

            $scope.shareImage = function (image) {
                $rootScope.currentImageData = image;
                var imageId = $rootScope.currentImageData.id;
                var imageUrl = $rootScope.currentImageData.link;
                console.log('image Id', $rootScope.currentImageData.id);
                console.log('image Link', $rootScope.currentImageData.link);
                $cordovaSocialSharing.share("", "", imageUrl,"");
            };
			
			
		$scope.pictureUrl = '';
		$scope.takePicture = function(){
			
			var options={
			//Take a photo and retrieve it as a Base64-encoded image:
				//destinationType: Camera.DestinationType.DATA_URL,
				
			//Take a photo and retrieve the image's file location:
				destinationType: Camera.DestinationType.DATA_URL,
				sourceType: Camera.PictureSourceType.CAMERA,
				encodingType: Camera.EncodingType.JPEG,
				saveToPhotoAlbum: true,
				
				
			};
			$cordovaCamera.getPicture(options).then(function(data){
				//console.log('camera data:'+angular.toJson(data));
				//$scope.pictureUrl='data:image/jpeg;base64,' + data;
				//alert("file name="+data.substr(data.lastIndexOf('/')+1));
				$scope.imgUrl = "data:image/jpeg;base64," + data;
				var imgbaseUrl = $scope.imgUrl;
				$rootScope.currentImageData.link=imgbaseUrl;
				alert("image base64 data "+imgbaseUrl);
				$scope.uploadCameraPic(imgbaseUrl);
				
			},function(error){
				console.log('camera data:'+angular.toJson(data));
			});
		};
		
			$scope.uploadCameraPic = function(imgbaseUrl) {
				var filePath= imgbaseUrl;
				//var fileName = data.substr(data.lastIndexOf('/')+1);
				//var filePath= 'data:image/jpeg;base64,' + data;
				alert("in uploadcamerapic method" +fileName);
				var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers = {'Content-Type':'application/x-www-form-urlencoded'};
                $http({
                    method: "post",
                    url: $window.baseUrl + "/user/uploadCameraPic" +"?auth_token=" + authToken + "&user_id=" + userId,
                    data : "uploadImage="+filePath
                }).success(function (data) {
                    alert("Image uploaded successfully");
                }).error(function (data) {
                    alert("In error :"+data);
                    
                });
			}
					
					
		
    })

