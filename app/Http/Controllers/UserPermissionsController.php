<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserPermissionsController extends Controller
{
    public function index(Request $request, User $user)
    {
        if (Auth()->user()->can('role_access')) {
            if ($request->ajax()) {

                $usersModelHasPermission = DB::table('model_has_permissions as mhr')->get()->pluck('model_id');
                $users = $user::whereIn('id', $usersModelHasPermission)->get();

                return DataTables($users)
                    ->addColumn('full_name', function ($user) {
                        return str_replace('  ', ' ', $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name);
                    })
                    ->addColumn('permissions', function ($user) {
                        $permissions_array = $user->permissions->pluck('name');
                        $span = '';
                        foreach ($permissions_array as $permission) {
                            $span .= '<span class="bg-success rounded-pill d-inline-flex mb-1  pl-2 pr-2">' . $permission . '</span> ';
                        }
                        return $span;
                    })
                    ->addColumn('action', function ($user) {
                        $btn = '<a href="' . route('user_permission.edit', $user->id) . '" class="mx-auto bg-warning m-1 p-1 rounded"> <i class="far fa-pen"></i> Edit</a>';
                        return $btn;
                    })
                    ->rawColumns(['permissions', 'action'])
                    ->make(true);
            }
            return view('user-permission.index');
        } else return abort(404);
    }

    public function create(Request $request)
    {
        if (Auth()->user()->can('role_edit')) {

            $permissions = Permission::all()->pluck('name');

            if ($request->ajax()) {
                $users = User::limit(5)->select('id', 'first_name', 'middle_name', 'last_name', 'email', 'email_alternate')->get();
                return Response(json_encode($users));
            } else {
                return view('user-permission.create')->with(['permissions' => $permissions,]);
            }
        } else return abort(403, "you don't have permission!");
    }

    public function store(Request $request)
    {
        if (Auth()->user()->can('role_edit')) {
            $request->validate([
                'id' => 'required|integer',
                'permissions' => 'required|string',
            ]);
            $user = User::find($request->id);
            $user->syncPermissions($request->permissions);
            return Redirect::route('user_permission.index');
        } else return abort(404);
    }

    public function edit($id)
    {
        if (Auth()->user()->can('role_edit')) {
            $user = User::find($id);
            $permissions = Permission::all()->pluck('name');
            $userHasPermissions = $user->permissions->pluck('name');
            return view('user-permission.edit')->with(['user' => $user, 'permissions' => $permissions, 'userHasPermissions' => $userHasPermissions]);
        } else return abort(404);
    }

    public function update(Request $request, $id)
    {
        if (Auth()->user()->can('role_edit')) {
            $user = User::find($id);
            $user->syncPermissions($request->permissions);
            return Redirect::route('user_permission.index');
        } else return abort(404);
    }
}
