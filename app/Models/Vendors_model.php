<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Vendors_model extends Model
{

    public static $rules = array(

        'add_vendor' => array(
                        'company_name' => 'required|string',
                        'firstname' => 'required|string',
                        'lastname' => 'required|string',
                        'password' => 'required',
                        'email' => 'required|email'
                    ),

        'edit_vendor_general' => array(
                        'company_name' => 'required|string',
                        'firstname' => 'required|string',
                        'lastname' => 'required|string'                       
                    ),
    );
	/* Vendors */
    public static function checkVendorExists($condition=array()) 
    {

    	if(count($condition) > 0)
    	{       	 	
       	 	return DB::table('vendors')->where($condition)->count(); 
        }	
    }

    public static function insertVendor($insertData) 
    {
        
        if(count($insertData) > 0)
        {
    		$id = DB::table('vendors')->insertGetId($insertData);
    		return $id;
        }	
    }

    public static function getVendors($params=array())
    {
    	
    	$query = DB::table('vendors');
    	
    	//Fields
    	if(!empty($params['fields']))
    	{
    		$fields = $params['fields'];
    	}
    	else
    	{
    		$fields = array('vendors.*');	
    	}	

    	if(!empty($params['vendor_id']))
    	{
    		$query->where('vendors.vendor_id',$params['vendor_id']);
    	}

        if(!empty($params['email_first']))
        {
            $query->where('vendors.email_first',$params['email_first']);
        }

        if(!empty($params['email_second']))
        {
            $query->where('vendors.email_second',$params['email_second']);
        }

        if(!empty($params['password']))
        {
            $query->where('vendors.password',$params['password']);
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
    		$query->orderBy('vendors.vendor_id', 'DESC');    		
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

    public static function updateVendor($condition,$updateData)
    {
    	if(count($condition) > 0 && count($updateData) > 0)
    	{
    		DB::table('vendors')->where($condition)->update($updateData);
    	}	
    }

    public static function deleteVendor($condition)
    {
    	if(count($condition) > 0)
    	{
    		DB::table('vendors')->where($condition)->delete();			
    	}	
    }
}
