<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Report;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    public function index()
    {
        return view('v2.reports.index');
    }

    public function create()
    {
        return view('v2.reports.create');
    }

    public function store(ReportRequest $request)
    {
        $request->store();

        flash()->success('Success!', 'Successfully created.');

        return redirect()->route('reports.index');
    }

    public function dataTables(Request $request)
    {
        return Report::getDataTables($request);
    }
}
