<?php
require_once __DIR__ . '/load.php';

use hifi\Core, hifi\Router;

try {
	/**
	 * Instance core class to get all the full stack functionality
	 */
	$core = Core::getInstance();

	$core->registerModulesNamespace("hifi\Modules\\");

	$core->registerModule(__DIR__ . "/../apps/frontend/Module.php", "Frontend");
	/**
	 * Instance router and register
	 */
	$router = new Router();

	$router->add([
		'methods' => 'GET',
		'route' => '/',
		'module' => 'Frontend',
		'controller' => 'home',
		'name' => 'home'
	]);

	$router->add([
		'methods' => 'GET|POST',
		'route' => '/login',
		'module' => 'Frontend',
		'controller' => 'login',
		'name' => 'login',
		'middleware' => 'hifi\Middlewares::noUser'
	]);

	$router->add([
		'methods' => 'GET|POST',
		'route' => '/register',
		'module' => 'Frontend',
		'controller' => 'register',
		'name' => 'register',
		'middleware' => 'hifi\Middlewares::noUser'
	]);

	$router->add([
		'methods' => 'GET|POST',
		'route' => '/logout',
		'action' => 'hifi\Session::logout'
	]);

	/**
	 * Registering routes
	 */
	$core->setUpRouter($router);

	/**
	 * Start the session the first time some component request the session service
	 */
	$core->setUpSessions();

	/**
	 * Handle the request
	 */
	$core->mounthifi();
} catch (PDOException $e) {
	echo $e;
} catch (Exception $e) {
	echo $e;
}
