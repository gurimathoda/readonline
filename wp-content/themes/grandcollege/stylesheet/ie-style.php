<?php

	header("Content-type: text/css; charset: UTF-8");

	/*	
	*	Goodlayers IE Style File
	*	---------------------------------------------------------------------
	*	
	*	---------------------------------------------------------------------
	*/
	
?>
div.gdl-header-dropcap,
div.shortcode-dropcap.circle{
	position: relative;
	z-index: 0;
	behavior: url("<?php echo $_GET['path']?>/stylesheet/ie-fix/PIE.php");
}

label img {
	behavior: url("<?php echo $_GET['path']?>/stylesheet/ie-fix/label_img.htc");
}