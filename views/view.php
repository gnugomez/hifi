<?php
abstract class view implements IView
{
	public core $core;

	public function __construct(core $core)
	{
		$this->core = $core;
		$core->setView($this);
	}

	public static function init()
	{
		global $core;
		return new static($core);
	}
}

interface IView
{
	public function render(): string;
	public function mounted(): void;
	public function beforeMount(): void;
}
