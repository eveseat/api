@extends('web::layouts.grids.3-9')

@section('title', 'Api Token Admin')
@section('page_header', 'Api Token Admin')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Token</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('api-admin.token.create') }}" method="post" id="key-form">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="comment">Key Comment</label>
            <input type="text" name="comment" class="form-control" id="comment" value="{{ old('comment') }}"
                   placeholder="Comment">
          </div>

          <div class="form-group">
            <label for="text">Allowed IP Address</label>
            <input type="text" name="allowed_src" class="form-control" id="allowed_src" value="{{ old('allowed_src') }}"
                   placeholder="IP Address">
            <span class="help-block">
              This is the source IP address the will be allowed to use the generated token.
            </span>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            Generate
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Current API Token</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Created</th>
          <th>Comment</th>
          <th>Token</th>
          <th>Allowed From</th>
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
                  Delete
                </a>
                <a href="{{ route('api-admin.token.logs', [$token->id]) }}" type="button"
                   class="btn btn-primary btn-xs col-xs-6">
                  Logs
                </a>
              </div>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($tokens) }} tokens
    </div>
  </div>

@stop
