@extends('web::layouts.grids.12')

@section('title', trans('api::seat.api_token_logs'))
@section('page_header', trans('api::seat.api_token_logs'))

@section('full')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('api::seat.access_logs') }}</h3>
    </div>
    <div class="card-body">

      <table class="table table-condensed table-hover">
        <thead>
          <tr>
            <th>{{ trans('api::seat.date') }}</th>
            <th>{{ trans('api::seat.action') }}</th>
            <th>{{ trans('api::seat.request_method') }}</th>
            <th>{{ trans('api::seat.request_path') }}</th>
            <th>{{ trans('api::seat.source_ip') }}</th>
          </tr>
        </thead>
        <tbody>

          @foreach($logs as $log)

            <tr>
              <td @if($log->action == 'deny')class="text-danger"@endif>
                <span data-toggle="tooltip"
                      title="" data-original-title="{{ $log->created_at }}">
                  {{ human_diff($log->created_at) }}
                </span>
              </td>
              <td @if($log->action == 'deny')class="text-danger"@endif>
                @if($log->action == 'deny')
                  <i class="fa fa-warning"></i>
                @endif
                {{ ucfirst($log->action) }}
              </td>
              <td @if($log->action == 'deny')class="text-danger"@endif>
                @switch(strtolower($log->method))
                  @case('post')
                    <span class="badge badge-success">{{ strtoupper($log->method) }}</span>
                    @break
                  @case('put')
                    <span class="badge badge-warning">{{ strtoupper($log->method) }}</span>
                    @break;
                  @case('get')
                    <span class="badge badge-info">{{ strtoupper($log->method) }}</span>
                    @break;
                  @case('delete')
                    <span class="badge badge-danger">{{ strtoupper($log->method) }}</span>
                    @break;
                  @case('head')
                    <span class="badge bg-purple">{{ strtoupper($log->method) }}</span>
                    @break;
                  @case('patch')
                    <span class="badge bg-teal">{{ strtoupper($log->method) }}</span>
                    @break;
                  @case('options')
                    <span class="badge badge-primary">{{ strtoupper($log->method) }}</span>
                    @break;
                  @default
                    <span class="badge badge-default">{{ strtoupper($log->method) }}</span>
                @endswitch
              </td>
              <td @if($log->action == 'deny')class="text-danger"@endif>{{ $log->request_path }}</td>
              <td @if($log->action == 'deny')class="text-danger"@endif>{{ $log->src_ip }}</td>
            </tr>

          @endforeach

        </tbody>
      </table>

    </div>

    @if($logs->render())
      <div class="card-footer">
        {!! $logs->render() !!}
      </div>
    @endif
  </div>

@stop
