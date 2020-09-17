<?php
echo "Downloading...\n";
$raw = file_get_contents("https://raw.githubusercontent.com/alloc8or/gta5-nativedb-data/master/natives.json");
echo "Parsing...\n";
$namespaces = json_decode($raw, true);
echo "Updating...\n";
$fh = fopen("x64natives.dat", "w");
foreach($namespaces as $namespace_name => $natives)
{
	foreach($natives as $native_hash => $native)
	{
		fwrite($fh, $native_hash.":".$namespace_name.":".$native["name"]."\n");
	}
}
fclose($fh);
echo "Done!\n";
