		<!-- Part: myfiles.storageinfo.ajax_c -->
		<div style="width:400px; height:25px; margin-bottom:10px; background-color:#ddf;">
			<div style="height:17px; padding:4px 0px; color:#fff; text-align:center; background-color:#33F; width:{STORAGEINFO_USEDPERC}%; ">
				&nbsp;<!-- IF {STORAGEINFO_USEDPERC} > 10 -->{STORAGEINFO_USEDPERC} %<!-- ENDIF -->
			</div>
		</div>
		{PHP.L.myfiles_totalsize} : {STORAGEINFO_MAXTOTAL}{PHP.L.kb} ({STORAGEINFO_USEDPERC}%)<br />
		{PHP.L.myfiles_maxsize} : {STORAGEINFO_MAXFILE}{PHP.L.kb}
