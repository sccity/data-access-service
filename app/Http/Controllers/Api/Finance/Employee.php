<?php

namespace App\Http\Controllers\Api\Finance;

use App\Models\Das;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orion\Http\Controllers\Controller;

class Employee extends Controller
{
    protected $model = Das::class;

    protected $connection = 'finance';

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

        if ($request->has('hire_date_start') && $request->has('hire_date_end')) {
            $hireStart = Carbon::parse($request->input('hire_date_start'));
            $hireEnd = Carbon::parse($request->input('hire_date_end'));
            $query->whereBetween('HireDate', [$hireStart, $hireEnd]);
        }

        if ($request->has('status')) {
            if ($request->input('status') == 1) {
                $query->where('Status', 'Active');
            } elseif ($request->input('status') == 0) {
                $query->where('Status', 'Terminated');
            }
        } else {
            $query->where('Status', 'Active');
        }

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
                'status' => $employee->Status,
            ];
        });

        return $formattedData;
    }
}
