<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

$mskin = $cfg['plugins_dir']."/myfiles/tpl/myfiles.footer.tpl"; 
$t = new XTemplate($mskin);

/*	
$t->assign(array (
	"FOOTER_BOTTOMLINE" => $out['bottomline'],
	"FOOTER_CREATIONTIME" => $out['creationtime'],
	"FOOTER_COPYRIGHT" => $out['copyright'],
	"FOOTER_SQLSTATISTICS" => $out['sqlstatistics'],
	"FOOTER_LOGSTATUS" => $out['logstatus'],
	"FOOTER_PMREMINDER" => $out['pmreminder'],
	"FOOTER_ADMINPANEL" => $out['adminpanel'],
	"FOOTER_DEVMODE" => $out['devmode']
	));
*/

$t->parse("FOOTER");
$t->out("FOOTER");
	
@ob_end_flush();

//there is no need to close connections in PDO, but it may be like $db = NULL;
//i comment next line
//cot_sql_close();
?>