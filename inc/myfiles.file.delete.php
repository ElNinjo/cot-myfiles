
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
		$userid=$usr['id'];
		if ($userid=='0') {
			// guests do not have personal files !!!!!!  
			echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [User]"}';
			exit;
		}
	}
	if ($usr['id']!=$userid & !$usr['isadmin']) {
		// other then your own userid and not an admin
		echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [User]"}';
		exit;
	}

	$fileids = cot_import('fileids','G','TXT');

	$numbers = explode(',', $fileids);
	// remove empty items it there are any
	$numbers = array_filter($numbers );

	$usrfilter="pfs_userid='$userid' AND ";
	if ($usr['isadmin']) {
		$usrfilter=" ";
	}
	
	// basically we first GET the files for checking (with userid filter!!!)
	// we need the valid id's for reporting the deleted ones back
	// userid is needed to prevent others deleting files from other users by just putting in id's
	$sql = $db->query("SELECT pfs_id,pfs_userid,pfs_folderid,pfs_file,pfs_usrfolder FROM $db_pfs WHERE $usrfilter pfs_id IN (". implode(',' , $numbers) .')');
	if ($sql->rowCount()==0) {
		echo '{"status":"error", "message":"'.$L['myfiles_err_invalidid'].'"}';
		exit;
	}	

	// then we loop that list to make a new filter for the query
	$sep='';
	$list='';
	$folderdeletes=array();
	while($frow = $sql->fetch()) {
		$list .= $sep . (string)$frow['pfs_id'];
		$sep=',';
		$sfid=(string)$frow['pfs_folderid'];
		if (isset($folderdeletes[$sfid])) {
			$folderdeletes[$sfid]++;
		} else {
			$folderdeletes[$sfid]=1;
		}

		// we need to delete the actual files and thumbs and everything HERE !!!
		// location of the files is depending on $frow['pfs_usrfolder']
		
		if ($frow['pfs_usrfolder']=='1') {
			// file uses FSM 
			$file_url= $cfg['pfs_dir'].$frow['pfs_userid'].'/'.$frow['pfs_file'];
			
			// remove the file
			if (file_exists($file_url)) {
				unlink($file_url);
			}
			
			// remove possible thumbnail
			$thumb_url= $cfg['thumbs_dir'].$frow['pfs_userid'].'/'.$frow['pfs_file'];
			if (file_exists($thumb_url)) {
				unlink($thumb_url);
			}
			
		} else {
			// file uses root
			$file_url= $cfg['pfs_dir'].$frow['pfs_file'];

			// remove the file
			if (file_exists($file_url)) {
				unlink($file_url);
			}
			
			$thumb_url=$cfg['thumbs_dir'].$frow['pfs_file'];
			if (file_exists($thumb_url)) {
				unlink($thumb_url);
			}
		}
	}
	
	// delete the valid ones !
	$db->query("DELETE FROM $db_pfs WHERE $usrfilter pfs_id IN (".$list.")"); 

	/*==================================================
		Update the folder last changed flag here !
	==================================================*/
	//	$folderdeletes
	foreach ($folderdeletes as $folderid => $number) {
		if ((int)$folderdeletes[$folderid] > 0) {
			$sql = $db->query("UPDATE $db_pfs_folders SET pff_updated='".$sys['now']."' WHERE pff_id='$folderid'");
		}
	}
	
	
	// we report back in json which files were deleted
	// In ajax mode, only return results...
	echo '{"status":"ok","message":"","deleted_ids":"'.$list.'","folderinfo":'.json_encode($folderdeletes).'}';
	return;
	

?>
