<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('get:admin:admin:dashboard'));
});

// Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
	$trail->parent('home');
    $trail->push('Dashboard', route('get:admin:admin:dashboard'));
});

// Edit Profile
Breadcrumbs::for('edit_profile', function ($trail) {
	$trail->parent('home');
    $trail->push('Edit Profile', route('get:admin:admin:editProfile'));
});

// Change Password
Breadcrumbs::for('change_password', function ($trail) {
	$trail->parent('home');
    $trail->push('Change Password', route('get:admin:admin:changePassword'));
});

// General Settings
Breadcrumbs::for('general_settings', function ($trail) {
	$trail->parent('home');
    $trail->push('General Settings', route('get:admin:settings:generalSettings'));
});

// Edit General Settings
Breadcrumbs::for('edit_general_setting', function ($trail,$setting) {
	$trail->parent('general_settings');
    $trail->push('Edit General Setting', route('get:admin:settings:editGeneralSetting',$setting->setting_id));
});

Breadcrumbs::for('site_contact_info', function ($trail) {
    $trail->parent('home');
    $trail->push('Site Contact Info', route('get:admin:settings:siteContactInfo'));
});

// Manage Roles
Breadcrumbs::for('manage_roles', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Roles', route('get:admin:roles:manageRoles'));
});

// Add Role
Breadcrumbs::for('add_role', function ($trail) {
    $trail->parent('manage_roles');
    $trail->push('Add Role', route('get:admin:roles:addRole'));
});

// Edit Role
Breadcrumbs::for('edit_role', function ($trail,$role) {
    $trail->parent('manage_roles');
    $trail->push('Edit Role', route('get:admin:roles:editRole',$role->role_id));
});

// Manage Admins
Breadcrumbs::for('manage_admins', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Admins', route('get:admin:admins:manageAdmins'));
});

// Add Admin
Breadcrumbs::for('add_admin', function ($trail) {
    $trail->parent('manage_admins');
    $trail->push('Add Admin', route('get:admin:admins:addAdmin'));
});

// Edit Admin
Breadcrumbs::for('edit_admin', function ($trail,$admin) {
    $trail->parent('manage_admins');
    $trail->push('Edit Admin', route('get:admin:admins:editAdmin',$admin->admin_id));
});


// Manage Pages
Breadcrumbs::for('manage_pages', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Pages', route('get:admin:pages:managePages'));
});

// Add Page
Breadcrumbs::for('add_page', function ($trail) {
    $trail->parent('manage_pages');
    $trail->push('Add Page', route('get:admin:pages:addPage'));
});

// Edit Page
Breadcrumbs::for('edit_page', function ($trail,$page) {
    $trail->parent('manage_pages');
    $trail->push('Edit Page', route('get:admin:pages:editPage',$page->page_id));
});

// Manage Html Blocks
Breadcrumbs::for('manage_html_blocks', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Html Blocks', route('get:admin:pages:manageHtmlBlocks'));
});

// Add Html Block
Breadcrumbs::for('add_html_block', function ($trail) {
    $trail->parent('manage_html_blocks');
    $trail->push('Add Html Block', route('get:admin:pages:addHtmlBlock'));
});

// Edit Html Block
Breadcrumbs::for('edit_html_block', function ($trail,$block) {
    $trail->parent('manage_html_blocks');
    $trail->push('Edit Html Block', route('get:admin:pages:editHtmlBlock',$block->block_id));
});

// Manage Html Blocks
Breadcrumbs::for('manage_faq', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage FAQ', route('get:admin:pages:manageFAQ'));
});

// Add Html Block
Breadcrumbs::for('add_faq', function ($trail) {
    $trail->parent('manage_faq');
    $trail->push('Add FAQ', route('get:admin:pages:addFAQ'));
});

// Edit Html Block
Breadcrumbs::for('edit_faq', function ($trail,$faq) {
    $trail->parent('manage_faq');
    $trail->push('Edit FAQ', route('get:admin:pages:editFAQ',$faq->faq_id));
});


// Manage Social Medias
Breadcrumbs::for('manage_social_medias', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Social Medias', route('get:admin:settings:manageSocialMedias'));
});

// Add Html Block
Breadcrumbs::for('add_social_media', function ($trail) {
    $trail->parent('manage_social_medias');
    $trail->push('Add Social Media', route('get:admin:settings:addSocialMedia'));
});

// Edit Html Block
Breadcrumbs::for('edit_social_media', function ($trail,$social_media) {
    $trail->parent('manage_social_medias');
    $trail->push('Edit Social Media', route('get:admin:settings:editSocialMedia',$social_media->id));
});

// Manage Banners
Breadcrumbs::for('manage_banners', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Banners', route('get:admin:pages:manageBanners'));
});

// Add Banner
Breadcrumbs::for('add_banner', function ($trail) {
    $trail->parent('manage_banners');
    $trail->push('Add Banner', route('get:admin:pages:addBanner'));
});

// Edit Banner
Breadcrumbs::for('edit_banner', function ($trail,$banner) {
    $trail->parent('manage_banners');
    $trail->push('Edit Banner', route('get:admin:pages:editBanner',$banner->banner_id));
});

// Manage Logos
Breadcrumbs::for('manage_logos', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Logos', route('get:admin:settings:manageLogos'));
});

