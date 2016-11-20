<?php

namespace Rosie\Core\Dtos;

use Rosie\Core\Dtos\AbstractDto;

class ErrorDto extends AbstractDto
{
	public $isError = true;
	
	public $type;
	
	public $title;
	
	public $status;
	
	public $detail;

}