<?php


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
