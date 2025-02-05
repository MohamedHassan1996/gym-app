<?php

namespace App\Http\Controllers\ClientSubscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientSubscription\CreateFirstTimeCourseRequest;
use App\Http\Resources\ClientCourse\AllClientCourseCollection;
use App\Http\Resources\ClientSubscription\AllClientSubscriptionCollection;
use App\Models\Client\ClientCourse;
use App\Models\Client\ClientCourseSubscription;
use App\Models\Course\Course;
use App\Traits\SwitchDbConnection;
use App\Utils\PaginateCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenewClientSubscriptionController extends Controller
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

    public function create(Request $request)
    {
        try{
            DB::beginTransaction();

            $this->switchDatabase();

            $clientCourse = ClientCourse::find($request->clientCourseId);

            $course = Course::find($clientCourse->course_id);

            $clientCourseSubscriptionMonths = ClientCourseSubscription::where('client_course_id', $clientCourse->id)->sum('number_of_months');

            ClientCourseSubscription::create([
                'client_course_id' => $clientCourse->id,
                'subscription_date' => $request->subscriptionDate,
                'end_at' => Carbon::parse($clientCourse->start_date)->addMonths($request->numberOfMonths + $clientCourseSubscriptionMonths),
                'number_of_months' => $request->numberOfMonths,
                'price' => $course->price * $request->numberOfMonths,
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

}
