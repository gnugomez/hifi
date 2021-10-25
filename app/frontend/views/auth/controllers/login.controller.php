<?php

use App\Services\AuthService;
use App\Session;

class login extends App\Model\ViewController
{
	public $errors = array();

	public function beforeMount(): void
	{
		$this->doLogin();
	}

	public function doLogin(): void
	{
		$this->auth = AuthService::getInstance();
		$this->session = Session::getInstance();

		if ($this->core->requestMethod === 'POST') {
			$user = get_array_value($_POST, "user", null);
			$pass = get_array_value($_POST, "pass", null);
			$res = $this->auth->validateUser($user, $pass);
			if (isset($res["success"])) {
				$this->session->user = get_array_value($res["success"], "user");
				$this->core->routerPush($this->core->getPrevRoute());
			} else if (isset($res["errors"])) {
				$this->errors = $res["errors"];
			}
		}
	}

	public function render(): string
	{
		ob_start();
		require __DIR__ . '/../login.php';
		return ob_get_clean();
	}

	public static function redirect()
	{
		$core = App\Core::getInstance();
		$core->routerPush("login");
	}
}
