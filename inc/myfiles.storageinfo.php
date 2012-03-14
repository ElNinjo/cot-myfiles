
<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

//=========================================
//==-- Screen section return HTML (complete screen)
//=========================================
if (!$usr['auth_read']) { 
	//ERROR, 	
	die($L['myfiles_err_noread']);
}

// check for correct user!
$userid = cot_import('userid','G','TXT');
$theuserid = "";
if ($userid=="") {
	$theuserid=$usr['id'];
} else {
	$theuserid=$userid;
}
if ($theuserid!=$usr['id'] && !$usr['isadmin']) {
	die($L['myfiles_err_noaccess']);
}

require $cfg['plugins_dir']."/myfiles/inc/myfiles.inc.php";

echo myfiles_getInfoBlock_html($theuserid,'myfiles.storageinfo.ajax.tpl');

?>
