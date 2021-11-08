<?php

namespace hifi\Frontend;

use hifi\Providers\Component;

class home extends Component
{
	public function setup(...$props): array
	{
		return [
			'title' => 'Home',
			'template' => '@home/home.twig',
		];
	}
}
