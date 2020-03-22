@switch(strtolower($row->method))
  @case('post')
  <span class="badge badge-success">POST</span>
  @break
  @case('put')
  <span class="badge badge-warning">PUT</span>
  @break;
  @case('get')
  <span class="badge badge-info">GET</span>
  @break;
  @case('delete')
  <span class="badge badge-danger">DELETE</span>
  @break;
  @case('head')
  <span class="badge bg-purple">HEAD</span>
  @break;
  @case('patch')
  <span class="badge bg-teal">PATCH</span>
  @break;
  @case('options')
  <span class="badge badge-primary">OPTIONS</span>
  @break;
  @default
  <span class="badge badge-default">{{ strtoupper($row->method) }}</span>
@endswitch