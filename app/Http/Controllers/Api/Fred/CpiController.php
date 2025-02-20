<?php

namespace App\Http\Controllers\Api\Fred;

use Orion\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Fred\Cpi;
use App\Http\Resources\Fred\CpiResource;

class CpiController extends Controller
{
    protected $model = Cpi::class;
    protected $resource = CpiResource::class;

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return [
            'date',
            'cpi'
        ];
    }

    /**
     * Determine if the user is authorized to perform an action.
     */
    public function authorize(string $ability, mixed $arguments = []): bool
    {
        // Anyone can view CPI data
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
        Log::info('CPI index endpoint hit', [
            'token' => $request->token,
            'user' => auth()->user(),
            'request_path' => $request->path()
        ]);

        $query = Cpi::query();

        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $query->orderBy('date', 'desc');
        $cpiData = $query->get();

        Log::info('CPI data retrieved', [
            'count' => $cpiData->count()
        ]);

        return CpiResource::collection($cpiData);
    }
}
