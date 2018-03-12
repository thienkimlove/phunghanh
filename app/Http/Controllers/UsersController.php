<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use Illuminate\Http\Request;
use Validator;

class UsersController extends Controller
{

    public $model = 'users';

    public $validator = [
        'name' => 'required',
        'email' => 'required',
    ];
    private function init()
    {
        return '\\App\\' . ucfirst(str_singular($this->model));
    }
    public function index()
    {
        return view('v2.'.$this->model.'.index');
    }

    public function create()
    {
        return view('v2.'.$this->model.'.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validator);
        if ($validator->fails()) {
            return redirect($this->model.'/create')
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();
        $data['password'] =  Hash::make(time());
        if (!$request->get('is_admin')) {
            $data['is_admin'] = false;
        }
        $modelClass = $this->init();
        $modelClass::create($data);
        flash()->success('Success','Success created!');
        return redirect($this->model);
    }
    public function edit($id)
    {
        $modelClass = $this->init();
        $user = $modelClass::find($id);
        return view('v2.'.$this->model.'.edit', compact('user'));
    }
    public function update($id, Request $request)
    {
        $this->validator[] = ['email' => 'unique:users,email,'.$id];
        $validator = Validator::make($request->all(), $this->validator);
        if ($validator->fails()) {
            return redirect($this->model.'/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }
        $modelClass = $this->init();
        $content = $modelClass::find($id);
        $data = $request->all();
        if (!$request->get('is_admin')) {
            $data['is_admin'] = false;
        }
        $content->update($data);
        flash()->success('Success','Success edited!');
        return redirect($this->model);
    }
    public function dataTables(Request $request)
    {
        return User::getDataTables($request);
    }
}
