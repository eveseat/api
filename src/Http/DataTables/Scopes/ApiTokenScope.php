<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019, 2020  Leon Jacobs
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

namespace Seat\Api\Http\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class ApiTokenScope.
 *
 * @package Seat\Api\Http\DataTables\Scopes
 */
class ApiTokenScope implements DataTableScope
{
    private $token_id;

    /**
     * ApiTokenScope constructor.
     *
     * @param int $token_id
     */
    public function __construct(int $token_id)
    {
        $this->token_id = $token_id;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('api_token_id', $this->token_id);
    }
}
