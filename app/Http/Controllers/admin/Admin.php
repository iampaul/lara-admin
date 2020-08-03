<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Admins_model;
use App\Models\Regions_model;
use App\Models\Roles_model;
use App\Models\Tools_model;
use Session;
use View;
use Redirect;
use Validator;
use Response;
use DB;

class Admin extends Controller
{
    public function __construct()
    {
        $this->outputData = array();

        Validator::extend('uniqueEmail', function ($attribute, $value, $parameters, $validator) {
                if(!isset($parameters[0])) { $parameters[0] = 0; }
                $count = DB::table('admins')->where('admin_id', '<>', $parameters[0])
                                            ->where('email_first', safe_b64encode(firstEmail($value)))
                                            ->where('email_second', safe_b64encode(secondEmail($value)))
                                            ->count();
                return $count === 0;
        },'Email already exists');
    }

    public function getDashboard()
    {
    	
        $this->outputData['title'] = 'Dashboard';
        $this->outputData['breadcrumb'] = 'dashboard';	
        return view('admin.pages.dashboard',$this->outputData);
    }

    public function getEditProfile()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Admin
        $params = array('admin_id' => $admin_id,'result_type' => 'FIRST');
        $admin = Admins_model::getAdmins($params);

        //Countries
        $countries = Regions_model::getCountries();
        $this->outputData['countries'] = $countries;

