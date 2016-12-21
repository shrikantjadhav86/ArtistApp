angular.module('starter.videoControllers', [])

        .controller('VideoCtrl', function ($scope, $ionicModal, $rootScope, $cordovaSocialSharing, $timeout, $http, $window, $location) {

            $scope.addVideo = function () {
                console.log('Add Video', $rootScope.currentVideoData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + "/video",
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&video_title=" +
                            $rootScope.currentVideoData.media_title + "&link=" + $rootScope.currentVideoData.link
                }).success(function (data) {
                    $rootScope.currentVideoData = data;
                    console.log('Video Data', $rootScope.currentEventData);
                    $scope.videolists();
                    $window.location.href = '#/app/videolists';
                }).error(function (data) {
                    alert(data);
                });
            };
            $scope.showEdit = function (video) {
                $rootScope.currentVideoData = video;
                console.log('Edit video', $rootScope.currentVideoData);
            };
            $scope.editVideo = function (id) {
                console.log('edit video', $rootScope.currentVideoData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                //$http.defaults.headers = {'Content-Type':'application/x-www-form-urlencoded'};
                $http({
                    method: "post",
                    url: $window.baseUrl + "/video/edit/" + id,
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&video_title=" +
                            $rootScope.currentVideoData.media_title + "&link=" + $rootScope.currentVideoData.link
                }).success(function (data) {
                    $rootScope.currentVideoData = data;
                    console.log('video Data', $rootScope.currentVideoData);
                    $scope.videolists();
                    $window.location.href = '#/app/videolists';
                }).error(function (data) {
                    alert(data.message);
                    console.log("error message = " + data);
                });
                //console.log('Edit video', $scope.currentVideoData);

            };


            $scope.deleteVideo = function (id) {
                console.log('Delete video', $rootScope.currentVideoData);
                console.log('Add video', $rootScope.currentVideoData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                //$http.defaults.headers = {'Content-Type':'application/x-www-form-urlencoded'};
                $http({
                    method: "get",
                    url: $window.baseUrl + "/video/delete/" + id + "?auth_token=" + authToken + "&user_id=" + userId,
                    //data : "auth_token="+authToken +"&user_id="+userId +"&event_name="+$rootScope.currentEventData.event_name+"&event_location="+$rootScope.currentEventData.event_location+"&event_starttime="+$rootScope.currentEventData.event_starttime+"&event_endtime="+$rootScope.currentEventData.event_endtime+"&event_picture="+$rootScope.currentEventData.event_picture+"&event_description="+$rootScope.currentEventData.event_description
                }).success(function (data) {
                    $rootScope.currentVideoData = data;
                    console.log('Event Data', $rootScope.currentVideoData);
                    $scope.videolists();
                    $window.location.href = '#/app/videolists';
                }).error(function (data) {
                    alert(data.message);
                    console.log("error message = " + data);
                });
            };

            $scope.shareVideo = function (video) {
                $rootScope.currentVideoData = video;
                var videoId = $rootScope.currentVideoData.id;
                var videoUrl = $rootScope.currentVideoData.link;
                console.log('video Id', $rootScope.currentVideoData.id);
                console.log('video Link', $rootScope.currentVideoData.link);
                $cordovaSocialSharing.share("", "", "", videoUrl);
            };

        })