<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass, finfo, Excel;
use App\DataSource;

class ConsumersController extends Controller
{
    public function getProducts(Request $request) {
    	$data_sources = DataSource::select('name', 'path')->get();

    	$products = collect();

    	foreach ($data_sources as $data_source) {
    		$path_array = explode('.', $data_source->path);
    		$ext = $path_array[count($path_array)-1];

    		if (in_array($ext, ['csv', 'json', 'xml', 'xls', 'xlsx'])) {
    			// File handling
    			$content = $this->handleFile($data_source->path, $ext);
    			if (count($content)) {
    				$products->push([$data_source->name => $content]);
    			}
    		} else {
	    		// If API (JSON or XML)
	    		$content = file_get_contents($data_source->path);
				$file_info = new finfo(FILEINFO_MIME_TYPE);
				$mime_type = $file_info->buffer($content);

	    		if (in_array($mime_type, ['text/plain', 'text/xml'])) {
	    			$content = $this->handleApi($content, $mime_type);
	    			if (count($content)) {
	    				$products->push([$data_source->name => $content]);
	    			}
	    		}
			}
    	}
		return response()->json($products, 200);
    }

    protected function handleApi($content, $type)
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

    protected function handleFile($url, $file_extension)
    {
    	// Storing the file
    	$path = public_path('downloads/tmp.'.$file_extension);
    	$stored = $this->storeFile($url, $path);

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

    protected function storeFile($url, $path)
    {
		// Getting the file using curl
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	    $data = curl_exec($ch);
	    curl_close($ch);

	    if ($data) {
			// Storing the file in public/dowloads
			$download_path = $path;
			$file = fopen($download_path, "w+");
			fputs($file, $data);
			fclose($file);
			return true;
	    } else {
	    	return false;
	    }
    }
}
