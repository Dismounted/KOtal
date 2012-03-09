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

class Kotal_SourceResolver implements PHPTAL_SourceResolver {

	/**
	 * Resolves files according to Kohana conventions.
	 *
	 * @param string Path to resolve
	 *
	 * @return PHPTAL_FileSource
	 * @return null
	 */
	public function resolve($path)
	{
		$ext = Kohana::$config->load('kotal.ext');
		$path = str_replace(array(APPPATH.'views/', '.' . $ext), '', $path);
		$file = Kohana::find_file('views', $path, $ext);

		if ($file)
		{
			return new PHPTAL_FileSource($file);
		}

		return null;
	}
}
