<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Admins_model;
use App\Models\Settings_model;
use App\Models\Regions_model;
use Session;
use View;
use Redirect;
use Validator;
use Response;
use Str;
use Image;

class Settings extends Controller
{
    public function __construct()
    {
        $this->outputData = array();
    }

    public function getGeneralSettings()
    {
        $admin_id = Session::Get('admin_ID');

        $params = array('is_visible' => 'Y');
        $general_settings = Settings_model::getGeneralSettings($params);
        
        $this->outputData['general_settings'] = $general_settings;

        $this->outputData['title'] = 'General Settings';    
        $this->outputData['breadcrumb'] = 'general_settings';
        return view('admin.pages.settings.manage_general_settings',$this->outputData);
    }

    public function getEditGeneralSetting($setting_id)
    {
        $admin_id = Session::Get('admin_ID');
        
        $setting_id = safe_b64decode($setting_id);

        $params = array('setting_id' => $setting_id, 'result_type' => 'FIRST');
        $setting = Settings_model::getGeneralSettings($params);

        if(!$setting)
           return redirect_admin('settings/generalSettings');
        
                
        $this->outputData['setting'] = $setting;
        
        $this->outputData['title'] = 'Edit General Setting';    
        $this->outputData['breadcrumb'] = 'edit_general_setting';
        $this->outputData['breadcrumb_params'] = $setting;
        return view('admin.pages.settings.form_general_settings',$this->outputData);
    }

