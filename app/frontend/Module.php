<?php

namespace App\Modules;

use App\Module;

class Frontend extends Module
{
	public function __construct()
	{
		/**
		 * Here we will register the templates avaliable on this module, those templates will be imported when the route matches this module
		 */
		$this->registerTemplates(__DIR__ . '/templates/', 'global');
		$this->registerTemplates(__DIR__ . '/views/home/', 'home');
		$this->registerTemplates(__DIR__ . '/views/auth/', 'auth');

		/**
		 * Here we will register the avaliable controllers for this module
		 */
		$this->registerController([
			'path' => __DIR__ . '/views/home/controllers/Home.php',
			'class' => 'App\Frontend\Controllers\home',
			'name' => 'home'
		]);
		$this->registerController([
			'path' => __DIR__ . '/views/auth/controllers/Login.php',
			'class' => 'App\Frontend\Controllers\login',
			'name' => 'login'
		]);
		$this->registerController([
			'path' => __DIR__ . '/views/auth/controllers/Register.php',
			'class' => 'App\Frontend\Controllers\register',
			'name' => 'register'
		]);
	}
}
