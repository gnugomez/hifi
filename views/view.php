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
		$core = core::getInstance();
		return new static($core);
	}
	public function mounted(): void
	{
	}
	public function beforeMount(): void
	{
	}
}

interface IView
{
	public function render(): string;
}
