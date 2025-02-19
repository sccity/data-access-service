<?php

namespace App\Models\WarehouseDatabase;

use App\Models\WarehouseDatabase\BaseModel;

class Fred extends BaseModel
{
    protected $table = 'cpi';
    
    protected $fillable = [
        'id',
        'date',
        'cpi',
        // Add other fields as needed
    ];
} 