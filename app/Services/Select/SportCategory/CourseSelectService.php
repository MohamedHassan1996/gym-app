<?php

namespace App\Services\Select\SportCategory;

use App\Models\Course\Course;
use App\Models\Sport\SportCategory;
use App\Traits\SwitchDbConnection;

class CourseSelectService
{
    use SwitchDbConnection;
    public function getAllCourses($clientId = null)
    {
        $this->switchDatabase();

        $query = Course::select(['id as value', 'name as label']);

        if ($clientId) {
            $query->whereNotIn('id', function ($subQuery) use ($clientId) {
                $subQuery->select('course_id')
                        ->from('client_courses')
                        ->where('client_id', $clientId);
            });
        }

        return $query->get();
    }

}