// Add Logo
Breadcrumbs::for('add_logo', function ($trail) {
    $trail->parent('manage_logos');
    $trail->push('Add Logo', route('get:admin:settings:addLogo'));
});

// Edit Logo
Breadcrumbs::for('edit_logo', function ($trail,$logo) {
    $trail->parent('manage_logos');
    $trail->push('Edit Logo', route('get:admin:settings:editLogo',$logo->logo_id));
});


// Manage Email Templates
Breadcrumbs::for('manage_email_templates', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Email Templates', route('get:admin:settings:manageEmailTemplates'));
});

// Add Email Templates
Breadcrumbs::for('add_email_template', function ($trail) {
    $trail->parent('manage_email_templates');
    $trail->push('Add Email Template', route('get:admin:settings:addEmailTemplate'));
});

// Edit Email Template
Breadcrumbs::for('edit_email_template', function ($trail,$email_template) {
    $trail->parent('manage_email_templates');
    $trail->push('Edit Email Template', route('get:admin:settings:editEmailTemplate',$email_template->template_id));
});

// Manage Users
Breadcrumbs::for('manage_users', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Users', route('get:admin:users:manageUsers'));
});

// Add User
Breadcrumbs::for('add_user', function ($trail) {
    $trail->parent('manage_users');
    $trail->push('Add User', route('get:admin:users:addUser'));
});

// Edit User
Breadcrumbs::for('edit_user', function ($trail,$user) {
    $trail->parent('manage_users');
    $trail->push('Edit User', route('get:admin:users:editUser',$user->user_id));
});

// Manage Vendors
Breadcrumbs::for('manage_vendors', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Vendors', route('get:admin:vendors:manageVendors'));
});

// Add Vendor
Breadcrumbs::for('add_vendor', function ($trail) {
    $trail->parent('manage_vendors');
    $trail->push('Add Vendor', route('get:admin:vendors:addVendor'));
});

// Edit Vendor
Breadcrumbs::for('edit_vendor', function ($trail,$vendor) {
    $trail->parent('manage_vendors');
    $trail->push('Edit Vendor', route('get:admin:vendors:editVendor',$vendor->vendor_id));
});


// Manage Contact Requests
Breadcrumbs::for('manage_contact_requests', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Contact Requests', route('get:admin:pages:manageContactRequests'));
});

// Reply Contact Requests
Breadcrumbs::for('reply_contact_request', function ($trail,$contact_request) {
    $trail->parent('manage_contact_requests');
    $trail->push('Reply Contact Request', route('get:admin:pages:replyContactRequest',$contact_request->id));
});

// Manage Tools
Breadcrumbs::for('manage_tools', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Tools', route('get:admin:tools:manageTools'));
});

// Add Tool
Breadcrumbs::for('add_tool', function ($trail) {
    $trail->parent('manage_tools');
    $trail->push('Add Tool', route('get:admin:tools:addTool'));
});

// Edit Tool
Breadcrumbs::for('edit_tool', function ($trail,$tool) {
    $trail->parent('manage_tools');
    $trail->push('Edit Tool', route('get:admin:tools:editTool',$tool->tool_id));
});

// Manage Fabric Categories
Breadcrumbs::for('manage_tool_fabric_categories', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Fabric Categories', route('get:admin:tools:manageFabricCategories'));
});

// Add Fabric Category
Breadcrumbs::for('add_tool_fabric_category', function ($trail) {
    $trail->parent('manage_tool_fabric_categories');
    $trail->push('Add Fabric Category', route('get:admin:tools:addFabricCategory'));
});

// Edit Fabric Category
Breadcrumbs::for('edit_tool_fabric_category', function ($trail,$fabric_category) {
    $trail->parent('manage_tool_fabric_categories');
    $trail->push('Edit Fabric Category', route('get:admin:tools:editFabricCategory',$fabric_category->category_id));
});

// Manage Fabrics
Breadcrumbs::for('manage_tool_fabrics', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Fabrics', route('get:admin:tools:manageFabrics'));
});

// Add Fabric
Breadcrumbs::for('add_tool_fabric', function ($trail) {
    $trail->parent('manage_tool_fabrics');
    $trail->push('Add Fabric', route('get:admin:tools:addFabric'));
});

// Edit Fabric
Breadcrumbs::for('edit_tool_fabric', function ($trail,$fabric) {
    $trail->parent('manage_tool_fabrics');
    $trail->push('Edit Fabric', route('get:admin:tools:editFabric',$fabric->fabric_id));
});

// Manage Options
Breadcrumbs::for('manage_accessory_options', function ($trail,$accessory) {
    $trail->parent('home');
    $trail->push($accessory->group_name);
    $trail->push($accessory->name, route('get:admin:tools:manageOptions',$accessory->accessory_id));
});

// Add Option
Breadcrumbs::for('add_accessory_option', function ($trail,$accessory) {
    $trail->parent('manage_accessory_options',$accessory);    
    $trail->push('Add Option', route('get:admin:tools:addOption',$accessory->accessory_id));
});

// Edit Option
Breadcrumbs::for('edit_accessory_option', function ($trail,$option) {
    $trail->parent('manage_accessory_options',$option);    
    $trail->push('Edit '.$option->option_reference, route('get:admin:tools:editOption',$option->option_id));
});