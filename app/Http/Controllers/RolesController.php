<?php

namespace App\Http\Controllers;

use App\Events\RoleAdder;
use App\Events\RoleDeletor;
use App\Events\RoleEditor;
use App\Events\RolePermissionsChange;
use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RolesController extends Controller
{

  public function __construct(Request $request)
  {
    App::setLocale($request->header('locale'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $roles = Role::with("translations")
      /*->translatedIn($request->header('locale'))*/
      ->get();
    return RoleResource::collection($roles);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreRoleRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRoleRequest $request)
  {
    $role = Role::create($request->validated());
    broadcast(new RoleAdder(new RoleResource($role)));
    return new RoleResource($role);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function show(Role $role)
  {
    return new RoleResource($role->load("permissions"));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateRoleRequest  $request
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Role $role)
  {
    $role->permissions()->sync($request->input('rolePermissions', []));
    broadcast(new RolePermissionsChange($role->permissions->pluck('key'),$role));
    return ["status" => 200];
  }

  public function updateRole(UpdateRoleRequest $request, $id) {
    $role = Role::findOrFail($id);
    $role->update($request->validated());
    broadcast(new RoleEditor(new RoleResource($role)));
    return new RoleResource($role);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function destroy(Role $role)
  {
    $deletedRole = $role;
    $role->permissions()->sync([]);
    $role->delete();
    broadcast(new RoleDeletor($deletedRole));
    return ['status' => 204];
  }
}
