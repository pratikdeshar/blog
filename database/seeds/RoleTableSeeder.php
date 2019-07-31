<?php

use App\Constants\RoleConstant;
use App\Services\RoleService;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * @var RoleService
     */
    private $roleService;

    /**
     * RoleTableSeeder constructor.
     * @param RoleService $roleService
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->roleService->updateOrCreate(
            ['name' => RoleConstant::ADMIN],
            [
                'name'         => RoleConstant::ADMIN,
                'display_name' => RoleConstant::ADMIN,
                'description'  => RoleConstant::ADMIN,
            ]
        );
        $this->roleService->updateOrCreate(
            ['name' => RoleConstant::RETAILER],
            [
                'name'         => RoleConstant::RETAILER,
                'display_name' => RoleConstant::RETAILER,
                'description'  => RoleConstant::RETAILER,
            ]
        );
        $this->roleService->updateOrCreate(
            ['name' => RoleConstant::SUPPLIER],
            [
                'name'         => RoleConstant::SUPPLIER,
                'display_name' => RoleConstant::SUPPLIER,
                'description'  => RoleConstant::SUPPLIER,
            ]
        );
    }
}
