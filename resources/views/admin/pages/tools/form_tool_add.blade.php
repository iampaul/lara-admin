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
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ admin_url('tools/addTool') }}" enctype="multipart/form-data">
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
                    <label class="col-md-3 label-control" for="tool_category">Select Measurement Category</label>
                    <div class="col-md-6">
                        <select id="tool_category" name="tool_category" class="custom-select form-control">
                                <option value="SUITS">Suits</option>
                                <option value="SHIRTS">Shirts</option>                               
                        </select>    
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="tool_code">Tool Code</label>
                    <div class="col-md-6">
                        <input type="text" id="tool_code" name="tool_code" class="form-control validate[required]" placeholder="Tool Code" value="{{ old('tool_code', '') }}" >
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="tool_name">Tool Name</label>
                    <div class="col-md-6">
                        <input type="text" id="tool_name" name="tool_name" class="form-control validate[required]" placeholder="Tool Name" value="{{ old('tool_name', '') }}" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="product_name">Product Name</label>
                    <div class="col-md-6">
                        <input type="text" id="product_name" name="product_name" class="form-control validate[required]" placeholder="Tool Name" value="{{ old('product_name', '') }}" >
                    </div>
                </div>                

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="title">Image</label>
                    <div class="col-md-6">
                      <input class="form-control-file" type="file" id="banner_image" name="banner_image">
                    </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="default_price">Default Price</label>
                    <div class="col-md-2">
                        <input type="text" id="default_price" name="default_price" class="form-control validate[required,custom[number]]" placeholder="Default Price" value="{{ old('default_price', '') }}" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="shipping_price">Shipping Price</label>
                    <div class="col-md-2">
                        <input type="text" id="shipping_price" name="shipping_price" class="form-control validate[required,custom[number]]" placeholder="Shipping Price" value="{{ old('shipping_price', '') }}" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="designed_for">Designed For</label>
                    <div class="col-md-2">
                        <select id="designed_for" name="designed_for" class="custom-select form-control">
                                <option value="MEN">MEN</option>
                                <option value="WOMEN">WOMEN</option>                               
                        </select>    
                    </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 label-control" for="short_description">Short Description</label>
                  <div class="col-md-6">
                    <textarea id="short_description" name="short_description" class="form-control validate[required]">{{ old('short_description', '') }}</textarea>
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