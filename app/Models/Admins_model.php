<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\Rule;
use Session;
use DB;


class Admins_model extends Model
{    
    public static $rules = array(
        'login' => array(
                        'email' => 'required|email',
                        'password' => 'required'
                    ),
        'edit_profile' => array(
                        'firstname'  => 'required',
                        'lastname'   => 'required',                        
                    ),
        'change_password' => array(
                        'old_password' => 'required',
                        'new_password' => 'required',
                        'confirm_password' => 'required|same:new_password'
                    ),
        'general_settings' => array(
                        'old_password' => 'required',
                        'new_password' => 'required',
                        'confirm_password' => 'required|same:new_password'
                    ),
        'add_admin' => array(
                        'firstname' => 'required',
                        'lastname' => 'required',
                        'password' => 'required',
                        'email' => 'required|email'
                    ),

        'edit_admin_general' => array(
                        'firstname' => 'required',
                        'lastname' => 'required'                       
                    ),
    );

    public static $messages = array(
        'confirm_password.same' => "Password confirmation should match the password"        
    );

/*    public static function uniqueEmail() {  
                                             }*/

	/* Admin */
    public static function checkAdminExists($condition=array()) 
    {

    	if(count($condition) > 0)
    	{       	 	
       	 	return DB::table('admins')->where($condition)->count(); 
        }	
    }

    public static function insertAdmin($insertData) 
    {
        
        if(count($insertData) > 0)
        {
    		$id = DB::table('admins')->insertGetId($insertData);
    		return $id;
        }	
    }

    public static function getAdmins($params=array())
    {
    	
    	$query = DB::table('admins');
    	
    	//Fields
    	if(!empty($params['fields']))
    	{
    		$fields = $params['fields'];
    	}
    	else
    	{
    		$fields = array('admins.*');	
    	}	

    	if(!empty($params['admin_id']))
    	{
    		$query->where('admins.admin_id',$params['admin_id']);
    	}

        if(!empty($params['email_first']))
        {
            $query->where('admins.email_first',$params['email_first']);
        }

        if(!empty($params['email_second']))
        {
            $query->where('admins.email_second',$params['email_second']);
        }

        if(!empty($params['password']))
        {
            $query->where('admins.password',$params['password']);
        }

        if(!empty($params['is_not_type']))
        {
            $query->where('admins.type','<>',$params['is_not_type']);
        }

    	//Order By
    	if(!empty($params['order_by']))
    	{
    		foreach($params['order_by'] as $column => $order)
    		{
    			$query->orderBy($column, $order);		
    		}	    		
    	}	
    	else
    	{
    		$query->orderBy('admins.admin_id', 'DESC');    		
    	}

    	//Limit
    	if(!empty($params['per_page']))
    	{
    		if(!empty($params['offset']))
    		{
    			$offset = $params['offset'];
    		}	
    		else
    		{
    			$offset = 0;
    		}

    		$query->offset($offset);
    		$query->limit($params['per_page']);
    	}	
    	
    	//Result
        if(!empty($params['result_type']))
        {    
           if($params['result_type'] = 'FIRST')
                $result = $query->select($fields)->first(); 
           else if($params['result_type'] = 'COUNT')
                $result = $query->select($fields)->count();
           else 
                $result = $query->select($fields)->get();    
        }
        else
        {
           $result = $query->select($fields)->get();    
        }

    	return $result;
    }

    public static function updateAdmin($condition,$updateData)
    {
    	if(count($condition) > 0 && count($updateData) > 0)
    	{
    		$query = DB::table('admins')->where($condition)->update($updateData);
            return $query;
    	}	
    }

    public static function deleteAdmin($condition)
    {
    	if(count($condition) > 0)
    	{
    		$query = DB::table('admins')->where($condition)->delete();			

            return $query;
    	}	
    }
}
