<?php

namespace JGomez\Providers;

use RuntimeException, JGomez\Providers\Component;

abstract class Module implements IModule
{
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

	public function getControllers(): array
	{
		return $this->controllers;
	}

	/** end of getters */

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
}

interface IModule
{
	/**
	 * This method outputs the render of a given controller.
	 * @param Component $component
	 */
	public function render(Component $component): void;
}
