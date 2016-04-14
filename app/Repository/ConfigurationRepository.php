<?php

namespace App\Repository;

use App\Models\Configuration;
use DB;

class ConfigurationRepository extends AbstractRepository
{
    protected $rules = [
        'email' => 'required|email',
        'url_form' => 'required|url',
    ];

    protected $table;

    public function __construct()
    {
        $this->table = Configuration::getTableName();
    }

    /**
     * @return mixed
     */
    public function findFirst()
    {
        return DB::table($this->table)->orderBy('id')->first();
    }

    public function getValue($value)
    {
        $configuration = $this->findFirst();
        return isset($configuration->$value) ? $configuration->$value : null;
    }

}