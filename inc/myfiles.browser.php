<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

// viewmode = browser		(regular with name and blockInfo)
// viewmode = windowbrowser	(minimalized UI, no name block, no blockinfo)

defined('COT_CODE') or die('Wrong URL');

if (!$usr['auth_read']) {
    die($L['myfiles_err_noaccess']);
    return;
}

require_once $cfg['plugins_dir'] . "/myfiles/inc/myfiles.inc.php";

// folder id is on the url
$folderid = cot_import('folderid', 'G', 'TXT');
$userid = cot_import('userid', 'G', 'TXT');
$viewmode = cot_import('viewmode', 'G', 'TXT');
$foldermode = cot_import('foldermode', 'G', 'TXT');
$username = "";

if ($viewmode == '') {
    $viewmode = "browser";
}

$folderrights = myfiles_getFolderRightsInfo($folderid, $userid);

$pos = strpos($folderrights, "W");
$writeaccess = ($pos === FALSE) ? FALSE : TRUE;
$pos = strpos($folderrights, "R");
$readaccess = ($pos === FALSE) ? FALSE : TRUE;
$folderuserid = subtok($folderrights, ',', 1, 1);

$username = ($viewmode == 'browser') ? myfiles_getusername($folderuserid, $L['myfiles_sitefiles']) : '';

$infoblock = '';
$fldrselect = '';
if ($writeaccess) {
    $infoblock = ($viewmode == 'browser') ? myfiles_getInfoBlock_html($userid) : '';
    //$fldrselect=myfiles_getMiniDir_html($folderid,$userid,'0',$folderrights);
    $fldrselect = myfiles_getMiniDir_html($folderid, $userid, $foldermode, $shownewfolder, $hidebuttons);
}

$showfolderpath = '0';
$filelist = myfiles_getFilelist_html($folderid, $userid, "compact", $folderrights, $showfolderpath);

if ($outputmode != "plugin") {
    require $cfg['plugins_dir'] . '/myfiles/inc/myfiles.header.php';
}
$t = new XTemplate($cfg['plugins_dir'] . '/myfiles/tpl/myfiles.' . $viewmode . '.tpl');

$t->assign(array(
    'MYFILES_BASEDIR' => $cfg['plugins_dir'] . '/myfiles',
    'MYFILES_USERNAME' => $username,
    'MYFILES_INFOBLOCK' => $infoblock,
    'MYFILES_FOLDERSELECT' => $fldrselect,
    "MYFILES_FOLDERFILES" => $filelist
));

/* === Hook === */
foreach (cot_getextplugins('myfiles.' . $viewmode . '.tags') as $pl)
{
	include $pl;
}
/* ===== */

if ($outputmode != "plugin") {
    $t->parse('MAIN');
    $t->out('MAIN');
    require $cfg['plugins_dir'] . '/myfiles/inc/myfiles.footer.php';
}

?>
