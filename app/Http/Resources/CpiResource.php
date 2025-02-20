<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CpiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'date' => $this->date->format('Y-m-d'),
            'cpi' => $this->cpi,
        ];
    }
} 