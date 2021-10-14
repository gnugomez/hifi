<?php
global $core;
$core = new Core();

class Core
{
	public View $actualView;

	public function renderView(): void
	{
		echo $this->actualView->render();
	}

	public function renderTemplate(string $templateName): void
	{
		ob_start();
		require __DIR__ . "/templates/$templateName.php";
		echo ob_get_clean();
	}

	public function setView(View $view): void
	{
		$this->actualView = $view;
	}

	public function getView(): View
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

abstract class View implements IView
{
	public function __construct()
	{
		global $core;
		$core->setView($this);
	}

	public static function init()
	{
		return new static();
	}
}

interface IView
{
	public function render(): string;
}
