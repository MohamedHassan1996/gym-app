<?php

namespace App\Services\User;

use App\Enums\User\UserStatus;
use App\Enums\User\UserType;
use App\Filters\User\FilterUser;
use App\Filters\User\FilterUserRole;
use App\Models\User;
use App\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserService{

    private $users;
    protected $uploadService;

    public function __construct(User $users, UploadService $uploadService)
    {
        $this->users = $users;
        $this->uploadService = $uploadService;
    }

    public function allUsers()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterUser()), // Add a custom search filter
                AllowedFilter::exact('status'),
                AllowedFilter::custom('role', new FilterUserRole()),
            ])->get();

        return $users;


    }

    public function createUser(array $userData): User
    {

        $avatarPath = null;

        $tenantId = Auth::user()->tenant_id;

        if(isset($userData['avatar']) && $userData['avatar'] instanceof UploadedFile){
            $avatarPath =  $this->uploadService->uploadFile($userData['avatar'], 'avatars');
        }

        $user = User::create([
            'name' => $userData['name'],
            //'username' => $userData['username'],
            'email' => $userData['email']??"",
            'phone' => $userData['phone']??"",
            'address' => $userData['address']??"",
            'password' => $userData['password']??"",
            'status' => UserStatus::from($userData['status'])->value,
            'avatar' => $avatarPath,
            'tenant_id' => $tenantId,
            'role' => UserType::from($userData['role'])->value
        ]);

        if($userData['role'] == UserType::ADMIN->value){
            $role = Role::find($userData['roleId']);

            $user->assignRole($role->id);

        }


        return $user;

    }

    public function editUser(int $userId)
    {
        return User::where('id', $userId)->with('roles')->first();
    }

    public function updateUser(array $userData): User
    {

        $avatarPath = null;

        if(isset($userData['avatar']) && $userData['avatar'] instanceof UploadedFile){
            $avatarPath =  $this->uploadService->uploadFile($userData['avatar'], $userData['avatar']??'avatars');
        }

        $user = User::find($userData['userId']);
        $user->name = $userData['name'];
        //$user->username = $userData['username'];
        $user->email = $userData['email']??"";
        $user->phone = $userData['phone']??"";
        $user->address = $userData['address']??"";

        if($userData['password']){
            $user->password = $userData['password'];
        }

        $user->status = UserStatus::from($userData['status'])->value;

        if($avatarPath){
            $user->avatar = $avatarPath;
        }

        $user->save();

        if($userData['role'] == UserType::ADMIN->value){
            $role = Role::find($userData['roleId']);

            $user->syncRoles($role->id);

        }



        return $user;

    }


    public function deleteUser(int $userId)
    {

        $user = User::find($userId);

        $user->delete();

    }

    public function changeUserStatus(int $userId, int $status)
    {
        return User::where('id', $userId)->update(['status' => UserStatus::from($status)->value]);
    }


}
