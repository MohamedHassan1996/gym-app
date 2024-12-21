<?php

namespace App\Services\Auth;

use App\Enums\User\UserType;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\User\UserResource;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\UserRolePremission\UserPermissionService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AuthService
{

    protected $userPermissionService;

    public function __construct(
        UserPermissionService $userPermissionService,
    )
    {
        $this->userPermissionService = $userPermissionService;
    }

    public function register(array $data){
        try {

            $user = User::create([
                'name'=> $data['name'],
                'surname'=> $data['surname'],
                'email'=> $data['email'],
                'password'=> Hash::make($data['password']),
                'gender' => $data['gender'],
                'user_type' => $data['userType'],
            ]);

            return response()->json([
                'message' => 'user has been created!'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

    }


    public function login(array $data)
    {
        try {

            $userToken = Auth::attempt(['email' => $data['username'], 'password' => $data['password']]);

            if(!$userToken){
                return response()->json([
                    'message' => 'يوجد خطأ فى الاسم او الرقم السرى!',
                ], 401);
            }

            /*if($userToken && Auth::user()->status->value == 0){
                return response()->json([
                    'message' => 'هذا الحساب غير مفعل!',
                ], 401);
            }*/
            $user = Auth::user();

            if($user->role == UserType::ADMIN){
                $userRoles = $user->getRoleNames();
                $role = Role::findByName($userRoles[0]);
                $roleWithPermissions = $role->permissions;
                $subscription = Subscription::where('user_id', $user->id)->latest()->first();
                $plan= Plan::where('id', $subscription->plan_id)->first();
                return response()->json([
                    'token' => $userToken,
                    'profile' => new UserResource($user),
                    'role' => new RoleResource($role),
                    'features' => $plan->features['dashboard'],
                    'permissions' => $this->userPermissionService->getUserPermissions($user),
                ], 200)->header('Authorization', $userToken);
            }

            $tenantUser = User::where('id', $user->tenant_id)->first();
            $subscription = Subscription::where('user_id', $tenantUser->id)->latest()->first();
            $plan= Plan::where('id', $subscription->plan_id)->first();

            return response()->json([
                'token' => $userToken,
                'profile' => new UserResource($user),
                'features' => $plan->features['client'],
            ], 200)->header('Authorization', $userToken);


        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'you have logged out']);
    }

}
