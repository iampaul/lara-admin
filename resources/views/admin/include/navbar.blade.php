<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
<div class="navbar-wrapper">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
      <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
      <li class="nav-item"><a class="navbar-brand" href="html/ltr/vertical-menu-template-semi-dark/index.html"><img class="brand-logo" alt="Admin logo" src="{{ asset('admin-assets/app-assets/images/logo/store-logo.png') }}">
          <!-- <h2 class="brand-text"></h2> --></a></li>
      <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li>
    </ul>
  </div>
  <div class="navbar-container content">
    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="nav navbar-nav mr-auto float-left">
        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
        
        <!-- <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li> -->
      </ul>
      <ul class="nav navbar-nav float-right">              
        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="avatar avatar-online"><img src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-1.png') }}" alt="avatar"></span><span class="user-name">{{ session::get('admin_firstname') }}</span></a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{!! admin_url('editProfile') !!}"><i class="ft-user"></i> Edit Profile</a>
            <a class="dropdown-item" href="{!! admin_url('changePassword') !!}"><i class="fa fa-key"></i> Change Password </a>
            <a class="dropdown-item" href="{!! admin_url('settings/generalSettings') !!}"><i class="fa fa-gear"></i> General Settings </a>
            <a class="dropdown-item" href="{!! admin_url('settings/siteContactInfo') !!}"><i class="fa fa-file"></i> Site contact info </a>
            <div class="dropdown-divider"></div><a class="dropdown-item" href="{!! admin_url('logout') !!}"><i class="ft-power"></i> Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
</nav>