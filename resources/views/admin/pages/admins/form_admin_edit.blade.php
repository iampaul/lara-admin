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
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ route('post:admin:admins:editAdmin',array('sub_admin_id' => safe_b64encode($admin->admin_id) )) }}">
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
                    <label class="col-md-3 label-control" for="role">Select Role</label>
                    <div class="col-md-6">
                        <select id="role_id" name="role_id" class="custom-select form-control">
                            @if($roles)
                                @foreach($roles as $role)
                                <option value="{{ $role->role_id }}">{{ $role->title }}</option>    
                                @endforeach
                            @endif
                        </select>    
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="email">Email</label>
                    <div class="col-md-6">
                        <label class="col-md-6" for="email">{{ $admin_email }}</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="password">Password</label>
                    <div class="col-md-6">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="{{ old('password', '') }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="firstname">First Name</label>
                    <div class="col-md-6">
                        <input type="text" id="firstname" name="firstname" class="form-control validate[required]" placeholder="First Name" value="{{ old('firstname', $admin->firstname) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="lastname">Last Name</label>
                    <div class="col-md-6">
                        <input type="text" id="lastname" name="lastname" class="form-control validate[required]" placeholder="Last Name" value="{{ old('lastname', $admin->lastname) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="contact_number">Contact Number</label>
                    <div class="col-md-6">
                        <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="Contact Number" value="{{ old('contact_number', $admin->contact_number) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="address">Address</label>
                    <div class="col-md-6">
                        <input type="text" id="address" name="address" class="form-control" placeholder="Address" value="{{ old('address', $admin->address) }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="country">Country</label>
                    <div class="col-md-6">
                        <select id="country" name="country" class="custom-select form-control">
                            @if($countries)
                                @foreach($countries as $country)
                                <option {{ (isset($admin->country) && $admin->country == $country->name)?"SELECTED":"" }} value="{{ $country->name }}" data-country-id="{{ $country->country_id }}">{{ $country->name }}</option>    
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
                        <input type="text" id="postcode" name="postcode" class="form-control" placeholder="Postal code" value="{{ old('postcode', $admin->postcode) }}" >
                    </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 label-control" for="status">Status</label>  
                  <div class="col-md-6">
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-primary" name="status" id="status_active" value="ACTIVE" {{ $admin->status == "ACTIVE" ? 'CHECKED' : '' }} >
                    <label class="custom-control-label" for="status_active">Active</label>
                  </div>
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-danger" name="status" id="status_disabled" value="DISABLED" {{ $admin->status == "DISABLED" ? 'CHECKED' : '' }} >
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
  var selected_state = "{{ ($admin->state)?$admin->state:"" }}";
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
  var selected_city = "{{ ($admin->city)?$admin->city:"" }}";

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