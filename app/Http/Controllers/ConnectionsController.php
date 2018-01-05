<?php

namespace App\Http\Controllers;

use App\Connection;
use App\Http\Requests\ConnectionRequest;
use Illuminate\Http\Request;

class ConnectionsController extends Controller
{

    public function index()
    {
        return view('v2.connections.index');
    }

    public function create()
    {
        return view('v2.connections.create');
    }

    public function store(ConnectionRequest $request)
    {
        $request->store();

        flash()->success('Success!', 'Connection successfully created.');

        return redirect()->route('connections.index');
    }

    public function edit($id)
    {
        $connection = Connection::find($id);

        return view('v2.connections.edit', compact('connection'));
    }

    public function update(ConnectionRequest $request, $id)
    {
        $request->save($id);

        flash()->success('Thành công', 'Cập nhật thành công!');

        return redirect()->route('connections.index');
    }


    public function dataTables(Request $request)
    {
        return Connection::getDataTables($request);
    }

}
