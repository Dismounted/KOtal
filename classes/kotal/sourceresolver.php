<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Resolver that allows PHPTAL to resolve paths in the Kohana filesystem.
 *
 * @package    KOtal
 * @category   Base
 * @author     Hanson Wong
 * @author     johanlindblad
 * @copyright  (c) 2010 Hanson Wong
 * @license    http://github.com/Dismounted/KOtal/blob/master/LICENSE
 */

require_once Kohana::find_file('vendor', 'phptal/PHPTAL/SourceResolver');

class Kotal_SourceResolver implements PHPTAL_SourceResolver {

	/**
	 * Resolves files according to Kohana conventions.
	 *
	 * @param   string  path to resolve
	 * @return  PHPTAL_Source
	 */
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
