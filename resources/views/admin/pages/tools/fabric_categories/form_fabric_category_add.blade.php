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
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ admin_url('tools/addFabricCategory') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="tab-general" data-toggle="tab" aria-controls="general" href="#tab-general-content" role="tab" aria-selected="true">General</a>
              </li>
            </ul>
            <div class="tab-content px-2 pt-4">
            <div class="tab-pane active" id="tab-general-content" role="tabpanel" aria-labelledby="tab-general">
            <div class="form-body">


                <div class="form-group row">
                    <label class="col-md-3 label-control" for="tool_id">Tool</label>
                    <div class="col-md-6">
                        <select id="tool_id" name="tool_id" class="custom-select form-control">
                            @if($tools)
                                @foreach($tools as $tool)
                                <option value="{{ $tool->tool_id }}">{{ $tool->tool_name }}</option>    
                                @endforeach
                            @endif
                        </select>    
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="category_name">Category Name</label>
                    <div class="col-md-6">
                        <input type="text" id="category_name" name="category_name" class="form-control validate[required]" placeholder="Category Name" value="{{ old('category_name', '') }}" >
                    </div>
                </div>
                

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="price">Price</label>
                    <div class="col-md-2">
                        <input type="text" id="price" name="price" class="form-control validate[required,custom[number]]" placeholder="Price" value="{{ old('price', '') }}" >
                    </div>
                </div>
               
                <div class="form-group row">
                  <label class="col-md-3 label-control" for="status">Status</label>  
                  <div class="col-md-6">
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-primary" name="status" id="status_active" value="ACTIVE" checked>
                    <label class="custom-control-label" for="status_active">Active</label>
                  </div>
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-danger" name="status" id="status_disabled" value="DISABLED" >
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
            </div>
        </form>               
    </div>
    </div>
</div>
</div>                    
@endsection
@section('page-js')
@endsection