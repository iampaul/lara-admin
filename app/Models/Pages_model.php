<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Pages_model extends Model
{

    public static $rules = array(
        'add_page' => array(
                        'title' => 'required|string|unique:pages',
                        'slug' => 'required|string|unique:pages',
                        'status' => 'required'
                    ),
        'edit_page_general' => array(
                        'title' => 'required|string|unique:pages',
                        'slug' => 'required|string|unique:pages',
                        'status' => 'required'
                    ),

        'add_html_block' => array(
                        'title' => 'required|string|unique:html_blocks',
                        'status' => 'required'
                    ),
        'edit_html_block_general' => array(
                        'title' => 'required|string|unique:html_blocks',
                        'status' => 'required'
                    ),

        'add_faq' => array(
                        'title' => 'required|string|unique:faq',
                        'status' => 'required'
                    ),
        'edit_faq_general' => array(
                        'title' => 'required|string|unique:faq',
                        'status' => 'required'
                    ),

        'add_banner' => array(
                        'title' => 'required|string|unique:banners',
                        'price' => 'numeric',
                        'status' => 'required'
                    ),
        'edit_banner_general' => array(
                        'title' => 'required|string|unique:banners',
                        'price' => 'numeric',
                        'status' => 'required'
                    ),
        'reply_contact_request' => array(
                        'reply_message' => 'required|string',                        
                    ),
        'contact_request' => array(
                        'name' => 'required',                        
                        'email' => 'required|email',                        
                        'mobile' => 'required|numeric',                        
                        'subject' => 'required|string',                        
                        'message' => 'required|string',                        
                    ) 

    );

    public static $messages = array();

	/* Page */
    public static function checkPageExists($condition=array()) 
    {

    	if(count($condition) > 0)
    	{       	 	
       	 	return DB::table('pages')->where($condition)->count(); 
        }	
    }

    public static function insertPage($insertData) 
    {
        
        if(count($insertData) > 0)
        {
    		$id = DB::table('pages')->insertGetId($insertData);
    		return $id;
        }	
    }

    public static function getPages($params=array())
    {
    	
    	$query = DB::table('pages');
    	
    	//Fields
    	if(!empty($params['fields']))
    	{
    		$fields = $params['fields'];
    	}
    	else
    	{
    		$fields = array('pages.*');	
    	}	

    	if(!empty($params['page_id']))
    	{
    		$query->where('pages.page_id',$params['page_id']);
    	}

        if(!empty($params['slug']))
        {
            $query->where('pages.slug',$params['slug']);
        }

        if(!empty($params['status']))
        {
            $query->where('pages.status',$params['status']);
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
    		$query->orderBy('pages.page_id', 'ASC');    		
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

    public static function updatePage($condition,$updateData)
    {
    	if(count($condition) > 0 && count($updateData) > 0)
    	{
    		return DB::table('pages')->where($condition)->update($updateData);
    	}	
    }

    public static function deletePage($condition)
    {
    	if(count($condition) > 0)
    	{
    		return DB::table('pages')->where($condition)->delete();			
    	}	
    }


    /* Html Blocks */
    public static function checkHtmlBlockExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('html_blocks')->where($condition)->count(); 
        }   
    }

    public static function insertHtmlBlock($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('html_blocks')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getHtmlBlocks($params=array())
    {
        
        $query = DB::table('html_blocks');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('html_blocks.*'); 
        }   

        if(!empty($params['block_id']))
        {
            $query->where('html_blocks.block_id',$params['block_id']);
        }

        if(!empty($params['status']))
        {
            $query->where('html_blocks.status',$params['status']);
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
            $query->orderBy('html_blocks.block_id', 'ASC');            
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

    public static function updateHtmlBlock($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('html_blocks')->where($condition)->update($updateData);
        }   
    }

    public static function deleteHtmlBlock($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('html_blocks')->where($condition)->delete();         
        }   
    }


    /* FAQ */
    public static function checkFAQExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('faq')->where($condition)->count(); 
        }   
    }

    public static function insertFAQ($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('faq')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getFAQ($params=array())
    {
        
        $query = DB::table('faq');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('faq.*'); 
        }   

        if(!empty($params['faq_id']))
        {
            $query->where('faq.faq_id',$params['faq_id']);
        }

        if(!empty($params['status']))
        {
            $query->where('faq.status',$params['status']);
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
            $query->orderBy('faq.faq_id', 'ASC');            
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

    public static function updateFAQ($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('faq')->where($condition)->update($updateData);
        }   
    }

    public static function deleteFAQ($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('faq')->where($condition)->delete();         
        }   
    }

    /* Banners */
    public static function checkBannerExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('banners')->where($condition)->count(); 
        }   
    }

    public static function insertBanner($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('banners')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getBanners($params=array())
    {
        
        $query = DB::table('banners');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('banners.*'); 
        }   

        if(!empty($params['banner_id']))
        {
            $query->where('banners.banner_id',$params['banner_id']);
        }

        if(!empty($params['status']))
        {
            $query->where('banners.status',$params['status']);
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
            $query->orderBy('banners.banner_id', 'ASC');            
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

    public static function updateBanner($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('banners')->where($condition)->update($updateData);
        }   
    }

    public static function deleteBanner($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('banners')->where($condition)->delete();         
        }   
    }


    /* Contact Requests */
    public static function checkContactRequestExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('contact_requests')->where($condition)->count(); 
        }   
    }

    public static function insertContactRequest($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('contact_requests')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getContactRequests($params=array())
    {
        
        $query = DB::table('contact_requests');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('contact_requests.*'); 
        }   

        if(!empty($params['id']))
        {
            $query->where('contact_requests.id',$params['id']);
        }

        if(!empty($params['status']))
        {
            $query->where('contact_requests.status',$params['status']);
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
            $query->orderBy('contact_requests.id', 'ASC');            
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

    public static function updateContactRequest($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('contact_requests')->where($condition)->update($updateData);
        }   
    }

    public static function deleteContactRequest($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('contact_requests')->where($condition)->delete();         
        }   
    }


    public static function checkContactReplies($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('contact_replies')->where($condition)->count(); 
        }   
    }

    public static function insertContactReply($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('contact_replies')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getContactReplies($params=array())
    {
        
        $query = DB::table('contact_replies');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('contact_replies.*'); 
        }   

        if(!empty($params['reply_id']))
        {
            $query->where('contact_replies.reply_id',$params['reply_id']);
        }

        if(!empty($params['contact_id']))
        {
            $query->where('contact_replies.contact_id',$params['contact_id']);
        }

        if(!empty($params['status']))
        {
            $query->where('contact_replies.status',$params['status']);
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
            $query->orderBy('contact_replies.reply_id', 'ASC');            
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

    public static function updateContactReply($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('contact_replies')->where($condition)->update($updateData);
        }   
    }

    public static function deleteContactReply($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('contact_replies')->where($condition)->delete();         
        }   
    }
}
