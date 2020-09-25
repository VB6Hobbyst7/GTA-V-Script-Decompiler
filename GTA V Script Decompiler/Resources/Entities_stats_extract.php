<?php
// Search OpenIV for "statssetup", extract all results into the same folder as this script.

$fh = fopen("Entities_stats.txt", "w");
function processCharStat($mp, $name)
{
	global $fh;
	$arr = $mp ? ["MP0_", "MP1_"] : ["SP0_", "SP1_", "SP2_"];
	foreach($arr as $prefix)
	{
		fwrite($fh, $prefix.$name."\n");
	}
}
function processStats($mp, $stats)
{
	global $fh;
	foreach($stats->stat as $stat)
	{
		$name = $stat["Name"];
		if(empty($name))
		{
			$name = $stat["name"];
			if(empty($name))
			{
				echo "Unexpected stat with no name:\n";
				var_dump($stat);
				exit;
			}
		}
		fwrite($fh, $name."\n");
		if($stat["characterStat"] == "true")
		{
			processCharStat($mp, $name);
		}
	}
}
foreach(scandir(".") as $file)
{
	if(substr($file, -4) == ".xml")
	{
		echo $file."\n";
		$mp = substr($file, 0, 2) == "mp";
		$stats = simplexml_load_file($file)->stats;
		if($stats->Category)
		{
			foreach($stats->Category as $category)
			{
				processStats($mp, $category);
			}
		}
		else
		{
			processStats($mp, $stats);
		}
	}
}
fclose($fh);
