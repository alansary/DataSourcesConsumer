<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=DataSourcesSeeder

        DB::table('data_sources')->insert([
        	[
        		'id' => 1,
        		'name' => 'JSON_API',
        		'description' => 'JSON_API',
        		'path' => 'https://jsonplaceholder.typicode.com/posts/',
        		'created_at' => Carbon::now(),
        		'updated_at' => Carbon::now()
        	],
        	[
        		'id' => 2,
        		'name' => 'XML_API',
        		'description' => 'XML_API',
        		'path' => 'http://api.plos.org/search?q=title:%22Drosophila%22%20and%20body:%22RNA%22&fl=id,abstract',
        		'created_at' => Carbon::now(),
        		'updated_at' => Carbon::now()
        	],
        	[
        		'id' => 3,
        		'name' => 'JSON_FILE',
        		'description' => 'JSON_FILE',
        		'path' => 'https://www.reddit.com/r/all.json',
        		'created_at' => Carbon::now(),
        		'updated_at' => Carbon::now()
        	],
        	[
        		'id' => 4,
        		'name' => 'XML_FILE',
        		'description' => 'XML_FILE',
        		'path' => 'https://raw.githubusercontent.com/code4business/xmlimport/master/examples/example_product_import.xml',
        		'created_at' => Carbon::now(),
        		'updated_at' => Carbon::now()
        	],
        	[
        		'id' => 5,
        		'name' => 'CSV_FILE',
        		'description' => 'CSV_FILE',
        		'path' => 'https://perso.telecom-paristech.fr/eagan/class/igr204/data/cereal.csv',
        		'created_at' => Carbon::now(),
        		'updated_at' => Carbon::now()
        	],
        	[
        		'id' => 6,
        		'name' => 'EXCEL_FILE',
        		'description' => 'EXCEL_FILE',
        		'path' => 'https://inventory.data.gov/dataset/58fa1cd3-c1bf-4492-964d-f994b26a6cae/resource/f6d8dd83-3080-470f-b453-03f8ead0228f/download/time-to-hire-data-file.xlsx',
        		'created_at' => Carbon::now(),
        		'updated_at' => Carbon::now()
        	]
        ]);
    }
}
