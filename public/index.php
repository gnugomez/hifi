<?php
require_once __DIR__ . '/load.php';

use App\Core;

// importing views controllers
importFromDir(__DIR__ . "/../app/frontend/views/home/controllers");
importFromDir(__DIR__ . "/../app/frontend/views/auth/controllers");

try {
	/**
	 * Instance core class to get all the full stack functionality
	 */
	$core = Core::getInstance();

	/**
	 * Registering routes
	 */
	$core->map('GET|POST', '/', "home::init", "home");
	$core->map('GET', '/logout', "App\Session::logout", "logout");
	$core->map('GET|POST', '/auth/login', "login::init", "login", "App\Middlewares::noUser");
	$core->map('GET|POST', '/auth/register', "register::init", "register", "App\Middlewares::noUser");

	/**
	 * Start the session the first time some component request the session service
	 */
	$core->setUpSessions();

	/**
	 * Handle the request
	 */
	$core->mountApp();
} catch (PDOException $e) {
	echo $e->getMessage();
} catch (Exception $e) {
	echo $e->getMessage();
}
