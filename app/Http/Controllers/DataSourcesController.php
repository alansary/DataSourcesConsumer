<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use stdClass;
use App\DataSource;

class DataSourcesController extends Controller
{
    public function create(Request $request)
    {
    	$inputs = [
    		'name' => $request->input('name'),
    		'description' => $request->input('description'),
    		'path' => $request->input('path')
    	];

    	$rules = [
    		'name' => 'string|required|unique:data_sources,name|max:191',
    		'description' => 'string|nullable|max:500',
    		'path' => 'url|required|max:191'
    	];

    	$messages = [];

    	$validator = Validator::make($inputs, $rules, $messages);

    	if ($validator->fails()) {
    		$resp = new stdClass;
    		$resp->status = false;
    		$resp->message = implode(' ', $validator->errors()->all());
    		return response()->json($resp, 422);
    	}

    	$data_source = new DataSource;
    	foreach ($inputs as $key => $value) {
	    	$data_source->{$key} = $value;
    	}
    	$data_source->save();

    	$resp = new stdClass;
    	$resp->status = true;
    	$resp->message = 'Data source created successfully';
    	$resp->data = $data_source;

    	return response()->json($resp, 200);
    }

    public function all(Request $request) {
    	$resp = new stdClass;
    	$resp->status = true;
    	$resp->message = 'Data sources retrieved successfully';
    	$resp->data = DataSource::all();

    	return response()->json($resp, 200);
    }
}