<?php

namespace App\Services\Select\DocumentType;

use App\Models\DocumentType\DocumentType;
use App\Models\Sport\SportCategory;
use App\Traits\SwitchDbConnection;

class DocumentTypeSelectService
{
    use SwitchDbConnection;
    public function getAllDocuments()
    {
        $this->switchDatabase();
        return DocumentType::select(['id as value', 'name as label'])->get();
    }

}

