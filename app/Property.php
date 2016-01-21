<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $primary_key = 'property_id';
    public $timestamps = false;
}
