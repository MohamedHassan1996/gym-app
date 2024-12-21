<?php

namespace App\Http\Controllers\ClientCourse;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientSubscription\CreateFirstTimeCourseRequest;
use App\Http\Resources\ClientCourse\AllClientCourseCollection;
use App\Http\Resources\Course\AllCourseCollection;
use App\Models\Client\ClientCourse;
use App\Models\Client\ClientCourseSubscription;
use App\Models\Course\Course;
use App\Models\Trainer\Trainer;
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
        $clientCourses = ClientCourse::select('id', 'client_id', 'course_id', 'start_date')
        ->with([
            'course' => function ($query) {
                $query->select('id', 'name', 'start_at', 'sport_category_id') // Only the needed fields
                    ->with([
                        'sportCategory' => function ($query) {
                            $query->select('id', 'name'); // Modify this as per the fields you need from supportCategory
                        }
                    ]);
            },
            'subscriptions' => function ($query) {
                $query->select('id', 'subscription_date', 'client_course_id')
                    ->orderBy('subscription_date', 'desc'); // Order by date to get the latest
            }
        ])
        ->where('client_id', $request->clientId)
        ->get();

        return response()->json(
            new AllClientCourseCollection(PaginateCollection::paginate($clientCourses, $request->pageSize?$request->pageSize:10))
        , 200);    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateFirstTimeCourseRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->switchDatabase();

        $course = Course::find($request->courseId);

        $timesOfSubscriptions = $request->paid / $course->price;

        $courseClient = ClientCourse::create([
            'course_id' => $request->courseId,
            'client_id' => $request->clientId,
            'start_date' => $request->subscriptionDate,
            'status' => $request->status,
        ]);


        $newSubscriptionDate = Carbon::parse($request->subscriptionDate);


        foreach(range(1, $timesOfSubscriptions) as $index) {
            ClientCourseSubscription::create([
                'client_course_id' => $courseClient->id,
                'subscription_date' => $newSubscriptionDate,
                'price' => $course->price,
            ]);

            $newSubscriptionDate = $newSubscriptionDate->addMonth();


        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
