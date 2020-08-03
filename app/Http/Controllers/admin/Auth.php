<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Admins_model;
use Session;
use View;
use Redirect;
use Validator;
use Response;

class Auth extends Controller
{
    public function __construct()
    {
        $this->outputData = array();
    }

    public function getLogin()
    {
    	if(Session::Get('admin_ID'))
    		return Redirect::route('get:admin:admin:dashboard');
    			
        return view('admin.auth.login',$this->outputData);
    }

    public function postLogin(Request $request)
    {    
        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();
        
        //Check Valiation
        $validator = Validator::make( $input_data, Admins_model::$rules['login'] );
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
		//$pattern = safe_b64encode(strip_tags($data['pattern_code']));

		//Check Authenticated or not
		$condition = array('email_first' => $email_first,'email_second' => $email_second, 'password' => $password);
		$check_admin = Admins_model::checkAdminExists($condition);

		if($check_admin == 0)
		{
            $params = array(
                        'status' => "ERROR",
                        'message' => "Invalid Email or Password"                        
                    );
            
            $params['request_type'] = $request_type;
            return redirect_admin('',$params);
		}

        //Set Admin Session
		$params = array('email_first' => $email_first,'email_second' => $email_second, 'password' => $password,'result_type' => 'FIRST');
		$admin 	= Admins_model::getAdmins($params);        

		session(['admin_ID' => $admin->admin_id, 'admin_firstname' => $admin->firstname,  'admin_lastname' => $admin->lastname, 'admin_role_id' => $admin->role_id]);	

        //Response
        $params = array(
                        'status' => "SUCCESS",
                        'reload' => true                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('dashboard',$params);
    }

    public function logout()
    {
    	if(Session::Get('admin_ID'))
    	{
    		Session::flush();
    		Toastr::success('Logged Out Successfully','',["timeOut" => 10000]);

    		return redirect_admin('login');
    	}	
    }

}    