<?php
include_once __DIR__ . '/../../../services/auth.service.php';

class login extends view
{
	public $errors = array();
	private authService $auth;

	public function beforeMount(): void
	{
		$this->auth = authService::getInstance();
		if ($this->core->requestMethod === 'POST') {
			$user = get_array_value($_POST, "user", null);
			$pass = get_array_value($_POST, "pass", null);
			$this->errors = $this->auth?->doLogin($user, $pass);
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
		global $core;
		header("Location:" . $core->generate('login'));
		die();
	}
}
