<?php

namespace App\Http\Controllers\ClientCourse;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientCourse\AllClientCourseCollection;
use App\Models\Client\ClientCourse;
use App\Models\Client\ClientCourseSubscription;
use App\Models\Course\Course;
use App\Traits\SwitchDbConnection;
use App\Utils\PaginateCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->switchDatabase();
            DB::connection('tenant')->beginTransaction();

            $course = Course::find($request->courseId);

            $courseClient = ClientCourse::create([
                'client_id' => $request->clientId,
                'course_id' => $request->courseId,
                'start_date' => $request->subscriptionDate,
                'status' => 1
            ]);


            ClientCourseSubscription::create([
                'client_course_id' => $courseClient->id,
                'subscription_date' => $request->subscriptionDate,
                'end_at' => Carbon::parse($request->subscriptionDate)->addMonths($request->numberOfMonths),
                'number_of_months' => $request->numberOfMonths,
                'price' => $course->amount,
            ]);

            DB::commit();
            Db::connection('tenant')->commit();

            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->switchDatabase();
            DB::connection('tenant')->beginTransaction();

            $clientCourse = ClientCourse::find($request->clientCourseId);
            $clientCourse->delete();

            DB::connection('tenant')->commit();
            DB::commit();

            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
