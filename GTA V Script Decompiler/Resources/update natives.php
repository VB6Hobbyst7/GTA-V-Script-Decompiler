<?php
if(file_exists("natives.json"))
{
	echo "INFO: Using natives.json in working directory.\n";
	$raw = file_get_contents("natives.json");
}
else
{
	echo "Downloading...\n";
	$raw = file_get_contents("https://raw.githubusercontent.com/alloc8or/gta5-nativedb-data/master/natives.json");
}
echo "Parsing...\n";
$namespaces = json_decode($raw, true);

echo "Updating x64natives.dat...\n";
$fh = fopen("x64natives.dat", "w");
foreach($namespaces as $namespace_name => $natives)
{
	foreach($natives as $native_hash => $native)
	{
		fwrite($fh, $native_hash.":".$namespace_name.":".$native["name"]."\n");
	}
}
fclose($fh);

function typename_to_index(string $typename)
{
	return match(strtolower($typename))
	{
		"bool" => 0,
		"float" => 1,
		"int" => 2,
		"any" => 2,
		"hash" => 2,
		"entity" => 2,
		"scrhandle" => 2,
		"ped" => 2,
		"vehicle" => 2,
		"object" => 2,
		"cam" => 2,
		"player" => 2,
		"fireid" => 2,
		"blip" => 2,
		"pickup" => 2,
		"interior" => 2,
		"char*" => 4,
		"const char*" => 4,
		"int*" => 7,
		"bool*" => 7,
		"entity*" => 7,
		"scrhandle*" => 7,
		"ped*" => 7,
		"vehicle*" => 7,
		"object*" => 7,
		"hash*" => 7,
		"blip*" => 7,
		"any*" => 8,
		"vector3*" => 8,
		"float*" => 9,
		"vector3" => 10,
		"void" => 11,
		default => throw new Exception("Unknown type $typename")
	};
}

echo "Updating x64nativeinfo.dat...\n";
$fh = fopen("x64nativeinfo.dat", "wb");
foreach($namespaces as /*$namespace_name =>*/ $natives)
{
	foreach($natives as $native_hash => $native)
	{
		fwrite($fh, gmp_export(gmp_init($native_hash), 8));
		fwrite($fh, pack("c", typename_to_index($native["return_type"])));
		fwrite($fh, pack("c", count($native["params"])));
		foreach($native["params"] as $param)
		{
			fwrite($fh, pack("c", typename_to_index($param["type"])));
		}
	}
}
fclose($fh);

echo "All Done!\n";
