<?php

namespace App\Http\Controllers\ClientSubscription;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientSubscriptionRecords\AllClientSubscriptionRecordCollection;
use App\Models\Client\ClientCourse;
use App\Traits\SwitchDbConnection;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;

class ClientSubscriptionRecordsController extends Controller
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

        $filters = $request->clientId;

        $clientCourses = ClientCourse::select('id', 'client_id', 'course_id', 'start_date', 'status')
            ->with([
                'client',
                'course' => function ($query) {
                    $query->select('id', 'name', 'start_at'); // Only the needed fields

                },
                'subscriptions' => function ($query) {
                    $query->select('id', 'subscription_date', 'end_at', 'client_course_id', 'number_of_months', 'price')
                        ->orderBy('end_at', 'desc');
                }
            ])
            ->when($filters??null, function ($query) use ($filters) {
                $query->where('client_id', $filters);
            })
            ->get();
        return response()->json(
            new AllClientSubscriptionRecordCollection(PaginateCollection::paginate($clientCourses, $request->pageSize?$request->pageSize:10))
        , 200);
    }

}
