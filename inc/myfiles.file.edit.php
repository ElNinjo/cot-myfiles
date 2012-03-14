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

// return of an update = json !!!
if (!$usr['auth_write']) { 
	echo '{"status":"error","message":"'.$L['myfiles_err_nowrite'].'"}';
	exit;
}

// folder id is on the url
$fileid = cot_import('fileid','G','TXT');
if ($fileid=="") {
	echo '{"status":"error", "message":"'.$L['myfiles_err_nofile'].' [File]"}';
	exit;
}

//=================================
// userid comes from GET always !!!
// if its empty assume the current user
//=================================
$userid = cot_import('userid','G','TXT');
if ($userid=='') {
	// hmm this could even be userid = 0  (so guests)
	$userid=$usr['id'];
}
if ($usr['id']!=$userid & !$usr['isadmin']) {
	// other then your own userid and not an admin
	echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [User]"}';
	exit;
}

if ($usr['isadmin']) {
	// An admin can get the fileid without having to know a userid
	$curfile_sql= $db->query("SELECT * FROM $db_pfs WHERE pfs_id='$fileid'");
} else {
	// Existing file, first check if userid and fileid matches.. 
	$curfile_sql= $db->query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_id='$fileid'");
}

if ($curfile_sql->rowCount()==0) { 
	echo '{"status":"error","message":"'.$L['myfiles_err_nofile'].'"}';
	return;
}
$curfile= $curfile_sql->fetch();
$userid=$curfile['pfs_userid'];

$rfrname = cot_import('frname','P','TXT');
// check posted data  (name)
if (empty($rfrname)) { 
	echo '{"status":"error","message":"'.$L['myfiles_nofilename'].'"}';
	return;
}

$rfdesc 			= cot_import('fdescr','P','TXT');
$selectedfolderid 	= cot_import('selectedfolderid','P','TXT');

$sql = $db->query("UPDATE $db_pfs SET
	pfs_friendlyname='".$db->prep($rfrname)."',
	pfs_desc='".$db->prep($rfdesc)."',
	pfs_folderid='".$selectedfolderid."'
	WHERE pfs_userid='$userid' AND pfs_id='$fileid'" );

// In ajax mode, only return results as JSON...
echo '{"status":"ok","message":"","fileid":"'.$rfileid.'","folderid":"'.$selectedfolderid.'"}';
return;

?>
