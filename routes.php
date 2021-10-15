<?php
require_once __DIR__ . '/views/view.php';
// importing views controllers
importFromDir("/views/home/controllers");
importFromDir("/views/auth/controllers");

$routes = array(
	array('GET|POST', '/', "home::init", "home"),
	array('GET|POST', '/auth/login', "login::init", "login"),
	array('GET|POST', '/auth', "login::redirect", "authRedirect"),
	array('GET|POST', '/auth/register', "register::init", "register")
);
