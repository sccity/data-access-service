<?php

namespace App\Http\Controllers\Api\Finance;

use App\Models\Das;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orion\Http\Controllers\Controller;

class ExpenseDetail extends Controller
{
    protected $model = Das::class;

    protected $connection = 'finance';

    protected $table = '999.AllDepartments';

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

        $query->where('AccountType', 'Expense');
        $query->orderBy('Date', 'desc');

        if ($request->has('fund')) {
            $fund = (int) $request->input('fund');
            $query->where('FundNo', $fund);
        }

        if ($request->has('gl')) {
            $gl = $request->input('gl');
            $query->where('AccountNo', $gl);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'));
            $query->whereBetween('Date', [$startDate, $endDate]);
        }

        $expenseData = $query->select(
            'FundNo',
            'AccountNo',
            'AccountName',
            'Type',
            'Description',
            'Date',
            'Amount'
        )->get();

        $formattedData = $expenseData->map(function ($expense) {
            return [
                'fund_no' => $expense->FundNo,
                'gl_account' => $expense->AccountNo,
                'account_name' => $expense->AccountName,
                'type' => $expense->Type,
                'description' => $expense->Description,
                'date' => Carbon::parse($expense->Date)->format('Y-m-d'),
                'amount' => number_format($expense->Amount, 2, '.', ''),
            ];
        });

        return $formattedData;
    }
}
