<?php

namespace App\Services\Course;

use App\Enums\Course\CourseStatus;
use App\Filters\Course\FilterCourse;
use App\Models\Course\Course;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CourseService{

    private $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function allCourses()
    {
        $courses = QueryBuilder::for(Course::class)
            ->allowedFilters([
                //AllowedFilter::custom('search', new FilterCourse()), // Add a custom search filter
            ])
            //->with('t')
            ->get();

        return $courses;

    }

    public function createCourse(array $courseData): Course
    {


        $course = Course::create([
            'start_at' => $courseData['startAt'],
            'end_at' => $courseData['endAt'],
            'description' => $courseData['description'],
            'classes' => $courseData['classes'],
            'price' => $courseData['price'],
            'is_active' => CourseStatus::from($courseData['isActive'])->value,
            'trainer_id' => $courseData['trainerId'],
            'sport_category_id' => $courseData['sportCategoryId'],
        ]);

        return $course;

    }

    public function editCourse(int $courseId)
    {
        return Course::find($courseId);
    }

    public function updateCourse(array $courseData): Course
    {


        $course = Course::find($courseData['courseId']);

        $course->update([
            'start_at' => $courseData['startAt'],
            'end_at' => $courseData['endAt'],
            'description' => $courseData['description'],
            'classes' => $courseData['classes'],
            'price' => $courseData['price'],
            'is_active' => CourseStatus::from($courseData['isActive'])->value,
            'trainer_id' => $courseData['trainerId'],
            'sport_category_id' => $courseData['sportCategoryId'],
        ]);

        return $course;

    }


    public function deleteCourse(int $courseId)
    {

        $course = Course::find($courseId);

        if($course->user){
            $course->user->delete();
        }

        $course->delete();

    }


}