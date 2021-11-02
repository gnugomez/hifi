<?php

namespace App\Frontend\Controllers;

use App\Services\AuthService, App\Session, App\Providers\Component;

class register extends Component
{
	public $errors = array();

	public function setup(...$props): array
	{
		$res = $this->doRegister();

		$data = [
			'title' => 'Register',
			'template' => '@auth/register.twig',
			'res' => $res
		];

		return $data;
	}

	private function doRegister(): array
	{
		$this->session = Session::getInstance();
		$this->auth = AuthService::getInstance();

		$user = get_array_value($_POST, "user", null);
		$email = get_array_value($_POST, "email", null);
		$pass = get_array_value($_POST, "pass", null);
		$cpass = get_array_value($_POST, "cpass", null);

		if ($this->core->router->requestMethod === 'POST') {
			if ($pass == $cpass) {
				$res = $this->auth->registerUser($user, $email, $pass);
				if (isset($res["success"])) {
					$this->core->router->redirect("login");
				} else if (isset($res["errors"])) {
					return $res;
				}
			} else {
				return ["errors" => ["passwords_dont_match" => "Las contraseña no coinciden"]];
			}
		} else {
			return [];
		}
	}
}
