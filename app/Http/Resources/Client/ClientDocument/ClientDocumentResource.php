<?php

namespace App\Http\Resources\Client\ClientDocument;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ClientDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parameters = [];
        if ($this->parameter) {
            $parameters['documentTypeId'] = $this->parameter->id;
            $parameters['documentTypeName'] = $this->parameter->name;
        }
        return [
            'clientDocumentId' => $this->id,
            'startAt' => $this->start_at,
            'endAt' => $this->end_at,
            'documentType' => $parameters
        ];
    }
}
