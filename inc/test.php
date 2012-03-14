<?php
/* ====================
	test
  ==================== */

// http://www.php.net/manual/en/function.strtok.php
/* subtok(string,chr,pos,len)
 *
 * chr = chr used to seperate tokens
 * pos = starting postion
 * len = length, if negative count back from right
 *
 *  subtok('a.b.c.d.e','.',0)     = 'a.b.c.d.e'
 *  subtok('a.b.c.d.e','.',0,2)   = 'a.b'
 *  subtok('a.b.c.d.e','.',2,1)   = 'c'
 *  subtok('a.b.c.d.e','.',2,-1)  = 'c.d'
 *  subtok('a.b.c.d.e','.',-4)    = 'b.c.d.e'
 *  subtok('a.b.c.d.e','.',-4,2)  = 'b.c'
 *  subtok('a.b.c.d.e','.',-4,-1) = 'b.c.d'
 */
function subtok($string,$chr,$pos,$len = NULL) {
  return implode($chr,array_slice(explode($chr,$string),$pos,$len));
}

//==============================================================
// Get the folder path in text (with the foldernames !!!)
//  e.g.   /Main level/First/Second/Third/
//
//  	returns "" if the folderid is not found
//==============================================================

$cleanpath="/test1/test2/test3/";
echo $cleanpath."<br />";

$cleanpath = substr($cleanpath, 1, -1);

echo $cleanpath."<br /><br />";

		for ($i=0;$i<20;$i++) {
			$folderid=subtok($cleanpath,'/',$i,1);
			if ($folderid != "") {
				echo "|".subtok($cleanpath,'/',$i,1)."|<br />";
			
			}else {
				break;
			}	
		}
		

	
?>
