<?php

namespace Rosie\Commands;

abstract class AbstractCommand
{

	protected $command;

	public function __construct($command)
	{
		$this->command = $command;
	}
	
	abstract public function process();
}