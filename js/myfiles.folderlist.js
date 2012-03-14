//=======================================
// Author 2basix.nl (Leo Lems)
// Myfiles supporting JS for listdir
//===========================================================================
function folderlist_initrload() {
	// shorter periods then normaly
	$.blockUI.defaults.fadeIn = 0;
	$.blockUI.defaults.fadeOut = 200;

	// register an event listener
	$('body').bind('myfiles_folder_created', function(event,folderid) {  
		folderlist_goto(myfiles.baseinfo[0]);
		//folderlist_goto(folderid);			// goto new folder
	});
	$('body').bind('myfiles_folder_changed', function(event,folderid) {
		folderlist_goto(myfiles.baseinfo[0]);
	});  
}

/*==-- common functions --==*/
function folderlist_findIndex(folderid) {
	var index=-1;
	for(var i in myfiles.mb_json_allfolderinfo) {
		if (myfiles.mb_json_allfolderinfo[i].pff_id==folderid) {
			index=i;
			break;
		}
	}
	return index;
}
function folderlist_removeFromMemory(delfolderid) {
	//=================================================
	// remove it cleanly from the memory too
	var i=folderlist_findIndex(delfolderid);
	if (i>=0) {
		var elementtodelete=null;
		elementtodelete=myfiles.mb_json_allfolderinfo[i];
		myfiles.mb_json_allfolderinfo = $.grep(myfiles.mb_json_allfolderinfo, function(val) { return val != elementtodelete; });
	}
}

function folderlist_clearerror() {
	$('#mb_foldererror').html("").hide();
}

function folderlist_showerror(message) {
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
	myfiles.error_foldertimer=setTimeout('folderlist_clearerror()', 2500);
}
/*=============================================================*/


function folderlist_new() {
	var fld=myfiles.baseinfo[0];
	var usr=myfiles.baseinfo[3];
	if (usr!="") {	usr="&userid="+usr;	}
	window.open('plug.php?r=myfiles&m=folderadd&folderid='+fld+usr+"&close=1", myfiles.minidirlang[1], 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=500,height=350,left=0,top=0');
}

function folderlist_processhtml(data) {
	$('#mb_listajx').html(data);
	$('#folderlistcontent').unblock();
}

function folderlist_goto(id) {
	var usr=myfiles.baseinfo[3];
	if (usr!="") {	usr="&userid="+usr;	}
	var loadiconhtml="<img style=\"text-align:baseline;\" src=\""+myfiles.basepath+"/img/ajxldr_small.gif\" \>";
	var url="plug.php?r=myfiles&m=folderlistpart&folderid="+id+usr+"&hidebtns="+myfiles.baseinfo[2];
	$('#folderlistcontent').block({ message: loadiconhtml+"&nbsp;&nbsp;"+myfiles.minidirlang[0], overlayCSS: { backgroundColor: '#ddd' } });
	// trigger the event
	try { 	$('body').trigger('myfiles_folder_change',id); } catch(err) {}
	$.get(url, function (data) {folderlist_processhtml(data)} );
} 

function folderlist_processdelete(data) {
	if (data.status==="ok") {
		var delfolderid=data.info.deletedfolderid;
		// remove it cleanly from the memory too
		folderlist_removeFromMemory(delfolderid);
		// remove it from the table !!!
		$("#flrow_"+delfolderid).remove();
	} else {
		folderlist_showerror(data.message) // there was an error, so handle it
	}
	$('#folderlistcontent').unblock();
}


function folderlist_delete(id) {
	var folderid = id;
	if (folderid!='0') {
		var usr=myfiles.baseinfo[3];
		if (usr!="") {	usr="&userid="+usr;	}
		var loadiconhtml="<img style=\"text-align:baseline;\" src=\""+myfiles.basepath+"/img/ajxldr_small.gif\" \>";
		var url="plug.php?r=myfiles&m=folderdelete&folderid="+folderid+usr;
		$('#folderlistcontent').block({ message: loadiconhtml+"&nbsp;&nbsp;"+myfiles.minidirlang[0], overlayCSS: { backgroundColor: '#ddd' } });
		$.getJSON(url, function (data) {folderlist_processdelete(data)} );
	}
	return false;
	
}


function folderlist_edit(id) {
	var usr=myfiles.baseinfo[3];
	if (usr!="") {	usr="&userid="+usr;	}
	window.open('plug.php?r=myfiles&m=folderedit&folderid='+id+usr+"&close=1", myfiles.minidirlang[7], 'toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=500,height=350,left=0,top=0');
}


//===========================================================================
