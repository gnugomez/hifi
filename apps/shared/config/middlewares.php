<?php

namespace App;

class Middlewares
{
	public static function noUser(Core $core)
	{
		if (isset($core->session->user)) {
			header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
			die();
		}
	}
}
