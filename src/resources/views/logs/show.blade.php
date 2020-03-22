@extends('web::layouts.grids.12')

@section('title', trans('api::seat.api_token_logs'))
@section('page_header', trans('api::seat.api_token_logs'))

@section('full')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('api::seat.access_logs') }}</h3>
    </div>
    <div class="card-body">

      {!! $dataTable->table() !!}

    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
