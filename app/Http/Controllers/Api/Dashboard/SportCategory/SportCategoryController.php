<?php

namespace App\Http\Controllers\Api\Dashboard\SportCategory;

use App\Http\Controllers\Controller;
use App\Http\Requests\SportCategory\CreateSportCategoryRequest;
use App\Http\Requests\SportCategory\UpdateSportCategoryRequest;
use App\Http\Resources\SportCategory\SportCategoryResource;
use App\Http\Resources\SportCategory\AllSportCategoryCollection;
use App\Traits\DatabaseNames;
use App\Utils\PaginateCollection;
use App\Services\SportCategory\SportCategoryService;
use App\Traits\SwitchDbConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class SportCategoryController extends Controller
{
    use SwitchDbConnection;
    protected $sportCategoryService;

    public function __construct(SportCategoryService $sportCategoryService)
    {
        $this->middleware('auth:api');
        $this->middleware('feature:sport_categories');
        $this->middleware('permission:all_sport_categories', ['only' => ['allSportCategorys']]);
        $this->middleware('permission:create_sport_category', ['only' => ['create']]);
        $this->middleware('permission:edit_sport_category', ['only' => ['edit']]);
        $this->middleware('permission:update_sport_category', ['only' => ['update']]);
        $this->middleware('permission:delete_sport_category', ['only' => ['delete']]);
        $this->sportCategoryService = $sportCategoryService;
        $this->switchDatabase();

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $sportCategories = $this->sportCategoryService->allSportCategories();

        return response()->json(
            new AllSportCategoryCollection(PaginateCollection::paginate($sportCategories, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateSportCategoryRequest $createSportCategoryRequest)
    {

        try {

            DB::beginTransaction();

            $this->sportCategoryService->createSportCategory($createSportCategoryRequest->validated());

            DB::commit();

            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {

        $sportCategory  =  $this->sportCategoryService->editSportCategory($request->sportCategoryId);

        return new SportCategoryResource($sportCategory);//new SportCategoryResource($sportCategory)

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSportCategoryRequest $updateSportCategoryRequest)
    {

        try {
            DB::beginTransaction();
            $this->sportCategoryService->updateSportCategory($updateSportCategoryRequest->validated());
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
            DB::beginTransaction();
            $this->sportCategoryService->deleteSportCategory($request->sportCategoryId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

}
