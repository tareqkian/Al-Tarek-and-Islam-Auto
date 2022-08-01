<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMenuItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuItemRequest $request)
    {
        $data = $request->validated();
        $data['url'] = "";
        $data['order'] = MenuItem::highestOrderMenuItem($data);
        $data['importedComponent'] = $data['selectedComponent']['componentImported'] ?? null;
        unset($data['selectedComponent']);
        $item = MenuItem::create($data);
        return new MenuItemResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function show(MenuItem $menuItem,$id)
    {
        return MenuItemResource::collection(Menu::findOrFail($id)->parentItems);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMenuItemRequest  $request
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMenuItemRequest $request, $id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $data = $request->validated();
        $data['importedComponent'] = $data['selectedComponent']['componentImported'] ?? null;
        unset($data['menu_id']);
        unset($data['menu_name']);
        unset($data['selectedComponent']);
        $menuItem->update($data);
        return new MenuItemResource($menuItem);
    }
    public function updateOrder(Request $request, MenuItem $menuItem)
    {
        $this->order($request->all(),null);
        return $request->all();
    }
    private function order($arr, $parentID) {
        foreach ($arr as $index => $item) {
            $itemMenu = MenuItem::findOrFail($item['id']);
            $itemMenu->order = $index+1;
            $itemMenu->parent_id = $parentID;
            $itemMenu->save();
            if ( isset($item['children']) ) {
                $this->order($item['children'], $item['id']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuItem $menuItem,$id)
    {
        $item = MenuItem::findOrFail($id);
        if ( $item->children ) {
            $this->removeChild($item->children);
        }
        $item->delete();
        return ['status' => 200];
    }
    private function removeChild($children) {
        foreach ($children as $child) {
            $child = MenuItem::findOrFail($child->id);
            if ( $child->children ) {
                $this->removeChild($child->children);
            }
            $child->delete();
        }
    }
}
