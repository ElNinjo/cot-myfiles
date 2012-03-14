//=======================================
// Author 2basix.nl (Leo Lems)
// Myfiles supporting JS
//=======================================

// this is a hooked function from minidir
function folder_change() {
	var fldselect=$('.fldr_select').val();
	// watch out... minidir is not always present !!!, so this value could be undefined
	if (fldselect) {
		if (fldselect=="0") {
			// no folder selected, so take base folder from the minibrowser
			// non existing folder, so selected folder= choosen folder
			$("#folder input[name=selectedfolderid]").val(myfiles.baseinfo[0]);
		} else {
			$("#folder input[name=selectedfolderid]").val(fldselect);
		}
	}
}

// prepare the form when the DOM is ready 
$(document).ready(function() { 
	var options={"beforeSubmit":startFolderUpload,"dataType":'json',"success":processFolderUpload};
    $('#folder').submit(function(){ $(this).ajaxSubmit(options);return false;}); 
	folder_change();
}); 


function startFolderUpload() {
	var loadiconhtml="<img style=\"text-align:baseline;\" src=\""+basepath+"/img/ajxldr_small.gif\" \>";
	if (folder_action=="new") {
		foldername=$("#folder input[name=ntitle]").val();
	} else {
		foldername=$("#folder input[name=rtitle]").val();
	}	
	if (foldername==="") {alert(language[0]);return false;}
	$('#folder').block({ message: loadiconhtml+"&nbsp;&nbsp;"+language[1], overlayCSS: { backgroundColor: '#ddd' } });
	return true;
}

// post-submit callback
function processFolderUpload(data) {
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
		if (closewhenfinished!=="" && window.opener) {
			if (!window.opener.closed) {
				if (folder_action=="new") {
					// report back that a folder was created !
					try { window.opener.folder_wascreated(object.newfolderid,object.parentid);} catch(err) {}
					try { window.opener.$('body').trigger('myfiles_folder_created',object.newfolderid); } catch(err) {}
				} else {
					try { window.opener.folder_modified(object.folderid,object.folderrecord); } catch(err) {}
					try { window.opener.$('body').trigger('myfiles_folder_changed',object.folderid); } catch(err) {}
				}				
			}
			window.close();
			return;
		}
		$('#folder #icon').html("");
		$('#folder #result').html("");
		if (folder_action=="new") {
			// new folder, reset the inputs
			$("#folder input[name=ntitle]").val("");
			$("#folder input[name=ndesc]").val("");
			$('#folder input[name=nispublic]').filter('[value=0]').attr('checked', true);
			$('#folder input[name=nisgallery]').filter('[value=0]').attr('checked', true);
		}	
	}
	$('#folder').unblock();
}