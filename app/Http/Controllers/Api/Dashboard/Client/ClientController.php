<?php

namespace App\Http\Controllers\Api\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\Client\AllClientDataResource;
use App\Http\Resources\Client\AllClientCollection;
use App\Http\Resources\Client\ClientResource;
use App\Utils\PaginateCollection;
use App\Services\Client\ClientService;
use App\Services\User\userService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\SwitchDbConnection;

class ClientController extends Controller
{
    use SwitchDbConnection;
    protected $clientService;
    protected $userService;

    public function __construct(ClientService $clientService, userService $userService)
    {
        $this->middleware('auth:api');
        /*$this->middleware('permission:all_clients', ['only' => ['index']]);
        $this->middleware('permission:create_client', ['only' => ['create']]);
        $this->middleware('permission:edit_client', ['only' => ['edit']]);
        $this->middleware('permission:update_client', ['only' => ['update']]);
        $this->middleware('permission:delete_client', ['only' => ['delete']]);
        $this->middleware('permission:change_client_status', ['only' => ['changeStatus']]);*/
        $this->clientService = $clientService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->switchDatabase();

        $allClients = $this->clientService->allClients();

       return response()->json(
            new AllClientCollection(PaginateCollection::paginate($allClients, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

     public function create(CreateClientRequest $createClientRequest)
     {
         try {
             DB::beginTransaction();

             $data = $createClientRequest->validated();

             // Create user
             $user = $this->userService->createUser($data);

             $this->switchDatabase();
             // Create client
             $this->clientService->createClient([
                 'userId' => $user->id,
                 ...$data,
             ]);

             DB::commit();

             return response()->json([
                'message' => __('messages.success.created')
             ], 200);

         } catch (\Exception $e) {
             DB::rollBack();
             return response()->json([
                 'error' => $e->getMessage(),
             ], 500);
         }
     }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {
        $this->switchDatabase();

        $client  =  $this->clientService->editClient($request->clientId);

        return new ClientResource($client);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $updateClientRequest)
    {

        try {
            DB::beginTransaction();
            $data = $updateClientRequest->validated();

             // Create user
             $user = $this->userService->updateUser([
                 ...$data,
                 'roleId' => 1,
             ]);

             $this->switchDatabase();


            $this->clientService->updateClient([
                'userId' => $user->id,
                ...$data,
            ]);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.updated')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {

        try {
            $this->switchDatabase();

            DB::beginTransaction();
            $this->clientService->deleteClient($request->clientId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    public function changeStatus(Request $request)
    {

        try {
            DB::beginTransaction();
            //$this->clientService->changeClientStatus($request->clientId, $request->status);
            DB::commit();

            return response()->json([
                'message' => 'تم تغيير حالة المستخدم!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

}
