<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

if (!defined('COT_CODE')) { 
	echo '{"status":"error","message":"Wrong URL"}';
	exit;
}

// write permissions ?
if (!$usr['auth_write']) {
	echo '{"status":"error","message":"'.$L['myfiles_err_nowrite'].'"}';
	return;
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
	echo '{"status":"error","message":"'.$L['myfiles_err_noaccess'].'"}';
	return;
}

require $cfg['plugins_dir']."/myfiles/inc/myfiles.inc.php";

$folderid = cot_import('folderid','G','TXT');

if ((int)$folderid<=0) {
	echo '{"status":"error","message":"'.$L['myfiles_err_noaccess'].'"}';
	return;
} else {
	// folderid should be an existing record in the database !
	$frow=myfiles_getFolder($folderid,$theuserid);
	
	if ($frow==NULL) {
		// folder NOT found ???
		echo '{"status":"error","message":"'.$L['myfiles_err_invalidid'].'"}';
		return;	
	}
	
	// check for correct user!
	if (!($frow['pff_userid']==$usr['id'] || $usr['isadmin'])) {
		echo '{"status":"error","message":"'.$L['myfiles_err_noaccess'].'"}';
		return;
	}
}

//====================================
//== check if the folder is empty
//====================================
	$count=myfiles_getSubfoldersCount($theuserid,$frow['pff_path']);
	if ($count>0) {
		echo '{"status":"error","message":"'.$L['myfiles_err_notempty'].'"}';
		return;
	}
	
	$count=myfiles_getFilesCount($folderid);
	if ($count>0) {
		echo '{"status":"error","message":"'.$L['myfiles_err_notempty'].'"}';
		return;
	}
//======================================	

$sql = $db->query("DELETE FROM $db_pfs_folders WHERE pff_id=$folderid");

echo '{"status":"ok","message":"","info":{"deletedfolderid":"'.$folderid.'"}}';
return;

?>
