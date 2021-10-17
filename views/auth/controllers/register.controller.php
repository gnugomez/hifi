<?php
global $DB;

class register extends view
{
	public $errors = array();

	public function mounted(): void
	{
	}

	public function beforeMount(): void
	{
		if ($this->core->requestMethod === 'POST') {
			$this->doRegister();
		}
	}

	private function doRegister()
	{
		global $DB;
		$user = get_array_value($_POST, "user", null);
		$email = get_array_value($_POST, "email", null);
		$pass = get_array_value($_POST, "pass", null);
		$cpass = get_array_value($_POST, "cpass", null);

		if ($user && $email && $pass && $cpass) {
			$queryUser = $DB->getUser($user, $email);

			if (!count($queryUser)) {

				if ($pass == $cpass) {
					if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$hashedPass = password_hash($pass, PASSWORD_DEFAULT);
						$DB->addUser($email, $user, $hashedPass);
					} else {
						$this->errors["wrong_email"] = "El correo introducido no es válido";
					}
				} else {
					$this->errors["passwords_dont_match"] = "Las contraseña no coinciden";
				}
			} else {
				$this->errors["email_or_username_taken"] = "El nombre de usuario o el correo ya esta en uso por otro usuario";
			}
		} else {
			$this->errors["empty_fields"] = "Debes rellenar todos los campos";
		}
	}

	public function render(): string
	{
		ob_start();
		require __DIR__ . '/../register.php';
		return ob_get_clean();
	}
}
