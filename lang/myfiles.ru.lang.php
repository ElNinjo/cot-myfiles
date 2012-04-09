<?PHP
/* ====================
 * Russian Language File for Myfiles Plugin
 * Author: 2basix.nl 
==================== */

defined('COT_CODE') or die('Wrong URL.');

// General
$L['myfiles_myfilesinfo']	= "Myfiles info";

$L['myfiles_folder']		= "Folder";
$L['myfiles_folderurl']		= "Folder url";
$L['myfiles_folderurldesc']	= "url for sharing your folder content to others (ONLY public folders)";
$L['myfiles_folders']		= "Folders";
$L['myfiles_curfolder']		= "Current Folder";
$L['myfiles_choosefolder']	= "Choose a folder";
$L['myfiles_foldername']	= "Folder name";
$L['myfiles_description']	= "Description";
$L['myfiles_folderdescr']	= "Folder Description";
$L['myfiles_folderid']		= "Folder ID";
$L['myfiles_file']			= "File";
$L['myfiles_files']			= "Files";
$L['myfiles_upload']		= "Upload";
$L['myfiles_cancel']		= "Cancel";
$L['myfiles_yes']			= "yes";
$L['myfiles_no']			= "no ";
$L['myfiles_create']		= "Create";
$L['myfiles_new']			= "New";
$L['myfiles_update']		= "Update";
$L['myfiles_edit']			= "Edit";
$L['myfiles_save']			= "Save";
$L['myfiles_move']			= "Move";
$L['myfiles_delete']		= "Delete";
$L['myfiles_refresh']		= "Refresh";
$L['myfiles_nofiles']		= "Number of files";
$L['myfiles_showtn']		= "Show thumbnails";

// error messages
$L['myfiles_err_noaccess']			= "No access rights";
$L['myfiles_err_nowrite']			= "No access rights for writing";
$L['myfiles_err_folder_noname']		= "Missing folder name";
$L['myfiles_err_nofolder']			= "Incorrect folderid";
$L['myfiles_err_parentinchild']		= "Cannot move a parentfolder into a child";
$L['myfiles_err_invalidid']			= "ERROR: Invalid id";
$L['myfiles_err_invalidparams']		= "ERROR: Invalid parameters";
$L['myfiles_err_maximumflevel']		= "ERROR: Maximum subfolder level is reached";
$L['myfiles_err_maximumsubf']		= "ERROR: The maximum number of subfolders is reached";

$L['myfiles_err_nofile']			= "File not found for current user";
$L['myfiles_err_notempty']			= "This folder contains files or subfolders";
$L['myfiles_err_nofileselected']	= "There are no files selected";

// questions
$L['myfiles_q_suretodelete']		= "Are you ABSOLUTELY sure that you\\n\\nwant to delete the selected files ?";

// folder minibrowser
$L['myfiles_path']					= "Path";
$L['myfiles_foldernew'] 			= 'Create a new subfolder';
$L['myfiles_folderedit'] 			= 'Edit folder';
$L['myfiles_foldergoin'] 			= 'Go into this folder';
$L['myfiles_folderdelete'] 			= 'Delete folder';
$L['myfiles_nosubfolders'] 			= 'no subfolders..';
$L['myfiles_numbersubfolders'] 		= 'number of subfolders';
$L['myfiles_busy'] 					= 'busy...';
$L['myfiles_waiting'] 				= 'waiting...';
$L['myfiles_loading'] 				= 'loading...';
$L['myfiles_showfolderinfo']		= 'Show folderinfo';

// folder
$L['myfiles_root'] 			= 'root';
$L['myfiles_rootdesc']		= 'main user folder';
$L['myfiles_hideinfo']		= 'hide folder info';
$L['myfiles_showinfo']		= 'show folder info';
$L['myfiles_newfolder'] 	= 'New folder';
$L['myfiles_lastchanged'] 	= 'Last changed';


