<?php

namespace App\Models\WarehouseDatabase;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * Get the connection name for the model.
     *
     * @return string
     */
    protected $connection = 'warehouse';
} 