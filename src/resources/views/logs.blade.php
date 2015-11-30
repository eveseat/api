@extends('web::layouts.grids.12')

@section('title', 'Api Token Access Logs')
@section('page_header', 'Api Token Access Logs')

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Access Logs</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Date</th>
          <th>Action</th>
          <th>Source IP</th>
        </tr>

        @foreach($token->logs as $log)

          <tr>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $log->created_at }}">
                {{ human_diff($log->created_at) }}
              </span>
            </td>
            <td>{{ ucfirst($log->action) }}</td>
            <td>{{ $log->src_ip }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">Footer</div>
  </div>

@stop
