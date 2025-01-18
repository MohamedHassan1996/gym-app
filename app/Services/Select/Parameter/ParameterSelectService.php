<?php

namespace App\Services\Select\Parameter;

use App\Models\Parameter\ParameterValue;
use App\Traits\SwitchDbConnection;

class ParameterSelectService
{
    use SwitchDbConnection;
    public function getAllParameters(int $parameterId)
    {
        $this->switchDatabase();
        return ParameterValue::select(['id as value', 'name as label'])->where('parameter_id', $parameterId)->get();
    }

    /*public function getAllSubCategories(int $categoryId)
    {
        return Category::select(['id as value', 'name as label'])->where('parent_id', $categoryId)->get();
    }*/

}

