<?php

namespace App\Http\Controllers\Api\Dashboard\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\AllUserDataResource;
use App\Http\Resources\User\AllUserCollection;
use App\Models\Client\Client;
use App\Models\Client\ClientCourse;
use App\Models\Client\ClientCourseSubscription;
use App\Models\Course\Course;
use App\Models\Trainer\Trainer;
use App\Utils\PaginateCollection;
use App\Services\User\UserService;
use App\Traits\SwitchDbConnection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    use SwitchDbConnection;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api');
        // $this->middleware('feature:users');
        // $this->middleware('permission:all_users', ['only' => ['index']]);
        // $this->middleware('permission:create_user', ['only' => ['create']]);
        // $this->middleware('permission:edit_user', ['only' => ['edit']]);
        // $this->middleware('permission:update_user', ['only' => ['update']]);
        // $this->middleware('permission:delete_user', ['only' => ['delete']]);
        // $this->middleware('permission:change_user_status', ['only' => ['changeStatus']]);
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $this->switchDatabase();

        $clients = Client::count();
        $trainers = Trainer::count();
        $courses = Course::count();
        $subscriptions = ClientCourse::where('status', 1)->count();

        $today = Carbon::today();
        $fiveDaysLater = Carbon::today()->addDays(5);

        $endedSubscriptions = ClientCourseSubscription::where('end_at', '<', $today)
            ->orWhereBetween('end_at', [$today, $fiveDaysLater])
            ->count();

        return response()->json(
            [
                'totalClients' => $clients??0,
                'totalTrainers' => $trainers??0,
                'totalCourses' => $courses??0,
                'totalSubscriptions' => $subscriptions??0,
                'endedSubscriptions' => $endedSubscriptions??0,
            ]
        , 200);

    }


}
