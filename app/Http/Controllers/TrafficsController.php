<?php

namespace App\Http\Controllers;

use App\Traffic;
use Illuminate\Http\Request;

class TrafficsController extends Controller
{

    public function index()
    {
        return view('v2.traffics.index');
    }


    public function dataTables(Request $request)
    {
        return Traffic::getDataTables($request);
    }
}
