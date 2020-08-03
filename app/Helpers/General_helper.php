<?php

if ( ! function_exists('insep_encode'))
{
    function insep_encode($value) 
    {		
		if (!$value) {return false;}
		$text = $value;

		/*$cipher = "aes-128-gcm";
		$key 	= "Z8wREq3kcBm7sxA1";
		if (in_array($cipher, openssl_get_cipher_methods()))
		{
		    $ivlen = openssl_cipher_iv_length($cipher);
		    $iv = openssl_random_pseudo_bytes($ivlen).DB::getTablePrefix();
		    $ciphertext = openssl_encrypt($text, $cipher, $key, $options=0, $iv, $tag);
		    return trim(safe_b64encode($ciphertext));
		}*/

		return trim(safe_b64encode($text));		
	}   
}

if ( ! function_exists('insep_decode'))
{
    function insep_decode($value) 
    {		
		if (!$value) {return false;}
		$ciphertext = $value;

		/*$cipher = "aes-128-gcm";
		$key 	= "Z8wREq3kcBm7sxA1";
		if (in_array($cipher, openssl_get_cipher_methods()))
		{
		    $ivlen = openssl_cipher_iv_length($cipher);
		    $iv = openssl_random_pseudo_bytes($ivlen);	    
		    $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
		    return trim($original_plaintext);
		}*/

		return trim(safe_b64decode($ciphertext));
	}  
}

if ( ! function_exists('safe_b64encode'))
{
    function safe_b64encode($string) 
    {
		$data = base64_encode($string);
		$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
		return $data;
	}  
}

if ( ! function_exists('safe_b64decode'))
{
    function safe_b64decode($string) 
    {
		$data = str_replace(array('-', '_'), array('+', '/'), $string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}  
}

if ( ! function_exists('firstEmail'))
{
    function firstEmail($a) 
    {
    	return substr($a, 0, 4);
    }  
}

if ( ! function_exists('secondEmail'))
{
    function secondEmail($a) 
    {
    	return substr($a, 4);
    }  
}

if ( ! function_exists('getFormatDateTime'))
{
    function getFormatDateTime($datetime,$format) 
    {
    	$timestamp 		= strtotime($datetime);
		$formatted_date = date($format, $timestamp);
    	return $formatted_date;
    }  
}


?>