<?php

namespace hifi\Modules;

use hifi\Providers\Module, hifi\Providers\Component, PedroBorges\MetaTags\MetaTags, hifi\Core, RuntimeException;

class Frontend extends Module
{

	private array $templates;
	private array $layouts;
	private array $stylesheets;

	public function __construct()
	{
		/**
		 * Here we will register the templates avaliable on this module, those templates will be imported when the route matches this module
		 */
		$this->registerTemplates(__DIR__ . '/templates/', 'global');
		$this->registerTemplates(__DIR__ . '/pages/home/', 'home');
		$this->registerTemplates(__DIR__ . '/pages/auth/', 'auth');

		/**
		 * Here we will register the avaliable controllers for this module
		 */
		$this->registerController([
			'path' => __DIR__ . '/pages/home/Home.php',
			'class' => 'hifi\Frontend\home',
			'name' => 'home'
		]);
		$this->registerController([
			'path' => __DIR__ . '/pages/auth/Login.php',
			'class' => 'hifi\Frontend\login',
			'name' => 'login'
		]);
		$this->registerController([
			'path' => __DIR__ . '/pages/auth/Register.php',
			'class' => 'hifi\Frontend\register',
			'name' => 'register'
		]);

		$this->registerStylesheet('/dist/frontend/assets/scss/theme.css');

		/* Also we need to register the layouts */
		$this->registerLayout(__DIR__ . '/layouts/default.php', 'default');
	}

	public function getLayouts(): array
	{
		return $this->layouts;
	}

	public function getTemplates(): array
	{
		return $this->templates;
	}

	public function registerStylesheet(string $path, string $name = null): void
	{
		if (!$name) {
			$name = basename($path);
		}

		$this->stylesheets[$name] = $path;
	}

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

	public function registerLayout($path, $name): void
	{
		if (isset($this->layouts[$name])) {
			throw new RuntimeException("Can not redeclare layout '{$name}'");
		}
		$this->layouts[$name] = $path;
	}

	/**
	 * This method imports a layout with a given name previously registered.
	 * @return void
	 */
	public function loadLayout($name): void
	{
		if (!isset($this->layouts[$name])) {
			throw new RuntimeException("Layout '{$name}' not found");
		}
		require_once $this->layouts[$name];
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
	public function render(Component $component): void
	{
		// Load and render the template
		$this->loadTemplates();
		$template = $this->twig->load($component->getTemplate());
		$this->content = $template->render($component->getData());

		// Load and render meta tags
		$this->tags = new MetaTags;
		$this->tags->title($component->getDataKeyifExists('title'));

		foreach ($component->getDataKeyifExists('metas') as $name => $value) {
			$this->tags->meta($name, $value);
		}

		foreach ($component->getDataKeyifExists('og_metas') as $name => $value) {
			$this->tags->og($name, $value);
		}

		foreach ($this->stylesheets as $value) {
			$this->tags->link("stylesheet", $value);
		}

		$this->tags->link('canonical', $this->getCanonicalUrl());

		$this->loadLayout($component->getLayout());
	}

	// This method generates the canonical url from the $_SERVER superglobal and the core->router->match["route]
	private function getCanonicalUrl()
	{
		$core = Core::getInstance();
		$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$url = str_replace('/' . $core->router->generate($core->router->match['name']), '', $url);
		return $url;
	}
}
