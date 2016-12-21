angular.module('starter.eventControllers', [])

        .controller('EventCtrl', function ($scope, $rootScope, $ionicModal,$cordovaSocialSharing, $http, $timeout, $window, $location) {

            $ionicModal.fromTemplateUrl('templates/editevent.html', {
                scope: $scope
            }).then(function (modal) {
                $scope.modal = modal;
            });

            $scope.closeEvent = function () {
                $scope.modal.hide();
            };

            // Open the edit event modal
            $scope.modal = function () {
                $scope.modal.show();
            };

            //add event and reload events
            // Perform the event action when the user submits this form
            $scope.addEvent = function () {
                console.log('Add Event', $rootScope.currentEventData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId    = $window.sessionStorage.getItem("user_id");
                
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + '/event',
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&event_name=" +
                            $rootScope.currentEventData.event_name + "&event_location=" + $rootScope.currentEventData.event_location + "&event_starttime=" + $rootScope.currentEventData.event_starttime +
                            "&event_endtime=" + $rootScope.currentEventData.event_endtime + "&event_picture=" + $rootScope.currentEventData.event_picture + "&event_description=" + $rootScope.currentEventData.event_description

                }).success(function (data) {
                    $rootScope.currentEventData = data;
                    console.log('Event Data', $rootScope.currentEventData);
                    $scope.getEvent();
                    $window.location.href = '#/app/events';
                }).error(function (data) {
                    alert(data.message);
                });
            };
            
            // Delete Event and reload events
            $scope.deleteEvent = function (id) {
                console.log('Delete Event', $rootScope.currentEventData);
                console.log('Add Event', $rootScope.currentEventData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http({
                    method: "get",
                    url: $window.baseUrl + "/event/delete/" + id + "?auth_token=" + authToken + "&user_id=" + userId

                }).success(function (data) {
                    $rootScope.currentEventData = data;
                    console.log('Event Data', $rootScope.currentEventData);
                    $scope.getEvent();
                    $window.location.href = '#/app/events';
                }).error(function (data) {
                    alert(data.message);
                    console.log("error message = " + data);
                });
            };
            
            //Show edit page for event
            $scope.showEdit = function (event) {
                $rootScope.currentEventData = event;
                console.log('Edit Event', $rootScope.currentEventData);
            };

            $scope.viewEvent = function (event) {
                $rootScope.currentEventData = event;
                console.log('View Event', $rootScope.currentEventData);
            };

            /* Edit Event Data */
            $scope.editEvent = function (id) {
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + "/event/edit/" + id,
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&event_name=" +
                            $rootScope.currentEventData.event_name + "&event_location=" + $rootScope.currentEventData.event_location + "&event_starttime=" + $rootScope.currentEventData.event_starttime +
                            "&event_endtime=" + $rootScope.currentEventData.event_endtime + "&event_picture=" + $rootScope.currentEventData.event_picture + "&event_description=" + $rootScope.currentEventData.event_description
                }).success(function (data) {
                    $rootScope.currentEventData = data;
                    console.log('Event Data', $rootScope.currentEventData);
                    $window.location.href = '#/app/events';
                }).error(function (data) {
                    alert(data.message);
                    console.log("error message = " + data);
                });
                console.log('Edit Event', $scope.currentEventData);

            };
			
			$scope.shareEvent = function (currentEventData) {
                $rootScope.currentEventData = currentEventData;
                var imageId = $rootScope.currentEventData.id;
                var imageUrl = $rootScope.currentEventData.event_picture;
                console.log('image Id', $rootScope.currentEventData.id);
                console.log('image Link', $rootScope.currentEventData.event_picture);
                $cordovaSocialSharing.share("", "", imageUrl,"");
            };
			
        })