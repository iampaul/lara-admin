@extends('admin.layout.default')
@section('title')
    {{ $title }}
@endsection
@section('page-css')
@endsection
@section('page-content')
<div class="card">
    <div class="card-header">
        <h5>Profile</h5>
    </div>
    <div class="card-content">
    <div class="card-body"> 
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ route('post:admin:admin:changePassword') }}">
            {{ csrf_field() }}
            <div class="form-body">
                <!-- <h4 class="form-section"><i class="ft-user"></i> Personal Info</h4> -->
                <div class="form-group row">
                    <label class="col-md-3 label-control" for="old_password">Old Password</label>
                    <div class="col-md-6">
                        <input type="password" id="old_password" name="old_password" class="form-control validate[required]" placeholder="Old Password" value="" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="new_password">New Password</label>
                    <div class="col-md-6">
                        <input type="password" id="new_password" name="new_password" class="form-control validate[required]" placeholder="New Password" value="" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="confirm_password">Confirm Password</label>
                    <div class="col-md-6">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control validate[required,equals[new_password]]" placeholder="Confirm Password" value="" >
                    </div>
                </div>
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

