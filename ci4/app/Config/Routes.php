<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Dashboard::index');
$routes->get('/admin/dashboard', 'Dashboard::index');
$routes->group('admin', static function ($routes) {
    $routes->get('/admin/dashboard', 'Dashboard::index');
    $routes->get('violations/(:any)', 'Violations::$1');
    $routes->get('violations/(:any)/(:any)', 'Violations::$1/$2');
    $routes->get('violations/(:any)/(:any)/(:any)', 'Violations::$1/$2/$3');

    $routes->post('violations/(:any)', 'Violations::$1');
    $routes->post('violations/(:any)/(:any)', 'Violations::$1/$2');
    $routes->post('violations/(:any)/(:any)/(:any)', 'Violations::$1/$2/$3');


    $routes->get('mmc', 'Mmc::index');
    $routes->get('mmc/(:any)', 'Mmc::$1');
    $routes->get('mmc/(:any)/(:any)', 'Mmc::$1/$2');
    $routes->get('mmc/(:any)/(:any)/(:any)', 'Mmc::$1/$2/$3');
    $routes->post('mmc/(:any)', 'Mmc::$1');
    $routes->post('mmc/(:any)/(:any)', 'Mmc::$1/$2');


    $routes->get('plates', 'Plates::index');
    $routes->get('plates/(:any)', 'Plates::$1');
    $routes->get('plates/(:any)/(:any)', 'Plates::$1/$2');
    $routes->get('plates/(:any)/(:any)/(:any)', 'Plates::$1/$2/$3');
    $routes->post('plates/(:any)', 'Plates::$1');
    $routes->post('plates/(:any)/(:any)', 'Plates::$1/$2');

    $routes->get('villages', 'Villages::index');
    $routes->get('villages/(:any)', 'Villages::$1');
    $routes->get('villages/(:any)/(:any)', 'Villages::$1/$2');
    $routes->get('villages/(:any)/(:any)/(:any)', 'Villages::$1/$2/$3');
    $routes->post('villages/(:any)', 'Villages::$1');
    $routes->post('villages/(:any)/(:any)', 'Villages::$1/$2');

    $routes->get('cameras', 'Cameras::index');
    $routes->get('cameras/(:any)', 'Cameras::$1');
    $routes->get('cameras/(:any)/(:any)', 'Cameras::$1/$2');
    $routes->get('cameras/(:any)/(:any)/(:any)', 'Cameras::$1/$2/$3');
    $routes->post('cameras/(:any)', 'Cameras::$1');
    $routes->post('cameras/(:any)/(:any)', 'Cameras::$1/$2');

    $routes->get('users', 'Users::index');
    $routes->get('users/(:any)', 'Users::$1');
    $routes->get('users/(:any)/(:any)', 'Users::$1/$2');
    $routes->get('users/(:any)/(:any)/(:any)', 'Users::$1/$2/$3');
    $routes->post('users/(:any)', 'Users::$1');
    $routes->post('users/(:any)/(:any)', 'Users::$1/$2');

    $routes->get('group', 'Group::index');
    $routes->get('group/(:any)', 'Group::$1');
    $routes->get('group/(:any)/(:any)', 'Group::$1/$2');
    $routes->get('group/(:any)/(:any)/(:any)', 'Group::$1/$2/$3');
    $routes->post('group/(:any)', 'Group::$1');
    $routes->post('group/(:any)/(:any)', 'Group::$1/$2');

    $routes->get('profile', 'Profile::index');
    $routes->get('profile/(:any)', 'Profile::$1');
    $routes->get('profile/(:any)/(:any)', 'Profile::$1/$2');
    $routes->get('profile/(:any)/(:any)/(:any)', 'Profile::$1/$2/$3');
    $routes->post('profile/(:any)', 'Profile::$1');
    $routes->post('profile/(:any)/(:any)', 'Profile::$1/$2');
    

});

$routes->get('auth', 'Auth::index');
$routes->get('auth/(:any)', 'Auth::$1');
$routes->get('auth/(:any)/(:any)', 'Auth::$1/$2');
$routes->get('auth/(:any)/(:any)/(:any)', 'Auth::$1/$2/$3');
$routes->post('auth/(:any)', 'Auth::$1');
$routes->post('auth/(:any)/(:any)', 'Auth::$1/$2');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
