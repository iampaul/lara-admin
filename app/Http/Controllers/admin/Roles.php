<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Admins_model;
use App\Models\Roles_model;
use Session;
use View;
use Redirect;
use Validator;
use Response;

class Roles extends Controller
{
    public function __construct()
    {
        $this->outputData = array();
    }

    public function getManageRoles()
    {
        $admin_id = Session::Get('admin_ID');

        //Get Admin        
        $roles = Roles_model::getRoles();
        
        $this->outputData['roles'] = $roles;
        $this->outputData['title'] = 'Roles';    
        $this->outputData['breadcrumb'] = 'manage_roles';
        return view('admin.pages.roles.manage_roles',$this->outputData);
    }

    public function getAddRole()
    {
        $admin_id = Session::Get('admin_ID');
        
        $this->outputData['title'] = 'Add Role';    
        $this->outputData['breadcrumb'] = 'add_role';
        return view('admin.pages.roles.form_role_add',$this->outputData);
    }

    public function postAddRole(Request $request)
    {   
        $admin_id = Session::Get('admin_ID');

    	$request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        //Check Valiation
    	$validator = Validator::make( $input_data, Roles_model::$rules['add_role'] );
		if ( $validator->fails() ) { 

			$messages = $validator->messages();

            $params = array(                        
                        'status' => "ERROR",
                        'validation_error_messages' => $messages                        
                    );

            $params['request_type'] = $request_type;
            return redirect_admin('',$params);                
		}

        //Insert Admin Profile
		$insert_data  = array(
                        'title' => $input_data['title'],
                        'status'=> $input_data['status']
                    );
		
		$role_id      = Roles_model::insertRole($insert_data);

        //Response
        $params = array(                            
                        'status' => "SUCCESS",
                        'message'=> "Success! Role added"                        
                    );

        $params['request_type'] = $request_type;
        return redirect_admin('roles/editRole/'.safe_b64encode($role_id),$params);
    }

    public function getEditRole($role_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $role_id    = safe_b64decode($role_id);        

        $params = array('role_id' => $role_id, 'result_type' => 'FIRST');
        $role   = Roles_model::getRoles($params);

        if(!$role)
           return redirect_admin("roles/manageRoles"); 


        $params = array('status' => 'ACTIVE','is_with_role_permissions' => 'Y','role_id' => $role_id,'is_settings' => 'Y');
        $permissions = Roles_model::getPermissions($params);

        if(Session::Get('active_tab'))
        {
            $this->outputData['active_tab'] = Session::Get('active_tab');
            Session::Forget('active_tab');   
        }
        else
        {
            $this->outputData['active_tab'] = 'general';    
        }        

        $this->outputData['role'] = $role;
        $this->outputData['permissions'] = $permissions;
        
        $this->outputData['title'] = 'Edit Role';    
        $this->outputData['breadcrumb'] = 'edit_role';
        $this->outputData['breadcrumb_params'] = $role;
        return view('admin.pages.roles.form_role_edit',$this->outputData);
    }

    public function postEditRole(Request $request,$role_id)
    {   

        $admin_id = Session::Get('admin_ID');

        $role_id    = safe_b64decode($role_id);

        $request_type = ($request->ajax())?"AJAX":"REGULAR";        

        $input_data = $request->all();

        if($request->has('update_general'))
        {    
            //Check Valiation
            $rules = Roles_model::$rules['edit_role_general'];
            $rules['title'] = 'required|string|unique:roles,title,'.$role_id.',role_id';
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

            //Update Role Details
            $update_data = array(
                            'title'     => $input_data['title'],
                            'status'    => $input_data['status'],
                            'updated_at'=> date('Y-m-d H:i:s')
                        );
            
            $condition = array('role_id' => $role_id);
            Roles_model::updateRole($condition,$update_data);
            Session::Put('active_tab','general');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Role updated"
                        );
        }

        if($request->has('update_permissions'))
        {
            $condition = array('role_id' => $role_id);
            Roles_model::deleteRolePermission($condition);

            if(isset($input_data['permissions']))
            {    
                $permissions = $input_data['permissions'];

                foreach($permissions  as $key => $permission)
                {   
                    $insert_data=array(
                        'permission_id' => $key,
                        'role_id'   => $role_id,
                        'is_view'   => (isset($permission['is_view']))?"Y":"N",
                        'is_update' => (isset($permission['is_update']))?"Y":"N",
                        'is_delete' => (isset($permission['is_delete']))?"Y":"N"
                    );

                    Roles_model::insertRolePermission($insert_data);                
                }
            }

            Session::Put('active_tab','permissions');

            //Response
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Success! Role permissions were updated"                                                
                        );
        }    

        $params['request_type'] = $request_type;
        return redirect_admin('',$params);
    }

    public function getDeleteRole($role_id)
    {
        $admin_id   = Session::Get('admin_ID');

        $request_type = (isAjaxRequest())?"AJAX":"REGULAR"; 

        $role_id    = safe_b64decode($role_id);

        $condition  = array('role_id' => $role_id);

        Roles_model::deleteRolePermission($condition);
        $result = Roles_model::deleteRole($condition);

        //Response
        if($result)
        {            
            $params = array(
                            'status' => "SUCCESS",
                            'message'=> "Sucess! Role Deleted.",
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