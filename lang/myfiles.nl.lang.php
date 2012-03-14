<?php
/* ====================
 * Dutch Language File for Myfiles Plugin
 * Nederlands taalbestand voor Myfiles Plugin

 * Author: 2basix.nl 
==================== */

defined('COT_CODE') or die('Wrong URL.');

$L['myfiles_myfilesinfo']	= "Myfiles info";

// General
$L['myfiles_folder']		= "Folder";
$L['myfiles_folderurl']		= "Folder url";
$L['myfiles_folderurldesc']	= "url voor het delen van deze folder inhoud (ALLEEN voor Publieke folders)";
$L['myfiles_folders']		= "Folders";
$L['myfiles_curfolder']		= "Huidige Folder";
$L['myfiles_choosefolder']	= "Kies een folder";
$L['myfiles_foldername']	= "Folder naam";
$L['myfiles_description']	= "Omschrijving";
$L['myfiles_folderdescr']	= "Folder Omschrijving";
$L['myfiles_folderid']		= "Folder ID";
$L['myfiles_file']			= "File";
$L['myfiles_files']			= "Files";
$L['myfiles_upload']		= "Upload";
$L['myfiles_cancel']		= "Annuleren";
$L['myfiles_yes']			= "Ja";
$L['myfiles_no']			= "Nee ";
$L['myfiles_create']		= "Maken";
$L['myfiles_new']			= "Nieuw";
$L['myfiles_update']		= "Bijwerken";
$L['myfiles_edit']			= "Wijzigen";
$L['myfiles_save']			= "Opslaan";
$L['myfiles_move']			= "Verplaatsen";
$L['myfiles_delete']		= "Verwijderen";
$L['myfiles_refresh']		= "Vernieuwen";
$L['myfiles_nofiles']		= "Aantal bestanden";
$L['myfiles_showtn']		= "Toon thumbnails";

// error messages
$L['myfiles_err_noaccess']			= "Geen toegangs rechten";
$L['myfiles_err_nowrite']			= "Geen rechten voor schrijven";
$L['myfiles_err_folder_noname']		= "Foldernaam ontbreekt";
$L['myfiles_err_nofolder']			= "Folder is incorrect";
$L['myfiles_err_parentinchild']		= "Kan een hoofdfolder niet in een subfolder verplaatsen";
$L['myfiles_err_invalidid']			= "ERROR: Invalid id";
$L['myfiles_err_invalidparams']		= "ERROR: Invalid parameters";
$L['myfiles_err_maximumflevel']		= "ERROR: De maximale folderdiepte is bereikt";
$L['myfiles_err_maximumsubf']		= "ERROR: Het maximale aantal subfolders is bereikt";
$L['myfiles_err_nofile']			= "File niet gevonden";
$L['myfiles_err_notempty']			= "Deze folder bevat nog files of subfolders";
$L['myfiles_err_nofileselected']	= "Er zijn geen files geselecteerd";

// questions
$L['myfiles_q_suretodelete']		= "Weet u ZEKER dat u de geselecteerde\\n\\nbestanden wilt verwijderen ?";

// folder minibrowser
$L['myfiles_path']			= "Folder";
$L['myfiles_foldernew'] 	= 'Maak een nieuwe subfolder';
$L['myfiles_folderedit'] 	= 'Bewerk folder';
$L['myfiles_foldergoin'] 	= 'Ga in deze folder';
$L['myfiles_folderdelete'] 	= 'Verwijder folder';
$L['myfiles_nosubfolders'] 	= 'geen subfolders..';
$L['myfiles_numbersubfolders'] 	= 'aantal subfolders';
$L['myfiles_busy'] 			= 'busy...';
$L['myfiles_waiting'] 		= 'wachten...';
$L['myfiles_loading'] 		= 'laden...';
$L['myfiles_showfolderinfo']= 'Toon folderinfo';

// folder
$L['myfiles_root'] 			= 'root';
$L['myfiles_rootdesc']		= 'Gebruikers Hoofd folder';
$L['myfiles_hideinfo']		= 'Verberg folder info';
$L['myfiles_showinfo']		= 'Toon folder info';
$L['myfiles_newfolder']		= 'Nieuwe Folder';
$L['myfiles_lastchanged'] 	= 'Laatst gewijzigd';

