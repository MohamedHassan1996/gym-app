<?php

namespace App\Http\Controllers\Api\Dashboard\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\Course\AllCourseCollection;
use App\Http\Resources\Course\CourseResource;
use App\Utils\PaginateCollection;
use App\Services\Course\CourseService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\SwitchDbConnection;

class CourseController extends Controller
{
    use SwitchDbConnection;
    protected $courseService;
    protected $userService;

    public function __construct(CourseService $courseService, userService $userService)
    {
        $this->middleware('auth:api');
        /*$this->middleware('permission:all_courses', ['only' => ['index']]);
        $this->middleware('permission:create_course', ['only' => ['create']]);
        $this->middleware('permission:edit_course', ['only' => ['edit']]);
        $this->middleware('permission:update_course', ['only' => ['update']]);
        $this->middleware('permission:delete_course', ['only' => ['delete']]);
        $this->middleware('permission:change_course_status', ['only' => ['changeStatus']]);*/
        $this->courseService = $courseService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->switchDatabase();

        $allCourses = $this->courseService->allCourses();

       return response()->json(
            new AllCourseCollection(PaginateCollection::paginate($allCourses, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

     public function create(CreateCourseRequest $createCourseRequest)
     {
         try {
             DB::beginTransaction();

             $data = $createCourseRequest->validated();

             $this->switchDatabase();
             // Create course
             $this->courseService->createCourse($data);

             DB::commit();

             return response()->json([
                'message' => __('messages.success.created')
             ], 200);

         } catch (\Exception $e) {
             DB::rollBack();
             return response()->json([
                 'error' => $e->getMessage(),
             ], 500);
         }
     }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {
        $this->switchDatabase();

        $course  =  $this->courseService->editCourse($request->courseId);

        return new CourseResource($course);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $updateCourseRequest)
    {

        try {
            DB::beginTransaction();
            $data = $updateCourseRequest->validated();

             // Create user
             $user = $this->userService->updateUser([
                 ...$data,
                 'roleId' => 1,
             ]);

             $this->switchDatabase();


            $this->courseService->updateCourse([
                'userId' => $user->id,
                ...$data,
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
            $this->switchDatabase();

            DB::beginTransaction();
            $this->courseService->deleteCourse($request->courseId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    public function changeStatus(Request $request)
    {

        try {
            DB::beginTransaction();
            //$this->courseService->changeCourseStatus($request->courseId, $request->status);
            DB::commit();

            return response()->json([
                'message' => 'تم تغيير حالة المستخدم!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

}
