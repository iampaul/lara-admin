<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow pt-2" data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
      <!-- <li class=" navigation-header"><span>General</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="General"></i>
      </li> -->
      <li class=" nav-item"><a href="{!! admin_url() !!}"><i class="ft-home"></i>Dashboard</a></li>
      <li class=" nav-item"><a href="{!! admin_url('roles/manageRoles') !!}"><i class="ft-user-plus"></i>Roles</a></li>
      <li class=" nav-item"><a href="{!! admin_url('admins/manageAdmins') !!}"><i class="ft-users"></i>Admins</a></li>
      <li class=" nav-item"><a href="{!! admin_url('users/manageUsers') !!}"><i class="icon-users"></i>Users</a></li>
      <li class=" nav-item"><a href="{!! admin_url('vendors/manageVendors') !!}"><i class="fa fa-user-circle"></i>Vendors</a></li>
 
      <li><a class="menu-item" href="#"><i class="ft-file"></i>CMS</a>
        <ul class="menu-content">
          <li><a class="menu-item" href="{!! admin_url('pages/managePages') !!}">Pages</a>
          </li>
          <li><a class="menu-item" href="{!! admin_url('pages/manageHtmlBlocks') !!}">HTML Blocks</a>
          </li>
          <li><a class="menu-item" href="{!! admin_url('pages/manageFAQ') !!}">FAQ</a>
          </li>
          <li><a class="menu-item" href="{!! admin_url('pages/manageBanners') !!}">Banners</a>
          </li>                    
        </ul>
      </li>
      <li><a class="menu-item" href="#"><i class="ft-settings"></i>Settings</a>
        <ul class="menu-content">
          <li><a class="menu-item" href="{!! admin_url('settings/generalSettings') !!}">General Settings</a>
          </li>          
          <li><a class="menu-item" href="{!! admin_url('settings/manageLogos') !!}">Site Logos</a>
          </li>
          <li><a class="menu-item" href="{!! admin_url('settings/siteContactInfo') !!}">Site Contact Info</a>
          </li>
          <li><a class="menu-item" href="{!! admin_url('settings/manageSocialMedias') !!}">Social Medias</a>
          </li>
          <li><a class="menu-item" href="{!! admin_url('settings/manageEmailTemplates') !!}">Email Templates</a>
          </li>
        </ul>
      </li>
      <li><a class="menu-item" href="{!! admin_url('pages/manageContactRequests') !!}"><i class="ft-mail"></i>Contact Messages</a></li>
           
    </ul>
  </div>
</div>