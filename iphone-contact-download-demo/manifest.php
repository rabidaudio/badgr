<?php
	header('Content-Type: text/cache-manifest');
	echo "CACHE MANIFEST\n";
	echo "CACHE:\n";

	$hashes = "";

	$dir = new RecursiveDirectoryIterator(".");
	foreach(new RecursiveIteratorIterator($dir) as $file) {
		if ($file->IsFile() &&
			(!strstr($file,'manifest.php'))&&
			(!strstr($file,'vcard.php'))&&
			(!strstr($file,'vcal.php'))&&
			(!strstr($file,'download.php'))&&
			(!strstr($file,'iphonecontact.vcf'))&&
			(!strstr($file,'iphonecontact.ics'))&&
			(substr($file->getFilename(), 0, 1) != "."))
		{
			echo $file . "\n";
			$hashes .= md5_file($file);
		}
	}

	echo "# Hash: " . md5($hashes) . "\n";
	echo "FALLBACK:\n";
	echo "NETWORK:\n";
	echo "*\n";
?>
