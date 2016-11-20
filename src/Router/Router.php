<?php

namespace Rosie\Router;

use Rosie\Router\Route;
use Rosie\Core\Interfaces\IsService;
use Rosie\Core\Traits\ServiceTrait;
use Rosie\Core\Traits\GetServiceTrait;

class router implements IsService
{

	use ServiceTrait;

	use GetServiceTrait;

	private $route;

	public function __construct()
	{
		$this->route = new Route();
	}

	public function calculateRoute($routeString = null)
	{
		$routePieces   = array();
		$base_url =  ( $routeString == null ? $this->getCurrentUri() : $routeString );

		$exploded = explode('/', $base_url);
		foreach($exploded as $route)
		{
			if(trim($route) != '') {
				array_push($routePieces, $route);
			}
		}

		$this->route->init($routePieces);

		return $this->route;
	}

	public function route(Route $route = null)
	{
		if($route == null) {
			$route = $this->route;
		}

		if($route == null) {
			$route = $this->calculateRoute();
		}

		if($route == null) {
			throw new \Exception('Cant route with out a route');
		}

		if($route->controller == null) {
			$route->controller = INDEX_CONTROLLER;
		}

		$class = 'App\\Controllers\\' . ucfirst($route->controller) . 'Controller';

		if( class_exists($class)) {
			$controller = new $class();
		} else {
			throw new \Exception('Page ' . $route->controller . ' was not found', 404);
		}

		$controller->controll($route);

	}

	private function getBasePath()
	{
		return implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';;
	}

	/*
	The following function will strip the script name from URL i.e.  http://www.something.com/search/book/fitzgerald will become /search/book/fitzgerald
	*/
	private function getCurrentUri()
	{
		if(!isset($_SERVER['REQUEST_URI'])) {
			return null;
		}

		$basepath = $this->getBasePath();
		$uri      = substr($_SERVER['REQUEST_URI'], strlen($basepath));

		if (strstr($uri, '?')) {
			$uri = substr($uri, 0, strpos($uri, '?'));
		}

		$uri = '/' . trim($uri, '/');

		return $uri;
	}

	public function routeError(\Exception $error)
	{

		$errorDto = $errorHandler = $this->get('app.error.handler')->generateError($error);

		// if route type is json return json error
		if($this->get('core.request')->getType() == 'ajax') {
			$this->get('app.ajax.controller')->returnAjaxError($errorDto);
		}

		$this->get('app.general.controller')->renderError($errorDto);

		exit;
	}

	public function getRoute()
	{
		return $this->route;
	}

}
