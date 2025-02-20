<?php

namespace App\Models\Fred;

class Cpi extends BaseModel
{
    protected $connection = 'fred';
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
