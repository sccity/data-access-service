<?php

namespace App\Http\Controllers\Api\Finance;

use App\Models\Das;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orion\Http\Controllers\Controller;

class Pastdue extends Controller
{
    protected $model = Das::class;

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
        $currentDate = new DateTime;
        $balanceMonth = ($currentDate->format('m') >= 3) ? $currentDate->format('m') - 2 : $currentDate->format('m') + 10;
        $balanceYear = ($currentDate->format('m') >= 3) ? $currentDate->format('Y') : $currentDate->format('Y') - 1;
        $balanceDate = new DateTime;
        $balanceDate->setDate($balanceYear, $balanceMonth, 25);
        $balanceDate = $balanceDate->format('Y-m-d');

        $results = DB::connection('finance')->select('EXEC [999].[GetAccountBalances] @BalanceDate = ?', [$balanceDate]);

        $collection = collect($results);
        $data = [
            'summary' => [
                'balance_date' => $balanceDate,
                'total_accounts' => count($collection),
            ],
            'data' => $collection->map(function ($item) {
                return [
                    'AccountNo' => $item->AccountNo,
                    'Location' => $item->Location,
                    'Customer' => $item->Customer,
                    'Address1' => $item->Address1,
                    'Address2' => $item->Address2,
                    'City' => $item->City,
                    'State' => $item->State,
                    'Zip' => $item->Zip,
                    'PrimaryPhone' => $item->PrimaryPhone,
                    'SecondaryPhone' => $item->SecondaryPhone,
                    'PrimaryEmail' => $item->PrimaryEmail,
                    'SecondaryEmail' => $item->SecondaryEmail,
                    'NoPenalty' => $item->NoPenalty,
                    'NoDisconnect' => $item->NoDisconnect,
                    'DelinquentAmount' => number_format($item->DelinquentAmount, 2, '.', ''),
                    'Balance' => number_format($item->Balance, 2, '.', ''),
                ];
            })->toArray(),
        ];

        return response()->json($data);
    }
}
