<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

//cot_uriredir_store();

unset($title_tags, $title_data);
$title_tags[] = array('{MAINTITLE}', '{DESCRIPTION}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['fulltitle'] = cot_title('title_header', $title_tags, $title_data);

/** **/
$out['meta_contenttype'] = ($cfg['doctypeid']>2 && $cfg['xmlclient']) ? "application/xhtml+xml" : "text/html";
$out['meta_desc'] = $plug_desc.$cfg['maintitle']." - ".$cfg['subtitle'];
$out['head_head'] = $plug_head;
$out['basehref'] = '<base href="'.$cfg['mainurl'].'/" />';
//disabling next line to find better way of doing that in Siena (cot_rc_link_file)
//$out['compopup'] = sed_javascript($morejavascript);

$pluglocation = $cfg['plugins_dir']."/myfiles";

cot_sendheaders();

/* === Hook === */
foreach (cot_getextplugins('myfiles.header') as $pl)
{
	include $pl;
}
/* ===== */

$mskin = $cfg['plugins_dir']."/myfiles/tpl/myfiles.header.tpl";
$t = new XTemplate($mskin);
//cot_checkpoint();
//cot_watch($out['compopup']);
$t->assign(array (
	"HEADER_DOCTYPE" => $cfg['doctype'],
	"HEADER_META_CONTENTTYPE" => $out['meta_contenttype'],
	"HEADER_META_CHARSET" => $cfg['charset'],
	"HEADER_META_DESCRIPTION" => $out['meta_desc'],
	"HEADER_HEAD" => $out['head_head'],
	"HEADER_BASEHREF" => $out['basehref'],
	"HEADER_COMPOPUP" => $out['compopup'],
	"HEADER_TITLE" => $plug_title.$out['fulltitle'],
	"HEADER_PLUGLOCATION"	=> $pluglocation
));

$t->parse("HEADER");
$t->out("HEADER");

?>