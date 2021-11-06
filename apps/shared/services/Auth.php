<?php

namespace App\Services;

use App\Providers\Service;
use App\Core;
use App\Session;
use Error;

final class AuthService extends Service
{

	private Session $session;
	private ContentService $contentService;

	public function __construct()
	{
		$this->session = Session::getInstance();
		$this->contentService = ContentService::getInstance();
		$this->core = Core::getInstance();
	}

	public function isloggedin(): bool
	{
		return isset($this->session->user);
	}

	/**
	 * This function check if the password and the user is correct if it isn't it returns an array with the errors
	 *
	 * @param string $user
	 * @param string $password
	 * @return array 
	 */
	public function validateUser(string $user, string $pass): array
	{
		$errors = array();
		$success = array();

		if ($user && $pass) {
			$queryUser = $this->contentService->getUser($user);
			if (count($queryUser)) {
				if (password_verify($pass, get_array_value($queryUser[0], "password"))) {
					unset($queryUser[0]["password"]);
					$success["user"] = $queryUser[0];
				} else {
					$errors["wrong_password"] = "el nombre de usuario o la contraseña son incorrectos";
				}
			} else {
				$errors["user_do_not_exist"] = "el usuario que has introducido no existe";
			}
		} else {
			$errors["no_user_and_pass"] = "introduce usuario y contraseña";
		}
		$res[$errors ? "errors" : "success"] = $errors ?  $errors :  $success;
		return $res;
	}

	public function registerUser(string $user, string $email, string $pass): array
	{
		$errors = array();
		$success = array();

		if ($user && $email && $pass) {
			$queryUser = $this->contentService->getUser($user, $email);

			if (!count($queryUser)) {
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$hashedPass = password_hash($pass, PASSWORD_DEFAULT);
					$this->contentService->addUser($email, $user, $hashedPass);
					$success["user_registered"] = $queryUser;
				} else {
					$errors["wrong_email"] = "El correo introducido no es válido";
				}
			} else {
				$errors["email_or_username_taken"] = "El nombre de usuario o el correo ya esta en uso por otro usuario";
			}
		} else {
			$errors["empty_fields"] = "Debes rellenar todos los campos";
		}

		$res[$errors ? "errors" : "success"] = $errors ?  $errors :  $success;
		return $res;
	}
}
