<?php
/* ====================
Cotonti - Website engine  http://www.cotonti.com

[BEGIN_COT_EXT]
Code=myfiles
Name=Myfiles plugin
Description=PFS replacement plugin
Version=160
Date=2012-mar-02
Author=2basix.nl (ez)
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
myfiles_maxupload=01:select:1,2,3,4,5,6,7,8,9,10:4:Maximum number of simultane uploads
myfiles_bbcode=02:select:Yes,No:Yes:Does the site use the BBCODE editor
[END_COT_EXT_CONFIG]

==================== */

defined('COT_CODE') or die('Wrong URL.');

if($b=='install')
{
	// myfiles adds 1 field to the folders table
	$db_folders = $db_x.'pfs_folders';
//	cot_watch($db_folders);
	$check = $db->query("SELECT * FROM $db_folders LIMIT 1"); 
	if ($check){
		$columns = mysql_num_fields($check);
		for ($i = 0; $i < $columns; $i++) {$field_names[] = mysql_field_name($check, $i);}

		if (!in_array('pff_path', $field_names)) {
			$result = $db->query("ALTER TABLE $db_folders ADD pff_path varchar(255) NOT NULL default '/init/'");
			echo($result);
		}
	}else{
		// create the table...
		// is done by the original setup, so do nothing here
	}
	
	$db_files = $db_x.'pfs';
	$check = cot_sql_query ("SELECT * FROM $db_files LIMIT 1"); 
	if ($check){
		$columns = mysql_num_fields($check);
		for ($i = 0; $i < $columns; $i++) {$field_names[] = mysql_field_name($check, $i);}
		if (!in_array('pfs_friendlyname', $field_names)) {
			$result = $db->query("ALTER TABLE $db_files ADD pfs_friendlyname varchar(255) NOT NULL default ''");
		}
		// extra parameter to point out that the file is in the userfolder or not !
		// done to support both userfolder on and off !!!
		if (!in_array('pfs_usrfolder', $field_names)) {
			// add the field
			$result = $db->query("ALTER TABLE $db_files ADD pfs_usrfolder char(1) NOT NULL default '".$cfg['pfs']['pfsuserfolder']."'");
		}
	}else{
		// create the table...
		// is done by the original setup, so do nothing here
	}
	
	
}
elseif($b=='uninstall')
{
	// you could remove stuff here, but I leave it
}

?>
