<?php

namespace App\Http\Controllers\Api\Dashboard\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\Course\AllCourseCollection;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course\Course;
use App\Utils\PaginateCollection;
use App\Services\Course\CourseService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\SwitchDbConnection;

class CoursePriceController extends Controller
{
    use SwitchDbConnection;
    protected $courseService;
    protected $userService;

    public function __construct()
    {
        $this->middleware('auth:api');
        /*$this->middleware('permission:all_courses', ['only' => ['index']]);
        $this->middleware('permission:create_course', ['only' => ['create']]);
        $this->middleware('permission:edit_course', ['only' => ['edit']]);
        $this->middleware('permission:update_course', ['only' => ['update']]);
        $this->middleware('permission:delete_course', ['only' => ['delete']]);
        $this->middleware('permission:change_course_status', ['only' => ['changeStatus']]);*/
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->switchDatabase();

        $coursePrice = Course::find($request->courseId);

        return response()->json([
            'data' => [
                'coursePrice' => $coursePrice->price
            ]
        ]);

    }
}
