<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DataTables;

class Connection extends Model
{

    protected $fillable = [
        'name',
        'callback',
        'map_params',
        'extend_params',
    ];

    public static function getDataTables($request)
    {
        $connection = static::select('*');

        return DataTables::of($connection)
            ->filter(function ($query) use ($request) {
                if ($request->has('name')) {
                    $query->where('name', 'like', '%' . $request->get('name') . '%');
                }
            })
            ->addColumn('action', function ($connection) {
                return '<a class="table-action-btn" title="Chỉnh sửa Connection" href="' . route('connections.edit', $connection->id) . '"><i class="fa fa-pencil text-success"></i></a>';

            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
