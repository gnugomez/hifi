<?php

namespace App\Providers;

use App\Core;

abstract class Component implements IView
{
	public Core $core;

	/**
	 * This array represents the data that will be passed to the view.
	 *
	 * @var array
	 */
	public array $data = array();

	/**
	 * The constructor injects the core instance into the view and adds context to the data array.
	 * Then executes it self setup method to do not override the constructor in the child classes.
	 * Then saves the template specific data to the data array into a variable to get it with a getter.
	 */
	public function __construct(...$args)
	{
		$this->core = Core::getInstance();
		$this->data["context"] = $this->core->getContext();
		$this->data += $this->setup(...$args);
		$this->template = $this->data["template"];
	}

	/** Getters */
	public function getData(): array
	{
		return $this->data;
	}

	public function getTemplate(): string
	{
		return $this->template;
	}

	/** Lifecycle hooks */
	public function mounted(): void
	{
	}
	public function beforeMount(): void
	{
	}
}

interface IView
{
	public function setup(...$args): array;
}
