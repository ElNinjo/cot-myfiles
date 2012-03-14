//===========================================================================
// Author 2basix.nl (Leo Lems)
// Myfiles supporting JS for filelists
// FILELIST part
//===========================================================================

function files_initrload() {
	
	myfiles.filesloading=false;
	myfiles.filestimer=null;
	myfiles.forcefilelist=false;

	// register an event listener
	$('body').bind('myfiles_folder_change', function(event,folderid) {  
		files_folder_change(folderid);
	});  
	// register an event listener for moving files
	$('body').bind('myfiles_folder_movefiles', function(event,folderid) {  
		files_movetofolder(folderid);
	});  
	
	// register an event change listener
	$('body').bind('myfiles_folder_update', function(event,folderid,scrollToFiles) { 
		// root folder correction
		if ((folderid==0 || folderid==-1) && (myfiles.filesfolder==0 || myfiles.filesfolder==-1)) {
			folderid=myfiles.filesfolder;
		}	
		if (myfiles.filesloadingid==folderid) {
			if (scrollToFiles!=undefined) {
				if (scrollToFiles===true) { files_scrollToSelector("#files_ajx"); }
			}	
			files_loadfolder(true);
		}
	});  
	$("#mb_filesshowtn").change(function () {files_refresh()})
}

function files_clearerror() {
	$('#mb_fileserror').html("").hide();
}

function files_scrollToSelector(selector) {
	$('html,body').animate({ scrollTop: $(selector).offset().top}, 200);
}

function files_showerror(message) {
	if (myfiles.error_filestimer === undefined) {
		myfiles.error_filestimer=null;
	}	
	if (myfiles.error_filestimer!==null) {
		// timer is allready busy
		clearTimeout(myfiles.error_filestimer);
		myfiles.error_filestimer=null;
	}
	var erricon= '<img src="'+myfiles.basepath+'/img/error.png" />&nbsp;&nbsp;';
	$('#mb_fileserror').html(erricon+message).show();
	myfiles.error_filestimer=setTimeout('files_clearerror()', 2500);
}

function files_getSelectedFileIds() {
	var checkboxes=$('input[name=fileselect]');
	if (checkboxes.length<=0) {
		var obj={ nr:0, ids:""};
		return obj;
	}
	var idlist="",sep="",count=0;
	checkboxes.each( function () {
		if ($(this).attr('type')=="checkbox" && $(this).attr('checked') ) {
			count++;
			idlist+= sep+$(this).val();
			sep=',';
		}
	})
	var obj= {nr:count, ids:idlist};
	return obj;
}

function files_new() {
	// see filelist.tpl
	files_upload(myfiles.filesfolder,myfiles.filesuserid);
}

function files_edit(id) {
	var fileid="";
	if (typeof id==="string") {
		fileid=id;
	} else {
		var sfiles=files_getSelectedFileIds();
		if (sfiles.nr>0) {
			fileid=sfiles.ids.split(',',1);
		} else {
			files_showerror(myfiles.fileslang[4]);
			return false;
		}
	}	
	if (fileid!="") {
		files_modify(fileid,"1");
	}	
}

function files_removeidsfromUI(theids) {
	var arr=theids.split(",");
	var i,len=arr.length;
	for (i=0;i<len;i++) {
		// walk through all deleted files and remove them from the UI (the row)
		$('input[name=fileselect][type=checkbox][value='+arr[i]+']').parent().parent().remove();
	}
}

function files_processdeletions(data) {
	try { $('body').trigger('myfiles_storage_update');  }
	catch (err) {}
	if (typeof data === "object" && data.status == "error") {
		files_showerror(data.message);
		return;
	}
	if (typeof data === "object" && data.status == "ok") {
		if (data.deleted_ids !== undefined) {
			files_removeidsfromUI(data.deleted_ids);
		}
	}
}

function files_delete(id) {
	var fileid="";
	if (typeof id==="string") {
		fileid=id;
	} else {
		var sfiles=files_getSelectedFileIds();
		if (sfiles.nr==0) {
			files_showerror(myfiles.fileslang[4]);
			return false;
		}
		fileid=sfiles.ids;
	}
	
	if (fileid!=="") {
		var r=confirm(myfiles.fileslang[3]);
		if (r==true)
		{
			var url="plug.php?r=myfiles&m=filedelete&fileids="+fileid;
			$.getJSON(url, function (data) {files_processdeletions(data);} );
		}
	}
}


