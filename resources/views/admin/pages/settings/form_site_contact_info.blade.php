@extends('admin.layout.default')
@section('title')
    {{ $title }}
@endsection
@section('page-css')    
@endsection
@section('page-content')
<div class="card">
    <div class="card-header">
        <h5>Contact Details</h5>
    </div>
    <div class="card-content">
    <div class="card-body"> 
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ route('post:admin:settings:siteContactInfo') }}">
            {{ csrf_field() }}
            <ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="tab-general" data-toggle="tab" aria-controls="general" href="#tab-general-content" role="tab" aria-selected="true">General</a>
              </li>
            </ul>
            <div class="tab-content px-2 pt-4">
            <div class="tab-pane active" id="tab-general-content" role="tabpanel" aria-labelledby="tab-general">
            <div class="form-body">
                <!-- <h4 class="form-section"><i class="ft-user"></i> Personal Info</h4> -->
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="email">Email</label>
                    <div class="col-md-6">
                        <input type="text" id="email" name="email" class="form-control validate[required]" placeholder="Email" value="{{ old('email', $info->email ) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="address">Address</label>
                    <div class="col-md-6">
                        <input type="text" id="address" name="address" class="form-control validate[required]" placeholder="First Name" value="{{ old('address', $info->address) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="country">Country</label>
                    <div class="col-md-6">
                        <select id="country" name="country" class="custom-select form-control">
                            @if($countries)
                                @foreach($countries as $country)
                                <option {{ (isset($info->country) && $info->country == $country->name)?"SELECTED":"" }} value="{{ $country->name }}" data-country-id="{{ $country->country_id }}">{{ $country->name }}</option>    
                                @endforeach
                            @endif
                        </select>    
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="state">State</label>
                    <div class="col-md-6">
                        <select id="state" name="state" class="custom-select form-control">
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="city">City</label>
                    <div class="col-md-6">
                        <select id="city" name="city" class="custom-select form-control">
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="postcode">Post Code</label>
                    <div class="col-md-6">
                        <input type="text" id="postcode" name="postcode" class="form-control" placeholder="Postal code" value="{{ old('postcode', $info->postcode) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="postcode">Mobile</label>
                    <div class="col-md-6">
                        <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Mobile" value="{{ old('mobile', $info->mobile) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="postcode">Landline</label>
                    <div class="col-md-6">
                        <input type="text" id="landline" name="landline" class="form-control" placeholder="landline" value="{{ old('landline', $info->landline) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="postcode">Fax</label>
                    <div class="col-md-6">
                        <input type="text" id="fax" name="fax" class="form-control" placeholder="fax" value="{{ old('fax', $info->fax) }}" >
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
            </div>
            </div>
        </form>               
    </div>
    </div>
</div>                    
@endsection
@section('page-js')
<script type="text/javascript">

$(document).ready(function(){
    getStates();
});

$('#country').on('change',function(){
    getStates();
});

$('#state').on('change',function(){
    getCities();
});

function getStates()
{
  var selected_state = "{{ ($info->state)?$info->state:"" }}";
  var country_id = $("#country").find(':selected').data('country-id')
  var formData = "country_id="+country_id;

  $.ajax({
  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
  url: "{{ route('post:common:regions:getStates') }}",
  method: 'post',
  data: formData,
  global:false,
  success: function(data) {
      result = JSON.parse(data);
      if(result.status == "SUCCESS")
      {    
          states = result.states;
          var value="";
          
          states.forEach(function(element) {
              
              var selected = "";

              if(element.name == selected_state)
                  selected = "selected";

              value +="<option "+selected+" value='"+element.name+"' data-state-id='"+element.state_id+"'>"+element.name+"</option>";
          });
          $('#state').html(value);
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

function getCities()
{
  var selected_city = "{{ ($info->city)?$info->city:"" }}";

  var state_id = $("#state").find(':selected').data('state-id')
  var formData = "state_id="+state_id;

  $.ajax({
  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
  url: "{{ route('post:common:regions:getCities') }}",
  method: 'post',
  data: formData,
  global:false,
  success: function(data) {
      result = JSON.parse(data);
      
      if(result.status == "SUCCESS")
      {    
          cities = result.cities;
          var value="";
      
          cities.forEach(function(element) {
              
              var selected = "";

              if(element.name == selected_city)
                  selected = "selected";

              value +="<option "+selected+" value='"+element.name+"' >"+element.name+"</option>";
          });
          $('#city').html(value)
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