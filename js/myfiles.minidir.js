//=======================================
// Author 2basix.nl (Leo Lems)
// Myfiles supporting JS for minidir
//=======================================

if (myfiles===undefined) {
	var myfiles={};
}
myfiles.pub_selectnewfolder=null;
myfiles.pub_lastfldrselect="-2";

// prepare the form when the DOM is ready 
$(document).ready(function() { 
	if (myfiles.pub_lastfldrselect=="-2") {
		myfiles.pub_lastfldrselect="-1";
		event_folderchanged("0");
		folder_bindchange();
	}
}); 

function folder_initrload() {
	$.blockUI.defaults.fadeIn = 0;
	$.blockUI.defaults.fadeOut = 200;
	$("#mb_foldershowinfo").change(function () {$('#mb_fldrinfopart').slideToggle(250)})
}

function folder_clearerror() {
	$('#mb_foldererror').html("").hide();
}

function folder_showerror(message) {
	if (myfiles.error_foldertimer === undefined) {
		myfiles.error_foldertimer=null;
	}	
	
	if (myfiles.error_foldertimer!==null) {
		// timer is allready busy
		clearTimeout(myfiles.error_foldertimer);
		myfiles.error_foldertimer=null;
	}
	var erricon= '<img src="'+myfiles.basepath+'/img/error.png" />&nbsp;&nbsp;';
	$('#mb_foldererror').html(erricon+message).show();
	myfiles.error_foldertimer=setTimeout('folder_clearerror()', 2500);
}


function folder_bindchange() {
	$("#mb_ajx select.fldr_select").change( function() {
		var theval=$(this).val();
		event_folderchanged(theval);
	}).keyup(function(event) {
		return folder_keyb(event.which);
	}).focus();
}

function folder_findIndex(folderid) {
	var index=-1;
	for(var i in myfiles.mb_json_allfolderinfo) {
		if (myfiles.mb_json_allfolderinfo[i].pff_id==folderid) {
			index=i;
			break;
		}
	}
	return index;
}


function folder_getSubfoldercount(folderid) {
	var sfolderid=''+folderid;
	if (sfolderid=='0' || folderid==null || folderid=="-2") { sfolderid=myfiles.baseinfo[0]; }
	var i=folder_findIndex(folderid);
	var i=folder_findIndex(folderid);
	if (i<0) {
		return -1;
	}	
	return myfiles.mb_json_allfolderinfo[i].subfoldercount;
}

// put folder info on the screen
function folder_loadInfo(folderid) {
	var sfolderid=''+folderid;
	var s="";
	var fldrinfo;

	if (sfolderid=='0' || folderid==null || folderid=="-2") { sfolderid=myfiles.baseinfo[0]; }
	var i=folder_findIndex(folderid);
	if (i<0) {
		return;
	}	
	fldrinfo=myfiles.mb_json_allfolderinfo[i];	
	myfiles.pub_lastfldrselect=folderid;
	if (fldrinfo.pff_id!="-1" && fldrinfo.subfoldercount==0) {
		$('#mb_btnfdelete').show();
		$('#img_fdelete_grey').hide();
	} else {
		$('#mb_btnfdelete').hide();
		$('#img_fdelete_grey').show();
	}
	if (fldrinfo.pff_id!="-1" || (folderid*1) > 0) {
		$('#mb_btnfedit').show(); 
		$('#img_fedit_grey').hide();
		// limit folder levels
		if ((fldrinfo.pff_path.split("/").length - 1) > myfiles.baseinfo[4]) {
			$('#mb_btnnew').hide();
			$('#img_fnew_grey').show();
		} else {
			$('#mb_btnnew').show();
			$('#img_fnew_grey').hide();
		}		
	} else {
		$('#mb_btnfedit').hide();
		$('#img_fedit_grey').show();
	}
	
	var selectval = $("#mb_ajx select.fldr_select").val()
	if (fldrinfo.subfoldercount==0 || folderid==0 || selectval==0) {
		$('#img_fgoin').hide();
		$('#fldrsubfolders').html('');
	} else {
		$('#img_fgoin').show();
		$('#fldrsubfolders').html('&nbsp;&nbsp;&nbsp;( '+fldrinfo.subfoldercount+' )');
	}		
		
	if (fldrinfo.pff_ispublic=="1" || fldrinfo.pff_isgallery=="1") {
		var addcom="";
		var addstr="";
		if (fldrinfo.pff_ispublic=="1") {
			addstr=myfiles.minidirlang[3];
			addcom=",";
		}
		if (fldrinfo.pff_isgallery=="1") {
			addstr += addcom+myfiles.minidirlang[2];
			addcom=",";
		}
		$('#fldrflags').html('( '+addstr+' )');
	} else {
		$('#fldrflags').html('');
	}
	if (fldrinfo.pff_desc=="") {
		$('#fldrdescription').html(' - ');
	} else {
		$('#fldrdescription').html(fldrinfo.pff_desc);
	}
	
	// process folder link
	$('a#fldrurl').attr("href", "plug.php?e=myfiles&folderid="+fldrinfo.pff_id).text(fldrinfo.pff_title);
	if (fldrinfo.pff_id=="-1") {
		$('#fldrlastchange').html('');
	} else {	
		$('#fldrlastchange').html(fldrinfo.lastchange);
	}	
}

