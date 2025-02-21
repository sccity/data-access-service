<?php

namespace App\Http\Controllers\Api\Finance;

use App\Models\Das;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orion\Http\Controllers\Controller;

class Budget extends Controller
{
    protected $model = Das::class;

    protected $connection = 'finance';

    protected $table = '999.AllDepartmentsBudget';

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

        $query->orderBy('YearEnd', 'desc');

        if ($request->has('fy')) {
            $fiscalYear = (int) $request->input('fy');
            $query->whereYear('YearEnd', $fiscalYear);
        }

        if ($request->has('fund')) {
            $fund = (int) $request->input('fund');
            $query->where('FundNo', $fund);
        }

        if ($request->has('gl')) {
            $gl = $request->input('gl');
            $query->where('AccountNo', $gl);
        }

        $budgetData = $query->select(
            'FundNo',
            'AccountNo',
            'AccountName',
            'Type',
            'YearEnd',
            'Amount'
        )->get();

        $formattedData = $budgetData->map(function ($budget) {
            return [
                'fund_no' => $budget->FundNo,
                'gl_account' => $budget->AccountNo,
                'account_name' => $budget->AccountName,
                'type' => $budget->Type,
                'budget_year' => Carbon::parse($budget->YearEnd)->format('Y-m-d'),
                'amount' => number_format($budget->Amount, 2, '.', ''),
            ];
        });

        return $formattedData;
    }
}
