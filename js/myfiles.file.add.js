//=======================================
// Author 2basix.nl (Leo Lems)
// Myfiles supporting JS for upload
//=======================================

function ezaddurl(gfile) {
	if (gfile!="" && opener!==null && !window.opener.closed) {
		insertText(opener.document, fileadd.formitems[0], fileadd.formitems[1], gfile);

		if (fileadd.quick_action!="") { window.close(); };
	}
}
//		insertText(opener.document, fileadd.formitems[0], fileadd.formitems[1], '<img src="'+gfile+'" alt="inserted"/>');

function ezaddthumb(gfile) {

	if (opener.CKEDITOR.instances.rpagetext != undefined) {
		opener.CKEDITOR.instances.rpagetext.insertHtml("<img src='"+gfile+"' alt='inserted' />");
	} else {
		insertText(opener.document, fileadd.formitems[0], fileadd.formitems[1], '[img='+gfile+']'+gfile+'[/img]');
	}
	console.log(opener.CKEDITOR.instances.formitems[1]);
	if (fileadd.quick_action!="") {
		window.close();
	};
}

function ezaddpix(gfile) {
	if (gfile!="" && opener!==null && !window.opener.closed) {
		insertText(opener.document, fileadd.formitems[0], fileadd.formitems[1], '[img]'+gfile+'[/img]');
		if (fileadd.quick_action!="") { window.close(); };
	}
}
function ezaddlink(gfile,linktext) {
	if (gfile!="" && opener!==null && !window.opener.closed) {
		var lnktxt;
		lnktxt=gfile;
		if (typeof linktext=="string") {
			if (linktext!=="") {
			lnktxt=linktext;
			}
		}
		insertText(opener.document, fileadd.formitems[0], fileadd.formitems[1], '[url='+gfile+']'+lnktxt+'[/url]');
		if (fileadd.quick_action!="") { window.close(); };
	}
}

function fileadd_triggerParentEvent(folderid) {
	if (opener!==null && !window.opener.closed) {
		try { 	window.opener.$('body').trigger('myfiles_folder_update',[folderid,true]);
				$('body').trigger('myfiles_folder_update',[folderid,true]);	// inline preparation
			}
		catch (err) {}
		try { 	window.opener.$('body').trigger('myfiles_storage_update');
				$('body').trigger('myfiles_storage_update');	// inline preparation
			}
		catch (err) {}
	}
}

function fileadd_GetCurrentFolderid() {
	var fid=null;
	try {	// this is if the folderbrowser is present
			fid=folder_GetCurrentFolderid();
	} catch(err) {
		// public var if no browser is present
		fid=fileadd.fixfolderid;
	}
	return fid;
}

function getFileExt(name) {
	var x=name.lastIndexOf(".");
	var str=name.substring(x+1).toLowerCase();
	return str;
}

function checkFileExt(name,formid) {
	var ext=getFileExt(name);
	var len=fileadd.allowedext.length;
	var x;
	for (x=0;x<len;x++) {
		if (fileadd.allowedext[x][0]===ext) {
			$('#submit'+formid).removeAttr('disabled').removeClass('mf_error');
			$('#myfilesupload'+formid+' input[name=userfile]').removeClass('mf_error');
			return true;
		}
	}
	$('#myfilesupload'+formid+' input[name=userfile]').addClass('mf_error');
	$('#submit'+formid).attr('disabled','disabled').addClass('mf_error');
	alert(fileadd.language[8]);
	return false;
}


function FileChange(val,formid) {
	var str;
	if (!checkFileExt(val,formid)) {	return false; }
	if(val.indexOf("\\")>=0) {
		var x=str=val.lastIndexOf("\\");
		str=val.substring(x+1);
	} else {str=val;}
	$('#myfilesupload'+formid+' #frname').val(str);
	$("#myfilesupload"+formid+" #fdescr").val("");
	$('#result'+formid).html("");
	$('#icon'+formid).html("");
}

