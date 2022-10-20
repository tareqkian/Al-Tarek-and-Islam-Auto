<?php

namespace App\Http\Controllers;

use App\Events\MenuAdder;
use App\Events\MenuDeleter;
use App\Events\MenuEditor;
use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuItemResource;
use App\Http\Resources\MenuResource;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MenuController extends Controller
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
    public function index()
    {
        return MenuResource::collection(Menu::all());
    }
    public function initROUTES()
    {
        return MenuResource::collection(Menu::with('items')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuRequest $request)
    {
        $menu = Menu::create($request->validated());
        broadcast(new MenuAdder(new MenuResource($menu)));
        return new MenuResource($menu);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */

    public function show(Menu $menu)
    {
        return new MenuResource($menu);
    }
    public function initROUTE($fullPath)
    {
        /* return MenuResource::collection(Menu::with('items')->get()); */
        return MenuItem::where('route',$fullPath)->with("menu",'translations')->get()->first();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMenuRequest  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());
        broadcast(new MenuEditor(new MenuResource($menu)));
        return new MenuResource($menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        broadcast(new MenuDeleter($menu));
        MenuItem::where("menu_id",$menu)->delete();
        $menu->delete();
        return ["status" => 204];
    }
}
