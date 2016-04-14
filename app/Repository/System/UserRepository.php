<?php

namespace App\Repository\System;

use App\Helpers\Filter;
use App\Repository\AbstractRepository;
use DB;
use Illuminate\Http\Request;

class UserRepository extends AbstractRepository
{
    protected $table = 'system_user';

    protected $rules = [
        'name' => 'required|max:200',
        'password' => 'required',
        'email' => 'required|email',
        'status' => 'required'
    ];

    public function getAllPaginate(Request $request, $itemsPage = 30)
    {
        $fields = [
            "{$this->table}.*",
            "system_profile.name as profile_name"
        ];

        $query = DB::table($this->table);
        $query->select($fields);
        $query->join('system_profile', "{$this->table}.profile_id", '=', 'system_profile.id');
        $query = Filter::parse($request, $query, $this->table);
        $query->orderBy($request->get('sort'), $request->get('order', 'ASC'));

        $paginator = $query->paginate($itemsPage);
        $paginator->appends(app('request')->except('page'));
        return $paginator;
    }

}