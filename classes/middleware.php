<?php
class middleware
{
	public static function noUser(core $core)
	{
		if (isset($core->session->user)) {
			$core->routerPush("home");
		}
	}
}
