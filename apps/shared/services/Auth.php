<?php

namespace hifi\Services;

use hifi\Providers\Service;
use hifi\Core;
use hifi\Session;
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
					$errors[] = [
						"fields" => ["username", "password"],
						"message" => "El nombre de usuario o la contraseña son incorrectos"
					];
				}
			} else {
				$errors[] = [
					"fields" => ["username"],
					"message" => "El usuario que has introducido no existe"
				];
			}
		} else {
			$errors[] = [
				"fields" => ["username", "password"],
				"message" => "introduce usuario y contraseña"
			];
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
					$errors[] = [
						"fields" => ["email"],
						"message" => "El correo introducido no es válido"
					];
				}
			} else {
				$errors[] = [
					"fields" => ["email", "username"],
					"message" => "El nombre de usuario o el correo ya esta en uso por otro usuario"
				];
			}
		} else {
			$errors[] = [
				"fields" => ["username", "email", "password", "cpassword"],
				"message" => "Debes rellenar todos los campos"
			];
		}

		$res[$errors ? "errors" : "success"] = $errors ?  $errors :  $success;
		return $res;
	}
}
