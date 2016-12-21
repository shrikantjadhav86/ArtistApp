angular.module('starter.controllers', [])

/*.controller('common',function($scope, $ionicModal,$http,$timeout,$window,$location){
	$scope.getEvent = function(){
		$http.get('https://demo0923422.mockable.io/api/artist/all_event').success( function(data) {
			$scope.eventData = data.data;
			console.log('event message', $scope.eventData);
			console.log('All Event Response Data', $scope.eventData);
		});
	}
	
	$scope.getBlog = function(){
		$http.get('https://demo0923422.mockable.io/artist_new/api/blog').success( function(data) {
               $scope.blogData = data.data;
			   console.log('All Blog Response Data', $scope.blogData);
			   
            });
	}
	
	
})
*/

.controller('LoginCtrl', function($scope, $ionicModal,$http,$timeout,$window,$location) {
	$scope.loginData={};
  $ionicModal.fromTemplateUrl('templates/login.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });

  
  // Triggered in the login modal to close it
  $scope.closeLogin = function() {
    $scope.modal.hide();
  };

  // Open the login modal
  $scope.login = function() {
    $scope.modal.show();
  };

  // Perform the login action when the user submits the login form
  $scope.doLogin = function() {
    console.log('Doing login', $scope.loginData);
	$http.post('https://demo0923422.mockable.io/api/artist/login').success( function(data) {
               $scope.loginData = data;
			   console.log('Response Data from login', $scope.loginData);
			   $window.location.href = '#/app/welcome';
            });

    // Simulate a login delay. Remove this and replace with your login
    // code if using a login system
    $timeout(function() {
      $scope.closeLogin();
    }, 500);
  };
})


.controller('AppCtrl', function($scope, $ionicModal,$http,$timeout,$window,$location) {

  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //$scope.$on('$ionicView.enter', function(e) {
  //});

  // Form data for the login modal
 
  //$scope.eventData = {};	
  
  // Create the login modal that we will use later
  $ionicModal.fromTemplateUrl('templates/login.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });

  
  // Triggered in the login modal to close it
  $scope.closeLogin = function() {
    $scope.modal.hide();
  };

  // Open the login modal
  $scope.login = function() {
    $scope.modal.show();
  };

  // Perform the login action when the user submits the login form
  $scope.doLogin = function() {
    console.log('Doing login', $scope.loginData);
	$http.post('https://demo0923422.mockable.io/api/artist/login').success( function(data) {
               $scope.loginData = data;
			   console.log('Response Data from login', $scope.loginData);
			   
            });

    // Simulate a login delay. Remove this and replace with your login
    // code if using a login system
    $timeout(function() {
      $scope.closeLogin();
    }, 500);
  };
})


.controller('LogoutCtrl', function($scope,$http,$window) {
		
	$scope.logout = function() {
    $http.post('https://demo0923422.mockable.io/api/artist/logout').success( function(data) {
               $scope.playlists = data;
			   console.log('Logout Response Data', $scope.playlists);
			   $window.location.href = '#/app/welcome';
            });
    
  };
			
})

.controller('PopupCtrl', function($scope, $ionicModal, $timeout) {

   $scope.modalData = {};
   
  $ionicModal.fromTemplateUrl('templates/modal.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });

  
  $scope.closeModal = function() {
    $scope.modal.hide();
  };

  // Open the login modal
  $scope.modal = function() {
    $scope.modal.show();
  };

  // Perform the login action when the user submits the login form
  $scope.doModal = function() {
    console.log('Doing login', $scope.modalData);

    // Simulate a login delay. Remove this and replace with your login
    // code if using a login system
    $timeout(function() {
      $scope.closeModal();
    }, 500);
  };

})


.controller('EventCtrl', function($scope, $ionicModal,$http,$timeout,$window,$location) {

	
	//console.log('value=', $scope.eventData);
	//console.log('All Event', $scope.testValue);
	
  
  
  $ionicModal.fromTemplateUrl('templates/editevent.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });

  
  $scope.closeEvent = function() {
    $scope.modal.hide();
  };

  // Open the edit event modal
  $scope.modal = function() {
	$scope.testValue = "this is test value 5";
    $scope.modal.show();
  };


	$scope.allEvents = function() {
		/*$scope.testValue = "this is test value2";
		console.log('All Event', $scope.eventData);
		console.log('All Event', $scope.testValue);
		$http.get('https://demo0923422.mockable.io/api/artist/all_event').success( function(data) {
					$scope.eventData = data;
					$scope.testValue = "this is test value3";
					console.log('event message', $scope.eventData.message);
					console.log('All Event Response Data', $scope.eventData);
				});
		console.log('value=', $scope.eventData);
		console.log('All Event', $scope.testValue);
		$timeout(function() {
		  $scope.closeEvent();
		}, 500);*/
	  };
  
  // Perform the event action when the user submits this form
  $scope.doEvent = function() {
    console.log('Add Event', $scope.eventData);
	$http.post('https://demo0923422.mockable.io/artist_new/api/event').success( function(data) {
               $scope.eventData = data;
			   console.log('Event Response Data', $scope.eventData);
			   
            });
    
    $timeout(function() {
      $scope.closeEvent();
    }, 500);
  };

    $scope.eventDelete = function() {
    console.log('Delete Event', $scope.eventData);
	$http.delete('https://demo0923422.mockable.io/artist_new/api/event/1/event_1').success( function(data) {
               $scope.eventData = data;
			   console.log('Event Delete Response Data', $scope.eventData);
			   
            });
   
    $timeout(function() {
      $scope.closeEvent();
    }, 500);
  };
})

.controller('BlogCtrl', function($scope, $ionicModal,$http, $timeout) {

   $scope.blogData = {};
   
  $ionicModal.fromTemplateUrl('templates/editblog.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });

  
  $scope.closeBlog = function() {
    $scope.modal.hide();
  };

  // Open the blog modal
  $scope.modal = function() {
    $scope.modal.show();
  };

  $scope.allBlogs = function() {
    console.log('All Blog', $scope.blogData);
	$http.get('https://demo0923422.mockable.io/artist_new/api/blog').success( function(data) {
               $scope.blogData = data;
			   console.log('All Blog Response Data', $scope.blogData);
			   
            });
   
    $timeout(function() {
      $scope.closeBlog();
    }, 500);
  };
  $scope.blogDelete = function() {
    console.log('Delete Blog', $scope.blogData);
	$http.delete('https://demo0923422.mockable.io/artist_new/api/blog/blog_1').success( function(data) {
               $scope.blogData = data;
			   console.log('Blog Delete Response Data', $scope.blogData);
			   
            });
   
    $timeout(function() {
      $scope.closeBlog();
    }, 500);
  };
  // Perform the blog action when the user submits this form
  $scope.doBlog = function() {
    console.log('Add Blog', $scope.blogData);
	$http.post('https://demo0923422.mockable.io/artist_new/api/blog').success( function(data) {
               $scope.blogData = data;
			   console.log('Blog Response Data', $scope.blogData);
			   
            });
    
    $timeout(function() {
      $scope.closeBlog();
    }, 500);
  };

})


.controller('MediaCtrl', function($scope,$http,$location,$window) {
	
	$scope.musiclists = function() {
    $http.get('https://demo3557586.mockable.io/artist_new/api/song').success( function(data) {
               $scope.musicData = data;
			   console.log('Music Response Data', $scope.musicData);
			  $window.location.href = '#/app/musiclists';
            });
    
  };
  $scope.imagelists = function() {
    $http.get('https://demo3557586.mockable.io/artist_new/api/image').success( function(data) {
               $scope.imageData = data;
			  
			   console.log('Image Response Data', $scope.imageData);
			  $window.location.href = '#/app/imagelists';
            });
    
  };
  $scope.videolists = function() {
    $http.get('https://demo3557586.mockable.io/artist_new/api/video').success( function(data) {
               $scope.videoData = data;
			   console.log('Video Response Data', $scope.videoData);
			  $window.location.href = '#/app/videolists';
            });
    
  };
			
})


.controller("ExampleController", function($scope, $cordovaSocialSharing) {
 
    $scope.shareAnywhere = function() {
        $cordovaSocialSharing.share("This is your message", "This is your subject", "www/imagefile.png", "https://www.thepolyglotdeveloper.com");
    }
 
    $scope.shareViaTwitter = function(message, image, link) {
        $cordovaSocialSharing.canShareVia("twitter", message, image, link).then(function(result) {
            $cordovaSocialSharing.shareViaTwitter(message, image, link);
        }, function(error) {
            alert("Cannot share on Twitter");
        });
    }
 
})




.controller('PlaylistsCtrl', function($scope,$http) {
	$scope.playlists={};
   $http.post('https://demo0923422.mockable.io/api/artist/login').success( function(data) {
               $scope.playlists = data;
			   console.log('Response Data', $scope.playlists);
            });
			
})


.controller('AccordionDemoCtrl', function ($scope) {
  $scope.oneAtATime = true;

  $scope.groups = [
    {
      title: 'Dynamic Group Header - 1',
      content: 'Dynamic Group Body - 1'
    },
    {
      title: 'Dynamic Group Header - 2',
      content: 'Dynamic Group Body - 2'
    }
  ];

  $scope.items = ['Item 1', 'Item 2', 'Item 3'];

  $scope.addItem = function() {
    var newItemNo = $scope.items.length + 1;
    $scope.items.push('Item ' + newItemNo);
  };

  $scope.status = {
    isCustomHeaderOpen: false,
    isFirstOpen: true,
    isFirstDisabled: false
  };
})

.controller('BlogController', ['$http',function($scope,$http){
    var type = this;
	    type.blogs = [
         {"name": "Shrikant","Date":"November 05, 1955","Description": "This is a basic Card which contains an item that has wrapping text."},
         {"name": "Aniket","Date":"October 07, 1965","Description": "This is a basic Card which contains an item that has wrapping text."},
         {"name": "Shrey","Date":"December 10, 1984","Description": "This is a basic Card which contains an item that has wrapping text."}
        
    ];
}])
  .controller("BlogPanelController", function() {
    this.tab = 1;
    this.blog;
    this.selectTab = function(setTab, blog) {
        this.tab = setTab;
        this.blog = blog;
    };
    this.isSelected = function(checkTab) {
        return this.tab === checkTab;   
    }
})

   
.controller('PlaylistCtrl', function($scope, $stateParams) {

});



