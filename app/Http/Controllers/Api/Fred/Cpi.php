<?php

namespace App\Http\Controllers\Api\Fred;

use App\Models\Das;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orion\Http\Controllers\Controller;

class Cpi extends Controller
{
    protected $model = Das::class;

    protected $connection = 'fred';

    protected $table = 'cpi';

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

        $query->orderBy('date', 'desc');
        $cpiData = $query->get();

        $collection = $cpiData->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date)->format('Y-m-d'),
                'cpi' => number_format($item->cpi, 2, '.', ''),
            ];
        });

        return $collection;
    }
}
