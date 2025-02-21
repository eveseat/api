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

namespace Seat\Api\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Trait Filterable.
 *
 * @package Seat\Api\Http\Traits
 */
trait Filterable
{
    /**
     * Apply OData filter over query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilters(Request $request, Builder $query)
    {
        $regex = "/((?'field'[a-z_]+)\s+(?'operator'eq|ne|gt|lt|ge|le)\s+(?'value'(?>'[\s\S]+?')|(?>[\w]+))(\s+(?'join'and|or))?)+/";
        if (preg_match_all($regex, $request->query('$filter', ''), $filters) === false)
            return $query;

        $groups = count($filters['field']);

        for ($i = 0; $i < $groups; $i++) {
            $field = $filters['field'][$i];
            $operator = $filters['operator'][$i];
            $value = $filters['value'][$i];
            $join = $i > 0 ? $filters['join'][$i - 1] : 'and';

            $this->addFilter($query, $field, $operator, $value, $join);
        }

        return $query;
    }

    /**
     * Append filter to current query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  string  $operator
     * @param  $value
     * @param  string|null  $join
     */
    protected function addFilter(Builder $query, string $field, string $operator, $value, ?string $join = 'and')
    {
        $value = rtrim(ltrim($value, '\''), '\'');

        if ($join == 'and')
            $query->where($field, $this->odataOperatorToQueryOperator($operator), $value);
        else
            $query->orWhere($field, $this->odataOperatorToQueryOperator($operator), $value);
    }

    /**
     * Convert odata operator into an SQL query operator.
     *
     * @param  string  $operator
     * @return string
     */
    private function odataOperatorToQueryOperator(string $operator): string
    {
        switch ($operator) {
            case 'ne': return '<>';
            case 'gt': return '>';
            case 'lt': return '<';
            case 'ge': return '>=';
            case 'le': return '<=';
            default: return '=';
        }
    }
}
