<?php

use App\Services\UserService;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder
 */
class UserTableSeeder extends Seeder
{
    /**
     * @var \App\Services\UserService
     */
    private $userService;

    /**
     * UserTableSeeder constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'first_name'        => 'admin',
            'last_name'         => 'admin',
            'email'             => 'admin@admin.com',
            'post_code'         => '5142',
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'remember_token'    => str_random(10),
        ];

        $user = $this->userService->updateOrCreate(['email' => $user['email']], $user);
        $user->roles()->sync([\App\Constants\RoleConstant::ADMIN_ID]);
    }
}
