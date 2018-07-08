<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use finfo;
use App\DataSource;

class ConsumersController extends Controller
{
	public function __construct()
	{
		$this->apiHandler = new ApiHandlersController;
		$this->fileHandler = new FileHandlersController;
	}

    public function getProducts(Request $request) {
    	$data_sources = DataSource::select('name', 'path')->get();

    	$products = collect();

    	foreach ($data_sources as $data_source) {
    		$path_array = explode('.', $data_source->path);
    		$ext = $path_array[count($path_array)-1];

    		if (in_array($ext, ['csv', 'json', 'xml', 'xls', 'xlsx'])) {
    			// File handling
    			$content = $this->fileHandler->handleFile($data_source->path, $ext);
    			if (count($content)) {
    				$products->push([$data_source->name => $content]);
    			}
    		} else {
	    		// If API (JSON or XML)
	    		$content = file_get_contents($data_source->path);
				$file_info = new finfo(FILEINFO_MIME_TYPE);
				$mime_type = $file_info->buffer($content);

	    		if (in_array($mime_type, ['text/plain', 'text/xml'])) {
	    			$content = $this->apiHandler->handleApi($content, $mime_type);
	    			if (count($content)) {
	    				$products->push([$data_source->name => $content]);
	    			}
	    		}
			}
    	}
		return response()->json($products, 200);
    }
}
