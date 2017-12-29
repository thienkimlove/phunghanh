<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class CategoriesController extends Controller
{

    public $model = 'categories';

    public $validator = [
        'name' => 'required'
    ];
    private function init()
    {
        return '\\App\\' . ucfirst(str_singular($this->model));
    }
    public function index(Request $request)
    {

        $searchContent = '';
        $modelClass = $this->init();

        $customUrl = $this->model.'?init=1';

        $contents = $modelClass::latest('created_at');
        if ($request->input('q')) {
            $searchContent = urldecode($request->input('q'));
            $contents = $contents->where('name', 'LIKE', '%'. $searchContent. '%');
            $customUrl .= '&q='.$searchContent;
        }

        $contents = $contents->paginate(10);
        $contents->withPath($customUrl);

        return view('v2.'.$this->model.'.index', compact('contents', 'searchContent'))->with('model', $this->model);
    }

    public function create()
    {
        $modelClass = $this->init();
        $content = new $modelClass;
        return view('v2.'.$this->model.'.form', compact('content'))->with('model', $this->model);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validator);
        if ($validator->fails()) {
            return redirect($this->model.'/create')
                ->withErrors($validator)
                ->withInput();
        }

        $modelClass = $this->init();
        $modelClass::create($request->all());
        flash()->success('Success created!');
        return redirect($this->model);
    }
    public function edit($id)
    {
        $modelClass = $this->init();
        $content = $modelClass::find($id);
        return view('v2.'.$this->model.'.form', compact('content'))->with('model', $this->model);
    }
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), $this->validator);
        if ($validator->fails()) {
            return redirect($this->model.'/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }
        $modelClass = $this->init();
        $content = $modelClass::find($id);
        $content->update($request->all());
        flash()->success('Success edited!');
        return redirect($this->model);
    }
    public function destroy($id)
    {
        $modelClass = $this->init();
        $content = $modelClass::find($id);
        $content->delete();
        flash()->success('Success Deleted!');
        return redirect($this->model);
    }
}
