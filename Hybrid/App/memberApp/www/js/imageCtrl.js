angular.module('starter.imageControllers', [])

.controller('ImageCtrl', function($scope, $ionicModal,$rootScope,$cordovaSocialSharing,$timeout,$http,$window,$location) {


  	$scope.shareImage= function(image) {
	  //alert("In shareImage");
	  $rootScope.currentImageData = image;
      var imageId = $rootScope.currentImageData.id;
	  var imageUrl = $rootScope.currentImageData.link;
	  console.log('image Id', $rootScope.currentImageData.id);
	  console.log('image Link', $rootScope.currentImageData.link);
      $cordovaSocialSharing.share("","",imageUrl,"");
		
 
    /*$scope.shareViaTwitter = function(message, image, link) {
        $cordovaSocialSharing.canShareVia("twitter", message, image, link).then(function(result) {
            $cordovaSocialSharing.shareViaTwitter(message, image, link);
        }, function(error) {
            alert("Cannot share on Twitter");
        });*/
   
    };

})

