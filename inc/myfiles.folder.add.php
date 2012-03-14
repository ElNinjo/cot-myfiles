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

$nfolderid = cot_import('selectedfolderid','P','TXT');

if ($nfolderid=="-1") {
	//  "/" is root folder
	$npath = "/";
} else {
	// folderid is an existing record in the database !
	$frow=myfiles_getFolder($nfolderid,$theuserid);
	
	if ($frow==NULL) {
		// parent folder NOT found ???
		echo '{"status":"error","message":"'.$L['myfiles_err_invalidid'].'"}';
		return;	
	}
	
	// Take the correct user!
	if ($frow['pff_userid']!=$usr['id'] && $usr['isadmin']) {
		// in case of admin, always take the original userid (from the parent folder)
		$theuserid=$frow['pff_userid'];
	}
	//  "/" is root folder
	$npath = $frow['pff_path'];
	
	//======================
	// check for the maximum level (see config.php)
	// $myFiles['cfg_maxfolderdepth'] = "x";
	if (substr_count($npath, '/') > $myFiles['cfg_maxfolderdepth']) {
		echo '{"status":"error","message":"'.$L['myfiles_err_maximumflevel'].'"}';
		return;
	}
}

//=======================================================
// check for the maximum subfolders (see config.php)
//=======================================================
$nrSubs=myfiles_getSubfoldersCount($theuserid,$npath); 
if ((int)$nrSubs >= $myFiles['cfg_maxsubfolders'] ) {
	echo '{"status":"error","message":"'.$L['myfiles_err_maximumsubf'].'"}';
	return;
}

$ntitle = cot_import('ntitle','P','TXT');
$ndesc = cot_import('ndesc','P','TXT');
$nispublic = cot_import('nispublic','P','BOL');
$nisgallery = cot_import('nisgallery','P','BOL');

// check posted data  (path and title)
if (empty($ntitle)) { 
	echo '{"status":"error","message":"'.$L['myfiles_err_folder_noname'].'"}';
	return;
}

$sql = $db->query("INSERT INTO $db_pfs_folders
(	pff_userid,
	pff_title,
	pff_path,
	pff_date,
	pff_updated,
	pff_desc,
	pff_ispublic,
	pff_isgallery,
	pff_count
)
VALUES
(".(int)$theuserid.",
	'".$db->prep($ntitle)."',
	'".$npath."',
	".(int)$sys['now'].",
	".(int)$sys['now'].",
	'".$db->prep($ndesc)."',
	".(int)$nispublic.",
	".(int)$nisgallery.",
	0)");

$newrowid=substr("00000000000".$db->lastInsertId(), -11);
$sql = $db->query("UPDATE $db_pfs_folders SET pff_path='".$npath.$newrowid.$myFiles['cfg_pathsep']."' WHERE pff_id='$newrowid'");

// In ajax mode, only return results...
$newpath=trim($npath.$newrowid.$myFiles['cfg_pathsep']);
echo '{"status":"ok","message":"","parent":'.json_encode($npath).',"newfolderid":"'.$newrowid.'","newpath":'.json_encode($newpath).',"parentid":"'.$nfolderid.'"}';
return;

?>
