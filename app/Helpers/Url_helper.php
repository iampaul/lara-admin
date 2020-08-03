<?php

if ( ! function_exists('redirect_admin'))
{
    function redirect_admin($redirect_url,$params = array(),$response=array()) 
    {	
    	if(isset($params['request_type']) && $params['request_type'] == "AJAX")
		{			
			$response = $params;

			if(!empty($redirect_url))
			{	
				$response['redirect_url'] = admin_url($redirect_url); 

				if(!empty($params['message']))
				{
					if($params['status'] == "SUCCESS")
						Toastr::success($params['message'],'',["timeOut" => 10000]);
					else
						Toastr::error($params['message'],'',["timeOut" => 10000]);
				}
			}	

			//Validation Errors
			if(!empty($params['validation_error_messages']))
			{	
				$errors = array();	
			    foreach ($params['validation_error_messages']->all() as $message)
			    { 
			        $errors[] = $message;
			    }

			    $response['errors'] = $errors;
			} 

			//Any Message Notification
			if(!empty($params['message']))
			{
				$response['message'] = $params['message'];	
			}	

			//Return Response	
			return Response::json($response);	
		}	
		else
		{
			//Validation Error Messages
			if(!empty($params['validation_error_messages']))
			{
				foreach ($params['validation_error_messages']->all() as $message)
		        { 
		            Toastr::error($message,'',["timeOut" => 10000]);
		        }
		    }    

		    //Any Message Notification
	        if(!empty($params['message']))
			{
				if($params['status'] == "SUCCESS")
					Toastr::success($params['message'],'',["timeOut" => 10000]);
				else
					Toastr::error($params['message'],'',["timeOut" => 10000]);
			}


			if(!empty($redirect_url))
				return redirect(ADMIN_URL.'/'.$redirect_url)->withInput();
			else
				return redirect()->back();

			/*$route_params = array();
			if(!empty($params['route_params']))
				$route_params = $params['route_params'];
					
			//Redirect to route
			if(!empty($params['route']))
				return Redirect::route($params['route'],$route_params)->withInput(); 
			else
				return redirect()->back();*/
		}			
	}   
}

if ( ! function_exists('redirect_front'))
{
    function redirect_front($redirect_url,$params = array(),$response=array()) 
    {	
    	if(isset($params['request_type']) && $params['request_type'] == "AJAX")
		{			
			$response = $params;

			if(!empty($redirect_url))
			{	
				$response['redirect_url'] = admin_url($redirect_url); 

				if(!empty($params['message']))
				{
					if($params['status'] == "SUCCESS")
						Toastr::success($params['message'],'',["timeOut" => 10000]);
					else
						Toastr::error($params['message'],'',["timeOut" => 10000]);
				}
			}	

			//Validation Errors
			if(!empty($params['validation_error_messages']))
			{	
				$errors = array();	
			    foreach ($params['validation_error_messages']->all() as $message)
			    { 
			        $errors[] = $message;
			    }

			    $response['errors'] = $errors;
			} 

			//Any Message Notification
			if(!empty($params['message']))
			{
				$response['message'] = $params['message'];	
			}	

			//Return Response	
			return Response::json($response);	
		}	
		else
		{
			//Validation Error Messages
			if(!empty($params['validation_error_messages']))
			{
				foreach ($params['validation_error_messages']->all() as $message)
		        { 
		            Toastr::error($message,'',["timeOut" => 10000]);
		        }
		    }    

		    //Any Message Notification
	        if(!empty($params['message']))
			{
				if($params['status'] == "SUCCESS")
					Toastr::success($params['message'],'',["timeOut" => 10000]);
				else
					Toastr::error($params['message'],'',["timeOut" => 10000]);
			}


			if(!empty($redirect_url))
				return redirect($redirect_url)->withInput();
			else
				return Redirect::back();

			/*$route_params = array();
			if(!empty($params['route_params']))
				$route_params = $params['route_params'];
					
			//Redirect to route
			if(!empty($params['route']))
				return Redirect::route($params['route'],$route_params)->withInput(); 
			else
				return redirect()->back();*/
		}			
	}   
}

if ( ! function_exists('admin_url'))
{
    function admin_url($url='') 
    {
    	$url = ADMIN_URL.'/'.$url ;
		return url($url);
    }   
}


if ( ! function_exists('site_url'))
{
    function site_url($url='') 
    {
		return url($url);
    }   
} 

if ( ! function_exists('image_url'))
{
    function image_url($url='') 
    {
		return url('images/'.$url);
    }   
}    	

?>