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


class DocumentTypeEndPeriodController extends Controller
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


        $documentType = DocumentType::find($request->documentTypeId);

        return response()->json([
            'data' => [
                'endDate' => $documentType->periodType == 0 ? $documentType->period : $documentType->period * 12,
            ]
        ], 200);


    }



}

