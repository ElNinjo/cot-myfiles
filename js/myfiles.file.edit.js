//=======================================
// Author 2basix.nl (Leo Lems)
// Myfiles supporting JS
//=======================================

function fileedit_initrload() {
	$('body').bind('myfiles_folder_change', function(event,folderid) {  
		folder_change(folderid);
	});  
}

// this is a hooked function from minidir
function folder_change(folderid) {
	console.log(folderid);
	var fldselect=$('.fldr_select').val();
	if (fldselect=="0") {
		// no folder selected, so take base folder from the minibrowser
		// non existing folder, so selected folder= choosen folder
		$("#fileedit input[name=selectedfolderid]").val(minidir[0]);
	} else {
		$("#fileedit input[name=selectedfolderid]").val(fldselect);
	}
}

// prepare the form when the DOM is ready 
$(document).ready(function() { 
	var options={"beforeSubmit":startFileEditUpload,"dataType":'json',"success":processFileEditUpload};
	$('#fileedit').submit(function(){ $(this).ajaxSubmit(options);return false;}); 
	// register an event listener


	
}); 

function fileedit_triggerParentEvent(folderid) {
	if (opener!==null && !window.opener.closed) {
		try { window.opener.$('body').trigger('myfiles_folder_update',folderid);  }
		catch (err) {}
	}
}

function startFileEditUpload() {
	var loadiconhtml="<img style=\"text-align:baseline;\" src=\""+basepath+"/img/ajxldr_small.gif\" \>";
	filename=$("#fileedit input[name=frname]").val();
	if (filename==="") {alert(language[0]);return false;}
	$('#fileedit').block({ message: loadiconhtml+"&nbsp;&nbsp;"+language[1], overlayCSS: { backgroundColor: '#ddd' } });
	return true;
}

// post-submit callback
function processFileEditUpload(data) {
    // 'data' is the json object returned from the server
	object='null';
	var error=false;
	var uploaded=0;
	var erroriconhtml="<img src=\""+basepath+"/img/error.png\" \>";
	var checkiconhtml="<img src=\""+basepath+"/img/check.png\" style=\"vertical-align:middle;\" \>&nbsp;&nbsp;";
	if (typeof data === "string") {alert('myfiles::processJson-ERROR: Invalid datatype !');alert(data);error=true;} 
		else {object=data;}

	if (object.status=="error") {
		error=true;
		$('#result').html(object.message);
		$('#icon').html(erroriconhtml);
	} else {
		fileedit_triggerParentEvent(object.folderid);
	
		if (closewhenfinished!=="" && window.opener) {
			if (!window.opener.closed) {
				try { window.opener.file_modified(object.fileid); } catch(err) {}
			}
			window.close();
			return;
		}
		$('#fileedit #icon').html("");
		$('#fileedit #result').html("");
	}
	$('#fileedit').unblock();
}