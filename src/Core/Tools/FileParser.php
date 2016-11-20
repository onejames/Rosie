<?php

namespace Rosie\Core\Tools;

use Rosie\Core\Interfaces\IsService;
use Rosie\Core\Traits\ServiceTrait;
use Rosie\Core\Traits\GetServiceTrait;

use Rosie\Core\Tools\Hydrator;

class FileParser implements IsService
{

	use ServiceTrait;
	
	use GetServiceTrait;

	private $dataPath = DATA_PATH;

	private $folderPath;

	private $scannedPath;

	private $scannedFiles;

	public function __construct($path = null)
	{
		$this->folderPath = $path;
	}

	public function fileExists($relativePath)
	{
		return file_exists($this->dataPath . $relativePath);
	}

	public function getFileNames($subFolderPath = null)
	{
		$path = $this->folderPath . $subFolderPath;

		if(!is_dir($this->dataPath . $path)) {
			throw new \Exception("Can not get file names from a non-directory");
		}

		$this->scannedPath  = $path;

		$this->scannedFiles = array_diff(scandir($this->dataPath .  $path), array('..', '.', '.DS_Store'));
		return $this->scannedFiles;
	}

	public function parseFilesIntoObjects(object $object, $type = 'json')
	{
		if($this->scannedFiles == null) {
			$this->getFileNames();
		}
		
		$hydrator = new Hydrator();
		$hydrator->setPrototype($object);
		$fileData = $this->parseFiles($type);
		$data     = array();

		foreach ($fileData as $jsonObject) {
			$data[] = $hydrator->htdrate($jsonObject);
		}

		return $data;
	}

	public function parseFileIntoObject($path, $object, $type = 'json') 
	{
		$hydrator = new Hydrator();
		$hydrator->setPrototype($object);

		$fileData = $this->parseFile($path, $type);

		$hydratedObject = $hydrator->hydrate($fileData);

		return $hydratedObject;
	}

	public function parseFiles($type = 'json')
	{
		if($this->scannedFiles == null) {
			$this->getFileNames();
		}

		$data = array();

		foreach ($this->scannedFiles as $filename) {
			$fileData = $this->parseFile($this->scannedPath . '\\' . $filename, $type);

			if($fileData == null) {
				continue;
			}

			$data[] = $fileData;
		}

		return $data;
	}

	public function parseFile($path, $type = 'json')
	{
		if($this->scannedFiles == null) {
			$this->getFileNames();
		}
		
		$data = array();

		switch($type) {
			case 'json':
				$data = $this->parseFileAsJson($path);
				break;
			case 'yaml':
			default:
				throw new \Exception($type . " is not implimented yet");
				break;
		}

		return $data;
	}

	private function parseFileAsJson($path)
	{
		try {
			$data = json_decode($this->getFileContents($path));
		} catch(\Exception $e) {
			throw new Exception("Error parsing json from file: " . $filename);
		}

		return $data;

	}

	public function getFileContents($path)
	{
		return file_get_contents($this->dataPath . $path);
	}

	public function writeData($path, $data)
	{
		if(!$this->fileExists()) {
			throw new Exception("File does not exist at path " . $path . '. Can not write data.');
		}

		return file_put_contents($this->dataPath . $path, $data);
	}

}
