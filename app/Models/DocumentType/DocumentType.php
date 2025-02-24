<?php

namespace App\Models\DocumentType;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'period_type',
        'period',
    ];

    public function getDocumentDescriptionAttribute()
    {
        $unit = $this->period_type == 0 ? 'month' : 'year';

        return $this->period . ' ' . \Illuminate\Support\Str::plural($unit, $this->period);
    }

}
