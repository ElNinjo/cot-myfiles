<?php
/* =========================================
	Constants file for Myfiles plugin
	Leo Lems (ez) Company: 2basix.nl
============================================ */

/* ---------------	FILE upload  ----------------- */
global $db_pfs_folders,$db_pfs;
$db_pfs_folders = (isset($db_pfs_folders)) ? $db_pfs_folders : $db_x . 'pfs_folders';
$db_pfs = (isset($db_pfs)) ? $db_pfs : $db_x . 'pfs';

/* ---------------	FILE upload  ----------------- */
$myFiles['con_use_fileupload']  = 'function files_upload(folderid,userid){ var usr=""; if(userid!=""){ usr="&userid="+userid;}
 window.open("plug.php?r=myfiles&m=fileupload&folderid="+folderid+usr, "Upload", "toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=600,height=395,left=0,top=0");};';

 /* ---------------	FILE edit  ----------------- */
 $myFiles['con_use_fileedit']  = 'function files_modify(fileid,close){
 window.open("plug.php?r=myfiles&m=fileedit&close="+close+"&fileid="+fileid, "Edit", "width=600,height=450,toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,left=0,top=0");};';

/* ---------------	forums  ----------------- */
// Javascript used in forum related tpls
$myFiles['con_use_topicjs']  = "<script type=\"text/javascript\">function addtextfiles(){
 window.open('plug.php?r=myfiles&m=fileupload&add=newtopic', 'Upload', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=600,height=395,left=0,top=0');
}
function browsefiles(){
 window.open('plug.php?r=myfiles&m=windowbrowser&viewmode=windowbrowser', 'Select', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=940,height=600,left=0,top=0');
}
</script>";

$myFiles['con_use_postjs'] = "<script type=\"text/javascript\">
function addtextfiles(){
 window.open('plug.php?r=myfiles&m=fileupload&add=newpost', 'Upload', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=600,height=395,left=0,top=0');
}
function browsefiles(){
 window.open('plug.php?r=myfiles&m=windowbrowser&viewmode=windowbrowser', 'Select', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=940,height=600,left=0,top=0');
}
</script>";

/* ---------------	pages  ----------------- */
// page add mode
$myFiles['con_use_pageaddjs'] = "<script type=\"text/javascript\">
function addurlfile(){
 window.open('plug.php?r=myfiles&m=fileupload&add=newurl&singleaction=url', 'Upload', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=600,height=300,left=0,top=0');
}
function addtextfiles(){
 window.open('plug.php?r=myfiles&m=fileupload&add=newtext', 'Upload', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=600,height=395,left=0,top=0');
}
function browsefiles(){
 window.open('plug.php?r=myfiles&m=windowbrowser&viewmode=windowbrowser', 'Select', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=940,height=600,left=0,top=0');
}
</script>";

// page edit mode
$myFiles['con_use_pageupdjs'] = "<script type=\"text/javascript\">
function addurlfile(){
 window.open('plug.php?r=myfiles&m=fileupload&add=updurl&singleaction=url', 'Upload', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=600,height=300,left=0,top=0');
}
function addtextfiles(){
 window.open('plug.php?r=myfiles&m=fileupload&add=updtext', 'Upload', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=600,height=395,left=0,top=0');
}
function browsefiles(){
 window.open('plug.php?r=myfiles&m=windowbrowser&viewmode=windowbrowser', 'Select', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=940,height=600,left=0,top=0');
}
</script>";

// resources minibrowser 24*24px
$myFiles['con_img_imgbase'] 		= $cfg['plugins_dir'].'/myfiles/img/';
$myFiles['con_img_foldernew'] 		= $myFiles['con_img_imgbase']."btnfolder_new.png";
$myFiles['con_img_foldernewgrey'] 	= $myFiles['con_img_imgbase']."btnfolder_new_grey.png";
$myFiles['con_img_foldergoto'] 		= $myFiles['con_img_imgbase']."btnfolder_goto.png";
$myFiles['con_img_foldergoto16'] 	= $myFiles['con_img_imgbase']."btnfolder_goto16.png";		// 16*16
$myFiles['con_img_foldergotogrey'] 	= $myFiles['con_img_imgbase']."btnfolder_goto_grey.png";
$myFiles['con_img_folderedit'] 		= $myFiles['con_img_imgbase']."btnfolder_edit.png";
$myFiles['con_img_folderedit16'] 	= $myFiles['con_img_imgbase']."folder_edit16.png";
$myFiles['con_img_foldereditgrey'] 	= $myFiles['con_img_imgbase']."btnfolder_edit_grey.png";
$myFiles['con_img_folderdelete'] 	= $myFiles['con_img_imgbase']."btnfolder_delete.png";
$myFiles['con_img_folderdelete16'] 	= $myFiles['con_img_imgbase']."folder_delete16.png";
$myFiles['con_img_folderdeletegrey']= $myFiles['con_img_imgbase']."btnfolder_delete_grey.png";
$myFiles['con_img_world16'] 		= $myFiles['con_img_imgbase']."world.png";		// 16*16
$myFiles['con_img_picture16']	 	= $myFiles['con_img_imgbase']."picture.png";	// 16*16

// files
$myFiles['con_img_filenew'] 	= $myFiles['con_img_imgbase']."btndoc_add.png";
$myFiles['con_img_filedelete'] 	= $myFiles['con_img_imgbase']."btndoc_delete.png";
$myFiles['con_img_refresh'] 	= $myFiles['con_img_imgbase']."btnrefresh.png";
//filelist
$myFiles['con_img_fileedit'] 	= $myFiles['con_img_imgbase']."page_edit.png";				// 16*16
$myFiles['con_img_filedelete16']= $myFiles['con_img_imgbase']."btndoc_delete16.png";	// 16*16

$myFiles['con_img_move'] 		= $myFiles['con_img_imgbase']."btnfolder_out.png";

// resources used for page and forums to upload and use files in content
$myFiles['con_img_upload'] 		= $myFiles['con_img_imgbase']."btnfolder_up.png";
$myFiles['con_btn_uploadfile']	= "<a href='#' title='".$L['myfiles_upload']."' onclick='addurlfile(); return false;'><img style='vertical-align:middle; border:none;' src='".$myFiles['con_img_upload']."' /></a>";
$myFiles['con_btn_file2txt']	= "<a href='#' title='".$L['myfiles_uploadanduse']."' onclick='addtextfiles(); return false;'><img style='vertical-align:middle; border:none;' src='".$myFiles['con_img_upload']."' /></a>";

// show a browser screen (myfiles)
$myFiles['con_btn_selectfile']	= "<a href='#' title='".$L['myfiles_myfiles']."' onclick='browsefiles(); return false;'>".$L['myfiles_myfiles']."</a>";

/*======================================================================================================================
 * MIMETYPES original types from Cotonti + MS Office types
======================================================================================================================*/
$myFiles['mimetype'] = array();
$myFiles['mimetype']['rar'][]	= array('application/x-rar', 'Rar!', '0', '0', '4', '0');
$myFiles['mimetype']['zip'][0]	= array('application/zip', '504B03041400', '1', '0', '6', '0');
$myFiles['mimetype']['zip'][1]	= array('application/zip', '504B03040A00', '1', '0', '6', '0');
$myFiles['mimetype']['gz'][]	= array('application/x-gzip', '1F8B0800', '1', '0', '4', '0');
$myFiles['mimetype']['tar.gz'][]= array('application/x-gzip', '1F8B0808', '1', '0', '4', '0');
$myFiles['mimetype']['pdf'][1]	= array('application/pdf', '!<PDF>!', '0', '0', '7', '0');
$myFiles['mimetype']['pdf'][2]	= array('application/pdf', 'PDF', '0', '1', '3', '0');
$myFiles['mimetype']['avi'][0]	= array('video/avi', 'AVI', '0', '8', '3', '0');
$myFiles['mimetype']['avi'][1]	= array('video/avi', 'RIFF', '0', '0', '4', '0');
$myFiles['mimetype']['qt'][0]	= array('video/quicktime', 'ftypqt', '0', '4', '6', '0');
$myFiles['mimetype']['qt'][1]	= array('video/quicktime', 'moov', '0', '24', '4', '0');
$myFiles['mimetype']['mov'][0]	= array('video/quicktime', 'ftypqt', '0', '4', '6', '0');
$myFiles['mimetype']['mov'][1]	= array('video/quicktime', 'moov', '0', '24', '4', '0');
$myFiles['mimetype']['mpg'][0]	= array('video/mpeg', '000001BA', '1', '0', '4', '0');
$myFiles['mimetype']['mpg'][1]	= array('video/mpeg', '000001B3', '1', '0', '4', '0');
$myFiles['mimetype']['mpeg'][0]	= array('video/mpeg', '000001BA', '1', '0', '4', '0');
$myFiles['mimetype']['mpeg'][1]	= array('video/mpeg', '000001B3', '1', '0', '4', '0');
$myFiles['mimetype']['ogg'][]	= array('application/ogg', 'OggS', '0', '0', '4', '0');
$myFiles['mimetype']['mp3'][]	= array('audio/mpeg', 'ID3', '0', '0', '3', '0');
$myFiles['mimetype']['wav'][0]	= array('audio/x-wav', 'WAVEfmt', '0', '8', '7', '0');
$myFiles['mimetype']['wav'][1]	= array('audio/x-wav', 'RIFF', '0', '0', '4', '0');
$myFiles['mimetype']['wmv'][]	= array('video/x-ms-wmv', '3026B2758E66CF11A6D900AA0062CE6C', '1', '0', '16', '0');

/*======================================================================================================================
 * PFS extensions
======================================================================================================================*/
$myFiles['extensions'] = array();
$myFiles['extensions'][] = array ('rar', 'Archive', 'rar');
$myFiles['extensions'][] = array ('zip', 'Archive', 'zip');
$myFiles['extensions'][] = array ('avi', 'Video', 'mov');
$myFiles['extensions'][] = array ('qt', 'Video', 'mov');
$myFiles['extensions'][] = array ('mov', 'Video', 'mov');
$myFiles['extensions'][] = array ('mpeg', 'Video', 'mov');
$myFiles['extensions'][] = array ('mpg', 'Video', 'mov');
$myFiles['extensions'][] = array ('ogg', 'Video', 'mov');
$myFiles['extensions'][] = array ('gif', 'Picture', 'gif');
$myFiles['extensions'][] = array ('jpeg', 'Picture', 'jpg');
$myFiles['extensions'][] = array ('jpg', 'Picture', 'jpg');
$myFiles['extensions'][] = array ('png', 'Picture', 'png');
$myFiles['extensions'][] = array ('mp3', 'Music', 'mp3');
$myFiles['extensions'][] = array ('wav', 'Music', 'wav');
$myFiles['extensions'][] = array ('txt', 'Text', 'txt');
$myFiles['extensions'][] = array ('pdf', 'Adobe document', 'pdf');

?>
