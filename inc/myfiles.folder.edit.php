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

if (!$usr['auth_write']) { 
	// updates only return JSON
	echo '{"status":"error","message":"'.$L['myfiles_err_nowrite'].'"}';
	return;
}

// folder id is on the url
$folderid = cot_import('folderid','G','TXT');
$userid   = cot_import('userid','G','TXT');
$alert="";

// check for correct user!
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

if ($folderid=="-1") {
	// "/" is root folder
	$path = "/";
} else {
	// folderid is anexisting record in the database !
	$curfolder=myfiles_getFolder($folderid,$theuserid);
	if ($curfolder==NULL) {
		// parent folder NOT found ???
		echo '{"status":"error","message":"'.$L['myfiles_err_invalidid'].'"}';
		return;	
	}
}

$rtitle = cot_import('rtitle','P','TXT');
// check posted data  (path and title)
if (empty($rtitle)) { 
	echo '{"status":"error","message":"'.$L['myfiles_err_folder_noname'].'"}';
	return;
}

$selectedfolderid = cot_import('selectedfolderid','P','TXT');
$rdesc = cot_import('rdesc','P','TXT');
$rispublic = cot_import('rispublic','P','BOL');
$risgallery = cot_import('risgallery','P','BOL');

// pff_path needs to be build from an id...  (empty means root folder)
// path of the selected folder
$selectedpath = myfiles_getFolderPath($selectedfolderid);
$myparent=myfiles_getParentFolder_Path($curfolder['pff_path']);

// move folder to hisself or his 'own' parent is not valid
if ($selectedfolderid!=$folderid && $selectedfolderid!=$myparent) {
	// there is an error if you want to move a parent into a child folder
	// you can never move a parent into a child of itself !!!!!
	$oldpath = myfiles_getFolderPath($folderid);
	$check = substr($selectedpath, 0, strlen($oldpath));
	if ($check==$oldpath) {
		echo '{"status":"error","message":"'.$L['myfiles_err_parentinchild'].'"}';
		return;
	}		

	// warning...  parent folder has CHANGED !!!!
	// the paths off all subfolders must be changed to the new path $selectedpath is the new parent
	// old path = $oldpath										/4/5/6/7/
	// new path = $selectedpath.$folderid.$myFiles['cfg_pathsep']		/8/9/7/
	// replace all subfolders with /4/5/6/7/...... to /8/9/7/
	
	// get all subfolders /4/5/6/7/%/
	$searchpath=$oldpath."%/";
	$sql_subfolders = $db->query("SELECT pff_id,pff_path FROM ".$db_pfs_folders." WHERE pff_userid='$theuserid' AND pff_path LIKE '".$searchpath."'");
	if ($sql_subfolders->rowCount()>0) {
		// there are subfolder, so change them to their new paths
		while($srow = $sql_subfolders->fetch()) {
			$newpath=$selectedpath.$folderid.$myFiles['cfg_pathsep'];
			$replacementpath = str_replace($oldpath, $newpath, $srow['pff_path']);				
			$sql = $db->query("UPDATE $db_pfs_folders SET
				pff_path='".$replacementpath."'	WHERE pff_userid='$theuserid' AND pff_id='".$srow['pff_id']."'" );
		}
		$alert=',"alert":"Subfolders present"';
	}
}

if ($selectedfolderid==$folderid || $selectedfolderid==$myparent) {
	// same selected folder or same parent, no change
	$selectedpath=$curfolder['pff_path'];
} else {
	// put folder under the new selected folder
	if ($selectedpath=="") {
		$selectedpath=$myFiles['cfg_pathsep'];
	}
	$selectedpath=$selectedpath.$folderid.$myFiles['cfg_pathsep'];
}

$sql = $db->query("UPDATE $db_pfs_folders SET
	pff_title='".$db->prep($rtitle)."',
	pff_path='".$selectedpath."',
	pff_updated='".$sys['now']."',
	pff_desc='".$db->prep($rdesc)."',
	pff_ispublic='$rispublic',
	pff_isgallery='$risgallery'
	WHERE pff_userid='$theuserid' AND pff_id='$folderid' " );

$folderarr=myfiles_getFolder($folderid,$theuserid);
// to support php 5.2.17 we cannot use force object
//$changedfolder=json_encode($folderarr,JSON_FORCE_OBJECT);
$changedfolder=json_encode($folderarr);
	
// In ajax mode, only return results...
echo '{"status":"ok","message":"","folderid":"'.$folderid.'"'.$alert.',"folderrecord":'.$changedfolder.'}';
return;

?>
