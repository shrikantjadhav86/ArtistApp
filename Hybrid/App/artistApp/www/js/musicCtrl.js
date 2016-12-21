angular.module('starter.musicControllers', [])


        .controller('MusicCtrl', function ($scope, $ionicModal, $rootScope, $cordovaSocialSharing, $timeout, $http, $window, $location) {

            $scope.addMusic = function () {
                console.log('Add Music', $rootScope.currentMusicData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + "/song",
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&song_title=" +
                            $rootScope.currentMusicData.media_title + "&link=" + $rootScope.currentMusicData.link
                }).success(function (data) {
                    $rootScope.currentMusicData = data;
                    console.log('Music Data', $rootScope.currentEventData);
                    $scope.musiclists();
                    $window.location.href = '#/app/musiclists';
                }).error(function (data) {
                    alert(data);
                });
            };
            $scope.showEdit = function (music) {
                $rootScope.currentMusicData = music;
                console.log('Edit Music', $rootScope.currentMusicData);
            };
            $scope.editMusic = function (id) {
                console.log('edit music', $rootScope.currentMusicData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                //$http.defaults.headers = {'Content-Type':'application/x-www-form-urlencoded'};
                $http({
                    method: "post",
                    url: $window.baseUrl + "/song/edit/" + id,
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&song_title=" +
                            $rootScope.currentMusicData.media_title + "&link=" + $rootScope.currentMusicData.link
                }).success(function (data) {
                    $rootScope.currentMusicData = data;
                    console.log('Music Data', $rootScope.currentMusicData);
                    $scope.musiclists();
                    $window.location.href = '#/app/musiclists';
                }).error(function (data) {
                    alert(data.message);
                    console.log("error message = " + data);
                });
                //console.log('Edit Music', $scope.currentMusicData);

            };


            $scope.deleteMusic = function (id) {
                console.log('Delete Music', $rootScope.currentMusicData);
                console.log('Add Music', $rootScope.currentMusicData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                //$http.defaults.headers = {'Content-Type':'application/x-www-form-urlencoded'};
                $http({
                    method: "get",
                    url: $window.baseUrl + "/song/delete/" + id + "?auth_token=" + authToken + "&user_id=" + userId,
                    //data : "auth_token="+authToken +"&user_id="+userId +"&event_name="+$rootScope.currentEventData.event_name+"&event_location="+$rootScope.currentEventData.event_location+"&event_starttime="+$rootScope.currentEventData.event_starttime+"&event_endtime="+$rootScope.currentEventData.event_endtime+"&event_picture="+$rootScope.currentEventData.event_picture+"&event_description="+$rootScope.currentEventData.event_description
                }).success(function (data) {
                    $rootScope.currentMusicData = data;
                    console.log('Event Data', $rootScope.currentMusicData);
                    $scope.musiclists();
                    $window.location.href = '#/app/musiclists';
                }).error(function (data) {
                    alert(data.message);
                    console.log("error message = " + data);
                });
            };

            $scope.shareMusic = function (music) {
                $rootScope.currentMusicData = music;
                var musicId = $rootScope.currentMusicData.id;
                var musicUrl = $rootScope.currentMusicData.link;
                console.log('Music Id', $rootScope.currentMusicData.id);
                console.log('Music Link', $rootScope.currentMusicData.link);
                $cordovaSocialSharing.share("", "", "", musicUrl);
            };
        })
