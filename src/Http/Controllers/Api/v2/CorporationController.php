<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Api\Http\Controllers\Api\v2;

use OpenApi\Attributes as OA;
use Seat\Api\Http\Resources\ContactResource;
use Seat\Api\Http\Resources\ContractResource;
use Seat\Api\Http\Resources\CorporationSheetResource;
use Seat\Api\Http\Resources\IndustryResource;
use Seat\Api\Http\Resources\Json\AnonymousResourceCollection;
use Seat\Api\Http\Resources\Json\JsonResource;
use Seat\Api\Http\Resources\MemberTrackingResource;
use Seat\Api\Http\Traits\Filterable;
use Seat\Eveapi\Models\Assets\CorporationAsset;
use Seat\Eveapi\Models\Contacts\CorporationContact;
use Seat\Eveapi\Models\Contracts\CorporationContract;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Eveapi\Models\Industry\CorporationIndustryJob;
use Seat\Eveapi\Models\Market\CorporationOrder;
use Seat\Eveapi\Models\Wallet\CorporationWalletJournal;
use Seat\Eveapi\Models\Wallet\CorporationWalletTransaction;

/**
 * Class CorporationController.
 *
 * @package Seat\Api\Http\Controllers\Api\v2
 */
class CorporationController extends ApiController
{
    use Filterable;

    #[OA\Get(
        path: '/api/v2/corporation/assets/{corporation_id}',
        description: 'Returns a list of assets',
        summary: 'Get a paginated list of assets for a corporation',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Assets'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CorporationAsset')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getAssets(int $corporation_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationAsset::with('type')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/corporation/contacts/{corporation_id}',
        description: 'Returns a list of contacts',
        summary: 'Get a list of contacts for a corporation',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Contacts'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CorporationContact')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getContacts(int $corporation_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationContact::with('labels')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return ContactResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/corporation/contracts/{corporation_id}',
        description: 'Returns a list of contracts',
        summary: 'Get a list of contracts for a corporation',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Contracts'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/ContractDetail')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getContracts(int $corporation_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationContract::with('detail', 'detail.acceptor', 'detail.assignee', 'detail.issuer', 'detail.bids', 'detail.lines', 'detail.start_location', 'detail.end_location')
            ->where('corporation_id', $corporation_id)
            ->whereHas('detail', function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return ContractResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/corporation/industry/{corporation_id}',
        description: 'Returns a list of industry jobs',
        summary: 'Get a paginated list of industry jobs for a corporation',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Industry'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CorporationIndustryJob')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getIndustry(int $corporation_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationIndustryJob::with('blueprint', 'product')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return IndustryResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/corporation/market-orders/{corporation_id}',
        description: 'Returns a list of market orders',
        summary: 'Get a paginated list of market orders for a corporation',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Market'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CorporationOrder')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getMarketOrders(int $corporation_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationOrder::with('type')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/corporation/member-tracking/{corporation_id}',
        description: 'Returns a list of members for a corporation',
        summary: 'Get a list of members for a corporation with tracking',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Corporation'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CorporationMemberTracking')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getMemberTracking(int $corporation_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationMemberTracking::with('ship')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return MemberTrackingResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/corporation/sheet/{corporation_id}',
        description: 'Returns a corporation sheet',
        summary: 'Get a corporation sheet',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Corporation'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/CorporationInfo', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getSheet(int $corporation_id): CorporationSheetResource
    {
        return new CorporationSheetResource(CorporationInfo::with('ceo', 'creator', 'alliance', 'faction')
            ->findOrFail($corporation_id));
    }

    #[OA\Get(
        path: '/api/v2/corporation/structures/{corporation_id}',
        description: 'Returns a list of corporation structures',
        summary: 'Get a list corporation structures',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Corporation'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CorporationStructure')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getStructures(int $corporation_id): AnonymousResourceCollection
    {
        $query = CorporationStructure::with('info', 'type', 'services', 'solar_system')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/corporation/wallet-journal/{corporation_id}',
        description: 'Returns a wallet journal',
        summary: 'Get a paginated wallet journal for a corporation',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Wallet'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CorporationWalletJournal')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getWalletJournal(int $corporation_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationWalletJournal::with('first_party', 'second_party')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/corporation/wallet-transactions/{corporation_id}',
        description: 'Returns wallet transactions',
        summary: 'Get paginated wallet transactions for a corporation',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Wallet'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CorporationWalletTransaction')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getWalletTransactions(int $corporation_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationWalletTransaction::with('party', 'type')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }
}
