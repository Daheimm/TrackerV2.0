<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes


session_start();
if (array_key_exists('user', $_SESSION)) {
    $router->add('', ['controller' => 'Home', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/logout', ['controller' => 'Logout', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/statistics', ['controller' => 'Statistics', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/profile', ['controller' => 'profile', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/integration', ['controller' => 'integration', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/blacklist', ['controller' => 'blacklist', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/clients', ['controller' => 'clients', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/promo', ['controller' => 'promo', 'action' => 'index']);
    $router->add('{controller}/{action}');


    $router->add('/createCompany', ['controller' => 'createCompany', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/deleteCompany', ['controller' => 'deleteCompany', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/download', ['controller' => 'dowloand', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/statistics/listcompany', ['controller' => 'statistics', 'action' => 'listCompany']);
    $router->add('{controller}/{action}');

    $router->add('/statistics', ['controller' => 'statistics', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/statistics/diagramTraffic', ['controller' => 'statistics', 'action' => 'diagramTraffic']);
    $router->add('{controller}/{action}');

    $router->add('/statistics/listTraffic', ['controller' => 'statistics', 'action' => 'listTraffic']);
    $router->add('{controller}/{action}');

    $router->add('/statistics/datatable', ['controller' => 'statistics', 'action' => 'datatable']);
    $router->add('{controller}/{action}');

    $router->add('/statistics/targetandbot', ['controller' => 'statistics', 'action' => 'ClickTargetandBot']);
    $router->add('{controller}/{action}');

    $router->add('/editCompany/sendDataEditCompany', ['controller' => 'Editcompany', 'action' => 'sendDataEditCompany']);
    $router->add('{controller}/{action}');

    $router->add('/editCompany/getDataEditCompany', ['controller' => 'Editcompany', 'action' => 'getDataSelectedClient']);
    $router->add('{controller}/{action}');

    $router->add('/editcompany', ['controller' => 'editcompany', 'action' => 'index']);
    $router->add('{controller}/{action}');

    $router->add('/activation', ['controller' => 'activationAccount', 'action' => 'activation']);
    $router->add('{controller}/{action}');


} else if ($_SERVER['QUERY_STRING'] == '/auth/check') {

    $router->add('/auth/check', ['controller' => 'auth', 'action' => 'show']);
    $router->add('{controller}/{action}');

} else if ($_SERVER['QUERY_STRING'] == '/registration') {

    $router->add('/registration', ['controller' => 'registration', 'action' => 'index']);
    $router->add('{controller}/{action}');

} else if ($_SERVER['QUERY_STRING'] == '/registration/check') {

    $router->add('/registration/check', ['controller' => 'registration', 'action' => 'check']);
    $router->add('{controller}/{action}');

} else {

    $router->add('/auth', ['controller' => 'auth', 'action' => 'index']);
    $router->add('{controller}/{action}');
    $_SERVER['QUERY_STRING'] .= '/auth';
}


$router->dispatch($_SERVER['QUERY_STRING']);
