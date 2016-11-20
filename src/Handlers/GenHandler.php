<?php

namespace Rosie\Handlers;

use Rosie\Core\Interfaces\IsService;
use Rosie\Core\Traits\ServiceTrait;
use Rosie\Core\Traits\GetServiceTrait;

class GenHandler implements IsService
{

	use ServiceTrait;
	use GetServiceTrait;

	public function getBio()
	{
		$bioPath    = 'bio/markdown/bio.markdown';
		$parsedown  = new \Parsedown();
		$fileParser = $this->get('core.tools.fileparser');

		$markdown   = $fileParser->getFileContents($bioPath);

		return $parsedown->text($markdown);
	}
}