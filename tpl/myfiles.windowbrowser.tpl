<!-- BEGIN: MAIN -->
	<script type="text/javascript">
		// Author: http://2basix.nl (Leo Lems)
		// load the resources we need.. (on the fly)
		// css will prevent browser caching.. for development purposes
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css',cache:false},
					{src:'js/jquery.blockui.min.js'}]);
	</script>
	<div id="minidir">{MYFILES_FOLDERSELECT}</div>
	<div id="filelist">{MYFILES_FOLDERFILES}</div>
<!-- END: MAIN -->