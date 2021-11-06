<?php

namespace App\Frontend\Controllers;

use App\Providers\Component;

final class home extends Component
{
	public function setup(...$props): array
	{
		return [
			'title' => 'Home',
			'template' => '@home/home.twig',
			'js' => [
				'home/home.js'
			],
			'css' => [
				'home/home.css'
			]
		];
	}
}
