<?php

namespace JGomez\Frontend;

use JGomez\Providers\Component, JGomez\Modules\frontend, JGomez\Services\AuthService, JGomez\Session;

final class login extends Component
{

	public function setup(...$props): array
	{
		$data = [
			'title' => 'Login',
			'template' => '@auth/login.twig',
			'form' => [
				'action' => '/login',
				'method' => 'POST',
				'fields' => [
					'username' => [
						'name' => 'user',
						'label' => 'Username',
						'type' => 'text',
						'value' => get_array_value($_POST, 'user', ''),
						'required' => 'true'
					],
					'password' => [
						'name' => 'pass',
						'label' => 'Password',
						'type' => 'password',
						'value' => get_array_value($_POST, 'pass', ''),
						'required' => 'true'
					],
				]
			]
		];

		$res = $this->doLogin();

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

	public function doLogin(): array
	{
		$this->session = Session::getInstance();
		$this->auth = AuthService::getInstance();

		if ($this->core->router->requestMethod === 'POST') {

			$user = get_array_value($_POST, "user", null);
			$pass = get_array_value($_POST, "pass", null);
			$res = $this->auth->validateUser($user, $pass);

			if (isset($res["success"])) {
				$this->session->user = get_array_value($res["success"], "user");
				$this->core->router->redirect('home');
			}

			return $res;
		} else {
			return [];
		}
	}

	public function render(): string
	{
		$module = frontend::getInstance();
		$module->loadTemplates();
		return $module->twig->render('@auth/login.html.twig', $this->data);
	}
}
