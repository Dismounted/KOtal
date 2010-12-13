<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Resolver that allows PHPTAL to load macros.
 *
 * @package    KOtal
 * @category   Base
 * @author     Hanson Wong, johanlindblad
 * @copyright  (c) 2010 Hanson Wong
 * @license    http://github.com/Dismounted/KOtal/blob/master/LICENSE
 */

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
