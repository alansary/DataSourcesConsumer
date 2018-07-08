<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiHandlersController extends Controller
{
    public function handleApi($content, $type)
    {
    	if ($type == 'text/plain') {
    		return $this->handleJsonApi($content);
    	} elseif ($type == 'text/xml') {
			return $this->handleXmlApi($content);
    	} else {
    		return collect([]);
    	}
    }

    protected function handleJsonApi($content)
    {
    	$json_content = json_decode($content, true);

    	return $json_content;
    }

    protected function handleXmlApi($content)
    {
		// Load the XML
		$xml_content = simplexml_load_string($content);
		// JSON encode the XML, and then JSON decode to an array.
		$xml_json_array = json_decode(json_encode($xml_content), true);

		return $xml_json_array;
    }
}
