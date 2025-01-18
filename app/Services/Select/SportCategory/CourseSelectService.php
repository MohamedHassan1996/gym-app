<?php

namespace App\Services\Select\SportCategory;

use App\Models\Course\Course;
use App\Models\Sport\SportCategory;
use App\Traits\SwitchDbConnection;

class CourseSelectService
{
    use SwitchDbConnection;
    public function getAllCourses()
    {
        $this->switchDatabase();
        return Course::select(['id as value', 'name as label'])->get();
    }

}

