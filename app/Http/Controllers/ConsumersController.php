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

			// If CSV File
    		if ($path_array[count($path_array)-1] == 'csv') {
				$url = $data_source->path;
				$path = public_path('downloads/tmp.csv');

				// Storing the file
				$stored = $this->storeFile($url, $path);

				if ($stored) {
					// Content of the file to an array
					$file = fopen($path, "r");
					$csv_content = array();
					while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
						array_push($csv_content, $data);
					}
					fclose($file);

					$products->push([$data_source->name => $csv_content]);
				}
    		} elseif ($path_array[count($path_array)-1] == 'json') {
				$url = $data_source->path;
				$path = public_path('downloads/tmp.json');

				// Storing the file
				$stored = $this->storeFile($url, $path);

				if ($stored) {
					// Content of the file to an array
					$content = file_get_contents(public_path('downloads/tmp.json'));
			        $json_content = json_decode($content, true);

					$products->push([$data_source->name => $json_content]);
				}
    		} elseif ($path_array[count($path_array)-1] == 'xml') {
				$url = $data_source->path;
				$path = public_path('downloads/tmp.xml');

				// Storing the file
				$stored = $this->storeFile($url, $path);

				if ($stored) {
					// Content of the file to an array
					$content = file_get_contents(public_path('downloads/tmp.xml'));
					$xml_content = simplexml_load_string($content);
					$xml_json_array = json_decode(json_encode($xml_content), true);

					$products->push([$data_source->name => $xml_json_array]);
				}
    		}  elseif ($path_array[count($path_array)-1] == 'xls') {
				$url = $data_source->path;
				$path = public_path('downloads/tmp.xls');

				// Storing the file
				$stored = $this->storeFile($url, $path);

				if ($stored) {
					// Content of the file to an array
					$data = Excel::load($path)->get();

					$products->push([$data_source->name => $data]);
				}
    		}  elseif ($path_array[count($path_array)-1] == 'xlsx') {
				$url = $data_source->path;
				$path = public_path('downloads/tmp.xlsx');

				// Storing the file
				$stored = $this->storeFile($url, $path);

				if ($stored) {
					// Content of the file to an array
					$data = Excel::load($path)->get();

					$products->push([$data_source->name => $data]);
				}
    		} else {
	    		// If API (JSON or XML text)
	    		$content = file_get_contents($data_source->path);
				$file_info = new finfo(FILEINFO_MIME_TYPE);
				$mime_type = $file_info->buffer($content);

				if ($mime_type == "text/plain") {
					// JSON
					$json_content = json_decode($content, true);
					$products->push([$data_source->name => $json_content]);
				} elseif ($mime_type == "text/xml") {
					// Load the XML
					$xml_content = simplexml_load_string($content);
					// JSON encode the XML, and then JSON decode to an array.
					$xml_json_array = json_decode(json_encode($xml_content), true);
					$products->push([$data_source->name => $xml_json_array]);
				}
			}
    	}
		return response()->json($products, 200);
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