// global var
if (fileadd===undefined) {	var fileadd={}; }
fileadd.mf_uploadhandles=[null,null,null,null,null,null,null,null];

function prepareFormSubmit(formid) {
	var options={	"beforeSerialize": uploadBeforeSerialize,
					"beforeSend": uploadBeforeSend,
					"beforeSubmit":showRequest,
					"dataType":'json',
					"success":processJson,
					"formid":formid				};
    $('#myfilesupload'+formid).submit(function(){ $(this).ajaxSubmit(options);return false;});
}

function uploadBeforeSend(xhr, opts){
	fileadd.mf_uploadhandles[opts.formid] = xhr;
}

function upload_abort(formid) {
	if (fileadd.mf_uploadhandles[formid]) {
		fileadd.mf_uploadhandles[formid].abort();
		// aborted, so clean interface up
		$("#smallslot"+formid).addClass("hidden").removeClass("shown");
		$("#uploadslot"+formid).addClass("hidden").removeClass("busy");
		var erroriconhtml="<img style=\"vertical-align:text-bottom\" src=\""+fileadd.basepath+"/img/error.png\" \>";
		$('#output2').prepend(erroriconhtml+"&nbsp;&nbsp;Aborted:&nbsp;&nbsp;&nbsp;"+$("#smallslot"+formid+' #uploadfilename').html()+"<br />");

	}
}

// process the form before anything has happened
function uploadBeforeSerialize($form, options){
	fid=fileadd_GetCurrentFolderid();
	if (fid!==null) {
		$('#myfilesupload'+options.formid+' #folder_id').val(fid);
	}
	var filename="";
	var frname="";
	filename=$("#myfilesupload"+options.formid+" input[name=userfile]").val();
	frname=$("#myfilesupload"+options.formid+" #frname").val();
	if (filename==="") {alert(fileadd.language[0]);return false;}
	if (frname==="") {alert(fileadd.language[2]);return false;}

	// set the uploading
	$("#smallslot"+options.formid+' #uploadfilename').html($('#myfilesupload'+options.formid+' #frname').val());
	$('#result'+options.formid).html('');
	$('#icon'+options.formid).html('');

	$("#smallslot"+options.formid).addClass("shown").removeClass("hidden");
	$("#uploadslot"+options.formid).addClass("busy").removeClass("shown");

	$('.uploadslot.hidden').first().addClass("shown").removeClass("hidden");

	return true
}

// prepare the form when the DOM is ready
$(document).ready(function() {
	for (x=0;x<fileadd.maxuploads;x++) {
		prepareFormSubmit(''+x);
	}
});

// pre-submit callback
function showRequest(formData, jqForm, options) {
	// no code
	return true;
}

function createfullname(folder,filename,fileurl){
	var ret="";
	if (folder.length==1){ ret=folder+filename;} else {ret=folder+"/"+filename;};

	if (fileurl!=='') {
		ret= '<a class="mf_filelink" href="'+fileurl+'">'+ret+'</a>';
	}
	return ret;
}

