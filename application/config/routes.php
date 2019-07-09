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
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|   my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'rest';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8

$route['ticket/(:num)'] = 'ticket/find_get/id/$1'; 
$route['ticket/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'ticket/find_get/id/$1/format/$3$4';
$route['ticket/delete/(:num)'] = 'ticket/$1'; 
$route['ticket/delete/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'ticket/$1/format/$3$4';

$route['category/(:num)'] = 'category/find_get/id/$1'; 
$route['category/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'category/find_get/id/$1/format/$3$4'; 

$route['progress/(:num)'] = 'progress/find_get/id/$1'; 
$route['progress/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'progress/find_get/id/$1/format/$3$4'; 

$route['solution/(:num)'] = 'solution/find_get/id/$1'; 
$route['solution/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'solution/find_get/id/$1/format/$3$4'; 

$route['diagnose/(:num)'] = 'diagnose/find_get/id/$1'; 
$route['diagnose/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'diagnose/find_get/id/$1/format/$3$4'; 

$route['pic/(:num)'] = 'pic/find_get/id/$1'; 
$route['pic/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'pic/find_get/id/$1/format/$3$4';

$route['user/(:num)'] = 'user/find_get/id/$1'; 
$route['user/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'user/find_get/id/$1/format/$3$4';

$route['chat/(:num)'] = 'chat/find_get/id/$1'; 
$route['chat/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'chat/find_get/id/$1/format/$3$4';

$route['status/(:num)'] = 'status/find_get/id/$1'; 
$route['status/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'status/find_get/id/$1/format/$3$4';

$route['auth/(:any)'] = 'auth/find_get/id/$1'; 
$route['sample/(:num)'] = 'sample/find_get/id/$1'; 
$route['sample/delete/(:num)'] = 'sample/$1'; 
$route['sample/delete/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'sample/$1/format/$3$4';
