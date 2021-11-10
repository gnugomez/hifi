<?php
require_once __DIR__ . '/load.php';

use JGomez\Core, JGomez\Router;

try {
	/**
	 * Instance core class to get all the full stack functionality
	 */
	$core = Core::getInstance();

	$core->registerModulesNamespace("JGomez\Modules\\");

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
		'middleware' => 'JGomez\Middlewares::noUser'
	]);

	$router->add([
		'methods' => 'GET|POST',
		'route' => '/register',
		'module' => 'Frontend',
		'controller' => 'register',
		'name' => 'register',
		'middleware' => 'JGomez\Middlewares::noUser'
	]);

	$router->add([
		'methods' => 'GET|POST',
		'route' => '/logout',
		'action' => 'JGomez\Session::logout'
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
	$core->mountApp();
} catch (PDOException | Exception $e) {
	echo $e;
}
