<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Admins_model;
use App\Models\Pages_model;
use App\Models\Settings_model;
use App\Models\Email_model;
use Session;
use View;
use Redirect;
use Validator;
use Response;
use Str;
use Image;
use Mail;

class Pages extends Controller
{
    public function __construct()
    {
        $this->outputData = array();
    }

    public function getManagePages()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Page        
        $pages = Pages_model::getPages();
        
        $this->outputData['pages'] = $pages;
        $this->outputData['title'] = 'Pages';    
        $this->outputData['breadcrumb'] = 'manage_pages';
        return view('admin.pages.cms.manage_pages',$this->outputData);
    }

    public function getAddPage()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add Page';    
        $this->outputData['breadcrumb'] = 'add_page';
        return view('admin.pages.cms.form_page_add',$this->outputData);
    }

    public function postAddPage(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

    	$request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['slug'] = (isset($input_data['slug']))?Str::slug($input_data['slug']):Str::slug($input_data['title']);

        //Check Valiation                
    	$validator = Validator::make( $input_data, Pages_model::$rules['add_page'] );
		if ( $validator->fails() ) { 

			$messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
		}

        //Insert Page
		$insert_data  = array(
                        'title' => $input_data['title'],
                        'slug'  => $input_data['slug'],
                        'description'  => $input_data['description'],
                        'status'=> $input_data['status'],
                        'created_at' => date('Y-m-d H:i:s')
                    );        
		
		$page_id      = Pages_model::insertPage($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Page added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('pages/editPage/'.safe_b64encode($page_id),$params);
    }

    public function getEditPage($page_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $page_id    = safe_b64decode($page_id);        

        $params = array('page_id' => $page_id, 'result_type' => 'FIRST');
        $page   = Pages_model::getPages($params);

        if(!$page)
           return redirect_admin("pages/managePages"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['page'] = $page;        
        
        $this->outputData['title'] = 'Edit Page';    
        $this->outputData['breadcrumb'] = 'edit_page';
        $this->outputData['breadcrumb_params'] = $page;
        return view('admin.pages.cms.form_page_edit',$this->outputData);
    }

    public function postEditPage(Request $request,$page_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $page_id    = safe_b64decode($page_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['slug'] = (isset($input_data['slug']))?Str::slug($input_data['slug']):Str::slug($input_data['title']);

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Pages_model::$rules['edit_page_general'];
            $rules['title'] = 'required|string|unique:pages,title,'.$page_id.',page_id';
            $rules['slug'] = 'required|string|unique:pages,slug,'.$page_id.',page_id';
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
                            'slug'  => $input_data['slug'],
                            'description'  => $input_data['description'],
                            'status'=> $input_data['status'],
                            'updated_at'=> date('Y-m-d H:i:s')
                        );            
            
            $condition = array('page_id' => $page_id);
            Pages_model::updatePage($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Page updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeletePage($page_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $page_id    = safe_b64decode($page_id);

        $condition  = array('page_id' => $page_id);
        
        $result     = Pages_model::deletePage($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Page Deleted.",
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


    public function getManageHtmlBlocks()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Page        
        $blocks = Pages_model::getHtmlBlocks();
        
        $this->outputData['blocks'] = $blocks;
        $this->outputData['title'] = 'HTML Blocks';    
        $this->outputData['breadcrumb'] = 'manage_html_blocks';
        return view('admin.pages.cms.html_blocks.manage_html_blocks',$this->outputData);
    }

    public function getAddHtmlBlock()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add Html Block';    
        $this->outputData['breadcrumb'] = 'add_html_block';
        return view('admin.pages.cms.html_blocks.form_html_block_add',$this->outputData);
    }

    public function postAddHtmlBlock(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['slug'] = (isset($input_data['slug']))?Str::slug($input_data['slug']):Str::slug($input_data['title']);

        //Check Valiation                
        $validator = Validator::make( $input_data, Pages_model::$rules['add_html_block'] );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
        }

        //Insert Page
        $insert_data  = array(
                        'title' => $input_data['title'],
                        'slug'  => $input_data['slug'],
                        'description'  => $input_data['description'],
                        'status'=> $input_data['status'],
                        'created_at' => date('Y-m-d H:i:s')
                    );        
        
        $block_id      = Pages_model::insertHtmlBlock($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Html Block added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('pages/editHtmlBlock/'.safe_b64encode($block_id),$params);
    }

    public function getEditHtmlBlock($block_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $block_id    = safe_b64decode($block_id);        

        $params = array('block_id' => $block_id, 'result_type' => 'FIRST');
        $block   = Pages_model::getHtmlBlocks($params);

        if(!$block)
           return redirect_admin("pages/manageHtmlBlocks"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['block'] = $block;        
        
        $this->outputData['title'] = 'Edit Html Block';    
        $this->outputData['breadcrumb'] = 'edit_html_block';
        $this->outputData['breadcrumb_params'] = $block;
        return view('admin.pages.cms.html_blocks.form_html_block_edit',$this->outputData);
    }

    public function postEditHtmlBlock(Request $request,$block_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $block_id    = safe_b64decode($block_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['slug'] = (isset($input_data['slug']))?Str::slug($input_data['slug']):Str::slug($input_data['title']);

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Pages_model::$rules['edit_html_block_general'];
            $rules['title'] = 'required|string|unique:html_blocks,title,'.$block_id.',block_id';
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
                            'slug'  => $input_data['slug'],
                            'description'  => $input_data['description'],
                            'status'=> $input_data['status'],
                            'updated_at'=> date('Y-m-d H:i:s')
                        );            
            
            $condition = array('block_id' => $block_id);
            Pages_model::updateHtmlBlock($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Html Block updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteHtmlBlock($block_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $block_id    = safe_b64decode($block_id);

        $condition  = array('block_id' => $block_id);
        
        $result     = Pages_model::deleteHtmlBlock($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Page Deleted.",
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


    public function getManageFAQ()
    {
        $admin_id = Session::Get('admin_ID');

        //Get FAQ        
        $all_faq = Pages_model::getFAQ();
        
        $this->outputData['all_faq'] = $all_faq;
        $this->outputData['title'] = 'FAQ';    
        $this->outputData['breadcrumb'] = 'manage_faq';
        return view('admin.pages.cms.faq.manage_faq',$this->outputData);
    }

    public function getAddFAQ()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add FAQ';    
        $this->outputData['breadcrumb'] = 'add_faq';
        return view('admin.pages.cms.faq.form_faq_add',$this->outputData);
    }

    public function postAddFAQ(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        //Check Valiation                
        $validator = Validator::make( $input_data, Pages_model::$rules['add_faq'] );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
        }

        //Insert FAQ
        $insert_data  = array(
                        'title' => $input_data['title'],
                        'description'  => $input_data['description'],
                        'status'=> $input_data['status'],
                        'created_at' => date('Y-m-d H:i:s')
                    );        
        
        $faq_id      = Pages_model::insertFAQ($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Html Block added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('pages/editFAQ/'.safe_b64encode($faq_id),$params);
    }

    public function getEditFAQ($faq_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $faq_id    = safe_b64decode($faq_id);        

        $params = array('faq_id' => $faq_id, 'result_type' => 'FIRST');
        $faq   = Pages_model::getFAQ($params);

        if(!$faq)
           return redirect_admin("pages/manageFAQ"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['faq'] = $faq;        
        
        $this->outputData['title'] = 'Edit FAQ';    
        $this->outputData['breadcrumb'] = 'edit_faq';
        $this->outputData['breadcrumb_params'] = $faq;
        return view('admin.pages.cms.faq.form_faq_edit',$this->outputData);
    }

    public function postEditFAQ(Request $request,$faq_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $faq_id    = safe_b64decode($faq_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Pages_model::$rules['edit_faq_general'];
            $rules['title'] = 'required|string|unique:faq,title,'.$faq_id.',faq_id';
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
                            'description'  => $input_data['description'],
                            'status'=> $input_data['status'],
                            'updated_at'=> date('Y-m-d H:i:s')
                        );            
            
            $condition = array('faq_id' => $faq_id);
            Pages_model::updateFAQ($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Page updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteFAQ($faq_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $faq_id    = safe_b64decode($faq_id);

        $condition  = array('faq_id' => $faq_id);
        
        $result     = Pages_model::deleteFAQ($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! FAQ Deleted.",
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

    public function getManageBanners()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Banners        
        $banners = Pages_model::getBanners();
        
        $this->outputData['banners'] = $banners;
        $this->outputData['title'] = 'Banners';    
        $this->outputData['breadcrumb'] = 'manage_banners';
        return view('admin.pages.cms.banners.manage_banners',$this->outputData);
    }

    public function getAddBanner()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add Banner';    
        $this->outputData['breadcrumb'] = 'add_banner';
        return view('admin.pages.cms.banners.form_banner_add',$this->outputData);
    }

    public function postAddBanner(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        //Check Valiation                
        $validator = Validator::make( $input_data, Pages_model::$rules['add_banner'] );
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
        if($request->file('banner_image') !== null)
        {    
            $original_image     = $request->file('banner_image');
            $upload_image       = Image::make($original_image->getRealPath());
            $image_name         = time().$original_image->getClientOriginalName();

            $original_path      = public_path().'/images/banners/';
            if (!is_dir($original_path)) {
                mkdir($original_path,0777, true);
            }

            $upload_image->save($original_path.$image_name);
            
            //Thumb
            $thumb_path  = public_path().'/images/banners/thumb/';
             if (!is_dir($thumb_path)) {
                mkdir($thumb_path,0777, true);
            }

            //Resize with aspect ratio
            $upload_image->resize(222, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $upload_image->save($thumb_path.$image_name);

            $image = $image_name;
        }
        else
        {
            $image = "";
        }    

        //Insert Banner
        $insert_data  = array(
                        'title' => $input_data['title'],                        
                        'description'  => $input_data['description'],
                        'price'=> $input_data['price'],
                        'image'=> $image, 
                        'status'=> $input_data['status'],
                        'created_at' => date('Y-m-d H:i:s')
                    );        
        
        $banner_id    = Pages_model::insertBanner($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Banner added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('pages/editBanner/'.safe_b64encode($banner_id),$params);
    }

    public function getEditBanner($banner_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $banner_id    = safe_b64decode($banner_id);        

        $params = array('banner_id' => $banner_id, 'result_type' => 'FIRST');
        $banner   = Pages_model::getBanners($params);

        if(!$banner)
           return redirect_admin("pages/manageBanners"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['banner'] = $banner;        
        
        $this->outputData['title'] = 'Edit Banner';    
        $this->outputData['breadcrumb'] = 'edit_banner';
        $this->outputData['breadcrumb_params'] = $banner;
        return view('admin.pages.cms.banners.form_banner_edit',$this->outputData);
    }

    public function postEditBanner(Request $request,$banner_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $banner_id    = safe_b64decode($banner_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Pages_model::$rules['edit_banner_general'];
            $rules['title'] = 'required|string|unique:banners,title,'.$banner_id.',banner_id';
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
            if($request->file('banner_image') !== null)
            {   
                //unlink existing images
                if(isset($input_data['uploaded_banner_image']))
                {    
                    if(file_exists(public_path().'/images/banners/'.$input_data['uploaded_banner_image']))
                        unlink(public_path().'/images/banners/'.$input_data['uploaded_banner_image']);
                    if(file_exists(public_path().'/images/banners/thumb/'.$input_data['uploaded_banner_image']))
                        unlink(public_path().'/images/banners/thumb/'.$input_data['uploaded_banner_image']); 
                }    

                $original_image     = $request->file('banner_image');
                $upload_image       = Image::make($original_image->getRealPath());
                $image_name         = time().$original_image->getClientOriginalName();

                $original_path      = public_path().'/images/banners/';
                if (!is_dir($original_path)) {
                    mkdir($original_path,0777, true);
                }

                $upload_image->save($original_path.$image_name);
                
                //Thumb
                $thumb_path  = public_path().'/images/banners/thumb/';
                 if (!is_dir($thumb_path)) {
                    mkdir($thumb_path,0777, true);
                }

                //Resize with aspect ratio
                $upload_image->resize(222, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $upload_image->save($thumb_path.$image_name);

                $image = $image_name;
            }
            else
            {
                $image = $input_data['uploaded_banner_image'];
            }            

            //Update Banner Details
            $update_data = array(
                            'title' => $input_data['title'],                            
                            'description'  => $input_data['description'],
                            'price'=> $input_data['price'],
                            'image'=> $image,
                            'status'=> $input_data['status'],
                            'updated_at'=> date('Y-m-d H:i:s')
                        );            
            
            $condition = array('banner_id' => $banner_id);
            Pages_model::updateBanner($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'image_url' => image_url('banners/thumb/'.$image),
                            'image' => $image,   
                            'status' => "SUCCESS",
                            'message'=> "Success! Banner updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteBanner($banner_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $banner_id    = safe_b64decode($banner_id);

        //Unlink Images
        $params = array('banner_id' => $banner_id, 'result_type' => 'FIRST');
        $banner = Pages_model::getBanners($params);

        if(file_exists(public_path().'/images/banners/'.$banner->image))
            unlink(public_path().'/images/banners/'.$banner->image);
        if(file_exists(public_path().'/images/banners/thumb/'.$banner->image))
            unlink(public_path().'/images/banners/thumb/'.$banner->image); 

        //Delete record
        $condition  = array('banner_id' => $banner_id);
        
        $result     = Pages_model::deleteBanner($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Banner Deleted.",
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


    public function getManageContactRequests()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Page        
        $contact_requests = Pages_model::getContactRequests();
        
        $this->outputData['contact_requests'] = $contact_requests;
        $this->outputData['title'] = 'Contact Requests';    
        $this->outputData['breadcrumb'] = 'manage_contact_requests';
        return view('admin.pages.cms.contact_requests.manage_contact_requests',$this->outputData);
    }

    public function getReplyContactRequest($id)
    {
        $admin_id   = Session::Get('admin_ID');

        $id         = safe_b64decode($id);        

        $params = array('id' => $id, 'result_type' => 'FIRST');
        $contact_request = Pages_model::getContactRequests($params);

        if(!$contact_request)
           return redirect_admin("pages/manageContactRequests"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['contact_request'] = $contact_request;

        //Replies
        $params = array('contact_id' => $id);
        $reply_messages = Pages_model::getContactReplies($params);
        $this->outputData['reply_messages'] = $reply_messages;        
        
        $this->outputData['title'] = 'Reply Contact Request';    
        $this->outputData['breadcrumb'] = 'reply_contact_request';
        $this->outputData['breadcrumb_params'] = $contact_request;
        return view('admin.pages.cms.contact_requests.form_contact_request_reply',$this->outputData);
    }

    public function postReplyContactRequest(Request $request,$id)
    {   

        $admin_id   = Session::Get('admin_ID');

        $id         = safe_b64decode($id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Pages_model::$rules['reply_contact_request'];
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

            //Get Contact Request info
            $params = array('id' => $id, 'result_type' => 'FIRST');
            $contact_request = Pages_model::getContactRequests($params);

            if(!$contact_request)
               return redirect_admin("pages/manageContactRequests");

            //Send Email
            //Get Email Template
            $params = array('slug' => 'contact-response','result_type' => 'FIRST');
            $email_template = Settings_model::getEmailTemplates($params);

            $placeholder = array(
                '###USERNAME###' => $contact_request->name,
                '###SUBJECT###' => $contact_request->subject,
                '###USERMSG###' => $contact_request->message,
                '###MESSAGE###' => $input_data['reply_message']
            );            

            $params = array('to' => $contact_request->email, 'template' => $email_template,'placeholder' => $placeholder);
            $send   = Email_Model::sendEmail($params);
            
            if (Mail::failures()) 
            {
                $params = array(
                            'status'  => "ERROR",
                            'message' => "Mail not sent! Please try again"                                                  
                        );

                $params['request_type'] = $request_type;
                return redirect_admin('',$params);
            }   

            //Reply Message
            $insert_data = array(
                            'contact_id'    => $id,
                            'reply_message' => $input_data['reply_message']                            
                        );            
            
            
            Pages_model::insertContactReply($insert_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Reply Sent"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteReplyRequest($id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $id    = safe_b64decode($id);

        $condition  = array('id' => $id);
        
        $result     = Pages_model::getDeleteReplyRequest($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Contact Request Deleted.",
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