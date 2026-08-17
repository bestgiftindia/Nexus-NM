<?php

namespace App\Http\Controllers\Account;

use App\Enums\Message;
use App\Http\Controllers\Controller;
use App\Services\flashService;
use Illuminate\Http\Request;
// use Spatie\Permission\Models\Role;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public $flasherService;

    function __construct(flashService $flasher)
    {
        $this->flasherService = $flasher;
    }

    public function index()
    {
        return view('account.roles.lists');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('account.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'exists:permissions,name',
            ],
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(
            $request->input('permissions', [])
        );

        $this->flasherService->successService(Message::ROLESAVE->value);

        return redirect()
            ->route('account.roles.index');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();

        $rolePermissions = $role->permissions
            ->pluck('name')
            ->toArray();

        return view(
            'account.roles.edit',
            compact('role', 'permissions', 'rolePermissions')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->ignore($role->id),
            ],
            'is_publish' => ['nullable', 'boolean'],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'exists:permissions,name',
            ],
        ]);

        if ($request->name) {
            $role->update([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);
        }

        if ($request->has('is_publish')) {
            $role->update([
                'is_publish' => $request->is_publish,
            ]);
        }



        $role->syncPermissions(
            $request->input('permissions', [])
        );

        $this->flasherService->successService(Message::ROLEUPDATE->value);

        return redirect()->back();
    }



    public function destroy(Role $role)
    {
        try {

            $role->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Role deleted successfully.'
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
            'id' => 'required|exists:roles,id',
            'is_publish' => 'required|boolean',
        ]);

        $role = Role::findOrFail($request->id);

        $role->update([
            'is_publish' => $request->is_publish,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role status updated successfully.',
        ]);
    }


    function list_data(Request $request)
    {
        $loginUser = loginAccount();
        $query = Role::orderBY('id', 'DESC');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', function ($role) {
                return $role->name;
            })
            ->addColumn('permissions', function ($role) {
                $permissions = $role->permissions->count();

                if ($permissions > 0) {
                    return '<span class="badge bg-primary-subtle text-primary badge-label fs-xxs fw-semibold">'
                        . $permissions . ' ' . ($permissions === 1 ? 'Permission' : 'Permissions') .
                        '</span>';
                }

                return '<span class="badge bg-danger-subtle text-danger badge-label fs-xxs fw-semibold"> No Permissions Assigned </span>';
            })
            ->addColumn('total_accounts', function ($role) {
                $users = $role->users->count();

                if ($users > 0) {
                    return '<span class="badge bg-info-subtle text-info badge-label fs-xxs fw-semibold">'
                        . $users . ' ' . ($users === 1 ? 'User' : 'Users') .
                        '</span>';
                }

                return '<span class="badge bg-danger-subtle text-danger badge-label fs-xxs fw-semibold"> No User Assigned </span>';
            })
            ->addColumn('action', function ($role) {

                $editUrl = route('account.roles.edit', $role->id);
                $deleteUrl = route('account.roles.destroy', $role->id);

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
                            data-id="' . $role->id . '"
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
