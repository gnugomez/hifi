<?php
$router = new AltoRouter();

$router->map('GET', '/', "Home::init", "home");

// Comprobamos si las ruats estan registradas si no devoldemos una cabecera 404
$match = $router->match();
if (is_array($match) && is_callable($match['target'])) {
	call_user_func_array($match['target'], $match['params']);
	global $core;
	$core->render("default");
} else {
	// no route was matched
	header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
