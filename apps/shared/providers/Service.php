<?php

namespace App\Providers;

abstract class Service
{
	private static Service $instance;

	/**
	 * This methods returns the instance of the service
	 *
	 * @return static
	 */
	public static function getInstance(): static
	{
		if (!isset(self::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