    public function postEditGeneralSetting(Request $request,$setting_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        //Check Valiation
        $validator = Validator::make( $input_data, Settings_model::$rules['edit_general_setting']);
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);
        }
        
        $setting_id = safe_b64decode($setting_id);

        //Update Settings
        $update_data = array(
                        'value'     => $input_data['value'],
                        'updated_at'=> date('Y-m-d H:i:s')
                    );
        
        $condition = array('setting_id' => $setting_id);
        Settings_model::updateGeneralSetting($condition,$update_data);

        //Response
        $params = array(
                        'status' => "SUCCESS",
                        'message'=> "Settings Updated Successfully"
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }


    public function getSiteContactInfo()
    {
        $admin_id = Session::Get('admin_ID');
        
        $id = 1;

        $params = array('id' => 1, 'result_type' => 'FIRST');
        $info   = Settings_model::getSiteContactInfo($params);

        if(!$info)
           return redirect_admin('settings/getE');
        
        //Countries
        $countries = Regions_model::getCountries();
        $this->outputData['countries'] = $countries;

        $this->outputData['info'] = $info;
        
        $this->outputData['title'] = 'Site Contact Info';    
        $this->outputData['breadcrumb'] = 'site_contact_info';
        $this->outputData['breadcrumb_params'] = $info;
        return view('admin.pages.settings.form_site_contact_info',$this->outputData);
    }

    public function postSiteContactInfo(Request $request)
    {   

        $admin_id = Session::Get('admin_ID');

        $id = 1;

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        //Check Valiation
        $validator = Validator::make( $input_data, Settings_model::$rules['site_contact_info']);
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);
        }        

        //Update Settings
        $update_data = array(
                        'email'     => $input_data['email'],
                        'address'   => $input_data['address'],                        
                        'city'      => $input_data['city'],
                        'state'     => $input_data['state'],
                        'country'   => $input_data['country'],
                        'postcode'  => $input_data['postcode'],
                        'landline'  => $input_data['landline'],
                        'fax'       => $input_data['fax'],                        
                        'updated_at'=> date('Y-m-d H:i:s')
                    );
        
        $condition = array('id' => $id);
        Settings_model::updateSiteContactInfo($condition,$update_data);

        //Response
        $params = array(
                        'status' => "SUCCESS",
                        'message'=> "Information Updated Successfully"
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    /* Social Media */
    public function getManageSocialMedias()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Social Medias        
        $social_medias = Settings_model::getSocialMedias();
        
        $this->outputData['social_medias'] = $social_medias;
        $this->outputData['title'] = 'Social Medias';    
        $this->outputData['breadcrumb'] = 'manage_social_medias';
        return view('admin.pages.settings.social_medias.manage_social_medias',$this->outputData);
    }

    public function getAddSocialMedia()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add Social Media';    
        $this->outputData['breadcrumb'] = 'add_social_media';
        return view('admin.pages.settings.social_medias.form_social_media_add',$this->outputData);
    }

    public function postAddSocialMedia(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        //Check Valiation                
        $validator = Validator::make( $input_data, Pages_model::$rules['add_social_media'] );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
        }

        //Insert Social Media
        $insert_data  = array(
                        'title' => $input_data['title'],
                        'social_link'  => $input_data['social_link'],                        
                        'status'=> $input_data['status'],
                        'created_at' => date('Y-m-d H:i:s')
                    );        
        
        $id      = Settings_model::insertSocialMedia($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Link added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('settings/editSocialMedia/'.safe_b64encode($id),$params);
    }

    public function getEditSocialMedia($id)
    {
        $admin_id   = Session::Get('admin_ID');

        $id    = safe_b64decode($id);        

        $params = array('id' => $id, 'result_type' => 'FIRST');
        $social_media   = Settings_model::getSocialMedias($params);

        if(!$social_media)
           return redirect_admin("settings/manageSocialMedias"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['social_media'] = $social_media;        
        
        $this->outputData['title'] = 'Edit Social Media';    
        $this->outputData['breadcrumb'] = 'edit_social_media';
        $this->outputData['breadcrumb_params'] = $social_media;
        return view('admin.pages.settings.social_medias.form_social_media_edit',$this->outputData);
    }

    public function postEditSocialMedia(Request $request,$id)
    {   

        $admin_id = Session::Get('admin_ID');

        $id    = safe_b64decode($id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Settings_model::$rules['edit_social_media_general'];
            $rules['title'] = 'required|string|unique:social_medias,title,'.$id;
            $validator = Validator::make( $input_data, $rules);
            if ( $validator->fails() ) { 

                $messages = $validator->messages();

                $params = array(
                            'status' => "ERROR",
                            'validation_error_messages' => $messages                                                  
                        );


                $params['request_type'] = $request_type;
                return redirect_admin('',$params);
            }            

            //Update Page Details
            $update_data = array(
                            'title' => $input_data['title'],                            
                            'social_link'  => $input_data['social_link'],
                            'status'=> $input_data['status'],
                            'updated_at'=> date('Y-m-d H:i:s')
                        );            
            
            $condition = array('id' => $id);
            Settings_model::updateSocialMedia($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Link updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteSocialMedia($id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $id    = safe_b64decode($id);

        $condition  = array('id' => $id);
        
        $result     = Settings_model::deleteSocialMedia($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Link Deleted.",
                            'remove_row' => true                       
                        );
        }
        else
        {
            $params = array(
                            'status' => "ERROR",
                            'message'=> "Alert! Something went wrong. Please try again.",                          
                        );   
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getManageLogos()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Logos        
        $logos = Settings_model::getLogos();
        
        $this->outputData['logos'] = $logos;
        $this->outputData['title'] = 'Logos';    
        $this->outputData['breadcrumb'] = 'manage_logos';
        return view('admin.pages.settings.logos.manage_logos',$this->outputData);
    }

    public function getAddLogo()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add Logo';    
        $this->outputData['breadcrumb'] = 'add_logo';
        return view('admin.pages.settings.logos.form_logo_add',$this->outputData);
    }

    public function postAddLogo(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['slug'] = (isset($input_data['slug']))?Str::slug($input_data['slug']):Str::slug($input_data['title']);

        //Check Valiation                
        $validator = Validator::make( $input_data, Settings_model::$rules['add_logo'] );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
        }

        //Image
        if($request->file('logo_image') !== null)
        {    
            $original_image     = $request->file('logo_image');
            $upload_image       = Image::make($original_image->getRealPath());
            $image_name         = $input_data['slug'].'.'.strtolower($original_image->getClientOriginalExtension());

            $original_path      = public_path().'/images/logo/';
            if (!is_dir($original_path)) {
                mkdir($original_path,0777, true);
            }

            $upload_image->save($original_path.$image_name);

            $image = $image_name;
        }
        else
        {
            $image = "";
        }    

        //Insert Logo
        $insert_data  = array(
                        'title' => $input_data['title'],                        
                        'slug'  => $input_data['slug'],                        
                        'image'=> $image, 
                        'status'=> $input_data['status'],
                        'created_at' => date('Y-m-d H:i:s')
                    );        
        
        $logo_id    = Settings_model::insertLogo($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Logo added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('settings/editLogo/'.safe_b64encode($logo_id),$params);
    }

    public function getEditLogo($logo_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $logo_id    = safe_b64decode($logo_id);        

        $params = array('logo_id' => $logo_id, 'result_type' => 'FIRST');
        $logo   = Settings_model::getLogos($params);

        if(!$logo)
           return redirect_admin("settings/manageLogos"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['logo'] = $logo;        
        
        $this->outputData['title'] = 'Edit Logo';    
        $this->outputData['breadcrumb'] = 'edit_logo';
        $this->outputData['breadcrumb_params'] = $logo;
        return view('admin.pages.settings.logos.form_logo_edit',$this->outputData);
    }

    public function postEditLogo(Request $request,$logo_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $logo_id    = safe_b64decode($logo_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['slug'] = (isset($input_data['slug']))?Str::slug($input_data['slug']):Str::slug($input_data['title']);        
        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Settings_model::$rules['edit_logo_general'];
            $rules['title'] = 'required|string|unique:site_logos,title,'.$logo_id.',logo_id';            
            $validator = Validator::make( $input_data, $rules);
            if ( $validator->fails() ) { 

                $messages = $validator->messages();

                $params = array(
                            'status' => "ERROR",
                            'validation_error_messages' => $messages                                                  
                        );

                $params['request_type'] = $request_type;
                return redirect_admin('',$params);
            }

            //Image
            if($request->file('logo_image') !== null)
            {   
                //unlink existing images
                if(isset($input_data['uploaded_logo_image']))
                {    
                    if(file_exists(public_path().'/images/logo/'.$input_data['uploaded_logo_image']))
                        unlink(public_path().'/images/logo/'.$input_data['uploaded_logo_image']);                    
                }    

                $original_image     = $request->file('logo_image');
                $upload_image       = Image::make($original_image->getRealPath());
                $image_name         = $input_data['slug'].'.'.strtolower($original_image->getClientOriginalExtension());

                $original_path      = public_path().'/images/logo/';
                if (!is_dir($original_path)) {
                    mkdir($original_path,0777, true);
                }

                $upload_image->save($original_path.$image_name);

                $image = $image_name;
            }
            else
            {
                $image = $input_data['uploaded_logo_image'];
            }            

            //Update Logo Details
            $update_data = array(
                            'title' => $input_data['title'],                            
                            'slug'  => $input_data['slug'],                            
                            'image'=> $image,
                            'status'=> $input_data['status'],
                            'updated_at'=> date('Y-m-d H:i:s')
                        );            
            
            $condition = array('logo_id' => $logo_id);
            Settings_model::updateLogo($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'image_url' => image_url('logo/'.$image),
                            'image' => $image,   
                            'status' => "SUCCESS",
                            'message'=> "Success! Logo updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteLogo($logo_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $logo_id    = safe_b64decode($logo_id);

        //Unlink Images
        $params = array('logo_id' => $logo_id, 'result_type' => 'FIRST');
        $logo = Settings_model::getLogos($params);

        if(file_exists(public_path().'/images/logo/'.$logo->image))
            unlink(public_path().'/images/logo/'.$logo->image);
        if(file_exists(public_path().'/images/logo/thumb/'.$logo->image))
            unlink(public_path().'/images/logo/thumb/'.$logo->image); 

        //Delete record
        $condition  = array('logo_id' => $logo_id);
        
        $result     = Settings_model::deleteLogo($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Logo Deleted.",
                            'remove_row' => true                       
                        );
        }
        else
        {
            $params = array(
                            'status' => "ERROR",
                            'message'=> "Alert! Something went wrong. Please try again.",                          
                        );   
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getManageEmailTemplates()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Email Templates        
        $email_templates = Settings_model::getEmailTemplates();
        
        $this->outputData['email_templates'] = $email_templates;
        $this->outputData['title'] = 'Email Templates';    
        $this->outputData['breadcrumb'] = 'manage_email_templates';
        return view('admin.pages.settings.email_templates.manage_email_templates',$this->outputData);
    }

    public function getAddEmailTemplate()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add Email Template';    
        $this->outputData['breadcrumb'] = 'add_email_template';
        return view('admin.pages.settings.email_templates.form_email_template_add',$this->outputData);
    }

    public function postAddEmailTemplate(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['slug'] = (isset($input_data['slug']))?Str::slug($input_data['slug']):Str::slug($input_data['title']);

        //Check Valiation                
        $validator = Validator::make( $input_data, Settings_model::$rules['add_email_template'] );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
        }

        //Insert Email Template
        $insert_data  = array(
                        'title' => $input_data['title'],
                        'slug'  => $input_data['slug'],
                        'subject'  => $input_data['subject'],
                        'message'  => $input_data['message'],
                        'status'=> $input_data['status'],
                        'created_at' => date('Y-m-d H:i:s')
                    );        
        
        $template_id      = Settings_model::insertEmailTemplate($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Email Template added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('settings/editEmailTemplate/'.safe_b64encode($template_id),$params);
    }

    public function getEditEmailTemplate($template_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $template_id    = safe_b64decode($template_id);        

        $params = array('template_id' => $template_id, 'result_type' => 'FIRST');
        $email_template  = Settings_model::getEmailTemplates($params);

        if(!$email_template)
           return redirect_admin("settings/manageEmailTemplates"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['email_template'] = $email_template;        
        
        $this->outputData['title'] = 'Edit Email Template';    
        $this->outputData['breadcrumb'] = 'edit_email_template';
        $this->outputData['breadcrumb_params'] = $email_template;
        return view('admin.pages.settings.email_templates.form_email_template_edit',$this->outputData);
    }

    public function postEditEmailTemplate(Request $request,$template_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $template_id    = safe_b64decode($template_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['slug'] = (isset($input_data['slug']))?Str::slug($input_data['slug']):Str::slug($input_data['title']);

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Settings_model::$rules['edit_email_template_general'];
            $rules['title'] = 'required|string|unique:email_templates,title,'.$template_id.',template_id';
            $rules['slug'] = 'required|string|unique:email_templates,slug,'.$template_id.',template_id';
            $validator = Validator::make( $input_data, $rules);
            if ( $validator->fails() ) { 

                $messages = $validator->messages();

                $params = array(
                            'status' => "ERROR",
                            'validation_error_messages' => $messages                                                  
                        );


                $params['request_type'] = $request_type;
                return redirect_admin('',$params);
            }            

            //Update Emailt template Details
            $update_data = array(
                            'title' => $input_data['title'],
                            'slug'  => $input_data['slug'],
                            'subject'  => $input_data['subject'],
                            'message'  => $input_data['message'],
                            'status'=> $input_data['status'],
                            'updated_at'=> date('Y-m-d H:i:s')
                        );            
            
            $condition = array('template_id' => $template_id);
            Settings_model::updateEmailTemplate($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Email Template updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteEmailTemplate($template_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $template_id    = safe_b64decode($template_id);

        $condition  = array('template_id' => $template_id);
        
        $result     = Settings_model::getDeleteEmailTemplate($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Email Template Deleted.",
                            'remove_row' => true                       
                        );
        }
        else
        {
            $params = array(
                            'status' => "ERROR",
                            'message'=> "Alert! Something went wrong. Please try again.",                          
                        );   
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    } 

}    