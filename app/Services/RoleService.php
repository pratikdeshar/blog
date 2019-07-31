<?php

namespace App\Services;


use App\Entities\Role;
use App\Services\Base\BaseService;
use Illuminate\Database\Eloquent\Model;

class RoleService extends  BaseService
{

    /**
     * @return Model
     */
    public function model()
    {
        return new Role();
    }
}
