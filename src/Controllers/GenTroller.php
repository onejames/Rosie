<?php

namespace Rosie\Controllers;

use Rosie\Controllers\AbstractController;
use Rosie\Core\Interfaces\IsService;
use Rosie\Core\Traits\ServiceTrait;

class GenTroller extends AbstractController implements IsService
{
	use ServiceTrait;

	public function __construct()
	{
		$this->setHandler('Rosie\Handlers\GenHandler');
	}

	public function renderError($errorDto)
	{
		$this->setPageValues(
			array(
				'title'      => $errorDto->title,
				'status'     => $errorDto->status,
				'errorTitle' => $errorDto->title,
				'detail'     => $errorDto->detail,
			)
		);

		$this->setTemplate('error');

		$this->setJavascript(
			array(
			)
		);

		$this->setCss(
			array(
				'error'
			)
		);

	}

}