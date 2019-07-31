<?php

namespace App\Services;


use App\Constants\RoleConstant;
use App\Events\UserCreated;
use App\Notifications\NewPasswordCreated;
use App\Notifications\MailTemplateNotification;
use App\Services\Base\BaseService;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class UserService
 * @package App\Http\Services
 */
class UserService extends BaseService
{

    /**
     * @return Model|string
     */
    public function model()
    {
        return new User();
    }

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all($columns = ['*'])
    {
        return $this->model->admins()->get();
    }

    /**
     * @param $insertData
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function create($insertData)
    {
        if (isset($insertData['image'])) {
            $insertData['image'] = store('image', 'users');
        }
        $insertData['password'] = bcrypt($insertData['password']);

        $user = $this->model->create($insertData);
        $user->roles()->attach([RoleConstant::ADMIN_ID]);

        return $user;
    }

    /**
     * @param array|Model|int $id
     * @param                 $data
     * @return bool|int
     * @throws \Throwable
     */
    public function update($id, $data)
    {
        $user       = $this->find($id);
        $updateData = array_filter($data);
        if (isset($data['image'])) {
            $updateData['image'] = store('image', 'users');
        }
        if (!empty($updateData['password'])) {
            $updateData['password'] = bcrypt($updateData['password']);
            $params                 = [
                'variables' => [
                    'NAME'   => $user->first_name,
                    'BUTTON' => view('front.emails.button')->with(['link' => route('front.login'), 'name' => 'Login'])
                        ->render(),
                ],
            ];
            $user->notify(new MailTemplateNotification('password_change_email', $params));
        }

        return $user->update($updateData);
    }

    /**
     * Get accounts
     *
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function accountsForDatatable()
    {
        $query = $this->model->whereHas('roles', function ($query) {
            $query->where('name', RoleConstant::RETAILER)->orWhere('name', RoleConstant::SUPPLIER);
        });
        if (!request()->has('order')) {
            return $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    /**
     * Create account for retailer or supplier
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|Model
     * @throws \Throwable
     */
    public function createAccount(array $data)
    {
        !isset($data['password']) ? $data['email_verified_at'] = Carbon::today()->format('Y-m-d H:i:s') : null;
        $newPassword      = $data['password'] ?? rand();
        $data['password'] = bcrypt($newPassword);
        if (!empty($data['send_password'])) {
            $data['password'] = bcrypt($newPassword);
        }
        if (!empty($data['send_password'])) {
            $data['reset_password'] = 1;
        }
        DB::beginTransaction();
        $account = $this->model->create($data);
        if (isset($data['roles'])) {
            $account->roles()->attach($data['roles']);
        }
        DB::commit();

        if (!empty($data['send_password'])) {
            $params = [
                'variables' => [
                    'NAME'     => $account->first_name,
                    'PASSWORD' => $newPassword,
                    'BUTTON'   => view('admin.emails.button')->with([
                        'link' => route('front.login'),
                        'name' => 'Get Started',
                    ])->render(),
                ],
            ];
            $account->notify(new MailTemplateNotification('new_password_generated', $params));
        }
        event(new UserCreated($account));

        return $account;
    }

    /**
     * Update and account
     *
     * @param array $data
     * @param       $id
     * @throws \Throwable
     */
    public function updateAccount(array $data, $id)
    {
        if ($sendPassword = $data['send_password'] ?? false) {
            $newPassword      = rand();
            $data['password'] = bcrypt($newPassword);
            $data['reset_password'] = 1;
        }
        $account = $this->findOrFail($id);
        DB::beginTransaction();
        $account->update($data);
        if (isset($data['roles'])) {
            $account->roles()->sync($data['roles']);
        }
        DB::commit();
        if (!empty($newPassword)) {
            //$account->notify(new NewPasswordCreated($newPassword));
            $params = [
                'variables' => [
                    'NAME'     => $account->first_name,
                    'PASSWORD' => $newPassword,
                    'BUTTON'   => view('admin.emails.button')->with([
                        'link' => route('login'),
                        'name' => 'Get Started',
                    ])->render(),
                ],
            ];
            $account->notify(new MailTemplateNotification('new_password_generated', $params));
        }
    }

    /**
     * Get all retailers for dropdown
     * @return \Illuminate\Support\Collection
     */
    public function retailersForDropDown()
    {
        return $this->model->retailers()->get()->pluck('full_name', 'id');
    }

    /**
     * get all suppliers for dropdown
     *
     * @return mixed
     */
    public function suppliersForDropdown()
    {
        return $this->model->suppliers()->get()->pluck('full_name', 'id');
    }

    /**
     * @param $id
     */
    public function toggleActive($id)
    {
        $user = $this->model->findOrFail($id);
        $user->update(['active' => !$user->active]);
    }

}
