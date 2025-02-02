<?php

namespace App\Http\Controllers\ClientCourse;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientCourse\AllClientCourseCollection;
use App\Models\Client\ClientCourse;
use App\Traits\SwitchDbConnection;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;

class ClientCourseController extends Controller
{
    use SwitchDbConnection;

    public function __construct()
    {
        $this->middleware('auth:api');
        /*$this->middleware('permission:all_clients', ['only' => ['index']]);
        $this->middleware('permission:create_client', ['only' => ['create']]);
        $this->middleware('permission:edit_client', ['only' => ['edit']]);
        $this->middleware('permission:update_client', ['only' => ['update']]);
        $this->middleware('permission:delete_client', ['only' => ['delete']]);
        $this->middleware('permission:change_client_status', ['only' => ['changeStatus']]);*/
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->switchDatabase();

        $clientCourses = ClientCourse::select('id', 'client_id', 'course_id', 'start_date', 'status')
        ->with([
            'client',
            'course' => function ($query) {
                $query->select('id', 'name', 'start_at'); // Only the needed fields

            }
        ])
        ->where('client_id', $request->clientId)
        ->whereNull('deleted_at')
        ->get();

        return response()->json(
            new AllClientCourseCollection(PaginateCollection::paginate($clientCourses, $request->pageSize?$request->pageSize:10))
        , 200);
    }
}
