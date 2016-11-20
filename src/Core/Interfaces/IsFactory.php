<?php

namespace Rosie\Core\Interfaces;

use Rosie\Core\Services\ServiceLocator;

interface IsFactory
{

	public function create(ServiceLocator $serviceLocator);

}