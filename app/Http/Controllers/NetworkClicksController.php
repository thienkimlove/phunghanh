<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class NetworkClicksController extends AdminController
{

    public $model = 'network_clicks';

    public $validator = [

    ];
    private function init()
    {
        return '\\App\\' . ucfirst(camel_case(str_singular($this->model)));
    }
    public function index(Request $request)
    {

        $searchContent = '';
        $modelClass = $this->init();

        $customUrl = 'admin/'.$this->model.'?init=1';

        $contents = $modelClass::latest('created_at');

        $contents = $contents->paginate(10);
        $contents->withPath($customUrl);

        return view('admin.'.$this->model.'.index', compact('contents', 'searchContent'))->with('model', $this->model);
    }


}
