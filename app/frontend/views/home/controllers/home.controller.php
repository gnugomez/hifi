<?php

namespace App\Frontend\Controllers;

use App\Model\ViewModel, App\Modules\frontend;

class home extends ViewModel
{

	public function render(): string
	{
		$module = frontend::getInstance();
		$module->loadTemplates();
		return $module->twig->render('@home/index.html.twig', $this->data);
	}
}
