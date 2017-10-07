@extends('web::layouts.grids.12')

@section('title', trans('api::seat.api_token_logs'))
@section('page_header', trans('api::seat.api_token_logs'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('api::seat.access_logs') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('api::seat.date') }}</th>
          <th>{{ trans('api::seat.action') }}</th>
          <th>{{ trans('api::seat.method') }}</th>
          <th>{{ trans('api::seat.request_path') }}</th>
          <th>{{ trans('api::seat.source_ip') }}</th>
        </tr>

        @foreach($logs as $log)

          <tr>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $log->created_at }}">
                {{ human_diff($log->created_at) }}
              </span>
            </td>
            <td>{{ ucfirst($log->action) }}</td>
            <td>
              @include('api::logs-method')
            </td>
            <td>{{ $log->request_path }}</td>
            <td>{{ $log->src_ip }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>

    @if($logs->render())
      <div class="panel-footer">
        {!! $logs->render() !!}
      </div>
    @endif
  </div>

@stop
