<?php

namespace App\Model;

use App\Core;
use App\Services\AuthService;

abstract class ViewModel implements IView
{
	public Core $core;

	public array $data = array();

	public function __construct()
	{
		$this->auth = AuthService::getInstance();
		$this->core = Core::getInstance();
		$this->data["isLoggedin"] = $this->auth->isloggedin();
		$this->setup();
	}

	public function setup(): void
	{
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
