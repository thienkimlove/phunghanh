<?php

namespace App\Http\Controllers;

use App\Http\Requests\NetworkRequest;
use App\Network;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NetworksController extends Controller
{

    public function index()
    {
        return view('v2.networks.index');
    }

    public function create()
    {
        return view('v2.networks.create');
    }

    public function store(NetworkRequest $request)
    {
        $request->store();

        flash()->success('Success!', 'Network successfully created.');

        return redirect()->route('networks.index');
    }

    public function edit($id)
    {
        $network = Network::find($id);

        return view('v2.networks.edit', compact('network'));
    }

    public function update(NetworkRequest $request, $id)
    {
        $request->save($id);

        flash()->success('Thành công', 'Cập nhật thành công!');

        return redirect()->route('networks.index');
    }

    public function connect($id)
    {
        $content = Network::find($id);
        return response()->json(['html' => view('v2.how', compact('content'))->render()]);
    }

    public function dataTables(Request $request)
    {
        return Network::getDataTables($request);
    }

    public function destroy($id) {
        Network::find($id)->delete();
        flash()->success('Success', 'Network đã xóa thành công!');
        return response()->json(['status' => true]);
    }

}
