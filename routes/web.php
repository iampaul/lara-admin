<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Regions
Route::post('regions/getStates', 'common\Regions@postGetStates')->name('post:common:regions:getStates');
Route::post('regions/getCities', 'common\Regions@postGetCities')->name('post:common:regions:getCities');

Route::get('/', function(){ return redirect(ADMIN_URL.'/login'); });
//Admin Panel
Route::prefix(ADMIN_URL)->group(function () {

	Route::get('/login', 'admin\Auth@getLogin')->name('get:admin:auth:login');
	Route::post('/login', 'admin\Auth@postLogin')->name('post:admin:auth:login');

	//Admin Authentication And Permission
	Route::group(['middleware' => ['admin_authentication']], function () {

		Route::get('/', 'admin\Admin@getDashboard')->name('get:admin:admin:dashboard');
		Route::get('/dashboard', 'admin\Admin@getDashboard')->name('get:admin:admin:dashboard');
		Route::get('/logout', 'admin\Auth@logout')->name('get:admin:auth:logout');

		Route::get('/editProfile', 'admin\Admin@getEditProfile')->name('get:admin:admin:editProfile');
		Route::post('/editProfile', 'admin\Admin@postEditProfile')->name('post:admin:admin:editProfile');

		Route::get('/changePassword', 'admin\Admin@getChangePassword')->name('get:admin:admin:changePassword');
		Route::post('/changePassword', 'admin\Admin@postChangePassword')->name('post:admin:admin:changePassword');

		//Settings
		Route::get('/settings/generalSettings', 'admin\Settings@getGeneralSettings')->name('get:admin:settings:generalSettings');
		Route::get('/settings/editGeneralSetting/{setting_id}', 'admin\Settings@getEditGeneralSetting')->name('get:admin:settings:editGeneralSetting');
		Route::post('/settings/editGeneralSetting/{setting_id}', 'admin\Settings@postEditGeneralSetting')->name('post:admin:settings:editGeneralSetting');

		Route::get('/settings/siteContactInfo', 'admin\Settings@getSiteContactInfo')->name('get:admin:settings:siteContactInfo');
		Route::post('/settings/siteContactInfo', 'admin\Settings@postSiteContactInfo')->name('post:admin:settings:siteContactInfo');

		//Social Media
		Route::get('/settings/manageSocialMedias', 'admin\Settings@getManageSocialMedias')->name('get:admin:settings:manageSocialMedias');
		Route::get('/settings/editSocialMedia/{id}', 'admin\Settings@getEditSocialMedia')->name('get:admin:settings:editSocialMedia');
		Route::post('/settings/editSocialMedia/{id}', 'admin\Settings@postEditSocialMedia')->name('post:admin:settings:editSocialMedia');
		
		//Logos
		Route::get('/settings/manageLogos', 'admin\Settings@getManageLogos')->name('get:admin:settings:manageLogos');
		
		Route::get('/settings/editLogo/{logo_id}', 'admin\Settings@getEditLogo')->name('get:admin:settings:editLogo');
		Route::post('/settings/editLogo/{logo_id}', 'admin\Settings@postEditLogo')->name('post:admin:settings:editLogo');
		
		//Email Templates
		Route::get('/settings/manageEmailTemplates', 'admin\Settings@getManageEmailTemplates')->name('get:admin:settings:manageEmailTemplates');		
		Route::get('/settings/editEmailTemplate/{template_id}', 'admin\Settings@getEditEmailTemplate')->name('get:admin:settings:editEmailTemplate');
		Route::post('/settings/editEmailTemplate/{template_id}', 'admin\Settings@postEditEmailTemplate')->name('post:admin:settings:editEmailTemplate');		

		//Roles
		Route::get('/roles/manageRoles', 'admin\Roles@getManageRoles')->name('get:admin:roles:manageRoles');
		Route::get('/roles/addRole', 'admin\Roles@getAddRole')->name('get:admin:roles:addRole');
		Route::post('/roles/addRole', 'admin\Roles@postAddRole')->name('post:admin:roles:addRole');
		Route::get('/roles/editRole/{role_id}', 'admin\Roles@getEditRole')->name('get:admin:roles:editRole');
		Route::post('/roles/editRole/{role_id}', 'admin\Roles@postEditRole')->name('post:admin:roles:editRole');
		Route::get('/roles/deleteRole/{role_id}', 'admin\Roles@getDeleteRole')->name('get:admin:roles:deleteRole');

		//Sub Admins
		Route::get('/admins/manageAdmins', 'admin\Admin@getManageAdmins')->name('get:admin:admins:manageAdmins');
		Route::get('/admins/addAdmin', 'admin\Admin@getAddAdmin')->name('get:admin:admins:addAdmin');
		Route::post('/admins/addAdmin', 'admin\Admin@postAddAdmin')->name('post:admin:admins:addAdmin');
		Route::get('/admins/editAdmin/{sub_admin_id}', 'admin\Admin@getEditAdmin')->name('get:admin:admins:editAdmin');
		Route::post('/admins/editAdmin/{sub_admin_id}', 'admin\Admin@postEditAdmin')->name('post:admin:admins:editAdmin');
		Route::get('/admins/deleteAdmin/{sub_admin_id}', 'admin\Admin@getDeleteAdmin')->name('get:admin:admins:deleteAdmin');

		//users
		Route::get('/users/manageUsers', 'admin\Users@getManageUsers')->name('get:admin:users:manageUsers');
		Route::get('/users/addUser', 'admin\Users@getAddUser')->name('get:admin:users:addUser');
		Route::post('/users/addUser', 'admin\Users@postAddUser')->name('post:admin:users:addUser');
		Route::get('/users/editUser/{user_id}', 'admin\Users@getEditUser')->name('get:admin:users:editUser');
		Route::post('/users/editUser/{user_id}', 'admin\Users@postEditUser')->name('post:admin:users:editUser');
		Route::get('/users/deleteUser/{user_id}', 'admin\Users@getDeleteUser')->name('get:admin:users:deleteUser');

		//users
		Route::get('/vendors/manageVendors', 'admin\Vendors@getManageVendors')->name('get:admin:vendors:manageVendors');
		Route::get('/vendors/addVendor', 'admin\Vendors@getAddVendor')->name('get:admin:vendors:addVendor');
		Route::post('/vendors/addVendor', 'admin\Vendors@postAddVendor')->name('post:admin:vendors:addVendor');
		Route::get('/vendors/editVendor/{vendor_id}', 'admin\Vendors@getEditVendor')->name('get:admin:vendors:editVendor');
		Route::post('/vendors/editVendor/{vendor_id}', 'admin\Vendors@postEditVendor')->name('post:admin:vendors:editVendor');
		Route::get('/vendors/deleteVendor/{vendor_id}', 'admin\Vendors@getDeleteVendor')->name('get:admin:vendors:deleteVendor');

		//Pages
		Route::get('/pages/managePages', 'admin\Pages@getManagePages')->name('get:admin:pages:managePages');
		Route::get('/pages/addPage', 'admin\Pages@getAddPage')->name('get:admin:pages:addPage');
		Route::post('/pages/addPage', 'admin\Pages@postAddPage')->name('post:admin:pages:addPage');
		Route::get('/pages/editPage/{page_id}', 'admin\Pages@getEditPage')->name('get:admin:pages:editPage');
		Route::post('/pages/editPage/{page_id}', 'admin\Pages@postEditPage')->name('post:admin:pages:editPage');
		Route::get('/pages/deletePage/{page_id}', 'admin\Pages@getDeletePage')->name('get:admin:pages:deletePage');

		//Html Blocks
		Route::get('/pages/manageHtmlBlocks', 'admin\Pages@getManageHtmlBlocks')->name('get:admin:pages:manageHtmlBlocks');		
		Route::get('/pages/editHtmlBlock/{block_id}', 'admin\Pages@getEditHtmlBlock')->name('get:admin:pages:editHtmlBlock');
		Route::post('/pages/editHtmlBlock/{block_id}', 'admin\Pages@postEditHtmlBlock')->name('post:admin:pages:editHtmlBlock');
		
		//FAQ
		Route::get('/pages/manageFAQ', 'admin\Pages@getManageFAQ')->name('get:admin:pages:manageFAQ');
		Route::get('/pages/addFAQ', 'admin\Pages@getAddFAQ')->name('get:admin:pages:addFAQ');
		Route::post('/pages/addFAQ', 'admin\Pages@postAddFAQ')->name('post:admin:pages:addFAQ');
		Route::get('/pages/editFAQ/{faq_id}', 'admin\Pages@getEditFAQ')->name('get:admin:pages:editFAQ');
		Route::post('/pages/editFAQ/{faq_id}', 'admin\Pages@postEditFAQ')->name('post:admin:pages:editFAQ');
		Route::get('/pages/deleteFAQ/{faq_id}', 'admin\Pages@getDeleteFAQ')->name('get:admin:pages:deleteFAQ');

		//Banners
		Route::get('/pages/manageBanners', 'admin\Pages@getManageBanners')->name('get:admin:pages:manageBanners');
		Route::get('/pages/addBanner', 'admin\Pages@getAddBanner')->name('get:admin:pages:addBanner');
		Route::post('/pages/addBanner', 'admin\Pages@postAddBanner')->name('post:admin:pages:addBanner');
		Route::get('/pages/editBanner/{banner_id}', 'admin\Pages@getEditBanner')->name('get:admin:pages:editBanner');
		Route::post('/pages/editBanner/{banner_id}', 'admin\Pages@postEditBanner')->name('post:admin:pages:editBanner');
		Route::get('/pages/deleteBanner/{banner_id}', 'admin\Pages@getDeleteBanner')->name('get:admin:pages:deleteBanner');	

		//Contact Us
		Route::get('/pages/manageContactRequests', 'admin\Pages@getManageContactRequests')->name('get:admin:pages:manageContactRequests');		
		Route::get('/pages/replyContactRequest/{id}', 'admin\Pages@getReplyContactRequest')->name('get:admin:pages:replyContactRequest');
		Route::post('/pages/replyContactRequest/{id}', 'admin\Pages@postReplyContactRequest')->name('post:admin:pages:replyContactRequest');
		Route::get('/pages/deleteContactRequest/{id}', 'admin\Pages@getDeleteContactRequest')->name('get:admin:pages:deleteContactRequest');

		//Tools
		Route::get('/tools/manageTools', 'admin\Tools@getManageTools')->name('get:admin:tools:manageTools');
		Route::get('/tools/addTool', 'admin\Tools@getAddTool')->name('get:admin:tools:addTool');
		Route::post('/tools/addTool', 'admin\Tools@postAddTool')->name('post:admin:tools:addTool');
		Route::get('/tools/editTool/{tool_id}', 'admin\Tools@getEditTool')->name('get:admin:tools:editTool');
		Route::post('/tools/editTool/{tool_id}', 'admin\Tools@postEditTool')->name('post:admin:tools:editTool');
		Route::get('/tools/deleteTool/{tool_id}', 'admin\Tools@getDeleteTool')->name('get:admin:tools:deleteTool');

		//Fabirc Categories
		Route::get('/tools/manageFabricCategories', 'admin\Tools@getManageFabricCategories')->name('get:admin:tools:manageFabricCategories');
		Route::get('/tools/addFabricCategory', 'admin\Tools@getAddFabricCategory')->name('get:admin:tools:addFabricCategory');
		Route::post('/tools/addFabricCategory', 'admin\Tools@postAddFabricCategory')->name('post:admin:tools:addFabricCategory');
		Route::get('/tools/editFabricCategory/{category_id}', 'admin\Tools@getEditFabricCategory')->name('get:admin:tools:editFabricCategory');
		Route::post('/tools/editFabricCategory/{category_id}', 'admin\Tools@postEditFabricCategory')->name('post:admin:tools:editFabricCategory');
		Route::get('/tools/deleteFabricCategory/{category_id}', 'admin\Tools@getDeleteFabricCategory')->name('get:admin:tools:deleteFabricCategory');
		Route::post('/tools/ajaxGetFabricCategories', 'admin\Tools@postAjaxGetFabricCategories')->name('post:common:tools:ajaxGetFabricCategories');

		//Fabrics
		Route::get('/tools/manageFabrics', 'admin\Tools@getManageFabrics')->name('get:admin:tools:manageFabrics');
		Route::get('/tools/addFabric', 'admin\Tools@getAddFabric')->name('get:admin:tools:addFabric');
		Route::post('/tools/addFabric', 'admin\Tools@postAddFabric')->name('post:admin:tools:addFabric');
		Route::get('/tools/editFabric/{fabric_id}', 'admin\Tools@getEditFabric')->name('get:admin:tools:editFabric');
		Route::post('/tools/editFabric/{fabric_id}', 'admin\Tools@postEditFabric')->name('post:admin:tools:editFabric');
		Route::get('/tools/deleteFabric/{fabric_id}', 'admin\Tools@getDeleteFabric')->name('get:admin:tools:deleteFabric');

		//Accesory Options
		Route::get('/tools/manageOptions/{accessory_id}', 'admin\Tools@getManageOptions')->name('get:admin:tools:manageOptions');
		Route::get('/tools/addOption/{accessory_id}', 'admin\Tools@getAddOption')->name('get:admin:tools:addOption');
		Route::post('/tools/addOption/{accessory_id}', 'admin\Tools@postAddOption')->name('post:admin:tools:addOption');
		Route::get('/tools/editOption/{option_id}', 'admin\Tools@getEditOption')->name('get:admin:tools:editOption');
		Route::post('/tools/editOption/{option_id}', 'admin\Tools@postEditOption')->name('post:admin:tools:editOption');
		Route::get('/tools/deleteOption/{option_id}', 'admin\Tools@getDeleteOption')->name('get:admin:tools:deleteOption');			
	});	

});	

//Front
Route::group(['namespace' => 'front'], function(){

});