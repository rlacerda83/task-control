<?php

namespace App\Repository;

use App\Helpers\UserLogged;
use App\Models\Configuration;
use App\Models\System\User;
use DB;

class ConfigurationRepository extends AbstractRepository
{
    protected $rules = [
        'email' => 'required|email',
        'url_form' => 'required|url',
    ];

    protected $table;

    /**
     * @var User
     */
    protected $user;

    public function __construct()
    {
        $this->user = UserLogged::get();
        $this->table = Configuration::getTableName();
    }

    /**
     * @return mixed
     */
    public function findFirst()
    {
        return DB::table($this->table)
            ->where('user_id', $this->user->id)
            ->orderBy('id')->first();
    }

    public function getValue($value)
    {
        $configuration = $this->findFirst();
        return isset($configuration->$value) ? $configuration->$value : null;
    }

}