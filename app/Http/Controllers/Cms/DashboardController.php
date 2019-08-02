<?php

namespace App\Http\Controllers\Cms;

use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * DashboardController constructor.
     * @param  UserService  $userService
     */
    public function __construct(
        UserService $userService
    ) {
        $this->userService          = $userService;
    }

    /**
     * Dashboard
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('cms.dashboard.index');
    }

}
