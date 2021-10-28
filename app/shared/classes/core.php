<?php

namespace App;

use App\Model\ViewModel;

use AltoRouter;
use RuntimeException;

class Core extends AltoRouter
{
	private static Core $instance;

	public ViewModel $actualView;

	public Session $session;

	public Router $router;

	private array $modules;

	private string $modulesNamespace;


	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function registerModule($path, $name)
	{
		$this->modules[$name] = $path;
	}

	public function registerModulesNamespace($namespace)
	{
		$this->modulesNamespace = $namespace;
	}

	/**
	 * Function that render the specified route if it have a valid callback, 
	 * and instances the db conection and the contentAPI.
	 *
	 * @return void
	 */
	public function mountApp()
	{

		$match = $this->router->match();

		if (is_array($match)) {


			if ($match['middleware']) {
				call_user_func_array($match['middleware'], array($this));
			}

			if (get_array_value($match, 'action', false)) {
				call_user_func_array($match['action'], $match['params']);
			} else if (!isset($this->modules[$match['module']])) {
				throw new RuntimeException("Module not found");
			} else {
				$this->loadModule($match['module']);
				$module = $this->modulesNamespace . $match['module'];
				$module = new $module;
				$controller = get_array_value($module->getControllers(), $match['controller'], false);

				if ($controller) {
					$module->loadController($match['controller']);
					$controller = new $controller['class']($match['params']);
					$this->handle($controller);
				}
			}
		} else {
			// no route was matched
			/* header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found'); */
		}
	}

	public function loadModule($name)
	{
		require_once $this->modules[$name];
	}

	public function setUpRouter(Router $router)
	{
		$this->router = $router;

		$this->router->initialize();
	}



	public function handle($view): void
	{
		$view->beforeMount();
		echo $view->render();
		$view->mounted();
		$this->setUpLastRoute();
	}

	public function setUpSessions()
	{
		$this->session = Session::getInstance();
		$next = $this->router->match();
		$this->session->next = $next;
	}


	public function setUpLastRoute()
	{
		$this->session->prev = $this->router->match();
	}

	public function getPrevRoute(): string
	{
		return $this->session->prev ? $this->session->prev["name"] : "home";
	}
	public function getNextRoute(): string
	{
		return $this->session->next ? $this->session->next["name"] : "home";
	}

	public function routerPush(string $route)
	{
		header("Location: " . $this->router->generate($route), false);
		die();
	}
}
