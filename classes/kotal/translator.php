<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Allows PHPTAL to take advantage of the Kohana i18n tranlator.
 *
 * @package    KOtal
 * @category   Base
 * @author     Hanson Wong, johanlindblad
 * @copyright  (c) 2010 Hanson Wong
 * @license    http://github.com/Dismounted/KOtal/blob/master/LICENSE
 */

require_once Kohana::find_file('vendor', 'phptal/PHPTAL/TranslationService');

class Kotal_Translator implements PHPTAL_TranslationService
{
	private $vars = array();
	
	function setVar($key, $value)
	{
		$this->vars[':'.$key] = $value;
	}

    function translate($key, $htmlescape=true)
	{
		// Replace ${var} with :var and run through the translator
		$text = __(preg_replace('/\$\{(.+?)\}/', ':$1', $key), $this->vars);
		if ($htmlescape)
		{
			// Remove comment if you want to. Then you'll only be able to use
			// <tal:block> for i18n:name attributes.
			// return htmlentities($text);
		}
		return $text;
	}
	
	// Not implemented and probably not needed
	function setLanguage()
	{
		
	}

    function setEncoding($encoding)
	{
	
	}

    function useDomain($domain)
	{
		
	}
}
