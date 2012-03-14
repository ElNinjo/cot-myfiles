<!-- BEGIN: MAIN -->
<script src="js/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		// Author: http://2basix.nl (Leo Lems)
		// load the resources we need.. (on the fly)
		// css will prevent browser caching.. for development purposes
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css',cache:false},
					{src:'js/jquery.blockui.min.js'}]);
	</script>
	<!-- IF {MYFILES_USERNAME} != "" -->
		<fieldset style="background-color:#0069D6; color:#FFF; font-size:1.5em; text-align: center; margin-bottom:10px">
			<strong>{MYFILES_USERNAME}</strong>
		</fieldset>
	<!-- ENDIF -->
	<!-- IF {MYFILES_INFOBLOCK} != "" -->
		<div id="infoblockdir">{MYFILES_INFOBLOCK}</div>
	<!-- ENDIF -->
	<div id="minidir">{MYFILES_FOLDERSELECT}</div>
	<div id="filelist">{MYFILES_FOLDERFILES}</div>
<!-- END: MAIN -->