function files_movetofolder(destfolderid) {
	var sfiles=files_getSelectedFileIds();
	if (sfiles.nr>0) {
		var theurl="plug.php?r=myfiles&m=filemove&a=move&destid="+destfolderid+"&fileids="+sfiles.ids;
		$.ajax({type: 'GET',url: theurl,dataType: 'json',async: false,success: 
			function(data) {
				if (typeof data === "object" && data.status == "error") {
					files_showerror(data.message);
					return;
				}
				if (typeof data === "object" && data.status == "ok") {
					if (data.moved_ids !== undefined) {
						files_removeidsfromUI(data.moved_ids);
					}
				}
			}
		});
	} else {
		files_showerror(myfiles.fileslang[4]);
	}
}
function files_move_screen() {
	var sfiles=files_getSelectedFileIds();
	if (sfiles.nr>0) {
		window.open("plug.php?r=myfiles&m=filemove&userid="+myfiles.filesuserid+"&folderid="+myfiles.filesfolder, "MoveFiles", "toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=800,height=400,left=0,top=0");
	} else {
		files_showerror(myfiles.fileslang[4]);
	}	
}

function files_refresh() {
	files_loadfolder(true);
	// reload filelist
}

// this is the event that gets triggered
function files_folder_change(folderid) {
	myfiles.filesfolder=folderid;
	if (myfiles.filestimer!==null) {
		// timer is allready busy
		clearTimeout(myfiles.filestimer);
		myfiles.filestimer=null;
	}
	//prevent two ajax loads !!!
	if (!myfiles.filesloading && myfiles.filesloadingid != myfiles.filesfolder) {
		$('#filescontent').block({ message: myfiles.fileslang[2], overlayCSS: { backgroundColor: '#ddd' } });
		myfiles.filestimer=setTimeout('files_loadfolder()', 500);
	} else {
		$('#filescontent').unblock();
	}
}

// this is an event that gets triggered when there is an update for a specific folder
function files_folder_update(folderid) {
	if ((folderid==0 || folderid==-1) && (myfiles.filesfolder==0 || myfiles.filesfolder==-1)) {
		folderid=myfiles.filesfolder;
	}
	if (myfiles.filesfolder==folderid) {
		files_folder_change(folderid); 
	}
}

function files_processajxdata(data) {
	if (myfiles.forcefilelist==false) {
		if (myfiles.filesloadingid!=myfiles.filesfolder) {
			// what if the selected folder has changed !!!
			// then we should load again (this time no timeout)
			files_loadfolder();
			return;
		}
	}
	$('#files_ajx').html(data);
	$('#filescontent').unblock();
	myfiles.forcefilelist=false;	
	myfiles.filesloading=false;
}


function files_loadfolder(force) {
	if (force===undefined) { 
		force=false; 
	} else {
		if (force===true) {
			myfiles.forcefilelist=true;
			if ($('div.blockUI').length<=0) {
				$('#filescontent').block({ message: myfiles.fileslang[1], overlayCSS: { backgroundColor: '#ddd' } });
			}
		}
	};
	if (force===false) {  
		if (myfiles.filesloadingid===myfiles.filesfolder) {
			return false;
		}
		myfiles.filesloading=true;
		myfiles.filesloadingid=myfiles.filesfolder;
		if (myfiles.filestimer!==null) {
			// timer was still busy so quit
			clearTimeout(myfiles.filestimer);
		}
	}	

	var showthumb="0";
	if ($('#mb_filesshowtn').is(':checked')) {
		showthumb="1";
	}
	$('div.blockMsg').html(myfiles.fileslang[1]);
	myfiles.filestimer=null;
	
	// load the folder info AJAX
	var usr=myfiles.filesuserid;
	if (usr!="") {	usr="&userid="+usr;	}
	var url="plug.php?r=myfiles&m=filespart&folderid="+myfiles.filesloadingid+"&thumbnails="+showthumb+usr;
	$.get(url, function (data) {files_processajxdata(data)} );
	return true;
}
