@extends('admin.layout.default')
@section('title')
{{ $title }}
@endsection
@section('page-css')    
@endsection
@section('header-right')
<div class="btn-group float-md-right" role="group">
    <a href="{{ admin_url('tools/addFabric') }}" class="btn btn-primary"><i class="fa fa-plus mr-1"></i>Add New Fabric</a>
</div>
@endsection
@section('page-content')

<div class="card">
<div class="card-header">
<h4 class="card-title">{{ $title }}</h4>
</div>
<div class="card-content">
<div class="card-body">
<div class="table-responsive">
<table id="new-cons" class="table table-striped table-bordered responsive dataTable" style="width:100%">
    <thead>
    <tr>
        <th data-priority="1">No</th>
        <th data-priority="2">Image</th>
        <th data-priority="2">Name</th>
        <th data-priority="2">Code</th>                
        <th data-priority="4">Status</th>
        <th class="text-center" data-priority="3">Actions</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($fabrics))
        @foreach($fabrics as $key => $fabric)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><img src="{!! image_url('tools/fabrics/'.$fabric->image) !!}" width="50" onerror="this.src='{!! image_url('no_image/item-no-image.jpg') !!}'" /></td> 
                <td>{{ Str::limit($fabric->fabric_name, $limit = 150, $end = '...') }}</td>
                <td>{{ $fabric->fabric_code }}</td>                               
                <td>{{ $fabric->status }}</td>
                <td class="action text-center">
                    
                    <a href="{{ admin_url('tools/editFabric/'.safe_b64encode($fabric->fabric_id)) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="ft-edit"></i> 
                    </a>

                    <a href="{{ admin_url('tools/deleteFabric/'.safe_b64encode($fabric->fabric_id)) }}" class="btn btn-sm btn-danger {{ (isAjaxRequest())?'ajax-delete-confirm':'delete-confirm' }}" data-toggle="tooltip" data-placement="top" title="Delete">
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