<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \File;
class TranslatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        foreach (glob(public_path()."\\trans\\**") as $key => $item) {
            $locale = explode('\\',$item)[count(explode('\\',$item))-1];
            $data[$locale] = [];
        }
        foreach (glob(public_path()."\\trans\\**\\**\\*.json") as $key => $item) {
            $locale = explode('\\',$item)[count(explode('\\',$item))-3];
            $page = explode('\\',$item)[count(explode('\\',$item))-2];
            $fileName = explode('\\',$item)[count(explode('\\',$item))-1];
            $fileName = substr($fileName, 0,strrpos($fileName, '.'));
            $data[$locale][$page][$fileName] = json_decode(file_get_contents($item));
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $locales = glob("trans/**");
        foreach ($locales as $item) {
            $path = public_path($item."/".$request->input('parent')."/");
            if ( !File::exists($path) ) {
                File::makeDirectory($path, 0777, true);
            }
            $filePath = public_path($item."/".$request->input('parent')."/".$request->input('route').".json");
            File::put($filePath,"");
        }
        return ["status" => 200];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function showFile(Request $request)
    {
        $fileGlob = glob("trans/**/".$request->input('parent')."/".$request->input('route').".json");
        $pageTrans = [];
        if ( count($fileGlob) ) {
            foreach ($fileGlob as $key => $item) {
                $locale = explode('/',$item)[count(explode('/',$item))-3];
                $fileName = explode('/',$item)[count(explode('/',$item))-1];
                $fileName = substr($fileName, 0,strrpos($fileName, '.'));
                $pageTrans[$locale] = json_decode(File::get($item));
            }    
        }
        return $pageTrans;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locales = glob("trans/**");
        foreach ($locales as $item) {
            $locale = explode('/',$item);
            $locale = end($locale);
            $path = public_path($item."/".$request[0]['parent']."/");
            if ( !File::exists($path) ) {
                File::makeDirectory($path, 0777, true);
            }
            $filePath = public_path($item."/".$request[0]['parent']."/".$request[0]['route'].".json");
            File::put($filePath,json_encode($request[1][$locale]));
        }
        return json_encode($request[1]["en"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
