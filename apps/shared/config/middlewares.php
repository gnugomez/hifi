<?php

namespace App;

class Middlewares
{
	public static function noUser(Core $core)
	{
		if (isset($core->session->user)) {
			$core->router->redirect($core->getPrevRoute());
		}
	}
}
