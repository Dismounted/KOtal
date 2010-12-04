<?php

require_once Kohana::find_file('vendor', 'phptal/PHPTAL/SourceResolver');

class Kotal_Resolver implements PHPTAL_SourceResolver
{
    public function resolve($path)
    {
		$path = str_replace(array(APPPATH.'views/', '.php'), array('', ''), $path);
		$file = Kohana::find_file('views', $path);
		if ($file)
		{
			return new PHPTAL_FileSource($file);
		}
        return null;
    }
}

?>