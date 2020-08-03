<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Users_model;
use App\Models\Regions_model;
use Session;
use View;
use Redirect;
use Validator;
use Response;
use DB;

class Users extends Controller
{
    public function __construct()
    {
        $this->outputData = array();

        Validator::extend('uniqueEmail', function ($attribute, $value, $parameters, $validator) {
                if(!isset($parameters[0])) { $parameters[0] = 0; }                
                $count = DB::table('users')->where('user_id', '<>', $parameters[0])
                                            ->where('email_first', safe_b64encode(firstEmail($value)))
                                            ->where('email_second', safe_b64encode(secondEmail($value)))
                                            ->count();
                return $count === 0;
        },'Email already exists');
    }

    public function getManageUsers()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Users                
        $users = Users_model::getUsers();
                
        $this->outputData['users'] = $users;
        $this->outputData['title'] = 'Users';    
        $this->outputData['breadcrumb'] = 'manage_users';
        return view('admin.pages.users.manage_users',$this->outputData);
    }

    public function getAddUser()
    {
        $admin_id = Session::Get('admin_ID');
        
        //Countries
        $countries = Regions_model::getCountries();
        $this->outputData['countries'] = $countries;

        $this->outputData['title'] = 'Add User';    
        $this->outputData['breadcrumb'] = 'add_user';
        return view('admin.pages.users.form_user_add',$this->outputData);
    }

    public function postAddUser(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        //Check Valiation
        $rules = Users_model::$rules['add_user'];
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

        //Insert User 
        $insert_data  = array(
                        'referred_by'   => "ADMIN",
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
        
        $user_id      = Users_model::insertUser($insert_data);

        //Response
        $params = array(
                        'status' => "SUCCESS",
                        'message'=> "Success! User added"
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('users/editUser/'.safe_b64encode($user_id),$params);
    }

    public function getEditUser($user_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $user_id   = safe_b64decode($user_id);

        //Get User
        $params = array('user_id' => $user_id,'result_type' => 'FIRST');
        $user = Users_model::getUsers($params);        

        if(!$user)
           return redirect_admin('users/manageUsers'); 


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

        $this->outputData['user'] = $user;
        $this->outputData['user_email'] = safe_b64decode($user->email_first).safe_b64decode($user->email_second);
        $this->outputData['title'] = 'Edit User';    
        $this->outputData['breadcrumb'] = 'edit_user';
        $this->outputData['breadcrumb_params'] = $user;
        return view('admin.pages.users.form_user_edit',$this->outputData);
    }

    public function postEditUser(Request $request,$user_id)
    {   
        $admin_id = Session::Get('admin_ID');

        $user_id    = safe_b64decode($user_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Users_model::$rules['edit_user_general'];
            $rules['email'] = ['required','email','uniqueEmail:'.$user_id];
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

            //Update User Details
            $update_data = array(
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
            
            $condition = array('user_id' => $user_id);
            Users_model::updateUser($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! User Details updated"                        
                        );
        } 

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteUser($user_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $user_id = safe_b64decode($user_id);

        $condition  = array(['user_id',$user_id]);
        
        $result     = Users_model::deleteUser($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! User Deleted.",
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