<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//user routes
$route['api/v1/user/login']  = 'user/login';
$route['api/v1/user/logout'] = 'user/logout';


$route['api/v1/user/register'] = 'user/register';
$route['api/v1/user/member'] = 'user/member';


$route['api/v1/user/edit/(:any)'] = 'user/index/edit/$1';
$route['api/v1/user/delete/(:any)'] = 'user/index/delete/$1';
$route['api/v1/user/(:any)'] = 'user/index/$1';
$route['api/v1/user'] = 'user';

//blog routes
$route['api/v1/blog/edit/(:any)'] = 'blog/edit/$1';
$route['api/v1/blog/delete/(:any)'] = 'blog/delete/$1';
$route['api/v1/blog/(:any)'] = 'blog/index/$1';
$route['api/v1/blog'] = 'blog';

//event routes
$route['api/v1/event/edit/(:any)'] = 'event/edit/$1';
$route['api/v1/event/delete/(:any)'] = 'event/delete/$1';
$route['api/v1/event/(:any)'] = 'event/index/$1';
$route['api/v1/event'] = 'event';

//song routes
$route['api/v1/song/edit/(:any)'] = 'song/edit/$1';
$route['api/v1/song/delete/(:any)'] = 'song/delete/$1';
$route['api/v1/song/(:any)'] = 'song/index/$1';
$route['api/v1/song'] = 'song';

//video routes
$route['api/v1/video/edit/(:any)'] = 'video/edit/$1';
$route['api/v1/video/delete/(:any)'] = 'video/delete/$1';
$route['api/v1/video/(:any)'] = 'video/index/$1';
$route['api/v1/video'] = 'video';

//image routes
$route['api/v1/image/uploadCameraPic'] = 'image/uploadCameraPic';
$route['api/v1/image/edit/(:any)'] = 'image/edit/$1';
$route['api/v1/image/delete/(:any)'] = 'image/delete/$1';
$route['api/v1/image/(:any)'] = 'image/index/$1';
$route['api/v1/image'] = 'image';

//fb api routs
$route['api/v1/fb'] = 'fb';
$route['api/v1/fb/fb_register'] = 'fb/fb_register';