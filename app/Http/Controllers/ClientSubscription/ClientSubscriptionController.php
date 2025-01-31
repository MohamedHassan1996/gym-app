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

class ClientSubscriptionController extends Controller
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

        $filters = $request->filter;

        $clientCourses = ClientCourse::select('id', 'client_id', 'course_id', 'start_date', 'status')
            ->with([
                'client',
                'course' => function ($query) {
                    $query->select('id', 'name', 'start_at', 'sport_category_id') // Only the needed fields
                        ->with([
                            'sportCategory' => function ($query) {
                                $query->select('id', 'name'); // Modify this as per the fields you need from sportCategory
                            }
                        ]);
                },
                'subscriptions' => function ($query) {
                    $query->select('id', 'subscription_date', 'end_at', 'client_course_id')
                        ->orderBy('end_at', 'desc');
                }
            ])
            ->when($filters['clientId']??null, function ($query) use ($filters) {
                $query->where('client_id', $filters['clientId']);
            })
            ->when($filters['courseId']??null, function ($query) use ($filters) {
                $query->where('course_id', $filters['courseId']);
            })
            ->when(isset($filters['endedSubscriptions']) && $filters['endedSubscriptions'] == 1, function ($query) {
                $query->whereHas('subscriptions', function ($subQuery) {
                    $subQuery->where(function ($innerQuery) {
                        $innerQuery->where('end_at', '<=', now())
                            ->orWhereBetween('end_at', [now(), now()->addDays(5)]);
                    });
                });
            })
            ->get();
        return response()->json(
            new AllClientSubscriptionCollection(PaginateCollection::paginate($clientCourses, $request->pageSize?$request->pageSize:10))
        , 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateFirstTimeCourseRequest $request)
    {
        try{
            DB::beginTransaction();

            $this->switchDatabase();

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $this->switchDatabase();

        $clientCourse = ClientCourse::find($request->clientCourseId);
        $clientCourseSubscriptions = ClientCourseSubscription::where('client_course_id', $clientCourse->id)->latest()->first();

        return response()->json([
            'data' => [
                'clientCourseId' => $clientCourse->id,
                'subscriptionDate' => $clientCourseSubscriptions->subscription_date,
                'numberOfMonths' => $clientCourseSubscriptions->number_of_months
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try{
            DB::beginTransaction();

            $this->switchDatabase();

            $clientCourse = ClientCourse::find($request->clientCourseId);

            $clientSubscription = ClientCourseSubscription::where('client_course_id', $clientCourse->id)->latest()->first();

            $clientSubscription->subscription_date = $request->subscriptionDate;
            $clientSubscription->number_of_months = $request->numberOfMonths;
            $clientSubscription->end_at = Carbon::parse($request->subscriptionDate)->addMonths($request->numberOfMonths);
            $clientSubscription->price = $clientCourse->price * $request->numberOfMonths;
            $clientSubscription->save();

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
        try{
            DB::beginTransaction();
            $this->switchDatabase();
            $courseSubscription = ClientCourse::find($request->clientCourseId);
            $courseSubscription->delete();
            DB::commit();

            return response()->json([
                'message' => __('messages.success.deleted')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function changeStatus(Request $request)
    {
        try{
            DB::beginTransaction();
            $this->switchDatabase();
            $courseSubscription = ClientCourse::find($request->clientCourseId);
            $courseSubscription->status = $request->status;
            $courseSubscription->save();
            DB::commit();

            return response()->json([
                'message' => __('messages.success.updated')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
