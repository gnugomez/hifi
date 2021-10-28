<?php

namespace App\Frontend\Controllers;

use App\Model\ViewModel, App\Modules\frontend, App\Services\AuthService, App\Session;

class login extends ViewModel
{
	public function beforeMount(): void
	{
		$this->doLogin();
	}

	public function doLogin(): void
	{
		$this->session = Session::getInstance();

		if ($this->core->router->requestMethod === 'POST') {
			$user = get_array_value($_POST, "user", null);
			$pass = get_array_value($_POST, "pass", null);
			$res = $this->auth->validateUser($user, $pass);
			if (isset($res["success"])) {
				$this->session->user = get_array_value($res["success"], "user");
				$this->core->routerPush($this->core->getPrevRoute());
			}

			$this->data["res"] = $res;
		}
	}

	public function render(): string
	{
		$module = frontend::getInstance();
		$module->loadTemplates();
		return $module->twig->render('@auth/login.html.twig', $this->data);
	}
}
