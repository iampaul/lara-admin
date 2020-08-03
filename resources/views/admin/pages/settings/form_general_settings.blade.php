@extends('admin.layout.default')
@section('title')
    {{ $title }}
@endsection
@section('page-css')    
@endsection
@section('page-content')
<div class="card">
    <div class="card-header">
        <h5>{{ $title }}</h5>
    </div>
    <div class="card-content">
    <div class="card-body"> 
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ route('post:admin:settings:editGeneralSetting',array('setting_id' => safe_b64encode($setting->setting_id))) }}">
            {{ csrf_field() }}
            <div class="form-body">
                <!-- <h4 class="form-section"><i class="ft-user"></i> Personal Info</h4> -->
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="name">Setting</label>
                    <div class="col-md-6">
                        <label> {{ $setting->name ?? old('name', '') }} </label>
                    </div>
                </div>
                
                @if(isset($setting) && $setting->code == 'SITE_STATUS')
                <div class="form-group row">
                  <label class="col-md-3 label-control" for="value">Value</label>  
                  <div class="col-md-6">
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-primary" name="value" id="value_online" value="ONLINE" {{ isset($setting) && $setting->value == "ONLINE" ? 'CHECKED' : '' }}>
                    <label class="custom-control-label" for="value_online">ONLINE</label>
                  </div>
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-danger" name="value" id="value_offline" value="OFFLINE" {{ isset($setting) && $setting->value == "OFFLINE" ? 'CHECKED' : '' }}>
                    <label class="custom-control-label" for="value_offline">OFFLINE</label>
                  </div>
                  </div>
                </div>
                @elseif(isset($setting) && $setting->code == 'admin_form_request_type')
                <div class="form-group row">
                  <label class="col-md-3 label-control" for="value">Value</label>  
                  <div class="col-md-6">
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-primary" name="value" id="value_regular" value="REGULAR" {{ isset($setting) && $setting->value == "REGULAR" ? 'CHECKED' : '' }}>
                    <label class="custom-control-label" for="value_regular">Regular</label>
                  </div>
                  <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input bg-primary" name="value" id="value_ajax" value="AJAX" {{ isset($setting) && $setting->value == "AJAX" ? 'CHECKED' : '' }}>
                    <label class="custom-control-label" for="value_ajax">Ajax</label>
                  </div>
                  </div>
                </div>
                @else
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="value">Value</label>
                    <div class="col-md-6">
                        <input type="text" id="value" name="value" class="form-control validate[required,@if((isset($setting) && $setting->code == 'SITE_ADMIN_MAIL'))custom[email]@endif]" placeholder="Value" value="{{ $setting->value ?? old('value', '') }}" >
                    </div>
                </div>
                @endif

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
        </form>               
    </div>
    </div>
</div>                    
@endsection
@section('page-js')
@endsection