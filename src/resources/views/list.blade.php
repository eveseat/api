@extends('web::layouts.grids.3-9')

@section('title', trans('api::seat.api_token_admin'))
@section('page_header', trans('api::seat.api_token_admin'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('api::seat.new_token') }}
        <span class="pull-right">
          <a href="{{ route('l5-swagger.api') }}" target="_blank" class="btn btn-success btn-xs">
            {{ trans('api::seat.api_docs') }}
          </a>
        </span>
      </h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('api-admin.token.create') }}" method="post" id="key-form">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="comment">{{ trans('api::seat.key_comment') }}</label>
            <input type="text" name="comment" class="form-control" id="comment" value="{{ old('comment') }}"
                   placeholder="Comment">
          </div>

          <div class="form-group">
            <label for="text">{{ trans('api::seat.allowed_ip_address') }}</label>
            <input type="text" name="allowed_src" class="form-control" id="allowed_src" value="{{ old('allowed_src') }}"
                   placeholder="IP Address">
            <span class="help-block">
              {{ trans('api::seat.ip_help') }}
            </span>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('api::seat.generate') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('api::seat.current_tokens') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('api::seat.date') }}</th>
          <th>{{ trans('api::seat.comment') }}</th>
          <th>{{ trans_choice('api::seat.token', 1) }}</th>
          <th>{{ trans('api::seat.allowed_from') }}</th>
        </tr>

        @foreach($tokens as $token)

          <tr>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $token->created_at }}">
                {{ human_diff($token->created_at) }}
              </span>
            </td>
            <td>{{ $token->comment }}</td>
            <td>{{ $token->token }}</td>
            <td>{{ $token->allowed_src }}</td>
            <td>
              <div class="btn-group">
                <a href="{{ route('api-admin.token.delete', [$token->id]) }}" type="button"
                   class="btn btn-danger btn-xs confirmlink col-xs-6">
                  {{ trans('api::seat.delete') }}
                </a>
                <a href="{{ route('api-admin.token.logs', [$token->id]) }}" type="button"
                   class="btn btn-primary btn-xs col-xs-6">
                  {{ trans('api::seat.logs') }}
                </a>
              </div>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($tokens) }} {{ trans_choice('api::seat.token', count($tokens)) }}
      <span class="pull-right">
        API Documentation can be found <a href="{{ route('l5-swagger.api') }}">here</a>.
      </span>
    </div>
  </div>

@stop
