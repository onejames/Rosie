<?php

namespace Rosie\Core\Factories;

use Rosie\Core\Interfaces\IsFactory;
use Rosie\Core\Services\ServiceLocator;

use Rosie\Core\Request;

class RequestFactory implements IsFactory
{
	
	public function create(ServiceLocator $serviceLocator)
	{
		return new Request($serviceLocator->getService('core.router')->getRoute());
	}

}