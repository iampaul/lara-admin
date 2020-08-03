
//Ajax Loader
$(document).ajaxStart(function () {
    //ajax request went so show the loading image
    $('#cover-spin').show();
 }).ajaxStop(function () {
   //got response so hide the loading image
    $('#cover-spin').hide();
 });

$(document).ready(function() {
    $("form button[type=submit]").click(function() {
        $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
    });
});

//Validate Form
$('.validate-form').validationEngine();

$('.ajax-form').on('submit',function(e){
  e.preventDefault();

  var submit_button = $("button[type=submit][clicked=true]");
  var args = {};
  args.submit_button = submit_button;

  if ( $(this).hasClass('validate-form')) 
  {
     if($('.ajax-form').validationEngine('validate'))
        ajaxFormSubmit($(this),args);
  }
  else
  {
      ajaxFormSubmit($(this),args);
  }   
});

//Ajax form Submit
function ajaxFormSubmit(that,args={})
{
   var form = that;
   var url = form.attr('action');

   var submit_button      = args.submit_button;
   var submit_button_name = submit_button.attr('name');
   
    /*var formData = form.serialize();
    formData = formData + '&'+submit_button_name+'=true';*/

    var data = new FormData();
    
    //Form data
    var form_data = form.serializeArray();
    $.each(form_data, function (key, input) {
        data.append(input.name, input.value);
    });

    //File data
    $(that).find("input[type=file]").each(function(index, field){      
      for (var i = 0; i < field.files.length; i++) {
          const file = field.files[i];
          data.append(field.name, file);
      }
    });

    //Custom data
    data.append(submit_button_name, true);

   $.ajax({
       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
       type: "POST",
       url: url,
       data: data,
       processData: false,
       contentType: false, 
       success: function(data)
       {
           //callback after successful ajax function
           var callback = submit_button.attr('data-callback');
           if(callback != "" && typeof callback !== 'undefined')
           { 
             var callback = window[callback];
             callback(data);
           }
           else if(data.reload)
           {              
              window.location.reload();
           }
           else if(data.redirect_url)
           {
              var callback = submit_button.attr('data-callback');              
              window.location = data.redirect_url
           } 
           else if(data.status == "SUCCESS")
           {                
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
  });
}

//Regular Delete Confirmation
$('.delete-confirm').on('click',function(e){
  e.preventDefault();

  var currentElement = $(this);
  
  swal({
        title: "Are you sure?",
        /*text: "Please confirm your action",*/
        icon: "warning",
        buttons: {
          cancel: {
              text: "No, cancel Please!",
              value: null,
              visible: true,
              className: "btn-primary",              
          },
          confirm: {
              text: "Yes, delete it!",
              value: true,
              visible: true,
              className: "btn-danger",
              closeModal: false
          }
        }
    }).then(isConfirm => {
        if (isConfirm) {
            window.location = currentElement.attr('href');
        } else {
            swal("Cancelled", "It's safe.", "error");
        }
    });
});

//Ajax Delete Confirmation
$('.ajax-delete-confirm').on('click',function(e){
  e.preventDefault();

  var that = $(this);
  
  swal({
        title: "Are you sure?",
        /*text: "Please confirm your action",*/
        icon: "warning",
        buttons: {
          cancel: {
              text: "No, cancel Please!",
              value: null,
              visible: true,
              className: "btn-primary",              
          },
          confirm: {
              text: "Yes, delete it!",
              value: true,
              visible: true,
              className: "btn-danger",
              closeModal: false
          }
        }
    }).then(isConfirm => {
        if (isConfirm) {
            ajaxRowDelete(that);
        } else {
            swal("Cancelled", "It's safe.", "error");
        }
    });
});

//Ajax Delete
function ajaxRowDelete(that,args=[])
{
   var form = that;
   var url = that.attr('href');
   
   $.ajax({
       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
       type: "GET",
       url: url,       
       success: function(data)
       {
           //callback after successful ajax function
           var callback = "";
           if(callback != "" && typeof callback !== 'undefined')
           { 
             var callback = window[callback];
             callback(data);
           }
           else if(data.reload)
           {
              window.location.reload();
           }
           else if(data.redirect_url)
           {
              window.location = data.redirect_url
           } 
           else if(data.status == "SUCCESS")
           {
                swal("Success!", data.message, "success");

                if(data.remove_row)
                {
                    $(that).parents('tr').remove();
                }  
           }  
           else
           {
              swal("Error!", "Unable to delete now. Try again later!", "error");
           } 
       }
  });
}


//Radio Checkbox
$('.skin-polaris input').iCheck({
    checkboxClass: 'icheckbox_polaris',
    radioClass: 'iradio_polaris',
    increaseArea: '-10%'
});

$('.skin-futurico input').iCheck({
    checkboxClass: 'icheckbox_futurico',
    radioClass: 'iradio_futurico',
    increaseArea: '20%'
});

$('.skin-flat input').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
});