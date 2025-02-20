<?php

namespace App\Models\Pelorus;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $connection = 'pelorus';
}
