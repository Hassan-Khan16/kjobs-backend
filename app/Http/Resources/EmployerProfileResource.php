<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerProfileResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'contact_person_name' => $this->contact_person_name,
            'phone' => $this->phone,
            'company_description' => $this->company_description,
            'website' => $this->website,
            'logo' => $this->logo,
        ];
    }
}
