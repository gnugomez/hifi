<?php

namespace App\Model;

abstract class ServiceModel
{
	private static ServiceModel $instance;

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
