<?php

namespace JGomez;

use AltoRouter, RuntimeException;

class Router extends AltoRouter
{
	private static Router $instance;

	public string $requestMethod;

	public $match;

	/**
	 * This method is used to get the instance of the Router class.
	 * @return Router
	 */
	public static function getInstance($routes = [])
	{
		if (!isset(self::$instance)) {
			self::$instance = new self($routes);
		}

		return self::$instance;
	}

	/**
	 * This method registers the requested method and the matched route.
	 *
	 * @return void
	 */
	public function initialize(): void
	{
		$this->requestMethod = $_SERVER['REQUEST_METHOD'];

		$this->match = $this->match();
	}

	/**
	 * This method is used to redirect the user to a given route name.
	 * @param string $routeName
	 * @param array $params
	 */
	public function redirect(string $routeName, array $params = []): void
	{
		$url = $this->generate($routeName, $params);

		header("Location: $url");
		exit;
	}

	/**
	 * This method is used to map a new route with an array istead of separed parameters.
	 * @param string $newRoute
	 * Input example
	 * 	['methods' => 'GET',
	 *	'route' => '/',
	 *	'module' => 'frontend',
	 *	'controller' => 'home',
	 *	'name' => 'home']
	 */
	public function add(array $newRoute)
	{

		$methods = get_array_value($newRoute, "methods", null);
		$route = get_array_value($newRoute, "route", null);
		$module = get_array_value($newRoute, "module", null);
		$controller = get_array_value($newRoute, "controller", null);
		$name = get_array_value($newRoute, "name", null);
		$middleware = get_array_value($newRoute, "middleware", null);
		$action = get_array_value($newRoute, "action", null);

		if (!$methods || !$route) {
			throw new RuntimeException("Can not declare route without methods and route");
		}

		if (!$module || !$controller) {
			if (!$action) {
				throw new RuntimeException("Can not declare route without module and controller or action");
			}
		}

		$this->routes[] = [$methods, $route, $module, $controller, $name, $middleware, $action];

		if ($name) {
			if (isset($this->namedRoutes[$name])) {
				throw new RuntimeException("Can not redeclare route '{$name}'");
			}
			$this->namedRoutes[$name] = $route;
		}

		return;
	}

	/**
	 * Match a given Request Url against stored routes
	 * @param string $requestUrl
	 * @param string $requestMethod
	 * @return array|boolean Array with route information on success, false on failure (no match).
	 */
	public function match($requestUrl = null, $requestMethod = null)
	{

		$params = [];

		// set Request Url if it isn't passed as parameter
		if ($requestUrl === null) {
			$requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		}

		// strip base path from request url
		$requestUrl = substr($requestUrl, strlen($this->basePath));

		// Strip query string (?a=b) from Request Url
		if (($strpos = strpos($requestUrl, '?')) !== false) {
			$requestUrl = substr($requestUrl, 0, $strpos);
		}

		$lastRequestUrlChar = $requestUrl ? $requestUrl[strlen($requestUrl) - 1] : '';

		// set Request Method if it isn't passed as a parameter
		if ($requestMethod === null) {
			$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		}

		foreach ($this->routes as $handler) {
			list($methods, $route, $module, $controller, $name, $middleware, $action) = $handler;

			$method_match = (stripos($methods, $requestMethod) !== false);

			// Method did not match, continue to next route.
			if (!$method_match) {
				continue;
			}

			if ($route === '*') {
				// * wildcard (matches all)
				$match = true;
			} elseif (isset($route[0]) && $route[0] === '@') {
				// @ regex delimiter
				$pattern = '`' . substr($route, 1) . '`u';
				$match = preg_match($pattern, $requestUrl, $params) === 1;
			} elseif (($position = strpos($route, '[')) === false) {
				// No params in url, do string comparison
				$match = strcmp($requestUrl, $route) === 0;
			} else {
				// Compare longest non-param string with url before moving on to regex
				// Check if last character before param is a slash, because it could be optional if param is optional too (see https://github.com/dannyvankooten/AltoRouter/issues/241)
				if (strncmp($requestUrl, $route, $position) !== 0 && ($lastRequestUrlChar === '/' || $route[$position - 1] !== '/')) {
					continue;
				}

				$regex = $this->compileRoute($route);
				$match = preg_match($regex, $requestUrl, $params) === 1;
			}

			if ($match) {
				if ($params) {
					foreach ($params as $key => $value) {
						if (is_numeric($key)) {
							unset($params[$key]);
						}
					}
				}

				return [
					'action' => $action,
					'module' => $module,
					'controller' => $controller,
					'params' => $params,
					'name' => $name,
					'middleware' => $middleware
				];
			}
		}

		return false;
	}
}
