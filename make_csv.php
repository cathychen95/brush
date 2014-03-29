<?php
	$handle = fopen("test.csv", "a");
	$line = array ('911', 'YES');
	fputcsv($handle, $line);
	fclose($handle);
