<?php
abstract class viewController implements IView
{
	public core $core;

	public array $data = array();

	public function __construct()
	{
		$this->auth = authService::getInstance();
		$this->core = core::getInstance();
		$this->core->setView($this);
		$this->data["isLoggedin"] = $this->auth->isloggedin();
	}

	public static function init()
	{
		return new static();
	}
	public function mounted(): void
	{
	}
	public function beforeMount(): void
	{
	}
}

interface IView
{
	public function render(): string;
}
