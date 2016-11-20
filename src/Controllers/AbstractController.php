<?php

namespace Rosie\Controllers;

use Rosie\Core\Interfaces\IsService;

use Rosie\Router\Route;
use Rosie\Core\Template\Template;
use Rosie\Core\Traits\GetServiceTrait;
use Rosie\Core\Traits\ServiceTrait;
use Rosie\Core\Interfaces\CatchAllController;

abstract class AbstractController implements IsService
{

	use ServiceTrait;

	use GetServiceTrait;

	protected $route;
	
	protected $request;

	protected $template;

	protected $pageValues = [];

	protected $javascript = [];

	protected $css        = [];

	protected $handler;

	public function controll(Route $route)
	{
		$this->route   = $route;
		$this->request = $this->get('core.request');

		if($this instanceof CatchAllController) {
			$this->process();
			exit;
		}

		if($this->request->getMethod() == 'GET') {

			if( $this->route->getPage() == null ) {

				$this->processIndex();

			} else {
			
				$this->processRoute();

			}
		
		} else if($this->request->getMethod() == 'POST') {

			if( $this->route->getPage() == null ) {

				throw new \Exception("You can not post collections");

			} else {
			
				$this->postEntity();

			}

		}  else if($this->request->getMethod() == 'PUT') {

			if( $this->route->getPage() == null ) {

				throw new \Exception("You can not put collections");
				
			} else {
			
				$this->putEntity();

			}

		}  else if($this->request->getMethod() == 'PATCH') {

			if( $this->route->getPage() == null ) {

				throw new \Exception("You can not patch collections");

			} else {
			
				$this->patchEntity();

			}

		}
		
		$this->render();
	}
	
	public function processRoute()
	{	
		throw new \Exception("This controller does not impliment routes");
	}	

	public function processInxex()
	{	
		throw new \Exception("This controller does not impliment an index");
	}

	public function getEntity()
	{
		throw new \Exception("This controller does not impliment get on an entity");
	}

	public function getCollection()
	{
		throw new \Exception("This controller does not impliment get on an collection");
	}

	public function postEntity()
	{
		throw new \Exception("This controller " . get_class($this) . " does not impliment post on an entity");
	}

	public function putEntity()
	{
		throw new \Exception("This controller does not impliment put on an entity");
	}

	public function patchEntity()
	{
		throw new \Exception("This controller does not impliment patch on an entity");
	}

	public function render()
	{
		$this->css[]        = 'main';
		$this->javascript[] = 'main';

		if($this->template == null) {
			throw new \Exception("Cant render a page with out a template");
		}

		$templateFile = TEMPLATE_PATH . $this->template . '.php';
		
		$pageTemplate = new Template($templateFile);

		$pageTemplate->setValues($this->pageValues);

		$pageOutput = $pageTemplate->render();

		$template = new Template(TEMPLATE_PATH . 'index.php');

		$indexValues = array(
				'subtemplateBody' => $pageOutput,
				'javascript'      => $this->javascript,
				'css'             => $this->css,
				'title'           => (isset($this->pageValues['title']) ? $this->pageValues['title'] : 'James Laster'),
			);

		$template->setValues($indexValues);

		$output = $template->render();
		
		echo $output;
	}

	public function setTemplate($value)
	{
		$this->template = $value;
	}


	public function setPageValues($value)
	{
		$this->pageValues = $value;
	}

	public function setJavascript($value)
	{
		$this->javascript = $value;
	}

	public function addJavascript($value)
	{
		$this->javascript[] = $value;
	}

	public function setCss($value)
	{
		$this->css = $value;
	}

	public function addCss($value)
	{
		$this->css[] = $value;
	}

	public function setHandler($value)
	{
	    $this->handler = $value;
	    
	    return $this;
	}

	public function getHandler()
	{
		if($this->handler == null) {
			throw new \Exception('Cant get a handler before setting class');
		}

		return new $this->handler;
	}
}