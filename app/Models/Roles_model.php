<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Roles_model extends Model
{

    public static $rules = array(
        'add_role' => array(
                        'title' => 'required|string|unique:roles',
                        'status' => 'required'
                    ),
        'edit_role' => array(
                        'title' => 'required|string|unique:roles',
                        'status' => 'required'
                    ),
        'edit_role_general' => array(
                        'title' => 'required|string',
                        'status' => 'required'
                    ),
    );

    public static $messages = array();

	/* Role */
    public static function checkRoleExists($condition=array()) 
    {

    	if(count($condition) > 0)
    	{       	 	
       	 	return DB::table('roles')->where($condition)->count(); 
        }	
    }

    public static function insertRole($insertData) 
    {
        
        if(count($insertData) > 0)
        {
    		$id = DB::table('roles')->insertGetId($insertData);
    		return $id;
        }	
    }

    public static function getRoles($params=array())
    {
    	
    	$query = DB::table('roles');
    	
    	//Fields
    	if(!empty($params['fields']))
    	{
    		$fields = $params['fields'];
    	}
    	else
    	{
    		$fields = array('roles.*');	
    	}	

    	if(!empty($params['role_id']))
    	{
    		$query->where('roles.role_id',$params['role_id']);
    	}

        if(!empty($params['status']))
        {
            $query->where('roles.status',$params['status']);
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
    		$query->orderBy('roles.role_id', 'ASC');    		
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

    public static function updateRole($condition,$updateData)
    {
    	if(count($condition) > 0 && count($updateData) > 0)
    	{
    		return DB::table('roles')->where($condition)->update($updateData);
    	}	
    }

    public static function deleteRole($condition)
    {
    	if(count($condition) > 0)
    	{
    		return DB::table('roles')->where($condition)->delete();			
    	}	
    }

    /* Role Permission*/
    public static function checkRolePermissionExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('role_permissions')->where($condition)->count(); 
        }   
    }

    public static function insertRolePermission($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('role_permissions')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getRolePermissions($params=array())
    {
        
        $query = DB::table('role_permissions');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('roles.*'); 
        }   

        if(!empty($params['permission_id']))
        {
            $query->where('role_permissions.permission_id',$params['permission_id']);
        }

        if(!empty($params['role_id']))
        {
            $query->where('role_permissions.role_id',$params['role_id']);
        }

        if(!empty($params['status']))
        {
            $query->where('role_permissions.status',$params['status']);
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
            $query->orderBy('role_permissions.permission_id', 'ASC');            
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

    public static function updateRolePermission($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            DB::table('role_permissions')->where($condition)->update($updateData);
        }   
    }

    public static function deleteRolePermission($condition)
    {
        if(count($condition) > 0)
        {
            DB::table('role_permissions')->where($condition)->delete();            
        }   
    }

    /* Permissions */
    public static function checkPermissionExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('permissions')->where($condition)->count(); 
        }   
    }

    public static function insertPermission($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('permissions')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getPermissions($params=array())
    {
        
        $query = DB::table('permissions');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('permissions.*'); 
        }   

        if(!empty($params['permission_id']))
        {
            $query->where('permissions.permission_id',$params['permission_id']);
        }

        if(!empty($params['status']))
        {
            $query->where('permissions.status',$params['status']);
        }

        if(!empty($params['code']))
        {
            $query->where('permissions.code',$params['code']);
        }

        if(!empty($params['is_with_role_permissions']) && !empty($params['role_id']))
        {
            $fields[] = DB::raw('IFNULL( '. DB::getTablePrefix() .'role_permissions.is_view,"N") as is_view');
            $fields[] = DB::raw('IFNULL( '. DB::getTablePrefix() .'role_permissions.is_update,"N") as is_update');
            $fields[] = DB::raw('IFNULL( '. DB::getTablePrefix() .'role_permissions.is_delete,"N") as is_delete');

            $query->leftJoin('role_permissions', function($join) use ($params){ 
                $join->on('role_permissions.permission_id', '=', 'permissions.permission_id')->where('role_permissions.role_id', '=', $params['role_id']);
            });

            if(!isset($params['is_settings']))
            {    
                $query->innerJoin('roles', function($join){ 
                    $join->on('roles.role_id', '=', 'role_permissions.role_id')->where('roles.status', '=', 'ACTIVE');
                });                    
            }    
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
            $query->orderBy('permissions.permission_id', 'ASC');            
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

    public static function updatePermission($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('permissions')->where($condition)->update($updateData);
        }   
    }

    public static function deletePermission($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('permissions')->where($condition)->delete();            
        }   
    }
}
