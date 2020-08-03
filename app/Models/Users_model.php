<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Users_model extends Model
{

    public static $rules = array(

        'add_user' => array(
                        'firstname' => 'required',
                        'lastname' => 'required',
                        'password' => 'required',
                        'email' => 'required|email'
                    ),

        'edit_user_general' => array(
                        'firstname'     => 'required|string',
                        'lastname'      => 'required|string',
                        'mobile'        => 'required|numeric',                       
                        'address_one'   => 'required',                       
                        'country'       => 'required',                       
                        'state'         => 'required',                       
                        'city'          => 'required',                       
                        'postal_code'   => 'required',                       
                    ),

        'login' => array(
                'email'      => 'required',
                'password'   => 'required',
            ),
        'update_password' => array(
                'current_password'      => 'required',
                'new_password'   => 'required',
                'confirm_new_password'   => 'required',
            ),

        'forgot_password' => array(
                'email' => 'required',
            ),
        'reset_password' => array(
                'new_password' => 'required',
            ),
        'shipping_address' => array(
                'firstname'     => 'required|string',
                'lastname'      => 'required|string',
                'email'         => 'required|email',
                'mobile'        => 'required|numeric',                       
                'address_line1'   => 'required',                       
                'country'       => 'required',                       
                'state'         => 'required',                       
                'city'          => 'required',                       
                'postalcode'   => 'required',
            ),
    );
	/* Users */
    public static function checkUserExists($condition=array()) 
    {

    	if(count($condition) > 0)
    	{       	 	
       	 	return DB::table('users')->where($condition)->count(); 
        }	
    }

    public static function insertUser($insertData) 
    {
        
        if(count($insertData) > 0)
        {
    		$id = DB::table('users')->insertGetId($insertData);
    		return $id;
        }	
    }

    public static function getUsers($params=array())
    {
    	$query = DB::table('users');
    	
    	//Fields
    	if(!empty($params['fields']))
    	{
    		$fields = $params['fields'];
    	}
    	else
    	{
    		$fields = array('users.*');	
    	}	
    	

    	if(!empty($params['user_id']))
    	{
    		$query->where('users.user_id',$params['user_id']);
    	}

        if(!empty($params['email_first']))
        {
            $query->where('users.email_first',$params['email_first']);
        }

        if(!empty($params['email_second']))
        {
            $query->where('users.email_second',$params['email_second']);
        }

        if(!empty($params['password']))
        {
            $query->where('users.password',$params['password']);
        }

        if(!empty($params['email_verify']))
        {
            $query->where('users.email_verify',$params['email_verify']);
        }

        if(!empty($params['status']))
        {
            $query->where('users.status',$params['status']);
        }

         if(!empty($params['otp_code']))
        {
            $query->where('users.otp_code',$params['otp_code']);
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
    		$query->orderBy('users.user_id', 'DESC');    		
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
            else if($params['result_type'] = 'RESULT')
                $result = $query->select($fields)->get();    
            else
                $result = $query->select($fields);
        }
        else
        {
           $result = $query->select($fields)->get();    
        }
    	return $result;
    }

    public static function updateUser($condition,$updateData)
    {
    	if(count($condition) > 0 && count($updateData) > 0)
    	{
    		DB::table('users')->where($condition)->update($updateData);
    	}	
    }

    public static function deleteUser($condition)
    {
    	if(count($condition) > 0)
    	{
    		DB::table('users')->where($condition)->delete();			
    	}	
    }

    public static function insertShippingAddress($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('shipping_address')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getShippingAddress($params=array())
    {
        $query = DB::table('shipping_address');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('shipping_address.*'); 
        }   
        

        if(!empty($params['user_id']))
        {
            $query->where('shipping_address.user_id',$params['user_id']);
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
            $query->orderBy('shipping_address.user_id', 'DESC');           
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
            else if($params['result_type'] = 'RESULT')
                $result = $query->select($fields)->get();    
            else
                $result = $query->select($fields);
        }
        else
        {
           $result = $query->select($fields)->get();    
        }
        return $result;
    }

    public static function updateShippingAddress($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            DB::table('shipping_address')->where($condition)->update($updateData);
        }   
    }

    public static function deleteShippingAddress($condition)
    {
        if(count($condition) > 0)
        {
            DB::table('shipping_address')->where($condition)->delete();            
        }   
    }
}
