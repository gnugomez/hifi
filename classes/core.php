<?php
require_once __DIR__ . '/../views/viewController.php';
//INCLUDING DB CONNECTION
require_once __DIR__ . '/database.php';
//INCLUDING SESSION MANAGER
require_once __DIR__ . '/session.php';
//INCLUDING API CALLBACKS
require_once __DIR__ . '/../services/content.service.php';

class core extends AltoRouter
{

	public viewController $actualView;

	public string $requestMethod;

	public session $session;

	public $match;

	private static core $instance;

	public static function getInstance($routes = null)
	{
		if (!isset(self::$instance)) {
			self::$instance = new self($routes);
		}

		return self::$instance;
	}

	/**
	 * Function that render the specified route if it have a valid callback, 
	 * and instances the db conection and the contentAPI.
	 *	
	 * @param content $api
	 * @return void
	 */
	public function mountApp()
	{
		$this->requestMethod = $_SERVER['REQUEST_METHOD'];

		$this->match = $this->match();

		$this->loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates/');
		$this->twig = new \Twig\Environment($this->loader, ['debug' => true]);

		if (is_array($this->match) && is_callable($this->match['target'])) {

			if ($this->match['middleware']) {
				call_user_func_array($this->match['middleware'], array($this));
			}

			call_user_func_array($this->match['target'], $this->match['params']);

			$this->render("default");
		} else {
			// no route was matched
			header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
		}
	}

	public function map($method, $route, $target, $name = null, $middleware = null)
	{

		$this->routes[] = [$method, $route, $target, $name, $middleware];

		if ($name) {
			if (isset($this->namedRoutes[$name])) {
				throw new RuntimeException("Can not redeclare route '{$name}'");
			}
			$this->namedRoutes[$name] = $route;
		}

		return;
	}

	public function renderView(): void
	{
		$this->actualView->beforeMount();
		echo $this->actualView->render();
		$this->actualView->mounted();
	}

	public function renderTemplate(string $templateName): void
	{
		ob_start();
		require __DIR__ . "/../templates/$templateName.php";
		echo ob_get_clean();
	}

	public function setView(viewController $view): void
	{
		$this->actualView = $view;
	}

	public function getView(): viewController
	{
		return $this->actualView;
	}

	public function setUpSessions()
	{
		$this->session = session::getInstance();

		$next = $this->match();

		$this->session->next = $next;
	}

	public function setUpLastRoute()
	{
		$this->session->prev = $this->match();
	}

	public function getPrevRoute(): string
	{
		return $this->session->prev ? $this->session->prev["name"] : "home";
	}
	public function getNextRoute(): string
	{
		return $this->session->next ? $this->session->next["name"] : "home";
	}

	public function render(string $layoutName): void
	{

		ob_start();
		require __DIR__ . "/../layouts/$layoutName.php";
		echo ob_get_clean();
		$this->setUpLastRoute();
	}

	public function routerPush(string $route)
	{
		header("Location: " . $this->generate($route), false);
		die();
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
			list($methods, $route, $target, $name, $middleware) = $handler;

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
					'target' => $target,
					'params' => $params,
					'name' => $name,
					'middleware' => $middleware
				];
			}
		}

		return false;
	}
}
