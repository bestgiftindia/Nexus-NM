<?php

namespace App\Http\Controllers\Account\User;

use App\Http\Controllers\Controller;
use App\Mail\AccountRegistered;
use App\Services\ActivityLogService;
use App\Services\flashService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use App\Enums\Message;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public $flasherService;
    public $logActivity;

    function __construct(flashService $flasher, ActivityLogService $logActivity)
    {
        $this->flasherService = $flasher;
        $this->logActivity = $logActivity;
    }

    function index()
    {
        return view('account.users.lists');
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        return view('account.users.create', compact('permissions', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'digits:10', 'unique:users,phone'],
            'phone_code' => ['required'],
            'role' => ['required', 'exists:roles,id'],
            'is_publish' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
            'profile'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($request) {

            $arrayData = [
                'user_id'  => generateUserId(),
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'phone_code'  => $request->phone_code,
                'is_publish'  => $request->is_publish,
                'password'    => bcrypt('12345678'),
            ];

            if (!empty($request->profile ?? '')) {
                $arrayData = array_merge($arrayData, ['avatar' => $request->profile ?? null]);
            }

            $user = User::create($arrayData);

            // Role Assign
            $role = Role::findById($request->role);
            $user->assignRole($role);

            // Direct Permissions
            if ($request->filled('permissions')) {
                $user->syncPermissions($request->permissions);
            }

            $this->logActivity->store(
                'User',
                'Create',
                $user->id,
                null,
                $user->load('roles', 'permissions')->toArray()
            );
        });

        $this->flasherService->successService(Message::USERSAVE->value);

        return redirect()->route('account.users.index');
    }

    public function edit(User $user)
    {
        $permissions = Permission::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        return view(
            'account.users.edit',
            compact('user', 'permissions', 'roles')
        );
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'digits:10', Rule::unique('users', 'phone')->ignore($user->id)],
            'phone_code' => ['required'],
            'role' => ['required', 'exists:roles,id'],
            'is_publish' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
            'profile'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($request, $user) {

            $oldData = $user->load('roles', 'permissions')->toArray();

            $arrayData = [
                'user_id'  => !empty($user->user_id) ? $user->user_id : generateUserId(),
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'phone_code'  => $request->phone_code,
                'is_publish'  => $request->is_publish,
            ];

            if (!empty($request->profile ?? '')) {
                $arrayData = array_merge($arrayData, ['avatar' => $request->profile ?? null]);
            }

            $user->update($arrayData);

            // Role Assign
            $role = Role::findById($request->role);
            $user->assignRole($role);

            // Direct Permissions
            if ($request->filled('permissions')) {
                $user->syncPermissions($request->permissions);
            }
            $newData = $user->fresh()->load('roles', 'permissions')->toArray();

            $this->logActivity->store(
                'User',
                'Update',
                $user->id,
                $oldData,
                $newData
            );
        });

        $this->flasherService->successService(Message::USERUPDATE->value);

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        try {
            $oldData = $user->load('roles', 'permissions')->toArray();
            $user->delete();

            $this->logActivity->store(
                'User',
                'Delete',
                $user->id,
                $oldData,
                null
            );

            return response()->json([
                'status'  => true,
                'message' => 'User deleted successfully.'
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
            'id' => 'required|exists:users,id',
            'is_publish' => 'required|boolean',
        ]);

        $user = User::findOrFail($request->id);

        $oldData = $user->toArray();

        $user->update([
            'is_publish' => $request->is_publish,
        ]);

        $newData = $user->fresh()->toArray();

        $this->logActivity->store(
            'User',
            'Status Update',
            $user->id,
            $oldData,
            $newData
        );

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
        ]);
    }


    function list_data(Request $request)
    {
        $loginUser = loginAccount();
        $query = User::orderBY('id', 'DESC');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('account_id', function ($user) {
                return '<a href="" class="fw-semibold link-reset">' . $user->user_id . '</a>';
            })
            ->addColumn('name', function ($user) {
                $imageComponent = new \App\View\Components\ImagePreview('users', $user->avatar);
                $imageComponent->pathName = 'users';
                $imageComponent->imageName = $user->avatar;

                $attributes = new ComponentAttributeBag(['class' => 'img-fluid rounded-circle', 'width' => '200']);
                $imageComponent->withAttributes($attributes->getAttributes());



                $html = '';
                $html .= '<div class="d-flex align-items-center gap-2">';
                $html .= '<div class="avatar avatar-sm">';
                $html .= view(
                    $imageComponent->render()->name(),
                    $imageComponent->data()
                )->render();
                $html .= '</div>';
                $html .= '<div>';
                $html .= '<h5 class="mb-0 lh-base fs-base">';
                $html .= '<a href="" class="link-reset">' . $user->name . '</a>';
                $html .= '</h5>';
                $html .= '<span class="badge badge-label bg-' . ($user->getRoleNames()->first() ? 'success' : 'danger') . '-subtle text-' . ($user->getRoleNames()->first() ? 'success' : 'danger') . ' " >' . e($user->getRoleNames()->first() ?? 'No Role') . '</span>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('contact_info', function ($user) {
                $html = '';
                $html .= '<div class="d-flex align-items-center gap-2">';
                $html .= '<div class="d-flex flex-column">';
                $html .= '<a href="tel:' . $user->phonecode->phonecode . $user->phone . '" class="text-muted fs-sm mb-0"><i data-lucide="phone"></i> +' . $user->phonecode->phonecode . '-' . $user->phone . '</a>';
                $html .= '<a href="mailto:' . $user->email . '" class="text-muted fs-sm mb-0"><i data-lucide="mail"></i> ' . $user->email . '</a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('action', function ($user) {

                $editUrl = route('account.users.edit', $user->id);
                $deleteUrl = route('account.users.destroy', $user->id);

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
                            data-id="' . $user->id . '"
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

            ->rawColumns(['status', 'account_id', 'name', 'contact_info', 'action'])

            ->toJson();
    }
}
