<?php
require_once __DIR__ . '/service.php';

class authService extends service
{

	private session $session;
	private contentService $contentService;
	private core $core;

	public function __construct()
	{
		$this->session = session::getInstance();
		$this->contentService = contentService::getInstance();
		$this->core = core::getInstance();
	}

	/**
	 * This function check if the password and the user is correct if it isn't it returns an array with the errors
	 *
	 * @param string $user
	 * @param string $password
	 * @return array | bool
	 */
	public function doLogin(string $user, string $pass): array | null
	{
		$errors = array();

		if ($user && $pass) {
			$queryUser = $this->contentService->getUser($user);
			if (count($queryUser)) {
				if (password_verify($pass, get_array_value($queryUser[0], "password"))) {
					$this->session->user = $queryUser;
					header("Location: " . $this->core->generate($this->core->getPrevRoute()), false);
					die();
				} else {
					$errors["wrong_password"] = "el nombre de usuario o la contraseña son incorrectos";
				}
			} else {
				$errors["user_do_not_exist"] = "el usuario que has introducido no existe";
			}
		} else {
			$errors["no_user_and_pass"] = "introduce usuario y contraseña";
		}

		return $errors ? $errors : null;
	}
}
