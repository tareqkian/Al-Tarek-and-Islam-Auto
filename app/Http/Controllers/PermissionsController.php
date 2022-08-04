<?php

namespace App\Http\Controllers;

use App\Events\PermissionsGenerator;
use App\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
//    $permissions = Permission::groupBy('table_name')->get();
//    return PermissionResource::collection($permissions);

    $permissions = Permission::select('table_name', Permission::raw("GROUP_CONCAT(JSON_OBJECT('key',`key`,'id',`id`)) total"))
      ->groupBy('table_name')
      ->get();
    return [
      "data" => $permissions
    ];
  }

  public function current()
  {
    return Auth::user()->role->permissions->pluck("key")->toArray();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StorePermissionRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StorePermissionRequest $request)
  {
    $permissions = Permission::generateFor($request->input('name'));
    broadcast(new PermissionsGenerator(PermissionResource::collection($permissions)));

    $role = Role::where('name', 'admin')->firstOrFail();
    $permissions = Permission::all();
    $role->permissions()->sync(
      $permissions->pluck('id')->all()
    );

    return $permissions;
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function show(Permission $permission)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdatePermissionRequest  $request
   * @param  \App\Models\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function update(UpdatePermissionRequest $request, Permission $permission)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function destroy(Permission $permission)
  {
    //
  }
}
