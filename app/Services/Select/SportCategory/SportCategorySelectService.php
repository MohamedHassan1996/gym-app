<?php

namespace App\Services\Select\SportCategory;

use App\Models\Sport\SportCategory;
use App\Traits\SwitchDbConnection;

class SportCategorySelectService
{
    use SwitchDbConnection;
    public function getAllSportCategories()
    {
        $this->switchDatabase();
        return SportCategory::select(['id as value', 'name as label'])->get();
    }

}

