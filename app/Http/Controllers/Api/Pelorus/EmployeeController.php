<?php

namespace App\Http\Controllers\Api\Pelorus;

use Orion\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Pelorus\Employee;
use App\Http\Resources\Pelorus\EmployeeResource;

class EmployeeController extends Controller
{
    protected $model = Employee::class;
    protected $resource = EmployeeResource::class;

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return [
            'HireDate',
            'Employee'
        ];
    }

    /**
     * Determine if the user is authorized to perform an action.
     */
    public function authorize(string $ability, mixed $arguments = []): bool
    {
        // Anyone can view Employee data
        if (in_array($ability, ['viewAny', 'view'])) {
            return true;
        }

        // Only admin can modify
        if (in_array($ability, ['create', 'update', 'delete'])) {
            return auth()->user()->email === 'admin@santaclarautah.gov';
        }

        return false;
    }

    public function index(Request $request)
    {
        Log::info('Employee index endpoint hit', [
            'token' => $request->token,
            'user' => auth()->user(),
            'request_path' => $request->path()
        ]);

        $query = Employee::query();

        if ($request->has('start_date')) {
            $query->where('HireDate', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('HireDate', '<=', $request->end_date);
        }

        $query->orderBy('HireDate', 'desc');

        $EmployeeData = $query->get();

        Log::info('Employee data retrieved', [
            'count' => $EmployeeData->count()
        ]);

        return EmployeeResource::collection($EmployeeData);
    }
}