// used in other JS code (file uploads) !!!
function folder_GetCurrentFolderid() {
	var fid;
	fid=parseInt(myfiles.pub_lastfldrselect);
	if (fid<0) { fid=0 }
	return ''+fid;
}


function event_folderchanged(folderid) {
// need this here because of folder edit
	if (folderid==0) {
		// this is the empty value in the select, so the currentdir is chosen
		folderid=myfiles.baseinfo[0];
	}	
	
	if (myfiles.pub_lastfldrselect==folderid) {
		return false;
	}
	folder_loadInfo(folderid);
	// trigger an event
	try { $('body').trigger('myfiles_folder_change',folderid);} catch(err) {}
}

function folder_processminibrowser(data) {
	$('#mb_ajx').html(data);
	var fldselect=$('#mb_ajx select[name=folderid]');
	
	if (myfiles.pub_selectnewfolder) {
		myfiles.pub_selectnewfolder=myfiles.pub_selectnewfolder.replace(/^0+(?=\d.)/, '');
		// select the newly created folder
		fldselect.val(myfiles.pub_selectnewfolder);
		event_folderchanged(myfiles.pub_selectnewfolder);
		myfiles.pub_selectnewfolder=null;
	} else {
		//changed
		event_folderchanged(fldselect.val());
		// edit changes data, so load it again
		folder_loadInfo(fldselect.val());
	}
	// bind the change again
	$('#minidir').unblock();
	folder_bindchange();
}

function folder_goto(folderid,forceload,goback) {
	if (!forceload) {
		if (myfiles.baseinfo[1] == myfiles.baseinfo[0] && myfiles.baseinfo[0] == myfiles.pub_lastfldrselect) {
			return
		}
	}	
	
	if (folderid!='0') {
		var usr=myfiles.baseinfo[3];
		if (usr!="") {	usr="&userid="+usr;	}
		var loadiconhtml="<img style=\"text-align:baseline;\" src=\""+myfiles.basepath+"/img/ajxldr_small.gif\" \>";
		var url="plug.php?r=myfiles&m=minidirpart&folderid="+folderid+usr;
		if (goback) { url+='&goback=1';	}
		$('#minidir').block({ message: loadiconhtml+"&nbsp;&nbsp;"+myfiles.minidirlang[0], overlayCSS: { backgroundColor: '#ddd' } });
		$("#mb_ajx select.fldr_select").unbind('change').unbind('keyup');
		$.get(url, function (data) {folder_processminibrowser(data)} );
	}
}

