@if(strtoupper($log->method) == 'GET')
    <span class="label label-info">{{ strtoupper($log->method) }}</span>
@elseif($log->method == 'PUT')
    <span class="label label-warning">{{ strtoupper($log->method) }}</span>
@elseif($log->method == 'PATCH')
    <span class="label-primary">{{ strtoupper($log->method) }}</span>
@elseif($log->method == 'POST')
    <span class="label label-success">{{ strtoupper($log->method) }}</span>
@elseif($log->method == 'DELETE')
    <span class="label label-danger">{{ strtoupper($log->method) }}</span>
@else
    <span class="label label-default">N/A</span>
@endif