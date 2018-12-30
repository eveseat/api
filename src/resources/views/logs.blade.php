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
          <th>{{ trans('api::seat.request_method') }}</th>
          <th>{{ trans('api::seat.request_path') }}</th>
          <th>{{ trans('api::seat.source_ip') }}</th>
        </tr>

        @foreach($logs as $log)

          <tr>
            <td @if($log->action == 'deny')class="danger"@endif>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $log->created_at }}">
                {{ human_diff($log->created_at) }}
              </span>
            </td>
            <td @if($log->action == 'deny')class="danger"@endif>
              @if($log->action == 'deny')
                <i class="fas fa-exclamation-triangle"></i>
              @endif
              {{ ucfirst($log->action) }}
            </td>
            <td @if($log->action == 'deny')class="danger"@endif>
              @switch(strtolower($log->method))
                @case('post')
                  <span class="label label-success">{{ strtoupper($log->method) }}</span>
                  @break
                @case('put')
                  <span class="label label-warning">{{ strtoupper($log->method) }}</span>
                  @break;
                @case('get')
                  <span class="label label-info">{{ strtoupper($log->method) }}</span>
                  @break;
                @case('delete')
                  <span class="label label-danger">{{ strtoupper($log->method) }}</span>
                  @break;
                @case('head')
                  <span class="label bg-purple">{{ strtoupper($log->method) }}</span>
                  @break;
                @case('patch')
                  <span class="label bg-teal">{{ strtoupper($log->method) }}</span>
                  @break;
                @case('options')
                  <span class="label label-primary">{{ strtoupper($log->method) }}</span>
                  @break;
                @default
                  <span class="label label-default">{{ strtoupper($log->method) }}</span>
              @endswitch
            </td>
            <td @if($log->action == 'deny')class="danger"@endif>{{ $log->request_path }}</td>
            <td @if($log->action == 'deny')class="danger"@endif>{{ $log->src_ip }}</td>
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
