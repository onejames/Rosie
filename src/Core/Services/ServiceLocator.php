<?php 

namespace Rosie\Core\Services;

use Rosie\Core\Interfaces\IsFactory;
use Rosie\Core\Interfaces\IsService;

class ServiceLocator
{

	private $usedClasses = array();

	private $factory = array(
		'Rosie\Core\Request' => 'Rosie\Core\Factories\RequestFactory'
	);

	private $alias = array(
		'core.router'            => 'Rosie\Router\Router',
		'core.request'           => 'Rosie\Core\Request',
		
		'core.tools.fileparser'  => 'Rosie\Core\Tools\FileParser',
		'core.tools.hydrator'    => 'Rosie\Core\Tools\Hydrator',
		
		'app.bio.controller'     => 'Rosie\Controllers\GenTroller',
		'app.general.controller' => 'Rosie\Controllers\GenTroller',
		'app.ajax.controller'    => 'Rosie\Controllers\AjaxController',

		'app.error.handler'      => 'Rosie\Handlers\ErrorHandler',
		'app.bio.handler'        => 'Rosie\Handlers\GenHandler',
		'app.general.handler'    => 'Rosie\Handlers\GenHandler',
		'app.project.handler'    => 'Rosie\Handlers\ProjectHandler',
		'app.recipe.handler'     => 'Rosie\Handlers\RecipeHandler',
	);

	private $nonpersistant = array(
		'Rosie\Core\Tools\FileParser',
	);

	public function __construct($config = null)
	{
		$this->config = $config;
	}

	public function getService($serviceName)
	{
		if(!isset($this->alias[$serviceName]) && !isset($this->service[$serviceName])) {
			throw new \Exception('Service ' . $serviceName . ' does not exist');
		}

		$isAlias    = isset($this->alias[$serviceName]);
		$className  = ( $isAlias ? $this->alias[$serviceName] : $serviceName);
		$hasFactory = isset($this->factory[$className]);


		if(isset($this->nonpersistant[$className])) {
			
			$classObject = new $className();
		
		} else if(isset($this->usedClasses[$className])) {
			
			$classObject = $this->usedClasses[$className];
		
		} else if($hasFactory) {
			
			$factoryName = $this->factory[$className];

			// TODO: check for infinite loop
			$factory     = new $factoryName();

			if( !$factory instanceof IsFactory ) {
				throw new \Exception('Regestered factory ' . get_class($factory) . ' is not a factory');
			}

			$classObject = $factory->create($this);

		} else {

			$classObject = new $className();
			$this->usedClasses[$className] = $classObject;

		}

		if( !$classObject instanceof IsService ) {
			throw new \Exception('Class ' . get_class($classObject) . ' is not a valid service');
		}

		$classObject->setServiceName($serviceName);

		return $classObject;
	}

}