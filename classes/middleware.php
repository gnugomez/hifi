<?php
class middleware
{
	public static function noUser(core $core)
	{
		if (isset($core->session->user)) {
			header("Location: " . $core->generate("home"), false);
			die();
		}
	}
}
