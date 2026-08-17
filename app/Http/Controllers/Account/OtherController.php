<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OtherController extends Controller
{
    function login_history(Request $request)
    {
        $loginUser = loginAccount();
        $query = AdminLoginHistory::query()
            ->where('user_id', $loginUser['account_id']);
        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('device', 'like', "%{$search}%")
                    ->orWhere('browser', 'like', "%{$search}%");
            });
        }

        // Example filter
        if ($request->filled('device')) {
            $query->where('device', $request->device);
        }

        // Date filter
        if ($request->filled('date_range')) {
            match ($request->date_range) {
                'Today' => $query->whereDate('created_at', today()),

                'Last 7 Days' => $query->where(
                    'created_at',
                    '>=',
                    now()->subDays(7)
                ),

                'Last 30 Days' => $query->where(
                    'created_at',
                    '>=',
                    now()->subDays(30)
                ),

                'This Year' => $query->whereYear(
                    'created_at',
                    now()->year
                ),

                default => null,
            };
        }

        // Sorting
        $allowedSorts = [
            'ip_address',
            'device',
            'browser',
            'created_at',
        ];

        $sort = in_array($request->sort, $allowedSorts)
            ? $request->sort
            : 'created_at';

        $direction = $request->direction === 'asc'
            ? 'asc'
            : 'desc';

        $query->orderBy($sort, $direction);

        // Rows per page
        $perPage = in_array(
            (int) $request->per_page,
            [10, 25, 50, 100]
        )
            ? (int) $request->per_page
            : 10;

        $loginHistories = $query
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'account.login-history',
            compact('loginHistories')
        );
    }

    public function destroy(AdminLoginHistory $loginHistory)
    {
        $loginUser = loginAccount();
        abort_if(
            $loginHistory->user_id !== $loginUser['account_id'],
            403
        );

        $loginHistory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Login history deleted successfully.',
        ]);
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);
        $loginUser = loginAccount();
        AdminLoginHistory::where('user_id', $loginUser['account_id'])
            ->whereIn('id', $request->ids)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected login histories deleted successfully.',
        ]);
    }

    function login_history_data(Request $request)
    {
        $loginUser = loginAccount();
        $query = AdminLoginHistory::query()
            ->where('user_id', $loginUser['account_id']);

        return DataTables::eloquent($query)

            ->addColumn('logged_in_at', function ($row) {
                return datetimeFormat($row->logged_in_at);
            })
            ->addColumn('ip_address', function ($row) {
                return $row->ip_address;
            })
            ->addColumn('browser', function ($row) {
                return $row->user_agent;
            })
            ->addColumn('logged_out_at', function ($row) {
                $html = '';
                if ($row->is_active == 0):
                    $html .= dateFormat($row->logged_out_at) . ',';
                    $html .= '<span class="text-muted">' . (timeFormat($row->logged_out_at)) . '</span>';
                else:
                    $html .= '<span
                        class="badge bg-success-subtle text-success badge-label fs-xxs fw-semibold">
                        Session Active</span>';
                endif;
                return $html;
            })

            ->rawColumns(['logged_out_at'])

            ->toJson();
    }
}
