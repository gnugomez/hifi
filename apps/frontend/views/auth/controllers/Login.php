<?php

namespace App\Frontend\Controllers;

use App\Providers\Component, App\Modules\frontend, App\Services\AuthService, App\Session;

final class login extends Component
{

	public function setup(...$props): array
	{
		$res = $this->doLogin();

		return [
			'title' => 'Login',
			'template' => '@auth/login.twig',
			'res' => $res
		];
	}

	public function doLogin(): array
	{
		$this->session = Session::getInstance();
		$this->auth = AuthService::getInstance();

		if ($this->core->router->requestMethod === 'POST') {

			$user = get_array_value($_POST, "user", null);
			$pass = get_array_value($_POST, "pass", null);
			$res = $this->auth->validateUser($user, $pass);

			if (isset($res["success"])) {
				$this->session->user = get_array_value($res["success"], "user");
				$this->core->router->redirect('home');
			}

			return $res;
		} else {
			return [];
		}
	}

	public function render(): string
	{
		$module = frontend::getInstance();
		$module->loadTemplates();
		return $module->twig->render('@auth/login.html.twig', $this->data);
	}
}
