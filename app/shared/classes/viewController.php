<?php

namespace App\Model;

use App\Core;
use App\Services\AuthService;

abstract class ViewController implements IView
{
	public Core $core;

	public array $data = array();

	public function __construct()
	{
		$this->auth = AuthService::getInstance();
		$this->core = Core::getInstance();
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
