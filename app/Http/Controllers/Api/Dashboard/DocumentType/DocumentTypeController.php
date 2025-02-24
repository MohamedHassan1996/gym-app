<?php

namespace App\Http\Controllers\Api\Dashboard\DocumentType;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentType\CreateDocumentTypeRequest;
use App\Http\Requests\DocumentType\UpdateDocumentTypeRequest;
use App\Http\Resources\DocumentType\AllDocumentTypeCollection;
use App\Http\Resources\DocumentType\DocumentTypeResource;
use App\Models\DocumentType\DocumentType;
use App\Utils\PaginateCollection;
use App\Services\SportCategory\SportCategoryService;
use App\Traits\SwitchDbConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DocumentTypeController extends Controller
{
    use SwitchDbConnection;
    protected $sportCategoryService;

    public function __construct(SportCategoryService $sportCategoryService)
    {
        $this->middleware('auth:api');
        // $this->middleware('feature:sport_categories');
        // $this->middleware('permission:all_sport_categories', ['only' => ['allSportCategorys']]);
        // $this->middleware('permission:create_sport_category', ['only' => ['create']]);
        // $this->middleware('permission:edit_sport_category', ['only' => ['edit']]);
        // $this->middleware('permission:update_sport_category', ['only' => ['update']]);
        // $this->middleware('permission:delete_sport_category', ['only' => ['delete']]);
        $this->sportCategoryService = $sportCategoryService;
        $this->switchDatabase();

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $documentTypes = DocumentType::all();

        return response()->json(
            new AllDocumentTypeCollection(PaginateCollection::paginate($documentTypes, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateDocumentTypeRequest $createSportCategoryRequest)
    {

        try {

            DB::beginTransaction();

            $data = $createSportCategoryRequest->validated();

            $documentType = DocumentType::create([
                'name' => $data['documentName'],
                'period' => $data['period'],
                'period_type' => $data['periodType'],
            ]);

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

        $documentType = DocumentType::find($request->documentTypeId);

        return new DocumentTypeResource($documentType);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentTypeRequest $updateSportCategoryRequest)
    {

        try {
            DB::beginTransaction();
            $data = $updateSportCategoryRequest->validated();
            $documentType = DocumentType::find($data['documentTypeId']);
            $documentType->update([
                'name' => $data['documentName'],
                'period' => $data['period'],
                'period_type' => $data['periodType'],
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
            DB::beginTransaction();
            $documentType = DocumentType::find($request->documentTypeId);
            $documentType->delete();
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
