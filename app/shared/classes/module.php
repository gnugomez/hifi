<?php

namespace App;

use RuntimeException;

abstract class Module
{

	private array $templates;
	private array $controllers;
	private static Module $instance;

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new static;
		}

		return self::$instance;
	}

	public function registerTemplates($path, $name)
	{
		if (isset($this->templates[$name])) {
			throw new RuntimeException("Can not redeclare template '{$name}'");
		}
		$this->templates[$name] = $path;
	}


	public function registerController($args)
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

	public function getTemplates(): array
	{
		return $this->templates;
	}

	public function getControllers(): array
	{
		return $this->controllers;
	}

	public function loadController($name)
	{
		require_once $this->controllers[$name]['path'];
	}

	public function loadTemplates()
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
}
