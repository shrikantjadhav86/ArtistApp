angular.module('starter.musicControllers', [])


.controller('MusicCtrl', function($scope, $ionicModal, $rootScope,$cordovaSocialSharing,$timeout,$http,$window,$location) {

  	$scope.shareMusic= function(music) {
	  //alert("In shareMusic");
	  $rootScope.currentMusicData = music;
      var musicId = $rootScope.currentMusicData.id;
	  var musicUrl = $rootScope.currentMusicData.link;
	  console.log('Music Id', $rootScope.currentMusicData.id);
	  console.log('Music Link', $rootScope.currentMusicData.link);
        $cordovaSocialSharing.share("","","",musicUrl);
		
 
    /*$scope.shareViaTwitter = function(message, image, link) {
        $cordovaSocialSharing.canShareVia("twitter", message, image, link).then(function(result) {
            $cordovaSocialSharing.shareViaTwitter(message, image, link);
        }, function(error) {
            alert("Cannot share on Twitter");
        });*/
   
    };
})
