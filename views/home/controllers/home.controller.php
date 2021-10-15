<?php
class home extends view
{
	public function mounted(): void
	{
	}

	public function beforeMount(): void
	{
	}

	public function render(): string
	{
		ob_start();
		require __DIR__ . '/../home.php';
		return ob_get_clean();
	}
}
