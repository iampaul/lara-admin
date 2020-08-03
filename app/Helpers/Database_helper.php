<?php

if ( ! function_exists('getGeneralSettingValue'))
{
    function getGeneralSettingValue($setting_code) 
    {		
		if (!$setting_code) {return false;}
		
		$params  = array('code' => $setting_code, 'result_type' => 'FIRST');
		$setting = App\Models\Settings_model::getGeneralSettings($params);

		$value = (isset($setting->value))?$setting->value:'';

		return $value;		
	}   
}

if ( ! function_exists('getLogo'))
{
    function getLogo($slug) 
    {		
		if (!$slug) {return false;}
		
		$params  = array('slug' => $slug, 'result_type' => 'FIRST');
		$logo = App\Models\Settings_model::getLogos($params);

		$value = (isset($logo->image))?image_url('logo/'.$logo->image):image_url('no_image/item-no-image.jpg');

		return $value;		
	}   
}

if ( ! function_exists('isAjaxRequest'))
{
    function isAjaxRequest() 
    {		
		$request_type = getGeneralSettingValue('admin_form_request_type');

		return ($request_type=="AJAX")?TRUE:FALSE;
	}   
}

if ( ! function_exists('get_meta_info'))
{
	function getMetaInfo($page_name = '',$field='')
	{
		return $page_name;	
	}
}

if ( ! function_exists('getUserMail'))
{
    function getUserMail($user_id) 
    {
    	if (!$user_id) {return false;}
		
		$params  = array('user_id' => $user_id, 'result_type' => 'FIRST');
		$result = App\Models\Users_model::getUsers($params);

		$value = insep_decode($result->email_first).insep_decode($result->email_second);

		return $value;
    }  
}

?>