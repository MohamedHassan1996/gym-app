<?php

namespace App\Services\SportCategory;

use App\Filters\SportCategory\FilterSportCategory;
use App\Models\Sport\SportCategory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SportCategoryService{

    private $sportCategories;

    public function __construct(SportCategory $sportCategories)
    {
        $this->sportCategories = $sportCategories;
    }

    public function allSportCategories()
    {
        $sportCategoris = QueryBuilder::for(SportCategory::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterSportCategory()), // Add a custom search filter
            ])->get();

        return $sportCategoris;

    }

    public function createSportCategory(array $sportCategoryData): SportCategory
    {


        $sportCategory = SportCategory::create([
            'name' => $sportCategoryData['name'],
            'description' => $sportCategoryData['description'],
        ]);

        return $sportCategory;

    }

    public function editSportCategory(int $sportCategoryId)
    {
        return SportCategory::find($sportCategoryId);
    }

    public function updateSportCategory(array $sportCategoryData): SportCategory
    {


        $sportCategory = SportCategory::find($sportCategoryData['sportCategoryId']);

        $sportCategory->name = $sportCategoryData['name'];
        $sportCategory->description = $sportCategoryData['description'];
        $sportCategory->save();

        return $sportCategory;

    }


    public function deleteSportCategory(int $sportCategoryId)
    {

        $sportCategory = SportCategory::find($sportCategoryId);

        $sportCategory->delete();

    }


}
