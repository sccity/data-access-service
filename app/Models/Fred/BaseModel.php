<?php

namespace App\Models\Fred;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $connection = 'fred';
}
