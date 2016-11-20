<?php

namespace Rosie\Core\Traits;

trait ServiceTrait
{

	private $serviceName;

	public function setServiceName($value)
	{
		$this->serviceName = $value;
	}

	public function getServiceName()
	{
		return $this->serviceName;
	}

}