<?php

namespace hifi\Frontend;

use hifi\Services\AuthService, hifi\Session, hifi\Providers\Component;

final class register extends Component
{
	public $errors = array();

	public function setup(...$props): array
	{

		$data = [
			'title' => 'Register',
			'template' => '@auth/register.twig',
			'form' => [
				'action' => '/register',
				'method' => 'POST',
				'fields' => [
					'username' => [
						'name' => 'user',
						'label' => 'Username',
						'type' => 'text',
						'value' => get_array_value($_POST, 'user', ''),
						'required' => 'true'
					],
					'email' => [
						'name' => 'email',
						'label' => 'Email',
						'type' => 'email',
						'value' => get_array_value($_POST, 'email', ''),
						'required' => 'true'
					],
					'password' => [
						'name' => 'pass',
						'label' => 'Password',
						'type' => 'password',
						'value' => get_array_value($_POST, 'pass', ''),
						'required' => 'true'
					],
					'cpassword' => [
						'name' => 'cpass',
						'label' => 'Confirm password',
						'type' => 'password',
						'value' => get_array_value($_POST, 'cpass', ''),
						'required' => 'true'
					],
				]
			]
		];

		$res = $this->doRegister();

		if (isset($res['errors'])) {
			$errors = get_array_value($res, 'errors');

			foreach ($errors as $error) {
				foreach ($error['fields'] as $field) {
					$data['form']['fields'][$field]['error'] = $error['message'];
				}
			}
		}

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
				return [
					"errors" => [[
						"fields" => ["password", "cpassword"],
						"message" => "Las contraseña no coinciden"
					]]
				];
			}
		} else {
			return [];
		}
	}
}
