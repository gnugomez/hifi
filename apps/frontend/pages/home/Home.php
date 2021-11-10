<?php

namespace JGomez\Frontend;

use JGomez\Providers\Component;

final class home extends Component
{
	public function setup(...$props): array
	{
		return [
			'title' => 'Home',
			'template' => '@home/home.twig',
		];
	}
}
