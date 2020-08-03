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
        <form class="form form-horizontal {{ (isAjaxRequest())?'ajax-form':'' }} validate-form" method="post" action="{{ admin_url('pages/replyContactRequest/'.safe_b64encode($contact_request->id)) }}">
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
                    <label class="col-md-3 label-control" for="title">Name</label>
                    <div class="col-md-6">
                       <label class="label-control" for="title">{{ $contact_request->name?$contact_request->name:'' }}</label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 label-control" for="title">Email</label>
                    <div class="col-md-6">
                       <label class="label-control" for="title">{{ $contact_request->email?$contact_request->email:'' }}</label>
                    </div>
                </div>

                 <div class="form-group row">
                    <label class="col-md-3 label-control" for="title">Mobile</label>
                    <div class="col-md-6">
                       <label class="label-control" for="title">{{ $contact_request->mobile?$contact_request->mobile:'' }}</label>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 label-control" for="title">Subject</label>
                    <div class="col-md-6">
                       <label class="label-control" for="title">{{ $contact_request->subject?$contact_request->subject:'' }}</label>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 label-control" for="title">Message</label>
                    <div class="col-md-6">
                       <label class="label-control" for="title">{{ $contact_request->message?$contact_request->message:'' }}</label>
                    </div>
                  </div>

                <h4>Reply Messages</h4> 
                @if(!$reply_messages->isEmpty())
                    @foreach($reply_messages as $key => $reply_message)
                    <div class="form-group row">
                      <label class="col-md-2 label-control" for="title">Reply {{ $loop->iteration }}:</label>
                      <div class="col-md-6">
                        <label class="label-control" for="title">{!! $reply_message->reply_message !!}</label>
                      </div>
                    </div>
                   @endforeach 
                @else

                  <p class="no-items">No Replies</p>

                @endif                

                <div class="form-group row">
                  <label class="col-md-2 label-control" for="content">Reply</label>
                  <div class="col-md-9">
                    <textarea id="reply_message" name="reply_message" class="form-control validate[required]">{{ old('reply_message', '') }}</textarea>
                  </div>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" name="update_general" class="btn btn-primary" data-callback="callReplySent">
                    Send
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
  function callReplySent(data)
  {
     if(data.status == "SUCCESS")
     {                
        $('#reply_message').val('');
        
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