@extends('admin.layout.default')
@section('title')
    {{ $title }}
@endsection
@section('page-css')    
@endsection
@section('page-content')
<div class="row">
<div class="col-12">
<div class="card">
    <div class="card-header">
        <h5>{{ $title }}</h5>
    </div>
    <div class="card-content">
    <div class="card-body"> 
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ route('post:admin:roles:editRole',array('role_id' => safe_b64encode($role->role_id))) }}">
            {{ csrf_field() }}
            <ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist">
              <li class="nav-item">
                <a class="nav-link {{ ($active_tab=='general')?'active':'' }}" id="tab-general" data-toggle="tab" aria-controls="general" href="#tab-general-content" role="tab" aria-selected="true">General</a>
              </li>
               <li class="nav-item">
                <a class="nav-link {{ ($active_tab=='permissions')?'active':'' }}" id="tab-permissions" data-toggle="tab" aria-controls="permissions" href="#tab-permissions-content" role="tab" aria-selected="true">Permissions</a>
              </li>
            </ul>
            <div class="tab-content px-1 pt-4 border-grey border-lighten-2 border-0-top">
            <div class="tab-pane {{ ($active_tab=='general')?'active':'' }}" id="tab-general-content" role="tabpanel" aria-labelledby="tab-general">
            <div class="form-body">
              <div class="form-group row">
                  <label class="col-md-3 label-control" for="title">Title</label>
                  <div class="col-md-6">
                      <input type="text" id="title" name="title" class="form-control validate[required]" placeholder="Title" value="{{ old('title', $role->title) }}" >
                  </div>
              </div>

              <div class="form-group row">
                  <label class="col-md-3 label-control" for="status">Status</label>  
                  <div class="col-md-6">
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-primary" name="status" id="status_active" value="ACTIVE" {{ $role->status == "ACTIVE" ? 'CHECKED' : '' }}>
                    <label class="custom-control-label" for="status_active">Active</label>
                  </div>
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-danger" name="status" id="status_disabled" value="DISABLED" {{ $role->status == "DISABLED" ? 'CHECKED' : '' }}>
                    <label class="custom-control-label" for="status_disabled">Disabled</label>
                  </div>
                  </div>
              </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="update_general" class="btn btn-primary">
                    Save
                </button>
            </div>
            </div>
            <div class="tab-pane {{ ($active_tab=='permissions')?'active':'' }}" id="tab-permissions-content" role="tabpanel" aria-labelledby="tab-permissions">
            <div class="form-body">
              <div class="form-group row">
                  <div class="table-responsive">
                  <table class="table table-bordered">
                  <thead>
                      <tr>
                          <th>Permissions</th>
                          <th class="text-center">View</th>
                          <th class="text-center">Update</th>
                          <th class="text-center">Delete</th>
                      </tr>
                  </thead>
                  <tbody>

                  @if($permissions) @foreach($permissions as $permission)
                  <tr class="skin skin-flat">
                  <td>{{ $permission->title }}</td>  
                  <td align="center"><input class="icheckbox_flat-green" type="checkbox" name="permissions[{{ $permission->permission_id }}][is_view]" value="Y" {{ ($permission->is_view == 'Y')?'CHECKED':'' }}></td>
                  <td align="center"><input class="icheckbox_flat-green" type="checkbox" name="permissions[{{ $permission->permission_id }}][is_update]" value="Y" {{ ($permission->is_update == "Y")?"CHECKED":"" }} ></td>
                  <td align="center"><input class="icheckbox_flat-green" type="checkbox" name="permissions[{{ $permission->permission_id }}][is_delete]" value="Y" {{ ($permission->is_delete == "Y")?"CHECKED":"" }} ></td>
                  </tr>                                      
                  @endforeach
                  @endif
                  
                  </tbody>

                  </table>
                  </div>
              </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="update_permissions" class="btn btn-primary">
                    Save
                </button>
            </div>
            </div>
            </div>
        </form>               
    </div>
    </div>
</div>
</div>                    
@endsection
@section('page-js')
@endsection