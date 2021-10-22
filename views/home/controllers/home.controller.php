<?php
class home extends viewController
{

	public function render(): string
	{
		$this->core->loader->addPath(__DIR__ . "/../", 'home');
		return $this->core->twig->render('@home/index.html', $this->data);
	}
}
