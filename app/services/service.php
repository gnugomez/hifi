<?php
class service
{
	private static service $instance;

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
