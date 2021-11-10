<?php

namespace JGomez;

use JGomez\Providers\Component, JGomez\Providers\Module, JGomez\Services\AuthService, JGomez\Router, RuntimeException;

class Core
{
	private static Core $instance;

	public Component $actualView;

	public Session $session;

	public Router $router;

	private array $modules;

	private string $modulesNamespace;


	public static function getInstance(): Core
    {
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function registerModule($path, $className)
	{
		$this->modules[$className] = $path;
	}

	public function registerModulesNamespace($namespace)
	{
		$this->modulesNamespace = $namespace;
	}

	public function loadModule($name)
	{
		require_once $this->modules[$name];
	}

	/**
	 * Function that render the specified route if it have a valid callback, 
	 * and instances the db conection and the contentAPI.
	 *
	 * @return void
	 */
	public function mountApp(): void
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
				$module = $module::getInstance();
				$controller = get_array_value($module->getControllers(), $match['controller'], false);

				if ($controller) {
					$module->loadController($match['controller']);
					$controller = new $controller['class']($match['params']);
					$this->handle($module, $controller);
				} else {
					throw new RuntimeException("Controller not found");
				}
			}
		} else {
			// no route was matched
			header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
		}
	}

	public function handle(Module $module, Component $controller): void
	{
		$module->render($controller);
		$this->setUpLastRoute();
	}

	public function setUpRouter(Router $router): void
	{
		$this->router = $router;

		$this->router->initialize();
	}

	public function getContext(): array
	{
		return ['isLogged' => AuthService::getInstance()->isloggedin()];
	}

	public function setUpSessions(): void
	{
		$this->session = Session::getInstance();
		$next = $this->router->match();
		$this->session->next = $next;
	}

	public function setUpLastRoute(): void
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
}
