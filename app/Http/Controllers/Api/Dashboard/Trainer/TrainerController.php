<?php

namespace App\Http\Controllers\Api\Dashboard\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\CreateTrainerRequest;
use App\Http\Requests\Trainer\UpdateTrainerRequest;
use App\Http\Resources\Trainer\AllTrainerDataResource;
use App\Http\Resources\Trainer\AllTrainerCollection;
use App\Http\Resources\Trainer\TrainerResource;
use App\Utils\PaginateCollection;
use App\Services\Trainer\TrainerService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\SwitchDbConnection;

class TrainerController extends Controller
{
    use SwitchDbConnection;
    protected $trainerService;
    protected $userService;

    public function __construct(TrainerService $trainerService, userService $userService)
    {
        $this->middleware('auth:api');
        /*$this->middleware('permission:all_trainers', ['only' => ['index']]);
        $this->middleware('permission:create_trainer', ['only' => ['create']]);
        $this->middleware('permission:edit_trainer', ['only' => ['edit']]);
        $this->middleware('permission:update_trainer', ['only' => ['update']]);
        $this->middleware('permission:delete_trainer', ['only' => ['delete']]);
        $this->middleware('permission:change_trainer_status', ['only' => ['changeStatus']]);*/
        $this->trainerService = $trainerService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->switchDatabase();

        $allTrainers = $this->trainerService->allTrainers();

       return response()->json(
            new AllTrainerCollection(PaginateCollection::paginate($allTrainers, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

     public function create(CreateTrainerRequest $createTrainerRequest)
     {
         try {
             DB::beginTransaction();

             $data = $createTrainerRequest->validated();

             // Create user
             $user = $this->userService->createUser([
                 ...$data,
                 'roleId' => 1,
             ]);

             $this->switchDatabase();
             // Create trainer
             $this->trainerService->createTrainer([
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

        $trainer  =  $this->trainerService->editTrainer($request->trainerId);

        return new TrainerResource($trainer);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrainerRequest $updateTrainerRequest)
    {

        try {
            DB::beginTransaction();
            $data = $updateTrainerRequest->validated();

             // Create user
             $user = $this->userService->updateUser([
                 ...$data,
                 'roleId' => 1,
             ]);

            $this->switchDatabase();

            $this->trainerService->updateTrainer([
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
            $this->trainerService->deleteTrainer($request->trainerId);
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
            //$this->trainerService->changeTrainerStatus($request->trainerId, $request->status);
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
