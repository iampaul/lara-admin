<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Settings_model extends Model
{

    public static $rules = array(        
        'edit_general_setting' => array(
                        'value' => 'required'                        
                    ),

        'site_contact_info' => array(
                        'email'     => 'required|email',
                        'address'   => 'required'                        
        ),

        'add_social_media' => array(
                        'title' => 'required|string|unique:social_medias',
                        'status' => 'required'
                    ),
        'edit_social_media_general' => array(
                        'title' => 'required|string|unique:social_medias',
                        'status' => 'required'
                    ),
        'add_logo' => array(
                        'title' => 'required|string|unique:site_logos',                        
                        'status' => 'required'
                    ),
        'edit_logo_general' => array(
                        'title' => 'required|string|unique:site_logos',                                               
                        'status' => 'required'
                    ),

        'add_email_template' => array(
                        'title' => 'required|string|unique:email_templates',
                        'slug' => 'required|string|unique:email_templates',
                        'subject' => 'required|string',
                        'message' => 'required|string',
                        'status' => 'required'
                    ),
        'edit_email_template_general' => array(
                        'title' => 'required|string|unique:email_templates',
                        'slug' => 'required|string|unique:email_templates',
                        'subject' => 'required|string',
                        'message' => 'required|string',
                        'status' => 'required'
                    ),                         
    );

    public static $messages = array(
    );

	/* General Settings */
    public static function checkGeneralSettingExists($condition=array()) 
    {

    	if(count($condition) > 0)
    	{       	 	
       	 	return DB::table('general_settings')->where($condition)->count(); 
        }	
    }

    public static function insertGeneralSetting($insertData) 
    {
        
        if(count($insertData) > 0)
        {
    		$id = DB::table('general_settings')->insertGetId($insertData);
    		return $id;
        }	
    }

    public static function getGeneralSettings($params=array())
    {
    	
    	$query = DB::table('general_settings');
    	
    	//Fields
    	if(!empty($params['fields']))
    	{
    		$fields = $params['fields'];
    	}
    	else
    	{
    		$fields = array('general_settings.*');	
    	}	

    	if(!empty($params['setting_id']))
    	{
    		$query->where('general_settings.setting_id',$params['setting_id']);
    	}

        if(!empty($params['code']))
        {
            $query->where('general_settings.code',trim($params['code']));
        }

        if(!empty($params['is_visible']))
        {
            $query->where('general_settings.is_visible',trim($params['is_visible']));
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
    		$query->orderBy('general_settings.setting_id', 'ASC');    		
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

    public static function updateGeneralSetting($condition,$updateData)
    {
    	if(count($condition) > 0 && count($updateData) > 0)
    	{
    		DB::table('general_settings')->where($condition)->update($updateData);
    	}	
    }

    public static function deleteGeneralSetting($condition)
    {
    	if(count($condition) > 0)
    	{
    		DB::table('general_settings')->where($condition)->delete();			
    	}	
    }


    /* Site Contact Info */
    public static function getSiteContactInfo($params=array())
    {
        
        $query = DB::table('site_contact_info');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('site_contact_info.*');  
        }   

        if(!empty($params['id']))
        {
            $query->where('site_contact_info.id',$params['id']);
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
            $query->orderBy('site_contact_info.id', 'ASC');          
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

    public static function updateSiteContactInfo($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            DB::table('site_contact_info')->where($condition)->update($updateData);
        }   
    }

    /* Social Medias */
    public static function checkSocialMediaExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('social_medias')->where($condition)->count(); 
        }   
    }

    public static function insertSocialMedia($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('social_medias')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getSocialMedias($params=array())
    {
        
        $query = DB::table('social_medias');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('social_medias.*'); 
        }   

        if(!empty($params['id']))
        {
            $query->where('social_medias.id',$params['id']);
        }

        if(!empty($params['status']))
        {
            $query->where('social_medias.status',$params['status']);
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
            $query->orderBy('social_medias.id', 'ASC');            
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

    public static function updateSocialMedia($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('social_medias')->where($condition)->update($updateData);
        }   
    }

    public static function deleteSocialMedia($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('social_medias')->where($condition)->delete();         
        }   
    }

    /* Logos */
    public static function checkLogoExists($condition=array()) 
    {
        if(count($condition) > 0)
        {               
            return DB::table('site_logos')->where($condition)->count(); 
        }   
    }

    public static function insertLogo($insertData) 
    {
        if(count($insertData) > 0)
        {
            $id = DB::table('site_logos')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getLogos($params=array())
    {
        
        $query = DB::table('site_logos');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('site_logos.*'); 
        }   

        if(!empty($params['logo_id']))
        {
            $query->where('site_logos.logo_id',$params['logo_id']);
        }

        if(!empty($params['slug']))
        {
            $query->where('site_logos.slug',$params['slug']);
        }

        if(!empty($params['status']))
        {
            $query->where('site_logos.status',$params['status']);
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
            $query->orderBy('site_logos.logo_id', 'ASC');            
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

    public static function updateLogo($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('site_logos')->where($condition)->update($updateData);
        }   
    }

    public static function deleteLogo($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('site_logos')->where($condition)->delete();         
        }   
    }

    /* Email Templates */
    public static function checkEmailTemplateExists($condition=array()) 
    {

        if(count($condition) > 0)
        {               
            return DB::table('email_templates')->where($condition)->count(); 
        }   
    }

    public static function insertEmailTemplate($insertData) 
    {
        
        if(count($insertData) > 0)
        {
            $id = DB::table('email_templates')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getEmailTemplates($params=array())
    {
        
        $query = DB::table('email_templates');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('email_templates.*'); 
        }   

        if(!empty($params['template_id']))
        {
            $query->where('email_templates.template_id',$params['template_id']);
        }

        if(!empty($params['slug']))
        {
            $query->where('email_templates.slug',$params['slug']);
        }

        if(!empty($params['status']))
        {
            $query->where('email_templates.status',$params['status']);
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
            $query->orderBy('email_templates.template_id', 'ASC');            
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

    public static function updateEmailTemplate($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            return DB::table('email_templates')->where($condition)->update($updateData);
        }   
    }

    public static function deleteEmailTemplate($condition)
    {
        if(count($condition) > 0)
        {
            return DB::table('email_templates')->where($condition)->delete();         
        }   
    }
}
