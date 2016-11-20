<?php

namespace Rosie\Core;

use Rosie\Core\Interfaces\IsService;
use Rosie\Core\Traits\ServiceTrait;
use Rosie\Core\Traits\GetServiceTrait;

use Rosie\Router\Route;

class Request implements IsService
{
	
	use ServiceTrait;
	
	use GetServiceTrait;

	private $method;

	private $type;

	CONST AJAX = 'ajax';

	CONST PAGE_REQUEST = 'pageRequest';

	public function __construct(Route $route)
	{

		$this->type   = ($route->controller == 'ajax' ? $this::AJAX : $this::PAGE_REQUEST);

		$this->method = $_SERVER['REQUEST_METHOD'];

	}

	public function getType()
	{
		return $this->type;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getRequestParam($key)
	{
		if(!isset($_REQUEST[$key])) {
			return null;
		}

		return $_REQUEST[$key];
	}

	public function getRequest()
	{
		return $_REQUEST;
	}

}