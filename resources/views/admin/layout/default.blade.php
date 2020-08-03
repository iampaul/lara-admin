<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
      <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
      <meta name="author" content="PIXINVENT">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <title>@yield('title')</title>      
      <!-- <link rel="icon"
         href="{{ isset($icon)? ($icon->website_favicon != Null) ? asset('assets/images/website-logo-icon/'.$icon->website_favicon) : '' : '' }}"
         type="image/x-icon"> -->
      <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
      <!-- BEGIN VENDOR CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/vendors.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/icheck/icheck.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/icheck/custom.css') }}">
      <!-- END VENDOR CSS-->
      <!-- BEGIN STACK CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/app.css') }}">
      <!-- END STACK CSS-->
      <!-- BEGIN Page Level CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/toastr.css') }}">
      <link rel="stylesheet" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert.css')}}">
      <link rel="stylesheet" href="{{ asset('admin-assets/app-assets/fonts/simple-line-icons/style.min.css')}}">
      <!-- END Page Level CSS-->

      <!-- Form Validation -->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/formValidator/css/validationEngine.jquery.css') }}">

      <!-- Data Table -->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">      
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css') }}">

      <!-- Switches -->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/toggle/switchery.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/switch.css') }}">

       <!-- Fancy Box -->    
      <link rel="stylesheet" href="{{ asset('common-assets/fancybox/jquery.fancybox.css') }}">

      <!-- BEGIN Custom CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/assets/css/style.css') }}">
      @yield('page-css')
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/style-changes.css') }}">
      <!-- END Custom CSS-->      
   </head>
   <body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
      <div id="cover-spin"></div>

      {{--navbar start--}}      
        @include('admin.include.navbar')

      {{--sidebar start--}}      
        @include('admin.include.sidebar')       
      
      <div class="app-content content">
        
         <div class="content-wrapper">            
            <div class="content-header row">
               <div class="content-header-left col-md-6 col-12 mb-2">

                  <div class="row breadcrumbs-top">
                     <div class="breadcrumb-wrapper col-12">
                        @if(isset($breadcrumb_params))
                           {{ ($breadcrumb)?Breadcrumbs::render($breadcrumb, $breadcrumb_params):'' }}
                        @else
                           {{ ($breadcrumb)?Breadcrumbs::render($breadcrumb):'' }}   
                        @endif   
                     </div>
                  </div>
                  <h3 class="content-header-title mb-0">@yield('title')</h3>
               </div>
               <div class="content-header-right col-md-6 col-12">
                  @yield('header-right')
               </div>
            </div>
            <div class="content-body">
               <section>
                  {{--content start--}}
                  <div id="render-content">
                     @yield('page-content')
                  </div>                  
                  {{--content end--}}
               </section>
            </div>
         </div>
      </div>
      
      <script src="{{ asset('admin-assets/app-assets/vendors/js/vendors.min.js') }}"></script>
      <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/icheck/icheck.min.js') }}"></script>
      <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js') }}"></script>
      <script src="{{ asset('admin-assets/app-assets/js/core/app-menu.js') }}"></script>
      <script src="{{ asset('admin-assets/app-assets/js/core/app.js') }}"></script>
      <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-login-register.js') }}"></script>
      <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert.min.js') }}"></script>
      <!-- Form Validation -->
      <script type="text/javascript" src="{{ asset('admin-assets/plugins/formValidator/js/jquery.validationEngine.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('admin-assets/plugins/formValidator/js/languages/jquery.validationEngine-en.js') }}"></script>

      <!-- Data Table -->
      <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
      <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>

      <!-- Switches -->
      <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js') }}"></script>  
      <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/toggle/switchery.min.js') }}"></script>
      <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/switch.js') }}"></script>

      <!-- Tiny MCE Editor -->
      <script src="{{ asset('common-assets/tinymce/tinymce.min.js') }}"></script>

      <!-- Fancy Box -->
      <script src="{{ asset('common-assets/fancybox/jquery.fancybox.js') }}"></script>

      @yield('page-js')
      
      <!-- Custom Scripts -->
      <script type="text/javascript" src="{{ asset('admin-assets/js/custom.js') }}"></script>
      
      <script>
         @if (Session::has('success'))
             toastr.options.positionClass = 'toast-top-right';
             toastr.success("{{ Session::get('success') }}");    
         @endif
         @if (Session::has('error'))
             toastr.options.positionClass = 'toast-top-right';
             toastr.error("{{ Session::get('error') }}");
         @endif

         //Datatable
         $('.dataTable').DataTable({
           responsive: true     
         });
      </script>
      {!! Toastr::message() !!}


      <!-- Tiny MCE Editor -->
       <script type="text/javascript">
         tinymce.init({
            selector: ".cleditor",
            relative_urls : false,
            remove_script_host : false,
            setup: function (editor) {
                 editor.on('change', function () { 
                     tinymce.triggerSave();
                 });
            },
            plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste responsivefilemanager"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | responsivefilemanager",
            external_filemanager_path:"{{ asset('common-assets/tinymce-filemanager/filemanager') }}/",
            filemanager_title:"Filemanager" ,
            external_plugins: { "filemanager" : "{{ asset('common-assets/tinymce-filemanager/filemanager/plugin.min.js') }}"}
            });
      </script>         
   </body>
</html>