<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\flashService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use App\Enums\Message;
use App\Models\Role;

class PermissionController extends Controller
{
    public $flasherService;

    function __construct(flashService $flasher)
    {
        $this->flasherService = $flasher;
    }

    public function index()
    {
        return view('account.permission.lists');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.permission.create');
    }

    public function store(Request $request)
    {
        if (trim(!empty($request->name))) {
            $request->merge([
                'name' => Str::slug($request->name),
            ]);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name'),
            ],
        ], [], [
            'name' => 'permission name'
        ]);

        Permission::create([
            'name' => Str::slug($request->name),
        ]);

        $this->flasherService->successService(Message::PERMISSIONSAVE->value);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions(Permission::all());

        return redirect()
            ->route('account.permissions.index');
    }

    public function edit(Permission $permission)
    {
        return view(
            'account.permission.edit',
            compact('permission')
        );
    }
    public function update(Request $request, Permission $permission)
    {
        if (trim(!empty($request->name))) {
            $request->merge([
                'name' => Str::slug($request->name),
            ]);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')
                    ->ignore($permission->id),
            ],
            'is_publish' => ['nullable', 'boolean'],
        ]);

        $permission->update([
            'name' => Str::slug($request->name),
            'is_publish' => $request->is_publish,
        ]);

        $this->flasherService->successService(Message::PERMISSIONUPDATE->value);

        return redirect()->back();
    }

    public function destroy(Permission $permission)
    {
        try {

            $permission->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Permission deleted successfully.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:permissions,id',
            'is_publish' => 'required|boolean',
        ]);

        $role = Permission::findOrFail($request->id);

        $role->update([
            'is_publish' => $request->is_publish,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission status updated successfully.',
        ]);
    }


    function list_data(Request $request)
    {
        $loginUser = loginAccount();
        $query = Permission::orderBY('id', 'DESC');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', function ($permission) {
                return $permission->name;
            })
            ->addColumn('total_accounts', function ($permission) {
                $users = $permission->users->count();

                if ($users > 0) {
                    return '<span class="badge bg-info-subtle text-info badge-label fs-xxs fw-semibold">'
                        . $users . ' ' . ($users === 1 ? 'User' : 'Users') .
                        '</span>';
                }

                return '<span class="badge bg-danger-subtle text-danger badge-label fs-xxs fw-semibold"> No User Assigned </span>';
            })
            ->addColumn('action', function ($permission) {

                $editUrl = route('account.permissions.edit', $permission->id);
                $deleteUrl = route('account.permissions.destroy', $permission->id);

                $html = '';

                $html .= '<div class="dropdown">';
                $html .= '<button class="btn btn-default btn-icon btn-sm rounded-circle dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown">';
                $html .= '<i data-lucide="ellipsis-vertical" class="fs-lg text-black"></i>';
                $html .= '</button>';

                $html .= '<ul class="dropdown-menu dropdown-menu-end">';

                // Edit
                $html .= '<li>
                            <a class="dropdown-item" href="' . $editUrl . '">
                                <i data-lucide="square-pen" class="me-1"></i>
                                Edit
                            </a>
                        </li>';

                // Delete
                $html .= '<li>
                            <a href="javascript:void(0);"
                            class="dropdown-item text-danger delete-record"
                            data-id="' . $permission->id . '"
                            data-url="' . $deleteUrl . '">
                                <i data-lucide="trash-2" class="me-1"></i>
                                Delete
                            </a>
                        </li>';

                $html .= '</ul>';
                $html .= '</div>';

                return $html;
            })

            ->addColumn('status', function ($row) {
                $html = '';

                $html .= '<div class="form-check form-switch">';
                $html .= '<input type="checkbox" class="form-check-input status-switch" data-id="' . $row->id . '" ' . ($row->is_publish ? 'checked' : '') . '>';
                $html .= ' </div>';

                return $html;
            })

            ->rawColumns(['permissions', 'total_accounts', 'status', 'action'])

            ->toJson();
    }
}
