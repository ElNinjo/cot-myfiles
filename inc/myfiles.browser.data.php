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

if (!$usr['auth_read']) { 
	echo '{"status":"error","message":"'.$L['myfiles_err_noaccess'].'"}';
	return;
}

$curfolderjson=''; 
$filesjson='';
$myUserid=$usr['id'];

require $cfg['plugins_dir']."/myfiles/inc/myfiles.inc.php";

// folder id is on the url
$folderid = cot_import('folderid','G','TXT');
$userid = cot_import('userid','G','TXT');

if ($folderid=="" || $folderid=="-1") { 
	$folderid="0";			// take root		
}

if ($userid=='') {
	// no url parameter was given, so take my own userid
	$userid=$myUserid;
}	

if ($folderid!='0') {
	// userid will be ignored (since we got a folderid)
	// check admin rights (admins can read all data)
	// existing folder, first check if folderid is valid.. 
	$curfolder_sql= $db->query("SELECT * FROM $db_pfs_folders WHERE pff_id='$folderid'");
	if ($curfolder_sql->rowCount()==0) { 
		//ERROR, folder not found... 		
		echo '{"status":"error","message":"'.$L['myfiles_err_invalidid'].'"}';
		return;
	}

	$curfolder= $curfolder_sql->fetch();

	// found a folder, check rights what to show
	// 1) it is MY folder (OR I am an admin!!!), Show everything
	// 2) its someone else his/her folder then ask if it is a public folder ???
	if ($usr['isadmin'] || $curfolder['pff_userid']==$myUserid) {
		// do nothing, its all OK
	} else {
		if(!$curfolder['pff_ispublic']) {
			echo '{"status":"error","message":"'.$L['myfiles_err_noaccess'].'"}';
			return;
		}
	}
	$filesjson=myfiles_getFolderFilesJSON($userid,$folderid);
} else {
	//===================================================================
	// ROOTFILES are special !!! (rootfiles can be public files or not)
	//===================================================================

	// NO folder, so get the root files
	if ($myUserid==$userid || $usr['isadmin']) {
		// rootfiles of myself or I am an admin
		$filesjson=myfiles_getFolderFilesJSON($userid,$folderid);
	} else {
		// root folder from another user or sitefiles !
		if ($myFiles['cfg_public_root']=="1") {
			$filesjson=myfiles_getFolderFilesJSON($userid,$folderid);
		} else {
			echo '{"status":"error","message":"'.$L['myfiles_err_noaccess'].'"}';
			return;
		}	
	}	
}

//=========================================
//==--  Output part
//=========================================
	$folderjson='{"userid":"'.$userid.'","files":'.$filesjson.'}';	
	echo $folderjson;
	return;
	

?>
