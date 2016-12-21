angular.module('starter.videoControllers', [])

.controller('VideoCtrl', function($scope, $ionicModal, $rootScope,$cordovaSocialSharing,$timeout,$http,$window,$location) {

  	
	$scope.shareVideo= function(video) {
	  //alert("In shareVideo");
	  $rootScope.currentVideoData = video;
      var videoId = $rootScope.currentVideoData.id;
	  var videoUrl = $rootScope.currentVideoData.link;
	  console.log('video Id', $rootScope.currentVideoData.id);
	  console.log('video Link', $rootScope.currentVideoData.link);
        $cordovaSocialSharing.share("","","",videoUrl);
	
   
    };
	
})