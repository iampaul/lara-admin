@extends('admin.layout.default')
@section('title')
{{ $title }}
@endsection
@section('page-css')    
@endsection
@section('header-right')
<!-- <div class="btn-group float-md-right" role="group">
<a href="#" class="btn btn-outline-primary"></a>
</div> -->
@endsection
@section('page-content')

<div class="card">
<div class="card-header">
<h4 class="card-title">General Settings</h4>
</div>
<div class="card-content">
<div class="card-body">
<div class="table-responsive">
<table id="new-cons" class="table table-striped table-bordered responsive dataTable" style="width:100%">
    <thead>
    <tr>
        <th data-priority="1">No</th>
        <th data-priority="2">Name</th>
        <th data-priority="4">Value</th>
        <th>Last Update</th>
        <th data-priority="3">Actions</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($general_settings))
        @foreach($general_settings as $key => $setting)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $setting->name }}</td>
                <td>{{ \Illuminate\Support\Str::limit($setting->value, 150, $end='...') }}</td>
                <td>{{ getFormatDateTime($setting->updated_at, 'd-M-Y') }}</td>
                <td class="action">
                    
                    <a href="{{ 
                    route('get:admin:settings:editGeneralSetting',array('setting_id' => safe_b64encode($setting->setting_id))) }}"
                       class="btn btn-sm btn-primary" data-toggle="tooltip"data-placement="top" title="Edit">
                            <i class="ft-edit"></i> 
                    </a>
                    
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
</div>
</div>
</div>
</div>
      
@endsection
@section('page-js')
@endsection