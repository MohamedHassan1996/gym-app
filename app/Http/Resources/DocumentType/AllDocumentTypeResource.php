<?php

namespace App\Http\Resources\DocumentType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AllDocumentTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'documentTypeId' => $this->id,
            'documentName' => $this->name,
            'period' => $this->period??'',
            'periodType' => $this->period_type??'',
            'documentDescription' => $this->document_description
        ];
    }
}
