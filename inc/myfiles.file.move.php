
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

	// userid is on the url
	$userid = cot_import('userid','G','TXT');
	if ($userid=='') {
		// hmm this could even be userid = 0  (so guests)
		if ($usr['id']=='0') {
			// guests do not have personal files !!!!!!  
			echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [User]"}';
			exit;
		}
	} else {
		if ($usr['id']!=$userid && !$usr['isadmin']) {
			// other then your own userid and not an admin
			echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [User]"}';
			exit;
		}
	}	

	
	$destfolder = cot_import('destid','G','TXT');
	if ($destfolder=='') {
		echo '{"status":"error", "message":"'.$L['myfiles_err_invalidid'].' [Folder]"}';
		exit;
	}
	if ($destfolder=='0' || $destfolder=='-1') {
		// ROOT
		$destfolder='0';
		if ($userid=='') {
			$userid=$usr['id'];
		}
	} else {	
		if ((int)$destfolder<0) {
			echo '{"status":"error", "message":"'.$L['myfiles_err_invalidid'].' [Folder]"}';
			exit;
		}

		//== check for a valid folderid (does it exist)
		if ($userid=='') {
			// check if it is a valid folder !!!!
			$filter = "pff_id='$destfolder'";
		} else {
			// check if it is a valid folder !!!!
			$filter = "pff_id='$destfolder' AND pff_userid='$userid'";
		}
				
		// search the specified folder to get the details
		$sql_fldr = $db->query("SELECT * FROM $db_pfs_folders WHERE $filter");
		if ($sql_fldr->rowCount()<=0) {
			echo '{"status":"error", "message":"'.$L['myfiles_err_invalidid'].' [Folder]"}';
			exit;
		}
		$folderrow = $sql_fldr->fetch();
		if ($folderrow['pff_userid']!=$usr['id'] && !$usr['isadmin']) {
			echo '{"status":"error", "message":"'.$L['myfiles_err_invalidid'].' [Folder]"}';
			exit;
		}		
		$userid=$folderrow['pff_userid'];
	}

	

	$fileids = cot_import('fileids','G','TXT');
	$numbers = explode(',', $fileids);
	// remove empty items it there are any
	$numbers = array_filter($numbers);

	$usrfilter="pfs_userid='$userid' AND ";
	if ($usr['isadmin']) {
		$usrfilter="";
	}

	// basically we first GET the files for checking (with userid filter!!!)
	// we need the valid id's for reporting the deleted ones back
	// userid is needed to prevent others deleting files from other users by just putting in id's
	$sql = $db->query("SELECT pfs_id,pfs_userid,pfs_folderid FROM $db_pfs WHERE $usrfilter pfs_id IN (". implode(',' , $numbers) .')');
	if ($sql->rowCount()==0) {
		echo '{"status":"error", "message":"'.$L['myfiles_err_invalidid'].'"}';
		exit;
	}	

	// then we loop that list to make a new filter for the query
	$sep='';
	$list='';
	$srcfolders='';
	
	while($frow = $sql->fetch()) {
		// if you are an admin, you could move files between users.... but that we dont support that
		// so filter that out
		if ($frow['pfs_userid'] == $userid || $usr['isadmin']) {
			$srcfolders	.= $sep.(string)$frow['pfs_folderid'];
			$list 		.= $sep.(string)$frow['pfs_id'];
			$sep=',';
		}	
	}
	
	if ($list!='') {
		// move the valid file ids to the new folder !
		$db->query("UPDATE $db_pfs SET pfs_folderid=$destfolder WHERE $usrfilter pfs_id IN (".$list.")"); 

		/*==================================================
		  Update the source folder last changed flag here
		==================================================*/
		$folders = array_unique(explode(',', $srcfolders));
		foreach ($folders as $folderid) {
			$sql = $db->query("UPDATE $db_pfs_folders SET pff_updated='".$sys['now']."' WHERE pff_id='$folderid'");
		}
		// we must update the destination folder changetime too
			$sql = $db->query("UPDATE $db_pfs_folders SET pff_updated='".$sys['now']."' WHERE pff_id='$destfolder'");
	}
	
	
	// we report back in json which files were moved
	// In ajax mode, only return results...
	echo '{"status":"ok","message":"","moved_ids":"'.$list.'"}';
	return;
	

?>
