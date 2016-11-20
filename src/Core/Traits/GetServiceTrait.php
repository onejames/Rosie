<?php

namespace Rosie\Core\Traits;

use Rosie\Core\Services\ServiceLocator;

trait GetServiceTrait
{

	public function get($serviceName)
	{
		global $rosie;

		$serviceLocator = $rosie->getServiceLocator();

		return $serviceLocator->getService($serviceName);
	}

}