function folder_edit() {
	var fld=myfiles.pub_lastfldrselect;
	if (fld>=0) {
		if (fld=="0") { fld=myfiles.baseinfo[0] };
		var usr=myfiles.baseinfo[3];
		if (usr!="") {	usr="&userid="+usr;	}
		window.open('plug.php?r=myfiles&m=folderedit&folderid='+fld+usr+"&close=1", myfiles.minidirlang[7], 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=500,height=350,left=0,top=0');
	}	
}

function folder_new() {
	var fld=myfiles.baseinfo[0];
	var usr=myfiles.baseinfo[3];
	if (myfiles.pub_lastfldrselect!="0" && myfiles.pub_lastfldrselect!="-2") {
		// there is a subfolder selected !!!!
		// 0 means empty selection box !!
		fld=myfiles.pub_lastfldrselect;
	}
	if (usr!="") {	usr="&userid="+usr;	}
	window.open('plug.php?r=myfiles&m=folderadd&folderid='+fld+usr+"&close=1", myfiles.minidirlang[1], 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=500,height=350,left=0,top=0');
}

// is called from a child create folder screen
function folder_wascreated(newid,parentid) {
	var nid= ''+newid;
	nid=nid.replace(/^0+(?=\d.)/, '');
	if (parentid) {
		myfiles.pub_selectnewfolder=nid;
		//myfiles.pub_selectnewfolder=''+newid;
		folder_goto(parentid,true);
	}	
}

function folder_removeFromMemory(delfolderid) {
	// remove it cleanly from the memory too
	var i=folder_findIndex(delfolderid);
	if (i>=0) {
		var elementtodelete=null;
		elementtodelete=myfiles.mb_json_allfolderinfo[i];
		myfiles.mb_json_allfolderinfo = $.grep(myfiles.mb_json_allfolderinfo, function(val) { return val != elementtodelete; });
	}
}

// is called from a child modify folder screen
function folder_modified(theid,folderrecord) {
	myfiles.pub_lastfldrselect=''+theid;
	if (folderrecord != undefined) {
		// there is a record so we update the screen data.... this saves a ajax request !!!
		var i=folder_findIndex(theid);
		if (i>=0) {
			// we now know the memory index, so update that one from the folderrecord
			var mrec=myfiles.mb_json_allfolderinfo[i]
			var oldtitle=mrec.pff_desc;
			var oldpub=mrec.pff_ispublic;
			
			if (mrec.pff_path == folderrecord.pff_path) {
				mrec.pff_desc = folderrecord.pff_desc;
				mrec.pff_isgallery = folderrecord.pff_isgallery;
				mrec.pff_ispublic = folderrecord.pff_ispublic;
				mrec.pff_title = folderrecord.pff_title;
				mrec.pff_updated = folderrecord.pff_updated;
				folder_loadInfo(theid);
				
				if (oldpub!=mrec.pff_ispublic || oldtitle != mrec.pff_title) {
					// change value inside select option
					var opt=$("#mb_ajx select.fldr_select option[value="+theid+"]");
					var newtxt=opt.text();
					newtxt=newtxt.substr(0,2)+mrec.pff_title
					if (mrec.pff_ispublic==1) {
						newtxt+=myfiles.s_pubstring;
						newtxt=newtxt.replace(/(&nbsp;)/g,' ');
					}
					// update the select optionvalue
					opt.text(newtxt);
				}
			} else {
				// the path has changed..... what should we do now ??? (what is logical)
				// go back to the original path (source from the folder)
				// or goto the new location !!!
				folder_goto(theid,true);	// quick way !!!
			}
		}
	} else {
		// load it the hard way
		folder_goto(theid,true);
	}	
}


function folder_processdelete(data) {
	if (data.status=="ok") {
		var delfolderid=data.info.deletedfolderid;
		var opt=$("#mb_ajx select.fldr_select option[value="+delfolderid+"]");
		var nextitem=opt.next();
		opt.remove();
		
		// remove it cleanly from the memory too
		folder_removeFromMemory(delfolderid);
		
		// ok the folder is deleted, do find next item in the folder select box
		if (nextitem.length>0) {
			nextitem.attr('selected', 'selected');
			event_folderchanged(nextitem.val());
		} else {
			$("#mb_ajx select.fldr_select option[value=0]").attr('selected', 'selected');
		}		
		// count howmany items are left to choose
		opt=$("#mb_ajx select.fldr_select option");
		if (opt.length==1) {
			// it is empty 1 item is the 0 value (so its basically empty... go back..
			folder_goto(myfiles.baseinfo[0],true,true)
		}
	} else {
		folder_showerror(data.message) // there was an error, so handle it
	}
	$('#minidir').unblock();
	folder_bindchange();	
}

function folder_delete() {
	var folderid = $("#mb_ajx select.fldr_select").val();
	if (folderid!='0') {
		var usr=myfiles.baseinfo[3];
		if (usr!="") {	usr="&userid="+usr;	}
		var loadiconhtml="<img style=\"text-align:baseline;\" src=\""+myfiles.basepath+"/img/ajxldr_small.gif\" \>";
		var url="plug.php?r=myfiles&m=folderdelete&folderid="+folderid+usr;
		$('#minidir').block({ message: loadiconhtml+"&nbsp;&nbsp;"+myfiles.minidirlang[0], overlayCSS: { backgroundColor: '#ddd' } });
		$("#mb_ajx select.fldr_select").unbind('change').unbind('keyup');
		$.getJSON(url, function (data) {folder_processdelete(data)} );
	}
	return false;
}



function folder_keyb(keyb) {
	var val = $("#mb_ajx select.fldr_select").val();
	switch(keyb)
	{
		case 38:	// up
		case 40:	// down
		  event_folderchanged(val);
		  break;

		case 13:	// enter
			if (folder_getSubfoldercount(val)!=0) {
				folder_goto(myfiles.pub_lastfldrselect);
			}	
			return false;
			break;

		case 37:	// left
			folder_goto(myfiles.baseinfo[0],true,true);
			break;

		default:
			// character keys.... (to select items through letters or digits)
			event_folderchanged(val);
			return true;
	}
}
//===========================================================================
