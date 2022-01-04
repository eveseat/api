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

namespace Seat\Api\Http\DataTables;

use Seat\Api\Models\ApiTokenLog;
use Yajra\DataTables\Services\DataTable;

/**
 * Class ApiTokenLogDataTable.
 *
 * @package Seat\Api\Http\DataTables
 */
class ApiTokenLogDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('created_at', function ($row) {
                return view('web::partials.date', ['datetime' => $row->created_at]);
            })
            ->editColumn('method', function ($row) {
                return view('api::logs.partials.method', compact('row'));
            })
            ->addRowAttr('class', function ($row) {
                return $row->action == 'deny' ? 'text-danger' : '';
            })
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addTableClass(['table-condensed', 'table-hover'])
            ->orderBy(0, 'desc')
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return ApiTokenLog::query();
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'created_at', 'title' => trans('api::seat.date')],
            ['data' => 'action', 'title' => trans('api::seat.action')],
            ['data' => 'method', 'title' => trans('api::seat.request_method')],
            ['data' => 'request_path', 'title' => trans('api::seat.request_path')],
            ['data' => 'src_ip', 'title' => trans('api::seat.source_ip')],
        ];
    }
}
