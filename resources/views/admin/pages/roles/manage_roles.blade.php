@extends('admin.layout.default')
@section('title')
{{ $title }}
@endsection
@section('page-css')    
@endsection
@section('header-right')
<div class="btn-group float-md-right" role="group">
    <a href="{{ route('get:admin:roles:addRole') }}" class="btn btn-primary"><i class="fa fa-plus mr-1"></i>Add New Role</a>
</div>
@endsection
@section('page-content')

<div class="card">
<div class="card-header">
<h4 class="card-title">Manage Roles</h4>
</div>
<div class="card-content">
<div class="card-body">
<div class="table-responsive">
<table id="new-cons" class="table table-striped table-bordered responsive dataTable" style="width:100%">
    <thead>
    <tr>
        <th data-priority="1">No</th>
        <th data-priority="2">Title</th>
        <th data-priority="4">Status</th>
        <th class="text-center" data-priority="3">Actions</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($roles))
        @foreach($roles as $key => $role)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $role->title }}</td>
                <td>{{ $role->status }}</td>
                <td class="action text-center">
                    
                    <a href="{{ 
                    route('get:admin:roles:editRole',array('role_id' => safe_b64encode($role->role_id))) }}"
                       class="btn btn-sm btn-primary" data-toggle="tooltip"data-placement="top" title="Edit">
                            <i class="ft-edit"></i> 
                    </a>

                    <a href="{{ 
                    route('get:admin:roles:deleteRole',array('role_id' => safe_b64encode($role->role_id))) }}"
                       class="btn btn-sm btn-danger {{ (isAjaxRequest())?'ajax-delete-confirm':'delete-confirm' }}" data-toggle="tooltip"data-placement="top" title="Delete">
                            <i class="ft-trash"></i> 
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