<?php

namespace App\Models\WarehouseDatabase;

class Cpi extends BaseModel
{
    protected $connection = 'warehouse';
    protected $table = 'cpi';
    
    protected $fillable = [
        'date',
        'cpi'
    ];

    protected $casts = [
        'date' => 'date',
        'cpi' => 'float'
    ];
} 