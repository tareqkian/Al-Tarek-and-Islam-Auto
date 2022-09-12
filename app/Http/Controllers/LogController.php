<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogController extends Controller
{

  protected $request;

  private $log_viewer;

  /**
   * @var string
   */
  protected $view_log = 'laravel-log-viewer::log';

  public function __construct()
  {
    $this->log_viewer = new LaravelLogViewer();
    $this->request = app('request');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $files = array_filter($this->log_viewer->getFiles(true), function ($x){return $x !== 'laravel.log';});
    $data = [
      'files' => $files,
      'standardFormat' => true,
      'structure' => array_reduce($this->log_viewer->foldersAndFiles(),
        function ($a,$b) use ($files){
          if ( !is_array($b) ) {
            $rec = explode('\\',$b);
            if (in_array(last($rec),$files)) $a[] = last($rec);
          } else {
            foreach ($b as $item) {
              $arr = explode('\\',$item);
              if (in_array(last($arr),$files)) $a[$arr[count($arr) - 2]][] = last($arr);
            }
          }
          return $a;
        }, []),
      'storage_path' => $this->log_viewer->getStoragePath(),
    ];
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
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($payload)
  {
/*    $this->log_viewer->setFile($file);
    return $this->log_viewer->all();*/

    $folder = explode(',',$payload)[1];
    $file = explode(',',$payload)[0];

    if ( $folder ) {
      $this->log_viewer->setFolder($folder);
    }
    $this->log_viewer->setFile($file);

    return $this->log_viewer->all();
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
    //
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





  /**
   * @return bool|mixed
   * @throws \Exception
   */
  private function earlyReturn()
  {
    if ($this->request->input('f')) {
      $this->log_viewer->setFolder(Crypt::decrypt($this->request->input('f')));
    }

    if ($this->request->input('dl')) {
      return $this->download($this->pathFromInput('dl'));
    } elseif ($this->request->has('clean')) {
      app('files')->put($this->pathFromInput('clean'), '');
      return $this->redirect(url()->previous());
    } elseif ($this->request->has('del')) {
      app('files')->delete($this->pathFromInput('del'));
      return $this->redirect($this->request->url());
    } elseif ($this->request->has('delall')) {
      $files = ($this->log_viewer->getFolderName())
        ? $this->log_viewer->getFolderFiles(true)
        : $this->log_viewer->getFiles(true);
      foreach ($files as $file) {
        app('files')->delete($this->log_viewer->pathToLogFile($file));
      }
      return $this->redirect($this->request->url());
    }
    return false;
  }

  /**
   * @param string $input_string
   * @return string
   * @throws \Exception
   */
  private function pathFromInput($input_string)
  {
    return $this->log_viewer->pathToLogFile(Crypt::decrypt($this->request->input($input_string)));
  }

  /**
   * @param $to
   * @return mixed
   */
  private function redirect($to)
  {
    if (function_exists('redirect')) {
      return redirect($to);
    }
    return app('redirect')->to($to);
  }

  /**
   * @param string $data
   * @return mixed
   */
  private function download($data)
  {
    if (function_exists('response')) {
      return response()->download($data);
    }

    // For laravel 4.2
    return app('\Illuminate\Support\Facades\Response')->download($data);
  }

}
