<?php

namespace Seat\Api\Http\Resources\Json;

use Illuminate\Support\Arr;
use OpenApi\Attributes as OA;

class PaginatedResourceResponse extends \Illuminate\Http\Resources\Json\PaginatedResourceResponse
{
    #[OA\Schema(
        schema: 'ResourcePaginatedMetadata',
        description: 'Information related to the paginated response',
        properties: [
            new OA\Property(property: 'current_page', description: 'The current page', type: 'integer'),
            new OA\Property(property: 'from', description: 'The first entity number on the page', type: 'integer'),
            new OA\Property(property: 'last_page', description: 'The last page available', type: 'integer'),
            new OA\Property(property: 'path', description: 'The base endpoint', type: 'string', format: 'url'),
            new OA\Property(property: 'per_page', description: 'The pagination step', type: 'integer'),
            new OA\Property(property: 'to', description: 'The last entity number on the page', type: 'integer'),
            new OA\Property(property: 'total', description: 'The total of available entities', type: 'integer')
        ],
        type: 'object'
    )]
    protected function meta($paginated)
    {
        $meta = parent::meta($paginated);

        return Arr::except($meta, ['links']);
    }

    #[OA\Schema(
        schema: 'ResourcePaginatedLinks',
        description: 'Provide pagination urls for navigation',
        properties: [
            new OA\Property(property: 'first', description: 'First Page', type: 'string', format: 'url'),
            new OA\Property(property: 'last', description: 'Last Page', type: 'string', format: 'url'),
            new OA\Property(property: 'prev', description: 'Previous Page', type: 'string', format: 'url'),
            new OA\Property(property: 'next', description: 'Next Page', type: 'string', format: 'url')
        ],
        type: 'object'
    )]
    protected function paginationLinks($paginated)
    {
        return parent::paginationLinks($paginated);
    }
}
