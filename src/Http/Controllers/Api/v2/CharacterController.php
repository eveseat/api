<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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
use Seat\Api\Http\Resources\CharacterSheetResource;
use Seat\Api\Http\Resources\ContactResource;
use Seat\Api\Http\Resources\ContractResource;
use Seat\Api\Http\Resources\CorporationHistoryResource;
use Seat\Api\Http\Resources\IndustryResource;
use Seat\Api\Http\Resources\Json\AnonymousResourceCollection;
use Seat\Api\Http\Resources\Json\JsonResource;
use Seat\Api\Http\Resources\JumpCloneResource;
use Seat\Api\Http\Resources\MailResource;
use Seat\Api\Http\Resources\NotificationResource;
use Seat\Api\Http\Traits\Filterable;
use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Character\CharacterCorporationHistory;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Eveapi\Models\Clones\CharacterJumpClone;
use Seat\Eveapi\Models\Contacts\CharacterContact;
use Seat\Eveapi\Models\Contracts\CharacterContract;
use Seat\Eveapi\Models\Industry\CharacterIndustryJob;
use Seat\Eveapi\Models\Mail\MailHeader;
use Seat\Eveapi\Models\Market\CharacterOrder;
use Seat\Eveapi\Models\Skills\CharacterSkillQueue;
use Seat\Eveapi\Models\Wallet\CharacterWalletJournal;
use Seat\Eveapi\Models\Wallet\CharacterWalletTransaction;

class CharacterController extends ApiController
{
    use Filterable;

    #[OA\Get(
        path: '/api/v2/character/assets/{character_id}',
        description: 'Returns a list of assets',
        summary: 'Get a paginated list of a assets for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Assets'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterAsset')),
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
    public function getAssets(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterAsset::with('type')
            ->where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/character/contacts/{character_id}',
        description: 'Returns list of contacs',
        summary: 'Get a paginated list of contacts for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Contacts'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterContact')),
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
    public function getContacts(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterContact::where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return ContactResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/character/contracts/{character_id}',
        description: 'Returns list of contracts',
        summary: 'Get a paginated list of contracts for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Contracts'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
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
    public function getContracts(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterContract::with(
            'detail', 'detail.acceptor', 'detail.assignee', 'detail.issuer',
            'detail.bids', 'detail.lines', 'detail.start_location', 'detail.end_location')
            ->where('character_id', $character_id)
            ->whereHas('detail', function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return ContractResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/character/corporation-history/{character_id}',
        description: 'Returns a corporation history',
        summary: 'Get the corporation history for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterCorporationHistory')),
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
    public function getCorporationHistory(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterCorporationHistory::where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return CorporationHistoryResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/character/industry/{character_id}',
        description: 'Returns list of industry jobs',
        summary: 'Get a paginated list of industry jobs for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Industry'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterIndustryJob')),
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
    public function getIndustry(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterIndustryJob::where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return IndustryResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/character/jump-clones/{character_id}',
        description: 'Returns list of jump clones',
        summary: 'Get a paginated list of jump clones for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterJumpClone')),
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
    public function getJumpClones(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterJumpClone::where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JumpCloneResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/character/mail/{character_id}',
        description: 'Returns mail',
        summary: 'Get a paginated list of mail for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/MailResource')),
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
    public function getMail(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = MailHeader::with('sender', 'body', 'recipients', 'recipients.entity')
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            })->where(function ($sub_query) use ($character_id) {
                $sub_query->whereHas('recipients', function ($query) use ($character_id) {
                    $query->where('recipient_id', $character_id);
                })->orWhere('from', $character_id);
            });

        return MailResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/character/market-orders/{character_id}',
        description: 'Returns list of market orders',
        summary: 'Get a paginated list of market orders for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Market'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterOrder')),
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
    public function getMarketOrders(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterOrder::with('type')
            ->where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/character/notifications/{character_id}',
        description: 'Returns a list of notifications',
        summary: 'Get a paginated list of notifications for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterNotification')),
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
    public function getNotifications(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterNotification::where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return NotificationResource::collection($query->paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/character/sheet/{character_id}',
        description: 'Returns a character sheet',
        summary: 'Get the character sheet for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/CharacterSheetResource', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getSheet(int $character_id): CharacterSheetResource
    {
        return new CharacterSheetResource(
            CharacterInfo::with('affiliation.corporation', 'affiliation.alliance', 'affiliation.faction', 'balance', 'skillpoints')
                ->findOrFail($character_id));
    }

    #[OA\Get(
        path: '/api/v2/character/skills/{character_id}',
        description: 'Returns character skills',
        summary: 'Get the skills for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterSkill')),
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
    public function getSkills(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterSkill::with('type')
            ->where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/character/skill-queue/{character_id}',
        description: 'Returns a skill queue',
        summary: 'Get a list of characters skill queue',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterSkillQueue')),
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
    public function getSkillQueue(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterSkillQueue::with('type')->where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/character/wallet-journal/{character_id}',
        description: 'Returns a wallet journal',
        summary: 'Get a paginated wallet journal for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterWalletJournal')),
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
    public function getWalletJournal(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterWalletJournal::with('first_party', 'second_party')
            ->where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }

    #[OA\Get(
        path: '/api/v2/character/wallet-transactions/{character_id}',
        description: 'Returns wallet transactions',
        summary: 'Get paginated wallet transactions for a character',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Wallet'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CharacterWalletTransaction')),
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
    public function getWalletTransactions(int $character_id): AnonymousResourceCollection
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CharacterWalletTransaction::with('party', 'type')
            ->where('character_id', $character_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return JsonResource::collection($query->paginate());
    }
}