//file
$L['myfiles_filename']		= 'Filename';			// friendly filename
$L['myfiles_filetype']		= 'Filetype';
$L['myfiles_fileedit'] 		= 'Bewerk file';
$L['myfiles_filesize'] 		= 'File grootte';
$L['myfiles_fileurl'] 		= 'File url';
$L['myfiles_filethumb'] 	= 'Thumbnail';
$L['myfiles_filedate'] 		= 'File upload datum';
$L['myfiles_fileid'] 		= 'File ID';
$L['myfiles_fileinfo']		= 'File informatie';
$L['myfiles_filenew'] 		= 'Upload nieuwe files';
$L['myfiles_fileedit'] 		= 'Edit file';
$L['myfiles_filedelete']	= 'Verwijder file';
$L['myfiles_filemove']		= "Verplaats files naar een andere folder";

// upload
$L['myfiles_succesfull'] 	= 'Uploads gereed:';
$L['myfiles_uploadresults'] = 'Upload resultaten';
$L['myfiles_nofileselected']= 'Er is geen File geselecteerd';
$L['myfiles_nofilename'] 	= 'Filenaam ontbreekt';
$L['myfiles_uploadbusy'] 	= 'Upload in verwerking...';
$L['myfiles_uploadanduse'] 	= 'Upload en gebruik';
$L['myfiles_addthumb'] 		= 'Voeg een thumbnail van deze afbeelding toe aan de text';
$L['myfiles_addimg'] 		= 'Voeg de afbeelding toe aan text';
$L['myfiles_addlink'] 		= 'Voeg een link van deze file toe aan de text';

// taken from main.lang.php

$L['myfiles_myfiles'] 			= 'Mijn bestanden';
$L['myfiles_sitefiles']			= 'Site bestanden';
$L['myfiles_title'] 			= 'Myfiles';
$L['myfiles_extallowed'] 		= 'Toegestane Bestandstypen';
$L['myfiles_filecheckfail'] 	= 'Waarschuwing: File Controle faalde for type: %1$s Filename - %2$s';	
$L['myfiles_filechecknomime'] 	= 'Waarschuwing: Mime Type data niet gevonden voor type: %1$s Filename - %2$s';
$L['myfiles_fileexists'] 		= 'De upload faalde, er is reeds een bestand met deze naam?';
$L['myfiles_filelistempty'] 	= 'Lijst is leeg.';
$L['myfiles_filemimemissing'] 	= 'De mime type voor %1$s ontbreekt. Upload is mislukt';
$L['myfiles_filenotmoved'] 		= 'Upload is mislukt, tijdelijk bestand kan niet verplaatst worden.';
$L['myfiles_filenotvalid'] 		= 'Dit is geen valide %1$s file.';
$L['myfiles_filesintheroot'] 	= 'File(s) in the root';
$L['myfiles_filesinthisfolder'] = 'File(s) in deze folder';

// new items for Myfiles
$L['myfiles_uploadfailed'] 			= 'Upload is mislukt..';
$L['myfiles_filerenamefailed'] 		= 'Upload is mislukt, file kon niet hernoemd worden..';
$L['myfiles_filetoobig'] 			= 'Upload is mislukt\\n file is te groot';
$L['myfiles_fileextnotallowed'] 	= 'Upload is mislukt, file type is niet toegestaan';
$L['myfiles_fileextnotallowed2'] 	= 'Dit filetype is niet toegestaan';
$L['myfiles_filetoobigmaxspace']	= 'Upload is mislukt\\n file is te groot (de maximum gebruikte ruimte is bereikt)';
$L['myfiles_fileuploadfailed'] 		= 'Upload is mislukt\\n deze file kan te groot zijn, of is ongeldig';

$L['myfiles_folderistempty'] 	= 'Deze folder is leeg.';
$L['myfiles_foldernosubfolder'] = 'Deze folder heeft geen subfolders';
$L['myfiles_isgallery'] 		= 'Afbeeldingen';
$L['myfiles_isgallery_c'] 		= 'a';
$L['myfiles_ispublic'] 			= 'Publiek';
$L['myfiles_ispublic_c']		= 'p';
$L['myfiles_maxsize'] 			= 'Maximum grootte voor een file';
$L['myfiles_max'] 				= 'Max.';
$L['myfiles_maxspace'] 			= 'Maximum grootte toegestaan';
$L['myfiles_newfile'] 			= 'Upload een file:';
$L['myfiles_resizeimages'] 		= 'Afbeelding schalen?';
$L['myfiles_totalsize'] 		= 'Totale grootte';

?>