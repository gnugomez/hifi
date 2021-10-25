<?php

use App\Services\AuthService;
use App\Session;

class register extends App\Model\ViewController
{
	public $errors = array();

	public function beforeMount(): void
	{
		if ($this->core->requestMethod === 'POST') {
			$this->doRegister();
		}
	}

	private function doRegister()
	{
		$this->auth = AuthService::getInstance();
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
		ob_start();
		require __DIR__ . '/../register.php';
		return ob_get_clean();
	}
}
