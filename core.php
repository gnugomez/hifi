<?php
require_once __DIR__ . "/routes.php";
require_once __DIR__ . '/views/view.php';

class core extends AltoRouter
{
	public view $actualView;

	public string $requestMethod;

	public function mountApp()
	{
		$this->requestMethod = $_SERVER['REQUEST_METHOD'];

		$match = $this->match();

		if (is_array($match) && is_callable($match['target'])) {
			call_user_func_array($match['target'], $match['params']);
			$this->render("default");
		} else {
			// no route was matched
			header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
		}
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
		require __DIR__ . "/templates/$templateName.php";
		echo ob_get_clean();
	}

	public function setView(view $view): void
	{
		$this->actualView = $view;
	}

	public function getView(): view
	{
		return $this->actualView;
	}

	public function render(string $layoutName): void
	{
		ob_start();
		require __DIR__ . "/layouts/$layoutName.php";
		echo ob_get_clean();
	}
}

$core = new core($routes);
$core->mountApp();
