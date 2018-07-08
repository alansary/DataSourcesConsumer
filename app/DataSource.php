<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    protected $table = 'data_sources';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
    	'name', 'description', 'path'
    ];
}
