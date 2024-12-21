<?php

namespace App\Filters\Client;

use App\Traits\DatabaseNames;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\DB;

class FilterClient implements Filter
{

    use DatabaseNames;

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereExists(function ($subQuery) use ($value) {
            $subQuery->select(DB::raw(1))
                ->from($this->getMainDatabaseName() . '.users') // Specify the full table name with the landlord connection
                ->whereColumn($this->getMainDatabaseName() .'.users.id', 'clients.user_id') // Link user_id to trainers
                ->where(function ($userQuery) use ($value) {
                    $userQuery->where($this->getMainDatabaseName() . '.users.name', 'like', '%' . $value . '%')
                        ->orWhere($this->getMainDatabaseName() . '.users.email', 'like', '%' . $value . '%')
                        ->orWhere($this->getMainDatabaseName() . '.users.phone', 'like', '%' . $value . '%')
                        ->orWhere($this->getMainDatabaseName() . '.users.address', 'like', '%' . $value . '%');
                });
        });
    }
}

