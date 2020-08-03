<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Admins_model;
use App\Models\Tools_model;
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

class Tools extends Controller
{
    public function __construct()
    {
        $this->outputData = array();
    }

    public function getManageTools()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Tools        
        $tools = Tools_model::getTools();
        
        $this->outputData['tools'] = $tools;
        $this->outputData['title'] = 'Tools';    
        $this->outputData['breadcrumb'] = 'manage_tools';
        return view('admin.pages.tools.manage_tools',$this->outputData);
    }

    public function getAddTool()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add Too';    
        $this->outputData['breadcrumb'] = 'add_tool';
        return view('admin.pages.tools.form_tool_add',$this->outputData);
    }

    public function postAddTool(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        //Check Valiation                
        $validator = Validator::make( $input_data, Tools_model::$rules['add_tool'] );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
        }

        //Insert Tool
        $insert_data  = array(
                'tool_category' => $input_data['tool_category'],
                'tool_name' => $input_data['tool_name'],
                'product_name' => $input_data['product_name'],
                'tool_code' => $input_data['tool_code'],
                'default_price'=> $input_data['default_price'],
                'shipping_price'=> $input_data['shipping_price'],
                'designed_for'=> $input_data['designed_for'],                
                'status'=> $input_data['status']                
        );        
        
        $tool_id    = Tools_model::insertTool($insert_data);

        //Image
        if($request->file('tool_image') !== null)
        {    
            $original_image     = $request->file('tool_image');
            $upload_image       = Image::make($original_image->getRealPath());
            $image_name         = time().$original_image->getClientOriginalName();

            $original_path      = public_path().'/images/tools/'.$tool_id.'/';
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


        //Update Banner Image
        $update_data = array(                                                    
                        'image' => $image,                        
        );            
            
        $condition = array('tool_id' => $tool_id);
        Tools_model::updateTool($condition,$update_data);

        //Response
        $params = array(                            
                        'status' => "SUCCEsafe_b64encodeSS",
                        'message'=> "Success! Tool added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('tools/editTool/'.safe_b64encode($tool_id),$params);
    }

    public function getEditTool($tool_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $tool_id    = safe_b64decode($tool_id);        

        $params = array('tool_id' => $tool_id, 'result_type' => 'FIRST');
        $tool   = Tools_model::getTools($params);

        if(!$tool)
           return redirect_admin("tools/manageTools"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['tool'] = $tool;        
        
        $this->outputData['title'] = 'Edit Tool';    
        $this->outputData['breadcrumb'] = 'edit_tool';
        $this->outputData['breadcrumb_params'] = $tool;
        return view('admin.pages.tools.form_tool_edit',$this->outputData);
    }

    public function postEditTool(Request $request,$tool_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $tool_id  = safe_b64decode($tool_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Tools_model::$rules['edit_tool_general'];
            $rules['tool_name'] = 'required|string|unique:tools,tool_name,'.$tool_id.',tool_id';
            $rules['product_name'] = 'required|string|unique:tools,product_name,'.$tool_id.',tool_id';
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
            if($request->file('tool_image') !== null)
            {   
                //unlink existing images
                if(isset($input_data['uploaded_tool_image']))
                {    
                    if(file_exists(public_path().'/images/tools/'.$tool_id.'/'.$input_data['uploaded_tool_image']))
                        unlink(public_path().'/images/tools/'.$tool_id.'/'.$input_data['uploaded_tool_image']);                    
                }    

                $original_image     = $request->file('tool_image');
                $upload_image       = Image::make($original_image->getRealPath());
                $image_name         = time().$original_image->getClientOriginalName();

                $original_path      = public_path().'/images/tools/'.$tool_id.'/';
                if (!is_dir($original_path)) {
                    mkdir($original_path,0777, true);
                }

                $upload_image->save($original_path.$image_name);

                $image = $image_name;
            }
            else
            {
                $image = $input_data['uploaded_tool_image'];
            }            

            //Update Banner Details
            $update_data = array(                            
                            'tool_name' => $input_data['tool_name'],
                            'image' => $image,
                            'product_name' => $input_data['product_name'],
                            'default_price'=> $input_data['default_price'],
                            'shipping_price'=> $input_data['shipping_price'],
                            'designed_for'=> $input_data['designed_for'],                
                            'status'=> $input_data['status'],
                            'updated_at' => date('Y-m-d H:i:s')
                        );            
            
            $condition = array('tool_id' => $tool_id);
            Tools_model::updateTool($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'image_url' => image_url('tools/'.$tool_id.'/'.$image),
                            'image' => $image,   
                            'status' => "SUCCESS",
                            'message'=> "Success! Tool updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteTool($tool_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $tool_id    = safe_b64decode($tool_id);

        //Unlink Images
        $params = array('tool_id' => $tool_id, 'result_type' => 'FIRST');
        $tool = Tools_model::getTools($params);

        if(file_exists(public_path().'/images/tools/'.$tool_id.'/'.$tool->image))
            unlink(public_path().'/images/tools/'.$tool_id.'/'.$tool->image);

        //Delete record
        $condition  = array('tool_id' => $tool_id);
        
        $result     = Tools_model::deleteTool($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Tool Deleted.",
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


    public function getManageFabricCategories()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Fabric Categories        
        $fabric_categories = Tools_model::getFabricCategories();
        
        $this->outputData['fabric_categories'] = $fabric_categories;
        $this->outputData['title'] = 'Fabric Categories';    
        $this->outputData['breadcrumb'] = 'manage_tool_fabric_categories';
        return view('admin.pages.tools.fabric_categories.manage_fabric_categories',$this->outputData);
    }

    public function getAddFabricCategory()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Tools        
        $tools = Tools_model::getTools();
        $this->outputData['tools'] = $tools;
        
        $this->outputData['title'] = 'Add Fabric Category';    
        $this->outputData['breadcrumb'] = 'add_tool_fabric_category';
        return view('admin.pages.tools.fabric_categories.form_fabric_category_add',$this->outputData);
    }

    public function postAddFabricCategory(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        //Check Valiation                
        $validator = Validator::make( $input_data, Tools_model::$rules['add_fabric_category'] );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
        }

        //Insert Fabric Category
        $insert_data  = array(
                'tool_id'       => $input_data['tool_id'],
                'category_name' => $input_data['category_name'],
                'price' => $input_data['price'],              
                'status'=> $input_data['status']                
        );        
        
        $category_id    = Tools_model::insertFabricCategory($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Fabric Category added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('tools/editFabricCategory/'.safe_b64encode($category_id),$params);
    }

    public function getEditFabricCategory($category_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $category_id    = safe_b64decode($category_id);        

        $params = array('category_id' => $category_id, 'result_type' => 'FIRST');
        $fabric_category   = Tools_model::getFabricCategories($params);

        if(!$fabric_category)
           return redirect_admin("tools/manageFabricCategories"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }

        //Get Tools        
        $tools = Tools_model::getTools();
        $this->outputData['tools'] = $tools;        

        $this->outputData['fabric_category'] = $fabric_category;        
        
        $this->outputData['title'] = 'Edit Fabric Category';    
        $this->outputData['breadcrumb'] = 'edit_tool_fabric_category';
        $this->outputData['breadcrumb_params'] = $fabric_category;
        return view('admin.pages.tools.fabric_categories.form_fabric_category_edit',$this->outputData);
    }

    public function postEditFabricCategory(Request $request,$category_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $category_id  = safe_b64decode($category_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Tools_model::$rules['edit_fabric_category_general'];
            $rules['category_name'] = 'required|string|unique:tools_fabric_categories,category_name,'.$category_id.',category_id';
            
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

            //Update Tool Details
            $update_data = array(                            
                            'tool_id'       => $input_data['tool_id'],
                            'category_name' => $input_data['category_name'],
                            'price' => $input_data['price'],              
                            'status'=> $input_data['status'], 
                            'updated_at' => date('Y-m-d H:i:s')
                        );            
            
            $condition = array('category_id' => $category_id);
            Tools_model::updateFabricCategory($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Fabric Category updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteFabricCategory($category_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $category_id    = safe_b64decode($category_id);

        //Delete record
        $condition  = array('category_id' => $category_id);
        
        $result     = Tools_model::getDeleteFabricCategory($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Fabric Category Deleted.",
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

    public function postAjaxGetFabricCategories(Request $request) 
    {
        $input_data = $request->all();

        $tool_id = $input_data['tool_id'];

        if(!$request->has('tool_id'))
        {
            $data['status']  = "ERROR";
            $data['message'] = "Invalide Tool ID";
            echo json_encode($data);
            exit;
        }   

        $params = array('tool_id'=>$tool_id);
        $fabric_categories = Tools_model::getFabricCategories($params); 

        $data['status'] = 'SUCCESS';
        $data['fabric_categories'] = $fabric_categories;

        echo json_encode($data);
        exit;
    }

    public function getManageFabrics()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Fabrics
        $fabrics = Tools_model::getFabrics();
        
        $this->outputData['fabrics'] = $fabrics;
        $this->outputData['title'] = 'Fabrics';    
        $this->outputData['breadcrumb'] = 'manage_tool_fabrics';
        return view('admin.pages.tools.fabrics.manage_fabrics',$this->outputData);
    }

    public function getAddFabric()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Tools        
        $tools = Tools_model::getTools();
        $this->outputData['tools'] = $tools;
        
        $this->outputData['title'] = 'Add Fabric';    
        $this->outputData['breadcrumb'] = 'add_tool_fabric';
        return view('admin.pages.tools.fabrics.form_fabric_add',$this->outputData);
    }

    public function postAddFabric(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['fabric_code'] = (isset($input_data['fabric_code']))?Str::slug($input_data['fabric_code']):Str::slug($input_data['fabric_name']);        

        //Check Valiation                
        $validator = Validator::make( $input_data, Tools_model::$rules['add_fabric'] );
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
        if($request->file('fabric_image') !== null)
        {    
            $original_image     = $request->file('fabric_image');
            $upload_image       = Image::make($original_image->getRealPath());
            $image_name         = $input_data['fabric_code'].'.'.strtolower($original_image->getClientOriginalExtension());

            $original_path      = public_path().'/images/tools/fabrics/';
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

        //Insert Tool
        $insert_data  = array(
                'tool_id'       => $input_data['tool_id'],
                'category_id'   => $input_data['category_id'],
                'fabric_name'   => $input_data['fabric_name'],
                'fabric_code'   => $input_data['fabric_code'], 
                'image'         => $image,             
                'status'        => $input_data['status']                    
        );        
        
        $fabric_id    = Tools_model::insertFabric($insert_data);
 

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Tool added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('tools/editFabric/'.safe_b64encode($fabric_id),$params);
    }

    public function getEditFabric($fabric_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $fabric_id    = safe_b64decode($fabric_id);        

        $params = array('fabric_id' => $fabric_id, 'result_type' => 'FIRST');
        $fabric   = Tools_model::getFabrics($params);

        if(!$fabric)
           return redirect_admin("tools/manageFabrics"); 

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }

        //Get Tools        
        $tools = Tools_model::getTools();
        $this->outputData['tools'] = $tools;        

        $this->outputData['fabric'] = $fabric;        
        
        $this->outputData['title'] = 'Edit Fabric';    
        $this->outputData['breadcrumb'] = 'edit_tool_fabric';
        $this->outputData['breadcrumb_params'] = $fabric;
        return view('admin.pages.tools.fabrics.form_fabric_edit',$this->outputData);
    }

    public function postEditFabric(Request $request,$fabric_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $fabric_id  = safe_b64decode($fabric_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['fabric_code'] = (isset($input_data['fabric_code']))?Str::slug($input_data['fabric_code']):Str::slug($input_data['fabric_name']);         

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Tools_model::$rules['edit_fabric_general'];
            $rules['fabric_name'] = 'required|string|unique:tools_fabrics,fabric_name,'.$fabric_id.',fabric_id'; 
            $rules['fabric_code'] = 'required|string|unique:tools_fabrics,fabric_code,'.$fabric_id.',fabric_id';            
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
            if($request->file('fabric_image') !== null)
            {   
                //unlink existing images
                if(isset($input_data['uploaded_fabric_image']))
                {    
                    if(file_exists(public_path().'/images/tools/fabrics/'.$input_data['uploaded_fabric_image']))
                        unlink(public_path().'/images/tools/fabrics/'.$input_data['uploaded_fabric_image']);                    
                }    

                $original_image     = $request->file('fabric_image');
                $upload_image       = Image::make($original_image->getRealPath());
                $image_name         = $input_data['fabric_code'].'.'.strtolower($original_image->getClientOriginalExtension());

                $original_path      = public_path().'/images/tools/fabrics/';
                if (!is_dir($original_path)) {
                    mkdir($original_path,0777, true);
                }

                $upload_image->save($original_path.$image_name);

                $image = $image_name;
            }
            else
            {
                $image = $input_data['uploaded_fabric_image'];
            }            

            //Update Banner Details
            $update_data = array(                            
                            'tool_id'       => $input_data['tool_id'],
                            'category_id'   => $input_data['category_id'],
                            'fabric_name'   => $input_data['fabric_name'],
                            'fabric_code'   => $input_data['fabric_code'], 
                            'image'         => $image,             
                            'status'        => $input_data['status'],
                            'updated_at' => date('Y-m-d H:i:s')
                        );            
            
            $condition = array('fabric_id' => $fabric_id);
            Tools_model::updateFabric($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'image_url' => image_url('tools/fabrics/'.$image),
                            'image' => $image,   
                            'status' => "SUCCESS",
                            'message'=> "Success! Fabric updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteFabric($fabric_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $fabric_id    = safe_b64decode($fabric_id);

        //Unlink Images
        $params = array('fabric_id' => $fabric_id, 'result_type' => 'FIRST');
        $fabric = Tools_model::getFabrics($params);

        if(file_exists(public_path().'/images/tools/fabrics/'.$fabric->image))
            unlink(public_path().'/images/tools/fabrics/'.$fabric->image);

        //Delete record
        $condition  = array('fabric_id' => $fabric_id);
        
        $result     = Tools_model::deleteFabric($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Fabric Deleted.",
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

    /* Accessory Options */
    public function getManageOptions($accessory_id)
    {
        $admin_id = Session::Get('admin_ID');

        $accessory_id = safe_b64decode($accessory_id);
        
        //Get Accessory
        $params = array('accessory_id' => $accessory_id,'result_type'=>'FIRST');
        $accessory = Tools_model::getAccessories($params);

        if(!$accessory)
            return redirect_admin("tools/manageTools");

        //Get Options
        $params  = array('accessory_id'=> $accessory->accessory_id);
        $options = Tools_model::getOptions($params);
        
        $this->outputData['accessory']  = $accessory;
        $this->outputData['options']    = $options;
        $this->outputData['title'] = 'Manage '.$accessory->name;    
        $this->outputData['breadcrumb'] = 'manage_accessory_options';
        $this->outputData['breadcrumb_params'] = $accessory;
        return view('admin.pages.tools.accessories.manage_options',$this->outputData);
    }

    public function getAddOption($accessory_id)
    {
        $admin_id = Session::Get('admin_ID');

        $accessory_id = safe_b64decode($accessory_id);

        //Get Accessory
        $params = array('accessory_id' => $accessory_id,'result_type'=>'FIRST');
        $accessory = Tools_model::getAccessories($params);

        if(!$accessory)
            return redirect_admin("tools/manageTools");
        
        $this->outputData['accessory']  = $accessory;
        $this->outputData['title'] = 'Add Option';    
        $this->outputData['breadcrumb'] = 'add_accessory_option';
        $this->outputData['breadcrumb_params'] = $accessory;
        return view('admin.pages.tools.accessories.form_option_add',$this->outputData);
    }

    public function postAddOption(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        $input_data['option_reference'] = (isset($input_data['option_reference']))?Str::slug($input_data['option_reference']):Str::slug($input_data['option_name']);

        //Get Accessory
        $params = array('accessory_id' => $input_data['accessory_id'],'result_type'=>'FIRST');
        $accessory = Tools_model::getAccessories($params);

        //Check Valiation                
        $validator = Validator::make( $input_data, Tools_model::$rules['add_option'] );
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
        if($request->file('option_image') !== null)
        {    
            $original_image     = $request->file('option_image');
            $upload_image       = Image::make($original_image->getRealPath());
            $image_name         = $input_data['option_reference'].'.'.strtolower($original_image->getClientOriginalExtension());

            $original_path      = public_path().'/images/tools/accessories/'.$accessory->slug.'/';
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

        //Insert Option
        $insert_data  = array(
                'accessory_id'      => $input_data['accessory_id'],                
                'option_name'       => $input_data['option_name'],
                'option_reference'  => $input_data['option_reference'],
                'short_description' => $input_data['short_description'], 
                'image'             => $image,            
                'status'            => $input_data['status']                
        );        
        
        $option_id = Tools_model::insertOption($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Option added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('tools/editOption/'.safe_b64encode($option_id),$params);
    }

    public function getEditOption($option_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $option_id  = safe_b64decode($option_id);

        if(empty($option_id))
            redirect_admin('tools/manageTools');       

        $params = array('option_id' => $option_id,'result_type' => 'FIRST');
        $option = Tools_model::getOptions($params);

        if(!$option)
           redirect_admin('tools/manageTools');

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }

        $this->outputData['option'] = $option;        
        
        $this->outputData['title'] = 'Edit '.$option->option_reference;    
        $this->outputData['breadcrumb'] = 'edit_accessory_option';
        $this->outputData['breadcrumb_params'] = $option;
        return view('admin.pages.tools.accessories.form_option_edit',$this->outputData);
    }

    public function postEditOption(Request $request,$option_id)
    {   

        $admin_id = Session::Get('admin_ID');
        
        $option_id       = safe_b64decode($option_id);

        if(empty($option_id))
            redirect_admin('tools/manageTools');

        $params = array('option_id' => $option_id,'result_type' => 'FIRST');
        $option = Tools_model::getOptions($params);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();        

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Tools_model::$rules['edit_option_general'];
            $rules['option_name'] = 'required|string|unique:accessory_options,option_name,'.$option_id.',option_id';
            $rules['option_reference'] = 'required|string|unique:accessory_options,option_reference,'.$option_id.',option_id';
            
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
            if($request->file('option_image') !== null)
            {
                //unlink existing images
                if(isset($input_data['uploaded_option_image']))
                {    
                    if(file_exists(public_path().'/images/tools/accessories/'.$option->slug.'/'.$input_data['uploaded_option_image']))
                    unlink(public_path().'/images/tools/accessories/'.$option->slug.'/'.$input_data['uploaded_option_image']);                    
                } 

                $original_image     = $request->file('option_image');
                $upload_image       = Image::make($original_image->getRealPath());
                $image_name         = $input_data['option_reference'].'.'.strtolower($original_image->getClientOriginalExtension());

                $original_path      = public_path().'/images/tools/accessories/'.$option->slug.'/';
                if (!is_dir($original_path)) {
                    mkdir($original_path,0777, true);
                }

                $upload_image->save($original_path.$image_name);            

                $image = $image_name;
            }
            else
            {
                $image = $input_data['uploaded_option_image'];
            }

            //Update Tool Details
            $update_data = array(                            
                            'accessory_id'      => $input_data['accessory_id'],                
                            'option_name'       => $input_data['option_name'],
                            'option_reference'  => $input_data['option_reference'],
                            'short_description' => $input_data['short_description'], 
                            'image'             => $image,            
                            'status'            => $input_data['status'],
                            'updated_at' => date('Y-m-d H:i:s')
                        );            
            
            $condition = array('option_id' => $option_id);
            Tools_model::updateOption($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'image_url' => image_url('tools/accessories/'.$option->slug.'/'.$image),
                            'image' => $image,
                            'status' => "SUCCESS",
                            'message'=> "Success! Option updated"
                        );
        }

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteOption($option_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $option_id    = safe_b64decode($option_id);

        //Unlink Images
        $params = array('option_id' => $option_id, 'result_type' => 'FIRST');
        $option = Tools_model::getOptions($params);

        if(file_exists(public_path().'/images/tools/accessories/'.$option->slug.'/'.$option->image))
            unlink(public_path().'/images/tools/accessories/'.$option->slug.'/'.$option->image);

        //Delete record
        $condition  = array('option_id' => $option_id);
        
        $result     = Tools_model::DeleteOption($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Option Deleted.",
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