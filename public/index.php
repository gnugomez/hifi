<?php
require_once __DIR__ . '/load.php';

use App\Core, App\Router;

try {
	/**
	 * Instance core class to get all the full stack functionality
	 */
	$core = Core::getInstance();

	$core->registerModulesNamespace("App\Modules\\");

	$core->registerModule(__DIR__ . "/../app/Frontend/Module.php", "Frontend");
	/**
	 * Instance router and register
	 */
	$router = Router::getInstance();

	$router->add([
		'methods' => 'GET',
		'route' => '/',
		'module' => 'Frontend',
		'controller' => 'home',
		'name' => 'home'
	]);

	$router->add([
		'methods' => 'GET|POST',
		'route' => '/auth/login',
		'module' => 'Frontend',
		'controller' => 'login',
		'name' => 'login',
		'middleware' => 'App\Middlewares::noUser'
	]);

	$router->add([
		'methods' => 'GET|POST',
		'route' => '/auth/register',
		'module' => 'Frontend',
		'controller' => 'register',
		'name' => 'register',
		'middleware' => 'App\Middlewares::noUser'
	]);

	$router->add([
		'methods' => 'GET|POST',
		'route' => '/logout',
		'action' => 'App\Session::logout'
	]);


	/* 	['GET|POST', '/', "home::init", "home"],
	['GET', '/logout', "App\Session::logout", "logout"],
	['GET|POST', '/auth/login', "login::init", "login", "App\Middlewares::noUser"],
	['GET|POST', '/auth/register', "register::init", "register", "App\Middlewares::noUser"] */

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
} catch (PDOException $e) {
	echo $e;
} catch (Exception $e) {
	echo $e;
}
