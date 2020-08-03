<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser dialog - load message
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see readme.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.0                   Date: 07/15/2006
	// ================================================	
	//-------------------------------------------------------------------------
	// include configuration settings
	if (!isset($l) || !$l) {
	/**** START XARAYA MODIFICATION ****/
    // we need to find the directory our server is opperating in
    // hopefully this is complete :)
    if(isset($_SERVER['DOCUMENT_ROOT'])) {
        $root_path = $_SERVER['DOCUMENT_ROOT'];
    } elseif(isset($HTTP_SERVER_VARS['DOCUMENT_ROOT'])) {
        $root_path = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];
    } else {
        $root_path = getenv('DOCUMENT_ROOT');
    }
    // Now for same hacking ;)
    if(isset($_SERVER['PHP_SELF'])) {
        $scriptpath= dirname($_SERVER['PHP_SELF']);
    } elseif(isset($HTTP_SERVER_VARS['PHP_SELF'])) {
        $scriptpath = dirname($HTTP_SERVER_VARS['PHP_SELF']);
    } else {
        $scriptpath= dirname(getenv('PHP_SELF'));
    }
    //ew .. but it should work ;)
    $scriptpath=parse_url($scriptpath);
    $scriptbase=preg_replace("/index\.php.*|\/modules.*|/is",'',$scriptpath['path']);
    $realpath=$root_path.$scriptbase;
    $realpath=str_replace('//','/',$realpath); //get rid of any double slashes

    // include image library config settings
    if (is_file($realpath.'/var/tinymce/tinymceconfig.inc.php')) {
        include_once $realpath.'/var/tinymce/tinymceconfig.inc.php';
   } else {
        // look in the templates directory of this module for the default file
        include_once $realpath.'/modules/tinymce/xartemplates/includes/tinymceconfig.inc.php';
   }
	//-------------------------------------------------------------------------
	// include configuration settings
    //	include dirname(__FILE__) . '/../config/config.inc.php';

  	/**** END XARAYA MODIFICATION ****/
		include dirname(__FILE__) . '/../langs/lang.class.php';	
		// language settings	
		$l = (isset($_REQUEST['lang']) ? new PLUG_Lang($_REQUEST['lang']) : new PLUG_Lang($cfg['lang']));
		$l->setBlock('ibrowser');
	}
?>
<div align="center" id="dialogLoadMessage" style="display: block;">
  <table width="100%" height="90%">
    <tr>		
      <td align="center" valign="middle"><div id="loadMessage"><?php echo $l->m('im_097'); ?></div></td>
    </tr>
  </table>
</div>