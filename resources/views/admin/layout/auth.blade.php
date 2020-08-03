<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
      <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
      <meta name="author" content="PIXINVENT">
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
      <!-- END Page Level CSS-->

      <!-- Form Validation -->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/formValidator/css/validationEngine.jquery.css') }}">

      <!-- BEGIN Custom CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/assets/css/style.css') }}">
      @yield('page-css')
      <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/style-changes.css') }}">
      <!-- END Custom CSS-->
   </head>
   <body class="vertical-layout vertical-menu 1-column   menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">
      <div id="cover-spin"></div>
      <div class="app-content content">     
         <div class="content-wrapper">            
            <div class="content-header row">
            </div>
            <div class="content-body">              
                  {{--content start--}}
                  <div id="render-content">
                     @yield('page-content')
                  </div>                  
                  {{--content end--}}
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
      
      @yield('page-js')

      <!-- Custom Scripts -->
      <script type="text/javascript" src="{{ asset('admin-assets/js/custom.js') }}"></script>
      
      <script type="text/javascript">
         @if (Session::has('success'))
             toastr.options.positionClass = 'toast-top-right';
             toastr.success("{{ Session::get('success') }}");    
         @endif
         @if (Session::has('error'))
             toastr.options.positionClass = 'toast-top-right';
             toastr.error("{{ Session::get('error') }}");
         @endif

         $(document).ajaxStart(function () {
            //ajax request went so show the loading image
            $('#cover-spin').show();
         }).ajaxStop(function () {
           //got response so hide the loading image
            $('#cover-spin').hide();
         });
      </script>
      {!! Toastr::message() !!}      
   </body>
</html>