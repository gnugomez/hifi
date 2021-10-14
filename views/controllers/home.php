<?php
class Home extends View
{
	public function render(): string
	{
		ob_start();
		echo "hola";
		return ob_get_clean();
	}
}
