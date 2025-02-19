<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FredResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'cpi' => $this->cpi,
        ];
    }
} 