<?php

namespace Rosie\Core\Tools;

class HtmlBuilder
{
	public function makeContactForm()
	{
		$fields = (object) array(
			'Name'		=> '',
			'Subject'	=> '',
			'Email'		=> '',
			'Comments'	=> ''
		);

		$html = '<form>';

		$html .= $this->makeInputTable();

		$html .= '<input type="submit" value="Submit" />';

		$html .= '</form>';

		return $html;
	}

	public function makeInputTable($object) 
	{
		if(is_array($object)) {
			$object = (object) $object;
		}

		if(!is_object($object)) {
			throw new \Exception("Error Processing Request");
		}

		$output = '<table class="inputTable">';

		foreach ($object as $key => $value) {
			$output .= '<tr>';
			
			$output .= '<td>';
			$output .= ucfirst($key) . ': ';
			$output .= '</ td>';
			
			$output .= '<td>';
				$output .= '<input type="text" name="' . $key . '" id="' . $key . '" value="' . $object->$key . '" />';
			$output .= '</ td>';

			$output .= '</ tr>';
		}

		return $output .= '</table>';
	}

	function makeTable($data, $style = null)
	{
		if(empty($data)) {
			return '<center><em>No Information Avalable</em></center>';
		}
		$header = false;
		$html = '<table style="' . $style . '">';

		foreach ($data as $name => $cell) {
			if(is_numeric($name) && !is_array($cell)) {
				continue;
			}

			if(is_array($cell)) {

				if(!$header) {
					$html .= '<tr>';
					foreach ($cell as $key => $value) {
						$html .= '<td>' . $key . '</td>';
					}
					$html  .= '</tr>';
					$header = true;
				}

				$html .= '<tr>';
				foreach ($cell as $key => $value) {
					if(!is_array($value)) {
						$html .= '<td>' . $value . '</td>';
					}
				}
				$html  .= '</tr>';

			} else {

				$html .= '<tr>';
				$html .= '<td align="right" width="50%" >' . $name . '</td>';
				$html .= '<td>' . $cell . '</td>';
				$html .= '</tr>';
			}

		}

		$html .= '</table>';

		return $html;
	}


}