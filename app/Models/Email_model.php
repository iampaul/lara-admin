<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Email_model extends Model
{
    public static function sendEmail($params=array())
    {

    	 Mail::send([], [], function($message) use ($params) {

            $template   = $params['template'];
            $placeholder= (!empty($params['placeholder']))?$params['placeholder']:array();

            $placeholder_default = array (
                            '###SITELOGO###'  => image_url('logo/email-logo.png'),
                            '###MAIL###'      => image_url('mailicon.png'),
                            '###SITENAME###'  => getGeneralSettingValue('SITE_TITLE'),
                            '###CONTACTURL###'=> "#",                            
                            '###SITELINK###'  => url('/'),
                            '###PRIVACY###'   => '#',
                            '###TERMS###'     => '#',
                            '###COPYRIGHT###' => getGeneralSettingValue('SITE_COPY_RIGHT_INFO')
                           );          
                                           
            $placeholder = array_merge($placeholder_default,$placeholder);

            if(!empty($params['from']))
                $from = $params['from'];
            else
                $from = getGeneralSettingValue('SMTP_EMAIL');

            $to = $params['to'];
            $subject     = strtr($template->subject,$placeholder);
            $content     = strtr($template->message,$placeholder);                

            $message->from($from); 
            $message->to($to);
            $message->subject($subject);
            $message->setBody($content, 'text/html');
        });
    }
}