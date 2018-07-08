<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpersController extends Controller
{
    public static function storeFile($url, $path)
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
