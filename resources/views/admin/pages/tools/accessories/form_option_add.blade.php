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
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ admin_url('tools/addOption/'.safe_b64encode($accessory->accessory_id)) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <input type ="hidden" id="accessory_id" name="accessory_id" value="{{ $accessory->accessory_id }}">

            <ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="tab-general" data-toggle="tab" aria-controls="general" href="#tab-general-content" role="tab" aria-selected="true">General</a>
              </li>
            </ul>
            <div class="tab-content px-2 pt-4">
            <div class="tab-pane active" id="tab-general-content" role="tabpanel" aria-labelledby="tab-general">
            <div class="form-body">


                <div class="form-group row">
                    <label class="col-md-3 label-control" for="option_name">Name</label>
                    <div class="col-md-6">
                        <input type="text" id="option_name" name="option_name" class="form-control validate[required]" placeholder="Name" value="{{ old('option_name', '') }}" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="option_reference">Reference</label>
                    <div class="col-md-6">
                        <input type="text" id="option_reference" name="option_reference" class="form-control" placeholder="Reference" value="{{ old('option_reference', '') }}" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="title">Image</label>
                    <div class="col-md-6">
                      <input class="form-control-file" type="file" id="option_image" name="option_image">
                    </div>
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
<script type="text/javascript">

$(document).ready(function(){
    getFabricCategories();
});

$('#tool_id').on('change',function(){
    getFabricCategories();
});

function getFabricCategories()
{
  var selected_category_id = "";
  var tool_id = $("#tool_id").find(':selected').val();
  var formData = "tool_id="+tool_id;

  $.ajax({
  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
  url: "{{ admin_url('tools/ajaxGetFabricCategories') }}",
  method: 'post',
  data: formData,
  global:false,
  success: function(data) {
      result = JSON.parse(data);
      if(result.status == "SUCCESS")
      {    
          fabric_categories = result.fabric_categories;
          var value="";
          
          fabric_categories.forEach(function(element) {
              
              var selected = "";

              if(element.category_id == selected_category_id)
                  selected = "selected";

              value +="<option "+selected+" value='"+element.category_id+"'>"+element.category_name+"</option>";
          });
          $('#category_id').html(value);
          getCities();
      }
      else
      {
        toastr.options.positionClass = 'toast-top-right';
        toastr.error('Something went wrong');
      }    
  }
  });
} 
</script>
@endsection