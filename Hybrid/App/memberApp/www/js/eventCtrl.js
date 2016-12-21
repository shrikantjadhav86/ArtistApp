angular.module('starter.eventControllers', [])

.controller('EventCtrl', function($scope, $rootScope, $ionicModal,$http,$timeout,$window,$location) {

	$scope.viewEvent = function(event) {
      $rootScope.currentEventData = event;
      console.log('View Event', $rootScope.currentEventData);
    };

    
})