// post-submit callback
function processJson(data,statustext) {
    // 'data' is the json object returned from the server
	if (statustext!="success") {
		alert(statustext);
	}
	object='null';
	var error=false;
	var uploaded=0;
	var erroriconhtml="<img style=\"vertical-align:text-bottom\" src=\""+fileadd.basepath+"/img/error.png\" \>";
	var checkiconhtml="<img style=\"vertical-align:text-bottom\" src=\""+fileadd.basepath+"/img/check.png\" style=\"vertical-align:middle;\" \>&nbsp;&nbsp;";
	var pixelhtml="<img src=\""+fileadd.basepath+"/img/pixel.gif\" width=\"16px\" height=\"16px\" \>";
	var addiconshtml = "";
	if (typeof data === "string") {alert('myfiles::processJson-ERROR: Invalid datatype !');alert(data);error=true;}
		else {object=data;}

	// process basic error returns
	if (object.status==='error') {
		if (typeof object.message === "string") {
			$('#output2').prepend(erroriconhtml+"&nbsp;&nbsp;&nbsp;&nbsp;"+object.message+"<br />");
		} else {
			$('#output2').prepend(erroriconhtml+"&nbsp;&nbsp;&nbsp;&nbsp;"+fileadd.language[7]+"<br />");
		}
		return false;
	}

	if (typeof(object.file)!="undefined") {
		if (object.file.status=="error") {
			error=true;
			$('#output2').prepend(erroriconhtml+"&nbsp;"+createfullname(object.file.foldername,object.file.filename,'')+"&nbsp; &nbsp;"+object.file.message+"<br />");
		} else {
			fileadd_triggerParentEvent(object.file.folderid);

			uploaded+=1;
			$('#icon'+object.file.id).html("");
			$('#result'+object.file.id).html("");
			// Quick actions can only be one file !!! (so only here)
			if (fileadd.quick_action!="") {
				switch(fileadd.quick_action) {
				case 'url':
					if (typeof(object.file.pfsfile)=="string") { ezaddurl(object.file.pfsfile); return false; }
					break;
				case 'link':
					if (typeof(object.file.pfsfile)=="string") { ezaddlink(object.file.pfsfile); return false; }
					break;
				case 'pic':
					if (typeof(object.file.thumbfile)=="string") { ezaddpix(object.file.pfsfile); return false; }
					break;
				case 'thumb':
					if (typeof(object.file.thumbfile)=="string") { ezaddthumb(object.file.thumbfile); return false; }
					break;
				}
			}
			var addiconshtml="";
			if (fileadd.add_icons=="1") {
				if (typeof(object.file.pfsfile)=="string") {
					addiconshtml =	"<a title=\""+fileadd.language[6]+"\" href=\"\" onclick=\"ezaddlink('"+object.file.pfsfile+"','"+object.file.friendlyname+"'); return false;\"><img src=\""+fileadd.basepath+"/img/image-link.png\" \></a>&nbsp;&nbsp;";
				}
				if (typeof(object.file.thumbfile)=="string") {
					addiconshtml +=	"<a title=\""+fileadd.language[5]+"\" href=\"\" onclick=\"ezaddpix('"+object.file.pfsfile+"'); return false;\"><img src=\""+fileadd.basepath+"/img/image.png\" \></a>&nbsp;&nbsp;"+
									"<a title=\""+fileadd.language[4]+"\" href=\"\" onclick=\"ezaddthumb('"+object.file.thumbfile+"'); return false;\"><img src=\""+fileadd.basepath+"/img/image-thumb.png\" \></a>";
				} else { addiconshtml += pixelhtml+"&nbsp;&nbsp;"+pixelhtml;}
			} else {
				addiconshtml = checkiconhtml;
			}
			var filename=$("#myfilesupload"+object.file.id+" input[name=userfile]").val();
			$('#output2').prepend(addiconshtml+"&nbsp;&nbsp;&nbsp;&nbsp;"+createfullname(object.file.foldername,filename,object.file.pfsfile)+"<br />");
		}

		$("#myfilesupload"+object.file.id+" input[name=desc]").val("");
		$("#myfilesupload"+object.file.id+" input[name=frname]").val("");
		$("#myfilesupload"+object.file.id+" input[name=userfile]").val("");

		$("#smallslot"+object.file.id).addClass("hidden").removeClass("shown");
		$("#uploadslot"+object.file.id).addClass("hidden").removeClass("busy");
	} else {
		$('#output2').prepend(erroriconhtml+"&nbsp;&nbsp;&nbsp;&nbsp;"+fileadd.language[7]+"<br />");
	}

	// error or not, you must always have an upload slot ready !!!
	// if there are no items shown, then show (unhide) the current one (it has become free)
	if ($(".uploadslot.shown").length === 0) {
		$("#uploadslot"+object.file.id).addClass("shown");
	}
}