<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tools_model extends Model
{

    public static $rules = array(

        'add_tool' => array(
                        'tool_category' => 'required|string',
                        'tool_name' => 'required|string|unique:tools',
                        'product_name' => 'required|string|unique:tools',
                        'tool_code' => 'required|string|unique:tools',
                        'default_price' => 'required|numeric',
                        'shipping_price' => 'numeric',
                        'designed_for' => 'required',
                        'status' => 'required'
                    ),
        'edit_tool_general' => array(                        
                        'tool_name' => 'required|string|unique:tools',
                        'product_name' => 'required|string|unique:tools',                        
                        'default_price' => 'required|numeric',
                        'shipping_price' => 'numeric',                  
                        'status' => 'required'
                    ),

        'add_fabric_category' => array(
                        'tool_id'   => 'required|numeric',
                        'category_name' => 'required|string|unique:tools_fabric_categories',
                        'price' => 'required|numeric',                        
                        'status' => 'required'
                    ),
        'edit_fabric_category_general' => array(                        
                        'tool_id'   => 'required|numeric',
                        'category_name' => 'required|string|unique:tools_fabric_categories',
                        'price' => 'required|numeric',                        
                        'status' => 'required'
                    ),

        'add_fabric' => array(
                        'tool_id'   => 'required|numeric',
                        'category_id'   => 'required|numeric',
                        'fabric_name' => 'required|string|unique:tools_fabrics',
                        'status' => 'required'
                    ),
        'edit_fabric_general' => array(                        
                        'tool_id'   => 'required|numeric',
                        'category_id'   => 'required|numeric',
                        'fabric_name' => 'required|string|unique:tools_fabrics',
                        'status' => 'required'
                    ),

        'add_option' => array(
                        'accessory_id'   => 'required|numeric',
                        'option_name'    => 'required|string|unique:accessory_options',
                        'option_reference' => 'required|string|unique:accessory_options',
                        'status' => 'required'
                    ),
        'edit_option_general' => array(                        
                        'accessory_id'   => 'required|numeric',
                        'option_name'    => 'required|string|unique:accessory_options',
                        'option_reference' => 'required|string|unique:accessory_options',
                        'status' => 'required'
                    ),
    );

    public static $messages = array();

    /* Tools */
    public static function checkToolExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('tools')->where($condition)->count(); 
        }   
    }

    public static function insertTool($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('tools')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getTools($params=array())
    {
        
        $query = DB::table('tools');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('tools.*'); 
        }   

        if(!empty($params['tool_id']))
        {
            $query->where('tools.tool_id',$params['tool_id']);
        }

        if(!empty($params['tool_code']))
        {
            $query->where('tools.tool_code',$params['tool_code']);
        }

        if(!empty($params['status']))
        {
            $query->where('tools.status',$params['status']);
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
            $query->orderBy('tools.tool_id', 'ASC');            
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

    public static function updateTool($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('tools')->where($condition)->update($updateData);
        }   
    }

    public static function deleteTool($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('tools')->where($condition)->delete();         
        }   
    }



    /* Fabric Categories */
    public static function checkFabricCategoryExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('tools_fabric_categories')->where($condition)->count(); 
        }   
    }

    public static function insertFabricCategory($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('tools_fabric_categories')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getFabricCategories($params=array())
    {
        
        $query = DB::table('tools_fabric_categories');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('tools_fabric_categories.*','tools.tool_name','tools.tool_code'); 
        }   

        if(!empty($params['tool_id']))
        {
            $query->where('tools_fabric_categories.tool_id',$params['tool_id']);
        }

        if(!empty($params['category_id']))
        {
            $query->where('tools_fabric_categories.category_id',$params['category_id']);
        }

        if(!empty($params['status']))
        {
            $query->where('tools.status',$params['status']);
        }

        $query->join("tools", function ($join) {
            $join->on("tools.tool_id", "=", "tools_fabric_categories.tool_id");        
        });

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
            $query->orderBy('tools_fabric_categories.tool_id', 'ASC');
            $query->orderBy('tools_fabric_categories.category_id', 'ASC');            
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

    public static function updateFabricCategory($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('tools_fabric_categories')->where($condition)->update($updateData);
        }   
    }

    public static function deleteFabricCategory($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('tools_fabric_categories')->where($condition)->delete();         
        }   
    }

     /* Fabrics */
    public static function checkFabricExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('tools_fabrics')->where($condition)->count(); 
        }   
    }

    public static function insertFabric($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('tools_fabrics')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getFabrics($params=array())
    {
        
        $query = DB::table('tools_fabrics');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('tools_fabrics.*'); 
        }   

        if(!empty($params['tool_id']))
        {
            $query->where('tools_fabrics.tool_id',$params['tool_id']);
        }

        if(!empty($params['fabric_id']))
        {
            $query->where('tools_fabrics.fabric_id',$params['fabric_id']);
        }

        if(!empty($params['status']))
        {
            $query->where('tools_fabrics.status',$params['status']);
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
            $query->orderBy('tools_fabrics.tool_id', 'ASC');
            $query->orderBy('tools_fabrics.fabric_id', 'ASC');            
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

    public static function updateFabric($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('tools_fabrics')->where($condition)->update($updateData);
        }   
    }

    public static function deleteFabric($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('tools_fabrics')->where($condition)->delete();         
        }   
    }

    /* Accessory Groups */
    public static function checkAccessoryGroupExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('accessory_groups')->where($condition)->count(); 
        }   
    }

    public static function insertAccessoryGroup($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('accessory_groups')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getAccessoryGroups($params=array())
    {
        
        $query = DB::table('accessory_groups');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array(
                            'accessory_groups.*'
                        ); 
        }   

        if(!empty($params['group_id']))
        {
            $query->where('accessory_groups.group_id',$params['group_id']);
        }

        if(!empty($params['category']))
        {
            $query->where('accessory_groups.category',$params['category']);
        }

        if(!empty($params['group_slug']))
        {
            $query->where('accessory_groups.group_slug',$params['group_slug']);
        }

        if(!empty($params['designed_for']))
        {
            $query->where('accessory_groups.designed_for',$params['designed_for']);
        }

        if(!empty($params['status']))
        {
            $query->where('accessory_groups.status',$params['status']);
        }

        $query->join("accessory_groups", function ($join) {
            $join->on("accessory_groups.group_id", "=", "accessory_groups.group_id");        
        });

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
            $query->orderBy('accessory_groups.group_id', 'ASC');            
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

    public static function updateAccessoryGroup($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('accessory_groups')->where($condition)->update($updateData);
        }   
    }

    public static function deleteAccessoryGroup($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('accessory_groups')->where($condition)->delete();         
        }   
    }


    /* Accessories */
    public static function checkAccessoryExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('accessories')->where($condition)->count(); 
        }   
    }

    public static function insertAccessory($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('accessories')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getAccessories($params=array())
    {
        
        $query = DB::table('accessories');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array(
                            'accessories.*',
                            'accessory_groups.group_name',
                            'accessory_groups.group_slug'
                        ); 
        }   

        if(!empty($params['accessory_id']))
        {
            $query->where('accessories.accessory_id',$params['accessory_id']);
        }

        if(!empty($params['slug']))
        {
            $query->where('accessories.slug',$params['slug']);
        }

        if(!empty($params['category']))
        {
            $query->where('accessory_groups.category',$params['category']);
        }

        if(!empty($params['group_slug']))
        {
            $query->where('accessory_groups.group_slug',$params['group_slug']);
        }

        if(!empty($params['designed_for']))
        {
            $query->where('accessory_groups.designed_for',$params['designed_for']);
        }

        if(!empty($params['status']))
        {
            $query->where('accessories.status',$params['status']);
        }

        $query->join("accessory_groups", function ($join) {
            $join->on("accessory_groups.group_id", "=", "accessories.group_id");        
        });

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
            $query->orderBy('accessories.accessory_id', 'ASC');            
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

    public static function updateAccessory($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('accessories')->where($condition)->update($updateData);
        }   
    }

    public static function deleteAccessory($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('accessories')->where($condition)->delete();         
        }   
    }

    /* Accessory Options */
    public static function checkOptionExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('accessory_options')->where($condition)->count(); 
        }   
    }

    public static function insertOption($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('accessory_options')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getOptions($params=array())
    {
        
        $query = DB::table('accessory_options');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array(
                        'accessory_options.*',
                        'accessories.name',
                        'accessories.slug',
                        'accessory_groups.group_name',
                        'accessory_groups.group_slug'
            ); 
        }

        if(!empty($params['group_id']))
        {
            $query->where('accessory_options.group_id',$params['group_id']);
        }   

        if(!empty($params['accessory_id']))
        {
            $query->where('accessory_options.accessory_id',$params['accessory_id']);
        }

        if(!empty($params['option_id']))
        {
            $query->where('accessory_options.option_id',$params['option_id']);
        }

        if(!empty($params['category']))
        {
            $query->where('accessory_groups.category',$params['category']);
        }

        if(!empty($params['group_slug']))
        {
            $query->where('accessory_groups.group_slug',$params['group_slug']);
        }

        if(!empty($params['designed_for']))
        {
            $query->where('accessory_groups.designed_for',$params['designed_for']);
        }

        if(!empty($params['status']))
        {
            $query->where('accessory_options.status',$params['status']);
        }

        $query->join("accessories", function ($join) {
            $join->on("accessories.accessory_id", "=", "accessory_options.accessory_id");        
        });

        $query->join("accessory_groups", function ($join) {
            $join->on("accessory_groups.group_id", "=", "accessories.group_id");        
        });

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
            $query->orderBy('accessory_options.position', 'ASC');            
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

    public static function updateOption($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('accessory_options')->where($condition)->update($updateData);
        }   
    }

    public static function deleteOption($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('accessory_options')->where($condition)->delete();         
        }   
    }
}
