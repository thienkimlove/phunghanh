<?php

namespace App\Http\Controllers;

use App\NetworkClick;
use Illuminate\Http\Request;

class NetworkClicksController extends Controller
{

    public function index()
    {
        return view('v2.leads.index');
    }

    public function dataTables(Request $request)
    {
        return NetworkClick::getDataTables($request);
    }

    public function export(Request $request)
    {
        return NetworkClick::exportToExcel($request);
    }


}
