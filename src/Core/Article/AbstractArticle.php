<?php

namespace Rosie\Core\Article;

use Rosie\Core\Interfaces\ToJson;
use Rosie\Core\Interfaces\MakesDto;

class AbstractArticle implements ToJson, MakesDto
{

	private $title;
	
	private $description;

	private $image;

	private $thumbnail;

	private $link;

	private $content;
	
	private $contentLink;

	public function toJson()
	{

		return json_encode($this->makeDto);

	}

	public function makeDto()
	{
		$dto = array(
			'title'     => $this->title,
			'thumbnail' => $this->thumbnail,
			'image'     => $this->image,
			'content'   => $this->content,
			'link'      => $this->link,
		);


		return (object) $dto;
	}

	public function getTitle()
	{
	    return $this->title;
	}

	public function setTitle($value)
	{
	    $this->title = $value;
	    
	    return $this;
	}

	public function getThumbnail()
	{
	    return $this->thumbnail;
	}

	public function setThumbnail($value)
	{
	    $this->thumbnail = $value;
	    
	    return $this;
	}

	public function getImage()
	{
	    return $this->image;
	}

	public function setImage($value)
	{
	    $this->image = $value;
	    
	    return $this;
	}

	public function getContentLink()
	{
	    return $this->contentLink;
	}

	public function setContentLink($value)
	{
	    $this->contentLink = $value;
	    
	    return $this;
	}

	public function getContent()
	{
	    return $this->content;
	}

	public function setContent($value)
	{
	    $this->content = $value;
	    
	    return $this;
	}

	public function getLink()
	{
	    return $this->link;
	}

	public function setLink($value)
	{
	    $this->link = $value;
	    
	    return $this;
	}

}