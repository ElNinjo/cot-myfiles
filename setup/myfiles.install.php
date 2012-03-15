<?php

defined('COT_CODE') or die('Wrong URL');

global $b;

if ($b == 'install') {

    global $db, $db_pfs_folders, $db_pfs;

    // myfiles adds 1 field to the folders table
    $db_pfs_folders = (isset($db_pfs_folders)) ? $db_pfs_folders : $db_x . 'pfs_folders';

    $check = $db->query("SELECT * FROM $db_pfs_folders LIMIT 1")->fetch();
    if ($check) {
	$field_names = array_keys($check);
	if (!in_array('pff_path', $field_names)) {
	    $result = $db->query("ALTER TABLE $db_pfs_folders ADD pff_path varchar(255) NOT NULL default '/init/'");
	}
    } else {
	// create the table...
	// is done by the original setup, so do nothing here
    }

    $db_pfs = (isset($db_pfs)) ? $db_pfs : $db_x . 'pfs';
    $check = $db->query("SELECT * FROM $db_pfs LIMIT 1")->fetch();
    if ($check) {
	$field_names = array_keys($check);
	if (!in_array('pfs_friendlyname', $field_names)) {
	    $result = $db->query("ALTER TABLE $db_pfs ADD pfs_friendlyname varchar(255) NOT NULL default ''");
	}
	// extra parameter to point out that the file is in the userfolder or not !
	// done to support both userfolder on and off !!!
	if (!in_array('pfs_usrfolder', $field_names)) {
	    // add the field
	    $result = $db->query("ALTER TABLE $db_pfs ADD pfs_usrfolder char(1) NOT NULL default '" . $cfg['pfs']['pfsuserfolder'] . "'");
	}
    } else {
	// create the table...
	// is done by the original setup, so do nothing here
    }
} elseif ($b == 'uninstall') {
    // you could remove stuff here, but I leave it
}
?>
