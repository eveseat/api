<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

/**
 * @SWG\Swagger(
 *     basePath="/api/v2",
 *     schemes={"http", "https"},
 *     host=L5_SWAGGER_CONST_HOST,
 *     produces={"application/json"},
 *     consumes={"application/json"},
 *     @SWG\Info(
 *         version="2.0.0",
 *         title="SeAT API",
 *         description=L5_SWAGGER_DESCRIPTION,
 *     )
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="ApiKeyAuth",
 *   type="apiKey",
 *   description="Authentication token generated from the SeAT Web UI",
 *   name="X-Token",
 *   in="header"
 * )
 */

/**
 * Class ApiController.
 * @package Seat\Api\v2
 */
class ApiController extends BaseController
{
    use ValidatesRequests;
}
