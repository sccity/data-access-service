<?php

namespace App\Http\Resources\Pelorus;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'first_name' => $this->FirstName,
            'middle_name' => $this->MiddleName,
            'last_name' => $this->LastName,
            'department' => $this->Department,
            'position' => $this->Position,
            'type' => $this->Type,
            'hire_date' => $this->HireDate,
            'rehire_date' => $this->RehireDate,
            'termination_date' => $this->TerminationDate,
            'status' => $this->Status
        ];
    }
}
