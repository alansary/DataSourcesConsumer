<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;

class FileHandlersController extends Controller
{
    public function handleFile($url, $file_extension)
    {
    	// Storing the file
    	$path = public_path('downloads/tmp.'.$file_extension);
    	$stored = HelpersController::storeFile($url, $path);

    	if ($stored) {
    		if ($file_extension == 'csv') {
    			return $this->getCsvFileContent($path);
    		} elseif ($file_extension == 'json') {
    			return $this->getJsonFileContent($path);
    		} elseif ($file_extension == 'xml') {
    			return $this->getXmlFileContent($path);
    		} elseif ($file_extension == 'xls') {
    			return $this->getXlsFileContent($path);
    		} elseif ($file_extension == 'xlsx') {
    			return $this->getXlsxFileContent($path);
    		}
    	} else {
    		return collect([]);
    	}
    }

    protected function getCsvFileContent($path)
    {
		$file = fopen($path, "r");
		$csv_content = array();
		while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
			array_push($csv_content, $data);
		}
		fclose($file);

		return $csv_content;
    }

    protected function getJsonFileContent($path)
    {
		$content = file_get_contents($path);
        $json_content = json_decode($content, true);

        return $json_content;
    }

    protected function getXmlFileContent($path)
    {
		$content = file_get_contents($path);
		$xml_content = simplexml_load_string($content);
		$xml_json_array = json_decode(json_encode($xml_content), true);

		return $xml_json_array;
    }

    protected function getXlsFileContent($path)
    {
    	$xls_content = Excel::load($path)->get();

    	return $xls_content;
    } 

    protected function getXlsxFileContent($path)
    {
    	$xlsx_content = Excel::load($path)->get();

    	return $xlsx_content;
    }
}
