// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
var baseUrl = "http://162.243.170.15/artist_app/api/v1";
//var baseUrl = "http://192.168.1.120/artist_app/api/v1";
angular.module('starter', ['ionic', 'ngCordova', 'ngStorage', 'starter.commonControllers', 'starter.loginControllers', 'starter.eventControllers', 'starter.blogControllers', 'starter.musicControllers', 'starter.imageControllers', 'starter.videoControllers','starter.logoutControllers'])

    .run(function ($ionicPlatform) {
        $ionicPlatform.ready(function () {
            // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
            // for form inputs)
            if (window.cordova && window.cordova.plugins.Keyboard) {
                cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
                cordova.plugins.Keyboard.disableScroll(true);

            }
            if (window.StatusBar) {
                // org.apache.cordova.statusbar required
                StatusBar.styleDefault();
            }
        });
    })

    .config(function ($stateProvider, $urlRouterProvider) {
        $stateProvider

                .state('app', {
                    url: '/app',
                    abstract: true,
                    templateUrl: 'templates/menu.html',
                    controller: 'LoginCtrl'
                })



                .state('app.login', {
                    url: '/login',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/login.html'
							
                        }
                    }
                })

                .state('app.welcome', {
                    url: '/welcome',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/welcome.html',
                        }
                    }
                })

                .state('app.profile', {
                    url: '/profile',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/profile.html'
                        }
                    }
                })

                .state('app.editprofile', {
                    url: '/editprofile',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/editprofile.html'
                        }
                    }
                })

                .state('app.editevent', {
                    url: '/event/edit/:id',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/editevent.html',
                            controller: "EventCtrl"
                        }
                    }
                })

                .state('app.addEvent', {
                    url: '/event/add',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/addEvent.html',
                            controller: "EventCtrl"
                        }
                    }
                })

                .state('app.viewevent', {
                    url: '/event/view/:id',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/viewevent.html',
                            controller: "EventCtrl"
                        }
                    }
                })

                .state('app.events', {
                    url: '/events',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/events.html',
                            controller: "EventCtrl"
                        }
                    }
                })


                .state('app.blogs', {
                    url: '/blogs',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/blogs.html',
                            controller: 'BlogCtrl'
                        }
                    }
                })

                .state('app.addBlog', {
                    url: '/blog/add',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/addBlog.html',
                            controller: 'BlogCtrl'

                        }
                    }
                })


                .state('app.editblog', {
                    url: '/blog/edit/:id',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/editblog.html',
                            controller: "BlogCtrl"
                        }
                    }
                })

                .state('app.viewblog', {
                    url: '/blog/view/:id',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/viewblog.html',
                            controller: "BlogCtrl"
                        }
                    }
                })

                /*.state('app.medialists', {
                 url: '/medialists',
                 views: {
                 'menuContent': {
                 templateUrl: 'templates/list.html',

                 }
                 }
                 })*/

                .state('app.imagelists', {
                    url: '/imagelists',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/imagelists.html',
                            controller: "ImageCtrl"
                        }
                    }
                })

                .state('app.editimage', {
                    url: '/image/edit/:id',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/editimage.html',
                            controller: "ImageCtrl"
                        }
                    }
                })

                .state('app.addImage', {
                    url: '/image/add',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/addImage.html',
                            controller: "ImageCtrl"
                        }
                    }
                })

                .state('app.musiclists', {
                    url: '/musiclists',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/musiclists.html',
                            controller: 'MusicCtrl'

                        }
                    }
                })

                .state('app.editmusic', {
                    url: '/music/edit/:id',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/editmusic.html',
                            controller: "MusicCtrl"
                        }
                    }
                })

                .state('app.addMusic', {
                    url: '/music/add',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/addMusic.html',
                            controller: "MusicCtrl"
                        }
                    }
                })

                .state('app.videolists', {
                    url: '/videolists',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/videolists.html',
                            controller: "VideoCtrl"
                        }
                    }
                })

                .state('app.editvideo', {
                    url: '/video/edit/:id',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/editvideo.html',
                            controller: "VideoCtrl"
                        }
                    }
                })

				.state('app.memberlist', {
                        url: '/memberlist',
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/memberlist.html'
								
                            }
                        }
                })
                .state('app.addVideo', {
                    url: '/video/add',
                    views: {
                        'menuContent': {
                            templateUrl: 'templates/addVideo.html',
                            controller: "VideoCtrl"
                        }
                    }
                })

				
        // if none of the above states are matched, use this as the fallback
        $urlRouterProvider.otherwise('/app/login');
    });
