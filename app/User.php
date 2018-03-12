<?php

namespace App;

use DataTables;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public static function getDataTables($request)
    {
        $user = static::select('*');

        return DataTables::of($user)
            ->filter(function ($query) use ($request) {
                if ($request->has('name')) {
                    $query->where('name', 'like', '%' . $request->get('name') . '%');
                }

                if ($request->has('email')) {
                    $query->where('email', 'like', '%' . $request->get('email') . '%');
                }

            })
            ->addColumn('action', function ($user) {
                return '<a class="table-action-btn" title="Chỉnh sửa người dùng" href="' . route('users.edit', $user->id) . '"><i class="fa fa-pencil text-success"></i></a>';

            })
            ->editColumn('is_admin', function ($user) {
                return $user->is_admin ? '<i class="ion ion-checkmark-circled text-success"></i>' : '<i class="ion ion-close-circled text-danger"></i>';
            })
            ->rawColumns(['action', 'email', 'name', 'is_admin'])
            ->make(true);
    }
}
