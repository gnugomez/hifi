<?php

namespace App;

use RuntimeException, App\Providers\Component;

abstract class Module
{

	private array $templates;
	private array $controllers;
	private static Module $instance;

	/**
	 * This method returns the instance of the module.
	 *
	 * @return static
	 */
	public static function getInstance(): static
	{
		if (!isset(self::$instance)) {
			self::$instance = new static;
		}

		return self::$instance;
	}

	/** Getters */
	public function getTemplates(): array
	{
		return $this->templates;
	}

	public function getControllers(): array
	{
		return $this->controllers;
	}

	/** end of getters */

	/**
	 * This method adds the path of the template to the templates array.
	 * @return void
	 */
	public function registerTemplates($path, $name): void
	{
		if (isset($this->templates[$name])) {
			throw new RuntimeException("Can not redeclare template '{$name}'");
		}
		$this->templates[$name] = $path;
	}

	/**
	 * This method adds the the path class and name of a controller to the controllers array with a given array of params.
	 * 	 * Input example
	 * 	['path' => 'path/to/controller',
	 *	'class' => 'classname',
	 *	'name' => 'nameForTheController']
	 * @return void
	 */
	public function registerController($args): void
	{
		$path = get_array_value($args, "path", false);
		$class = get_array_value($args, "class", false);
		$name = get_array_value($args, "name", false);

		if (isset($this->controllers[$name])) {
			throw new RuntimeException("Can not redeclare controller '{$name}'");
		}

		if (!($path && $class && $name)) {
			throw new RuntimeException("In order to register a controller you need to provide an array with path, class and name");
		}

		$this->controllers[$name] = ["path" => $path, "class" => $class];
	}

	/**
	 * This method imports a controller with a given name previously registered.
	 * @return void
	 */
	public function loadController($name): void
	{
		require_once $this->controllers[$name]['path'];
	}

	/**
	 * This method instances twig and saves the instance in the module in order to use it inside controllers.
	 *
	 * @return void
	 */
	public function loadTemplates(): void
	{
		if (!isset($this->templates['global'])) {
			throw new RuntimeException("You need to declare a global templates namespace in order to load all other templates");
		}

		$this->loader = new \Twig\Loader\FilesystemLoader($this->templates['global'], 'global');
		$this->twig = new \Twig\Environment($this->loader, ['debug' => true]);

		foreach ($this->templates as $namespace => $path) {
			$this->loader->addPath($path, $namespace);
		}
	}

	/**
	 * This method outputs the render of a given controller.
	 * @param Component $component
	 */
	public function render(Component $component)
	{
		$this->loadTemplates();
		$template = $this->twig->load($component->getTemplate());
		echo $template->render($component->getData());
	}
}
