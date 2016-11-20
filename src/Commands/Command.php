<?php

namespace Rosie\Commands;

class Command 
{
	private $rosie;

	private $commandArgs;

	public function __construct($rosie, $commandArgs)
	{
		$this->app         = $rosie;
		$this->commandArgs = $commandArgs;
	}

	public function exists()
	{
		return false;
	}
}