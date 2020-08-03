<?php

if ( ! function_exists('isLogin'))
{
    function isLogin()
    {       
       return (!empty(Session::Get('user_ID')))?TRUE:FALSE;
    }   
}

?>