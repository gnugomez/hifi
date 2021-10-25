<?php
require_once __DIR__ . '/../views/viewController.php';
// importing views controllers
importFromDir("/../views/home/controllers");
importFromDir("/../views/auth/controllers");

$routes = array(
	array('GET|POST', '/', "home::init", "home"),
	array('GET', '/logout', "session::logout", "logout"),
	array('GET|POST', '/auth/login', "login::init", "login", "middleware::noUser"),
	array('GET|POST', '/auth/register', "register::init", "register", "middleware::noUser")
);
