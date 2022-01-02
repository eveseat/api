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

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   version="2.0.0",
 *   title="SeAT API",
 *   description=L5_SWAGGER_DESCRIPTION,
 * )
 */

/**
 * @OA\Server(
 *   url=L5_SWAGGER_CONST_HOST,
 *   description="SeAT Server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *   securityScheme="ApiKeyAuth",
 *   type="apiKey",
 *   description="Authentication token generated from the SeAT Web UI",
 *   name="X-Token",
 *   in="header"
 * )
 */

/**
 * @OA\Schema(
 *     schema="ResourcePaginatedLinks",
 *     description="Provide pagination urls for navigation",
 *     type="object",
 *     @OA\Property(type="string", format="uri", property="first", description="First Page"),
 *     @OA\Property(type="string", format="uri", property="last", description="Last Page"),
 *     @OA\Property(type="string", format="uri", property="prev", description="Previous Page"),
 *     @OA\Property(type="string", format="uri", property="next", description="Next Page")
 * )
 *
 * @OA\Schema(
 *     schema="ResourcePaginatedMetadata",
 *     description="Information related to the paginated response",
 *     type="object",
 *     @OA\Property(type="integer", property="current_page", description="The current page"),
 *     @OA\Property(type="integer", property="from", description="The first entity number on the page"),
 *     @OA\Property(type="integer", property="last_page", description="The last page available"),
 *     @OA\Property(type="string", format="uri", property="path", description="The base endpoint"),
 *     @OA\Property(type="integer", property="per_page", description="The pagination step"),
 *     @OA\Property(type="integer", property="to", description="The last entity number on the page"),
 *     @OA\Property(type="integer", property="total", description="The total of available entities")
 * )
 */

/**
 * Class ApiController.
 *
 * @package Seat\Api\v2
 */
class ApiController extends BaseController
{
    use ValidatesRequests;
}
