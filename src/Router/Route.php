<?php

namespace Rosie\Router;

class route
{
	private $rawArray;

	public $controller;

	public $page;

	public $slugs;

	public function __construct()
	{

	}

	public function init($routeArray)
	{
		$this->rawArray = $routeArray;

		if(empty($routeArray) || !isset($routeArray[0])) {
			return $this;
		}

		if($routeArray[0] == null) {
			unset($routeArray[0]);
		}

		$this->controller = array_shift($routeArray);

		if( !empty($routeArray) ) {
			$this->page = array_shift($routeArray);
		}

		if( !empty($routeArray) ) {
			$this->slugs = $routeArray;
		}

	}

	public function getController()
	{
		return $this->controller;
	}

	public function getPage()
	{
		return $this->page;
	}

	public function getSlugs()
	{
		return $this->slugs;
	}

	public function getSlug($slug)
	{
		if(!isset($this->slugs[$slug])) {
			return null;
		}

		return $this->slugs[$slug];
	}

}