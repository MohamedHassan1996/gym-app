<?php

namespace App\Models\Client;

use App\Models\DocumentType\DocumentType;
use App\Models\Parameter\ParameterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDocument extends Model
{
    use HasFactory;

    protected $connection = 'tenant';
    protected $fillable = [
        'client_id',
        'document_type_id',
        'start_at',
        'end_at',
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id');
    }
}
