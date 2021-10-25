<?php

namespace App;

class Service
{
	private static Service $instance;

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
