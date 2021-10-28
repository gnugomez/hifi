<?php

namespace App\Frontend\Controllers;

use App\Services\AuthService, App\Session, App\Modules\frontend, App\Model\ViewModel;

class register extends ViewModel
{
	public $errors = array();

	public function beforeMount(): void
	{
		if ($this->core->router->requestMethod === 'POST') {
			$this->doRegister();
		}
	}

	private function doRegister()
	{
		$this->session = Session::getInstance();

		$user = get_array_value($_POST, "user", null);
		$email = get_array_value($_POST, "email", null);
		$pass = get_array_value($_POST, "pass", null);
		$cpass = get_array_value($_POST, "cpass", null);

		if ($pass == $cpass) {
			$res = $this->auth->registerUser($user, $email, $pass);
			if (isset($res["success"])) {
				$this->core->routerPush("login");
			} else if (isset($res["errors"])) {
				$this->errors = $res["errors"];
			}
		} else {
			$this->errors["passwords_dont_match"] = "Las contraseña no coinciden";
		}
	}

	public function render(): string
	{
		$module = frontend::getInstance();
		$module->loadTemplates();
		return $module->twig->render('@auth/register.html.twig', $this->data);
	}
}