        $this->outputData['admin_email'] = safe_b64decode($admin->email_first).safe_b64decode($admin->email_second);
        $this->outputData['admin'] = $admin;
        $this->outputData['title'] = 'Edit Profile';    
        $this->outputData['breadcrumb'] = 'edit_profile';
        return view('admin.pages.profile.edit_profile',$this->outputData);
    }

    public function postEditProfile(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

    	$request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        //Check Valiation
        $rules = Admins_model::$rules['edit_profile'];
        $rules['email'] = ['required','email','uniqueEmail:'.$admin_id];
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

        //Update Admin Profile
		$update_data = array(
                        'firstname' => $input_data['firstname'],
                        'lastname'  => $input_data['lastname'],
                        'contact_number'=> $input_data['contact_number'],
                        'address'       => $input_data['address'],
                        'city'          => $input_data['city'],
                        'state'         => $input_data['state'],
                        'country'       => $input_data['country'],
                        'postcode'   => $input_data['postcode']
                    );

		$condition = array('admin_id' => $admin_id);
		$check_admin = Admins_model::updateAdmin($condition,$update_data);

        //Response
        $params = array(
                        'status' => "SUCCESS",
                        'message'=> "Profile Updated Successfully"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getChangePassword()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Change Password';    
        $this->outputData['breadcrumb'] = 'change_password';
        return view('admin.pages.profile.change_password',$this->outputData);
    }

    public function postChangePassword(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        //Check Valiation
        $validator = Validator::make( $input_data, Admins_model::$rules['change_password'],Admins_model::$messages );
        if ( $validator->fails() ) { 

            $messages = $validator->messages();

            $params = array(
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);
        }

        //Check Old Password correct or not
        $old_password = safe_b64encode(strip_tags($input_data['old_password']));

        $condition = array('admin_id' => $admin_id, 'password' => $old_password);
        $check_old_password = Admins_model::checkAdminExists($condition);

        if($check_old_password == 0)
        {

            $params = array(
                        'status' => "ERROR",
                        'message' => "Old password is incorrect"                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);
        }    

        //Update Password
        $password = safe_b64encode(strip_tags($input_data['new_password']));

        $update_data = array(
                        'password' => $password,
                    );
        
        $condition = array('admin_ID' => $admin_id);
        $check_admin = Admins_model::updateAdmin($condition,$update_data);

        //Response
        $params = array(
                        'status' => "SUCCESS",
                        'message'=> "Password Updated Successfully"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getManageAdmins()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Admin        
        $params = array('is_not_type' => 'S');
        $admins = Admins_model::getAdmins($params);
                
        $this->outputData['admins'] = $admins;
        $this->outputData['title'] = 'Admins';    
        $this->outputData['breadcrumb'] = 'manage_admins';
        return view('admin.pages.admins.manage_admins',$this->outputData);
    }

    public function getAddAdmin()
    {
        $admin_id = Session::Get('admin_ID');
        
        //Countries
        $countries = Regions_model::getCountries();
        $this->outputData['countries'] = $countries;

        //Roles
        $roles = Roles_model::getRoles();
        $this->outputData['roles'] = $roles;

        $this->outputData['title'] = 'Add Admin';    
        $this->outputData['breadcrumb'] = 'add_admin';
        return view('admin.pages.admins.form_admin_add',$this->outputData);
    }

    public function postAddAdmin(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        //Check Valiation
        $rules = Admins_model::$rules['admin_add'];
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

        //Insert Admin 
        $insert_data  = array(
                        'role_id'       => $input_data['role_id'],
                        'firstname'     => $input_data['firstname'],
                        'lastname'      => $input_data['lastname'],
                        'email_first'   => $email_first,
                        'email_second'  => $email_second, 
                        'password'      => $password,
                        'contact_number'=> $input_data['contact_number'],
                        'address'       => $input_data['address'],
                        'city'          => $input_data['city'],
                        'state'         => $input_data['state'],
                        'country'       => $input_data['country'],
                        'postcode'      => $input_data['postcode'],
                        'status'        => $input_data['status']
                    );
        
        $sub_admin_id      = Admins_model::insertAdmin($insert_data);

        //Response
        $params = array(
                        'status' => "SUCCESS",
                        'message'=> "Success! Admin added"
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('users/editAdmin/'.safe_b64encode($admin_id),$params);
    }

    public function getEditAdmin($sub_admin_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $sub_admin_id   = safe_b64decode($sub_admin_id);

        //Get Admin
        $params = array('admin_id' => $sub_admin_id,'result_type' => 'FIRST');
        $admin = Admins_model::getAdmins($params);        

        if(!$admin)
           return redirect_admin('admins/manageAdmins'); 


        //Countries
        $countries = Regions_model::getCountries();
        $this->outputData['countries'] = $countries;

        //Roles
        $roles = Roles_model::getRoles();
        $this->outputData['roles'] = $roles;

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['admin'] = $admin;
        $this->outputData['admin_email'] = safe_b64decode($admin->email_first).safe_b64decode($admin->email_second);
        $this->outputData['title'] = 'Edit Admin';    
        $this->outputData['breadcrumb'] = 'edit_admin';
        $this->outputData['breadcrumb_params'] = $admin;
        return view('admin.pages.admins.form_admin_edit',$this->outputData);
    }

    public function postEditAdmin(Request $request,$sub_admin_id)
    {   
        $admin_id = Session::Get('admin_ID');

        $sub_admin_id    = safe_b64decode($sub_admin_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Admins_model::$rules['edit_admin_general'];
            $rules['email'] = ['required','email','uniqueEmail:'.$admin_id];
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

            //Update Admin Details
            $update_data = array(
                            'role_id'       => $input_data['role_id'],
                            'firstname'     => $input_data['firstname'],
                            'lastname'      => $input_data['lastname'],
                            'contact_number'=> $input_data['contact_number'],
                            'address'       => $input_data['address'],
                            'city'          => $input_data['city'],
                            'state'         => $input_data['state'],
                            'country'       => $input_data['country'],
                            'postcode'      => $input_data['postcode'],
                            'status'        => $input_data['status'],
                            'updated_at'    => data('Y-m-d H:i:s')
                        );

            if($request->has('password'))
            {
                $password = safe_b64encode(strip_tags($input_data['password']));
                $update_data['password'] = $password;
            }
            
            $condition = array('admin_id' => $sub_admin_id);
            Admins_model::updateAdmin($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Admin Details updated"                        
                        );
        } 

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteAdmin($sub_admin_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $sub_admin_id = safe_b64decode($sub_admin_id);

        $condition  = array(['admin_id',$sub_admin_id],['type','<>','S']);
        
        $result     = Admins_model::deleteAdmin($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Admin Deleted.",
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