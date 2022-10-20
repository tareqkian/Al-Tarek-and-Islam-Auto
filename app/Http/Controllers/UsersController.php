<?php

namespace App\Http\Controllers;

use App\Events\MainEvent;
use App\Events\UsersAdder;
use App\Events\UsersDeleter;
use App\Events\UsersEditor;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UsersController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return UserResource::collection(User::all());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreUserRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreUserRequest $request)
  {
    $validated = $request->validated();
    if ( isset($validated['avatar']) && $validated['avatar'] ){
      $dpPath = $this->saveImage($validated['avatar']);
      $validated['avatar'] = $dpPath;
    }
    $validated['settings']["devices"] = [
      [ "device" => "Desktop", "count" => $validated['Desktop'] ],
      [ "device" => "Mobile", "count" => $validated['Mobile'] ]
    ];
    $validated['settings'] = json_encode($validated['settings']);
    unset($validated['Desktop']);
    unset($validated['Mobile']);
    $user = User::create($validated);
    broadcast(new UsersAdder(new UserResource($user)));
    return new UserResource($user);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateUserRequest  $request
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateUserRequest $request, User $user)
  {
    $validated = $request->validated();
    if ( isset($validated['avatar']) && $validated['avatar'] ){
      $dpPath = $this->saveImage($validated['avatar']);
      $validated['avatar'] = $dpPath;
      if ( $user->avatar && $user->avatar !== 'users/default.png' ) {
        $deletePath = public_path($user->avatar);
        File::delete($deletePath);
      }
    } else {
      unset($validated['avatar']);
    }
    $validated['settings']["devices"] = [
      [ "device" => "Desktop", "count" => $validated['Desktop'] ],
      [ "device" => "Mobile", "count" => $validated['Mobile'] ]
    ];
    unset($validated['Desktop']);
    unset($validated['Mobile']);
    $user->update($validated);
    broadcast(new MainEvent());
    broadcast(new UsersEditor(new UserResource($user)));
    return new UserResource($user);
  }

  public function changePassword(ChangePasswordRequest $request, $userId){
    $user = User::findOrFail($userId);
    $user->update([
      "password" => bcrypt($request->input("newPassword"))
    ]);
    return [];
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    if ( $user->avatar && $user->avatar !== 'users/default.png' ) {
      $deletePath = public_path($user->avatar);
      File::delete($deletePath);
    }
    broadcast(new UsersDeleter($user));
    $user->delete();
    return [ "status" => 204 ];
  }

  private function saveImage($avatar)
  {
    if ($avatar && preg_match('/^data:image\/(\w+);base64,/',$avatar,$type)) {
      $avatar = substr($avatar, strpos($avatar,',')+1);
      $type = strtolower($type[1]);
      if ( !in_array($type, ['jpg','jpeg','gif','png']) ){
        throw new \Exception("Invalid Type");
      }
      $avatar = str_replace(' ','+',$avatar);
      $avatar = base64_decode($avatar);
      if ( $avatar === false ) {
        throw new \Exception("base64_decode Error");

      }
    } else {
      throw new \Exception("did not match data URI with image data");
    }
    $dir = "users/";
    $file = Str::random().time().'.'.$type;
    $absolutePath = public_path($dir);
    $relativePath = $dir.$file;
    if ( !File::exists($absolutePath) ) {
      File::makeDirectory($absolutePath,0777,true);
    }
    file_put_contents($relativePath,$avatar);
    return $relativePath;
  }
}