//file
$L['myfiles_filename']	= 'Filename';			// friendly filename
$L['myfiles_filetype']	= 'Filetype';
$L['myfiles_fileedit'] 	= 'Edit file';
$L['myfiles_filesize'] 	= 'File size';
$L['myfiles_fileurl'] 	= 'File url';
$L['myfiles_filethumb'] = 'Thumbnail';
$L['myfiles_filedate'] 	= 'File upload date';
$L['myfiles_fileid'] 	= 'File ID';
$L['myfiles_fileinfo']	= 'File information';
$L['myfiles_filenew'] 	= 'Upload new files';
$L['myfiles_fileedit'] 	= 'Edit file';
$L['myfiles_filedelete']= 'Delete file';
$L['myfiles_filemove']	= "Move files to another folder";


// upload
$L['myfiles_succesfull'] 	= 'Uploads finished:';
$L['myfiles_uploadresults'] = 'Upload results';
$L['myfiles_nofileselected']= 'There is no file selected';
$L['myfiles_nofilename'] 	= 'Filename is missing';
$L['myfiles_uploadbusy'] 	= 'Upload in progress...';
$L['myfiles_uploadanduse'] 	= 'Upload and use files';
$L['myfiles_addthumb'] 		= 'Add a thumbnail off this file to the text';
$L['myfiles_addimg'] 		= 'Add this image to the text';
$L['myfiles_addlink'] 		= 'Add a link off this file to the text';

// taken from main.lang.php
$L['myfiles_myfiles'] 			= 'My files';
$L['myfiles_sitefiles']			= 'Site files';
$L['myfiles_title'] 			= 'Myfiles';
$L['myfiles_extallowed'] 		= 'Extensions allowed';
$L['myfiles_filecheckfail'] 	= 'Warning: File Check Failed for Extension: %1$s Filename - %2$s';	
$L['myfiles_filechecknomime'] 	= 'Warning: No Mime Type data was found for the Extension: %1$s Filename - %2$s';
$L['myfiles_fileexists'] 		= 'The upload failed, there\'s already a file with this name?';
$L['myfiles_filelistempty'] 	= 'List is empty.';
$L['myfiles_filemimemissing'] 	= 'The mime type for %1$s is missing. Upload Failed';
$L['myfiles_filenotmoved'] 		= 'The upload failed, temporary file cannot be moved.';
$L['myfiles_filenotvalid'] 		= 'This is not a valid %1$s file.';
$L['myfiles_filesintheroot'] 	= 'File(s) in the root';
$L['myfiles_filesinthisfolder'] = 'File(s) in this folder';

// new items for Myfiles
$L['myfiles_uploadfailed'] 			= 'The upload failed..';
$L['myfiles_filerenamefailed'] 		= 'The upload failed, file could not be renamed..';
$L['myfiles_filetoobig'] 			= 'The upload failed, file is too big';
$L['myfiles_fileextnotallowed'] 	= 'The upload failed, file extension is not allowed (filetype)';
$L['myfiles_fileextnotallowed2'] 	= 'This file extension is not allowed (filetype)';
$L['myfiles_filetoobigmaxspace']	= 'The upload failed, file is too big (maximum allowed space is reached)';
$L['myfiles_fileuploadfailed'] 		= 'The upload failed, this file is maybe too big, or could be invalid.';

$L['myfiles_folderistempty'] 	= 'This folder is empty...';
$L['myfiles_foldernosubfolder'] = 'This folder has no subfolders';
$L['myfiles_isgallery'] 		= 'Images';
$L['myfiles_isgallery_c'] 		= 'i';
$L['myfiles_ispublic'] 			= 'Public';
$L['myfiles_ispublic_c']		= 'p';
$L['myfiles_maxsize'] 			= 'Maximum size for a file';
$L['myfiles_max'] 				= 'Max.';
$L['myfiles_maxspace'] 			= 'Maximum space allowed';
$L['myfiles_newfile'] 			= 'Upload a file:';
$L['myfiles_resizeimages'] 		= 'to scale the image?';
$L['myfiles_totalsize'] 		= 'Total size';


?>