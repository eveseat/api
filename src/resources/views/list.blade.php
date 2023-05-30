@extends('web::layouts.grids.3-9')

@section('title', trans('api::seat.api_token_admin'))
@section('page_header', trans('api::seat.api_token_admin'))

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('api::seat.new_token') }}
      </h3>
      <div class="card-tools">
        <span class="float-right">
          <a href="{{ route('l5-swagger.default.api') }}" target="_blank" class="btn btn-success btn-xs">
            <i class="fas fa-book"></i>
            {{ trans('api::seat.api_docs') }}
          </a>
        </span>
      </div>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('seatcore::api-admin.token.create') }}" method="post" id="key-form">
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
            <div class="card bg-danger p-1">
              {{ trans('api::seat.ip_danger') }}
            </div>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary float-right">
            <i class="fas fa-random"></i>
            {{ trans('api::seat.generate') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('api::seat.current_tokens') }}</h3>
    </div>
    <div class="card-body">

      <table class="table table-condensed table-hover">
        <thead>
          <tr>
            <th>{{ trans('api::seat.date') }}</th>
            <th>{{ trans('api::seat.comment') }}</th>
            <th>{{ trans_choice('api::seat.token', 1) }}</th>
            <th>{{ trans('api::seat.allowed_from') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
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
                  <a href="{{ route('seatcore::api-admin.token.delete', [$token->id]) }}" type="button"
                     class="btn btn-danger btn-xs confirmlink col-xs-6">
                    <i class="fas fa-trash-alt"></i>
                    {{ trans('api::seat.delete') }}
                  </a>
                  <a href="{{ route('seatcore::api-admin.token.logs', [$token->id]) }}" type="button"
                     class="btn btn-primary btn-xs col-xs-6">
                    <i class="fas fa-clipboard-list"></i>
                    {{ trans('api::seat.logs') }}
                  </a>
                </div>
              </td>
            </tr>

          @endforeach

        </tbody>
      </table>

    </div>
    <div class="card-footer">
      {{ count($tokens) }} {{ trans_choice('api::seat.token', count($tokens)) }}
      <span class="float-right">
        API Documentation can be found <a href="{{ route('l5-swagger.default.api') }}">here</a>.
      </span>
    </div>
  </div>

@stop
