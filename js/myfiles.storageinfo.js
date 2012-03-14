//===========================================================================
// Author 2basix.nl (Leo Lems)
// Myfiles supporting JS for storageinfo
// FILELIST part
//===========================================================================

function storage_initload() {
	$('body').bind('myfiles_storage_update', function(event) { 
		storage_refresh();	
	});  
}

function storage_refresh() {
	// load the folder info AJAX
	var usr=myfiles.storageinfo_userid;
	if (usr!="") {	usr="&userid="+usr;	}
	var url="plug.php?r=myfiles&m=storageinfopart"+usr;
	$.get(url, function (data) { $('#myfilesstoragecontainer').html(data); } );
	return true;
}


//===========================================================================
