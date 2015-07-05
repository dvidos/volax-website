<h1>Php Info</h1>
<style>
	table { max-width: 100%; }
	td, th { border: 1px solid #999; font-size: 85%; word-wrap: break-word; }
	.p { text-align: left; }
	.e { background-color: #ccccff; font-weight: bold; }
	.h { background-color: #9999cc; font-weight: bold; }
	.v { background-color: #cccccc; }
	.vr { background-color: #cccccc; text-align: right; }
</style>

<?php

	ob_start();
	phpinfo();
	$html = ob_get_clean();
	
	$start = strpos($html, '<body>');
	$end = strpos($html, '</body>');
	
	if ($start !== false && $end !== false)
		$html = substr($html, $start + 6, $end - $start - 6);
	
	echo $html;
?>




