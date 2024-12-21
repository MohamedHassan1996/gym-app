<?php

namespace App\Services\Trainer;

use App\Filters\Trainer\FilterTrainer;
use App\Models\Trainer\Trainer;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TrainerService{

    private $trainer;

    public function __construct(Trainer $trainer)
    {
        $this->trainer = $trainer;
    }

    public function allTrainers()
    {
        $trainers = QueryBuilder::for(Trainer::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterTrainer()), // Add a custom search filter
            ])
            ->with('user')
            ->get();

        return $trainers;

    }

    public function createTrainer(array $trainerData): Trainer
    {


        $trainer = Trainer::create([
            'description' => $trainerData['description'],
            'date_of_birth' => $trainerData['dateOfBirth'],
            'gender' => $trainerData['gender'],
            'user_id' => $trainerData['userId'],
        ]);

        $trainer->sportCategories()->sync($trainerData['sportCategoryIds']);

        return $trainer;

    }

    public function editTrainer(int $trainerId)
    {
        return Trainer::with('user')->find($trainerId);
    }

    public function updateTrainer(array $trainerData): Trainer
    {


        $trainer = Trainer::find($trainerData['trainerId']);

        $trainer->description = $trainerData['description'];
        $trainer->date_of_birth = $trainerData['dateOfBirth'];
        $trainer->gender = $trainerData['gender'];
        $trainer->user_id = $trainerData['userId'];
        $trainer->save();

        $trainer->sportCategories()->sync($trainerData['sportCategoryIds']);


        return $trainer;

    }


    public function deleteTrainer(int $trainerId)
    {

        $trainer = Trainer::find($trainerId);

        if($trainer->user){
            $trainer->user->delete();
        }

        $trainer->delete();

    }


}
