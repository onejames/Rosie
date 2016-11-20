<?php

namespace Rosie\Handlers;

use Rosie\Core\Interfaces\IsService;
use Rosie\Core\Traits\ServiceTrait;
use Rosie\Core\Traits\GetServiceTrait;

use Rosie\Core\Dtos\ErrorDto;

class ErrorHandler implements IsService
{

	use ServiceTrait;
	use GetServiceTrait;

	public function handleException(\Exception $e)
	{
		$this->get('core.router')->routeError($e);
	}

	public function handleError()
	{
	    if(defined('EVERYTHING_WENT_OK')){
	    	return;
	    }

	    if(ob_get_contents() != '') {
	        ob_end_clean();
	    }

    	$error = error_get_last();

		if($error == null) {
			return;
		}

		var_dump($error);
		$exception = new \Exception($error['message'], $error['type']);

		$this->get('core.router')->routeError($exception);
	}

	public function generateError(\Exception $e)
	{
		$error = new ErrorDto();

		$error->type   = 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html';
		$error->status = ( $e->getCode()   != null ? $e->getCode()    : 500);
		$error->title  = $this->getErrorTitle($error->status);
		$error->detail = ($e->getMessage() != null ? $e->getMessage() : $error->title);

		return $error;

	}

	private function getErrorTitle($code)
	{
		$codeArray = array(
			// 1xx Informational
			100 => "Continue",
			101 => "Switching Protocols",
			102 => "Processing (WebDAV)",

			// 2xx Success
			200 => "OK",
			201 => "Created",
			202 => "Accepted",
			203 => "Non-Authoritative Information",
			204 => "No Content",
			205 => "Reset Content",
			206 => "Partial Content",
			207 => "Multi-Status (WebDAV)",
			208 => "Already Reported (WebDAV)",
			226 => "IM Used",

			// 3xx Redirection
			300 => "Multiple Choices",
			301 => "Moved Permanently",
			302 => "Found",
			303 => "See Other",
			304 => "Not Modified",
			305 => "Use Proxy",
			306 => "(Unused)",
			307 => "Temporary Redirect",
			308 => "Permanent Redirect (experiemental)",

			// 4xx Client Error
			400 => "Bad Request",
			401 => "Unauthorized",
			402 => "Payment Required",
			403 => "Forbidden",
			404 => "Not Found",
			405 => "Method Not Allowed",
			406 => "Not Acceptable",
			407 => "Proxy Authentication Required",
			408 => "Request Timeout",
			409 => "Conflict",
			410 => "Gone",
			411 => "Length Required",
			412 => "Precondition Failed",
			413 => "Request Entity Too Large",
			414 => "Request-URI Too Long",
			415 => "Unsupported Media Type",
			416 => "Requested Range Not Satisfiable",
			417 => "Expectation Failed",
			418 => "I'm a teapot (RFC 2324)",
			420 => "Enhance Your Calm (Twitter)",
			422 => "Unprocessable Entity (WebDAV)",
			423 => "Locked (WebDAV)",
			424 => "Failed Dependency (WebDAV)",
			425 => "Reserved for WebDAV",
			426 => "Upgrade Required",
			428 => "Precondition Required",
			429 => "Too Many Requests",
			431 => "Request Header Fields Too Large",
			444 => "No Response (Nginx)",
			449 => "Retry With (Microsoft)",
			450 => "Blocked by Windows Parental Controls (Microsoft)",
			499 => "Client Closed Request (Nginx)",

	 		// 5xx Server Error
			500 => "Internal Server Error",
			501 => "Not Implemented",
			502 => "Bad Gateway",
			503 => "Service Unavailable",
			504 => "Gateway Timeout",
			505 => "HTTP Version Not Supported",
			506 => "Variant Also Negotiates (Experimental)",
			507 => "Insufficient Storage (WebDAV)",
			508 => "Loop Detected (WebDAV)",
			509 => "Bandwidth Limit Exceeded (Apache)",
			510 => "Not Extended",
			511 => "Network Authentication Required",
			598 => "Network read timeout error",
			599 => "Network connect timeout error",
		);

		if(!array_key_exists($code, $codeArray)) {
			return $codeArray[500];
		}

		return $codeArray[$code];
	}


}
