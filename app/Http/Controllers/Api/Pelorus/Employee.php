<?php

namespace App\Http\Controllers\Api\Pelorus;

use Orion\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Das;

class Employee extends Controller
{
    protected $model = Das::class;
    protected $connection = 'pelorus';
    protected $table = '999.Employees';

    public function authorize(string $ability, mixed $arguments = []): bool
    {
        if (in_array($ability, ['viewAny', 'view'])) {
            return true;
        }

        if (in_array($ability, ['create', 'update', 'delete'])) {
            return auth()->user()->email === 'admin@santaclarautah.gov';
        }

        return false;
    }

    public function index(Request $request)
    {
        $query = DB::connection($this->connection)->table($this->table);

        $query->orderBy('HireDate', 'desc');

        $EmployeeData = $query->get();

        $formattedData = $EmployeeData->map(function ($employee) {
            return [
                'first_name' => $employee->FirstName,
                'middle_name' => $employee->MiddleName,
                'last_name' => $employee->LastName,
                'department' => $employee->Department,
                'position' => $employee->Position,
                'type' => $employee->Type,
                'hire_date' => Carbon::parse($employee->HireDate)->format('Y-m-d'),
                'rehire_date' => Carbon::parse($employee->RehireDate)->format('Y-m-d'),
                'termination_date' => Carbon::parse($employee->TerminationDate)->format('Y-m-d'),
                'status' => $employee->Status
            ];
        });

        return $formattedData;
    }
}
