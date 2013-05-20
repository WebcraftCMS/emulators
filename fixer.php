<?php

function fixDirectory($directory)
{
	$items = glob($directory.'/*');

	$skip = array('.', '..');

	foreach($items as $item)
	{
		if(in_array($item, $skip))
		{
			continue;
		}

		if(is_dir($item)) fixDirectory($item);

		else fixFile($item);
	}
}

function fixFile($file)
{
	$lines = file($file);
	$content = '';

	foreach($lines as $line)
	{
		if($line[0] == '-')
		{
			$line = substr($line, 1);
		}

		$content .= $line;
	}

	saveFile($file, $content);
}

function saveFile($file, $content)
{
	file_put_contents($file, $content);
}

fixDirectory(__DIR__);