<?php
class login extends view
{
	public $errors = array();

	public function mounted(): void
	{
	}

	public function beforeMount(): void
	{
		if ($this->core->requestMethod === 'POST') {
			$this->doLogin();
		}
	}

	private function doLogin()
	{
		global $DB;
		$user = get_array_value($_POST, "user", null);
		$pass = get_array_value($_POST, "pass", null);

		if ($user && $pass) {
			$queryUser = $DB->getUser($user);
			if (count($queryUser)) {
				if (password_verify($pass, get_array_value($queryUser[0], "password"))) {
				} else {
					$this->errors["wrong_password"] = "el nombre de usuario o la contraseña son incorrectos";
				}
			} else {
				$this->errors["user_do_not_exist"] = "el usuario que has introducido no existe";
			}
		} else {
			$this->errors["no_user_and_pass"] = "introduce usuario y contraseña";
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
