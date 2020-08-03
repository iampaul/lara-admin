<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Vendors_model;
use App\Models\Regions_model;
use Session;
use View;
use Redirect;
use Validator;
use Response;
use DB;

class Vendors extends Controller
{
    public function __construct()
    {
        $this->outputData = array();

        Validator::extend('uniqueEmail', function ($attribute, $value, $parameters, $validator) {
                if(!isset($parameters[0])) { $parameters[0] = 0; }                
                $count = DB::table('vendors')->where('vendor_id', '<>', $parameters[0])
                                            ->where('email_first', safe_b64encode(firstEmail($value)))
                                            ->where('email_second', safe_b64encode(secondEmail($value)))
                                            ->count();
                return $count === 0;
        },'Email already exists');
    }

    public function getManageVendors()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Vendors                
        $vendors = Vendors_model::getVendors();
                
        $this->outputData['vendors'] = $vendors;
        $this->outputData['title'] = 'Vendors';    
        $this->outputData['breadcrumb'] = 'manage_vendors';
        return view('admin.pages.vendors.manage_vendors',$this->outputData);
    }

    public function getAddVendor()
    {
        $admin_id = Session::Get('admin_ID');
        
        //Countries
        $countries = Regions_model::getCountries();
        $this->outputData['countries'] = $countries;

        $this->outputData['title'] = 'Add Vendor';    
        $this->outputData['breadcrumb'] = 'add_vendor';
        return view('admin.pages.vendors.form_vendor_add',$this->outputData);
    }

    public function postAddVendor(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        //Check Valiation
        $rules = Vendors_model::$rules['add_vendor'];
        $rules['email'] = ['required','email','uniqueEmail'];
        $validator = Validator::make( $input_data, $rules );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
        }

        $email = strip_tags($input_data['email']);
        $email_first = safe_b64encode(firstEmail($email));
        $email_second = safe_b64encode(secondEmail($email));
        $password = safe_b64encode(strip_tags($input_data['password']));

        //Insert Vendor 
        $insert_data  = array(
                        'company_name'  => $input_data['company_name'],
                        'firstname'     => $input_data['firstname'],
                        'lastname'      => $input_data['lastname'],
                        'email_first'   => $email_first,
                        'email_second'  => $email_second, 
                        'password'      => $password,
                        'mobile'        => $input_data['mobile'],
                        'address_line1' => $input_data['address_line1'],
                        'address_line2' => $input_data['address_line2'],
                        'city'          => $input_data['city'],
                        'state'         => $input_data['state'],
                        'country'       => $input_data['country'],
                        'postcode'      => $input_data['postcode'],
                        'landmark'      => $input_data['landmark'],
                        'status'        => $input_data['status']                        
                    );
        
        $vendor_id      = Vendors_model::insertVendor($insert_data);

        //Response
        $params = array(
                        'status' => "SUCCESS",
                        'message'=> "Success! Vendor added"
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('vendors/editVendor/'.safe_b64encode($vendor_id),$params);
    }

    public function getEditVendor($vendor_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $vendor_id   = safe_b64decode($vendor_id);

        //Get Vendor
        $params = array('vendor_id' => $vendor_id,'result_type' => 'FIRST');
        $vendor = Vendors_model::getVendors($params);        

        if(!$vendor)
           return redirect_admin('vendors/manageVendors'); 


        //Countries
        $countries = Regions_model::getCountries();
        $this->outputData['countries'] = $countries;

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['vendor'] = $vendor;
        $this->outputData['vendor_email'] = safe_b64decode($vendor->email_first).safe_b64decode($vendor->email_second);
        $this->outputData['title'] = 'Edit Vendor';    
        $this->outputData['breadcrumb'] = 'edit_vendor';
        $this->outputData['breadcrumb_params'] = $vendor;
        return view('admin.pages.vendors.form_vendor_edit',$this->outputData);
    }

    public function postEditVendor(Request $request,$vendor_id)
    {   
        $admin_id = Session::Get('admin_ID');

        $vendor_id    = safe_b64decode($vendor_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Vendors_model::$rules['edit_vendor_general'];
            $rules['email'] = ['required','email','uniqueEmail:'.$vendor_id];
            $validator = Validator::make( $input_data, $rules );
            if ( $validator->fails() ) { 

                $messages = $validator->messages();

                $params = array(
                            'status' => "ERROR",
                            'validation_error_messages' => $messages                        
                        );

                $params['request_type'] = $request_type;
                return redirect_admin('',$params);
            }

            $email = strip_tags($input_data['email']);
            $email_first = safe_b64encode(firstEmail($email));
            $email_second = safe_b64encode(secondEmail($email));            

            //Update Vendor Details
            $update_data = array(
                            'company_name'  => $input_data['company_name'],
                            'firstname'     => $input_data['firstname'],
                            'lastname'      => $input_data['lastname'],
                            'email_first'   => $email_first,
                            'email_second'  => $email_second,                             
                            'mobile'        => $input_data['mobile'],
                            'address_line1' => $input_data['address_line1'],
                            'address_line2' => $input_data['address_line2'],
                            'city'          => $input_data['city'],
                            'state'         => $input_data['state'],
                            'country'       => $input_data['country'],
                            'postcode'      => $input_data['postcode'],
                            'landmark'      => $input_data['landmark'],
                            'status'        => $input_data['status'],
                            'updated_at'    => date('Y-m-d H:i:s') 
                        );

            if($request->has('password'))
            {
                $password = safe_b64encode(strip_tags($input_data['password']));
                $update_data['password'] = $password;
            }
            
            $condition = array('vendor_id' => $vendor_id);
            Vendors_model::updateVendor($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Vendor Details updated"                        
                        );
        } 

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteVendor($vendor_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $vendor_id = safe_b64decode($vendor_id);

        $condition  = array(['vendor_id',$vendor_id]);
        
        $result     = Vendors_model::deleteVendor($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Vendor Deleted.",
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