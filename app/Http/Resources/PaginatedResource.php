<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedResource extends ResourceCollection
{
    public function __construct(
        $resource,
        private string $itemResource
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [

            'items' => $this->itemResource::collection(
                $this->collection
            ),

            'pagination' => [

                'page' => $this->currentPage(),

                'per_page' => $this->perPage(),

                'total' => $this->total(),

                'last_page' => $this->lastPage(),

                'has_more' => $this->hasMorePages(),

            ],

        ];
    }
}