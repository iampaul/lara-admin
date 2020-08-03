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
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ admin_url('pages/editBanner/'.safe_b64encode($banner->banner_id)) }}" enctype="multipart/form-data">
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
                    <label class="col-md-3 label-control" for="title">Title</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" class="form-control validate[required]" placeholder="Title" value="{{ old('title', $banner->title) }}" >
                    </div>
                </div>

               <div class="form-group row">
                    <label class="col-md-3 label-control" for="title">Image</label>
                    <div class="col-md-6">
                      <input type="hidden" id="uploaded_banner_image" name="uploaded_banner_image" value="{{ $banner->image }}">
                      <input class="form-control-file" type="file" id="banner_image" name="banner_image">
                      <img id="banner-image-view" src="{!! image_url('banners/thumb/'.$banner->image) !!}" width ="75" />
                    </div>
                    </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 label-control" for="content">Description</label>
                  <div class="col-md-6">
                    <textarea id="description" name="description" class="form-control validate[required]">{{ old('description', $banner->description) }}</textarea>
                  </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="price">Price</label>
                    <div class="col-md-6">
                        <input type="text" id="price" name="price" class="form-control validate[required,custom[number]]" placeholder="Price" value="{{ old('price', $banner->price) }}" >
                    </div>
                </div>                

                <div class="form-group row">
                  <label class="col-md-3 label-control" for="status">Status</label>  
                  <div class="col-md-6">
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-primary" name="status" id="status_active" value="ACTIVE" {{ $banner->status == "ACTIVE" ? 'CHECKED' : '' }} >
                    <label class="custom-control-label" for="status_active">Active</label>
                  </div>
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-danger" name="status" id="status_disabled" value="DISABLED" {{ $banner->status == "DISABLED" ? 'CHECKED' : '' }} >
                    <label class="custom-control-label" for="status_disabled">Disabled</label>
                  </div>
                  </div>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" name="update_general" class="btn btn-primary" data-callback="callBackEditBanner">
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
<script type="text/javascript">
  function callBackEditBanner(data)
  {
     if(data.status == "SUCCESS")
     {                
        $('#banner-image-view').attr('src',data.image_url);
        $('#uploaded_banner_image').val(data.image);
        
        toastr.options.positionClass = 'toast-top-right';
        toastr.success(data.message);        
     }  
     else
     {
        if(data.errors)
        {  
          $.each( data.errors, function( key, value ) 
          {                           
             toastr.options.positionClass = 'toast-top-right';
             toastr.error(value);
          });
        }
        
        if(data.message)
        {
          toastr.options.positionClass = 'toast-top-right';
          toastr.error(data.message);  
        }  
     }
  }
</script>
@endsection